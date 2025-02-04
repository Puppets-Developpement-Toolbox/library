<?php
use Symfony\Component\Yaml\Yaml;

include_once __DIR__.'/vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// une pile stockant les variables locals aux templates ouverts
global $CARLO_ARGS;
$CARLO_ARGS = [];
// une pile qui permet de détecter le niveau de profondeur du template 
// en cours pour charger des données spécifiques au contexte
global $CARLO_TPL_PATH;
$CARLO_TPL_PATH = [];
// une pile stockant les variables contextuelles ajoutées
// par un parent et accessible à dans les enfants
global $CARLO_CONTEXT;
$CARLO_CONTEXT = [];

/**
 * load structure definition from structure.yml 
 */
function carlo_structure($type, $name) {
  static $structure;
  if(!isset($structure)) {
    $structure = Yaml::parseFile(__DIR__.'/structure.yml');
  }
  return $structure[$type][$name];
}

/**
 * load and return the template and inject args
 */
function carlo_render($tpl, $args = []) {
  global $CARLO_ARGS, $CARLO_TPL_PATH, $CARLO_CONTEXT;
  list($type, $name) = explode('/', $tpl.'/');
  if(empty($name)) {
    $name = $type;
    $type = 'templates';
  }
  $structure = carlo_structure($type, $name);
  $CARLO_TPL_PATH[] = $name;
  $CARLO_CONTEXT[] = [];

  $defaultArgs = $type === 'blocs' ? carlo_load_data($structure) : [];
  
  $CARLO_ARGS[] = array_merge($defaultArgs, $args);

  echo "<!-- {$tpl} -->\n";
  carlo_load_file("templates/{$tpl}.php");
  echo "<!-- /{$tpl} -->\n";
  array_pop($CARLO_ARGS);
  array_pop($CARLO_TPL_PATH);
  array_pop($CARLO_CONTEXT);
}

/**
 * return the value of key from local args or context
 */
function carlo_get($key = null) {
  global $CARLO_ARGS;
  $tpl_args = end($CARLO_ARGS);

  if($key === null) return $tpl_args;

  $values = carlo_get_value($key, $tpl_args);

  if($values === '') {
    $values = carlo_context($key);
  }

  return $values;
}

/**
 * load data for the current template
 */
function carlo_load_data($structure) {
  static $data;
  if(!isset($data)) {
    $data = Yaml::parseFile(__DIR__.'/data.yml');
  }
  
  return array_map(function($fieldname) use($data, $structure) {
    global $CARLO_TPL_PATH;

    $field = $structure[$fieldname];
    if(is_array($field) && !isset($field['type'])) {
      return carlo_load_data($field);
    }
     
    $type = is_string($field) ? $field : $field['type'];
    if ($type =="repeater") {
      return array_fill(0, $field['times']?? $field['min']+3 , carlo_load_data($field['repeat']) ); 
    }

    $try = $CARLO_TPL_PATH  ;
    do {
      $prefix = implode('--', $try);
      if($prefix) $prefix .= '--';
      if(isset($data["{$prefix}{$fieldname}"])) return $data["{$prefix}{$fieldname}"];
      if(isset($data["{$prefix}{$type}"])) return $data["{$prefix}{$type}"];
    }while(array_shift($try));
    $context = $CARLO_TPL_PATH;
    $context[] = $fieldname;
    $fieldpath = implode(' > ', $context);
    throw new Exception("Impossible de trouver du contenu pour le champ {$fieldpath} de type {$type}");
  }, array_combine(array_keys($structure), array_keys($structure)));
}

/**
 * include the given file
 */
function carlo_load_file($path) {
  $abspath = __DIR__."/src/{$path}";
  // par sécurité en interdit de charger un fichier hors du projet
  if(strpos($abspath, __DIR__) === false) {
    throw new Exception("Le chemin {$path} est hors du projet");
  }
  if(!file_exists($abspath)) {
    throw new Exception("Le fichier {$abspath} n'est pas présent");
  }
  include $abspath;
}


/**
 * add a contextual entry
 */
function carlo_context_add($key, $value) {
  global $CARLO_CONTEXT;
  end($CARLO_CONTEXT);
  $contextLastKey = key($CARLO_CONTEXT);
  $CARLO_CONTEXT[$contextLastKey][$key] = $value;
}

/**
 * return the value of key fron current context
 */
function carlo_context($key) {
  global $CARLO_CONTEXT;
  $values = call_user_func_array('array_merge', $CARLO_CONTEXT);
  return carlo_get_value($key, $values);
}

function carlo_get_value($key, $values) {
  $parts = explode('.', $key);
  while(($values = $values[array_shift($parts)] ?? '') && count($parts)) {}
  return $values;
}

/**
 * returns an html image, sized width length 
 */
function carlo_img($key, $dimensions){
  //https://picsum.photos/3000/1500
  return '<img src="https://picsum.photos/'.str_replace("x",'/', $dimensions).'" > ';
}