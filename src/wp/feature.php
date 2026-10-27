<?php

/**
 * Disable comment
 */
add_action('admin_init', function () {
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_safe_redirect(admin_url());
        exit;
    }
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
add_filter('comments_array', '__return_empty_array', 10, 2);

add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    // Retire les styles par défaut de l'éditeur (police serif…) : l'aperçu suit le CSS du thème.
    add_theme_support('editor-styles');
});

/**
 * Add style select buttons to TinyMCE editor
 */
function add_style_select_buttons($buttons) {
    array_unshift($buttons, 'styleselect');
    return $buttons;
}
add_filter('mce_buttons_2', 'add_style_select_buttons');

/**
 * Add classes to TinyMCE editor
 */
function carlo_tiny_mce_add_clases($init_array) {
    $style_formats = array(
        array(
            'title' => 'Large paragraphe',
            'help'  => 'Ajoute une classe "large" au paragraphe',
            'selector' => 'p,h1,h2,h3,h4,h5,h6,div,ul,ol,li,a,span',
            'classes' => 'large'
        ),
    );
    $init_array['style_formats'] = json_encode($style_formats);
    return $init_array;
}
add_filter('tiny_mce_before_init', 'carlo_tiny_mce_add_clases');
