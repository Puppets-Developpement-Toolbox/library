<?php $rub =  carlo_get('current_page') ?>
<header class="main-header">
	<div class="header-top header-section">
    <a href="/" class="logo">
      <svg viewBox="0 0 166.81 56.47"><use xlink:href="#svg-logo"></use></svg>
    </a>

    <nav class="main-nav-list" id="primary-navigation">
      <ul class="menu">
        <li class="<?php if($rub == "who") {echo " active-trail";}; ?>">
          <a href="who.php" class="js-roll-bold">
            <span>Qui sommes-nous</span>
          </a>
        </li>
        
        <li class="<?php if($rub == "partner") {echo " active-trail";}; ?>">
          <a href="partner.php" class="js-roll-bold">
            <span>Devenir partenaire</span>
          </a>
        </li>
        
        <li class="<?php if($rub == "actus") {echo " active-trail";}; ?>">
          <a href="actus.php" class="js-roll-bold">
            <span>Projets & actualités</span>
          </a>
        </li>
        
        <li class="<?php if($rub == "contact") {echo " active-trail";}; ?>">
          <a href="contact.php" class="js-roll-bold">
            <span>Contact</span>
          </a>
        </li>
        <li class="special">
          <a href="http://mafactory.innovationfcty.fr/login/?redirect_to=http%3A%2F%2Fmafactory.innovationfcty.fr%2F">
            <span>Ma factory</span>
          </a>
        </li>
      </ul>
    </nav>

    <button href="#" class="hamburger" aria-controls=="primary-navigation" aria-expanded="false">		
      <span class="top"></span>
      <span class="middle"></span>
      <span class="bottom"></span>
    </button>
	</div>
  
  <div class="header-section js-calcul"></div>
</header>

<div class="main-nav-mask"></div>
