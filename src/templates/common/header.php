


<?php $rub = carlo_get('current_page') ?>

<header class="flex justify-between items-center sticky z-20 top-0 py-5 px-6 h-18
              bg-white
              laptop:px-10 laptop:py-0 laptop:h-auto">



  <a href="/"
    class="laptop:hidden">
    <figure class="w-43 h-8">
      <svg viewBox="0 0 224 42"
          class="size-full object-contain"><use xlink:href="#svg__logo"></use></svg>
    </figure>
  </a>



  <nav id="main-nav"
      class="[ nav ]
            overflow-y-auto hidden fixed z-30 inset-0
            bg-primary
            text-white
            laptop:block laptop:static laptop:w-full laptop:bg-transparent laptop:text-primary">
    <div class="flex flex-col gap-16 mt-14
                laptop:flex-row laptop:justify-between laptop:h-24 laptop:mt-0">
    


      <figure class="h-20
                    laptop:flex laptop:justify-center laptop:items-center laptop:w-56 laptop:h-auto">
        <span class="laptop:hidden">
          <svg viewBox="0 0 163 79"
              class="size-full object-contain"><use xlink:href="#svg__logo-white--vertical"></use></svg>
        </span>
        <span class="hidden
                    laptop:block">
          <svg viewBox="0 0 224 42"
              class="size-full object-contain"><use xlink:href="#svg__logo"></use></svg>
        </span>
      </figure>



<?php // TODO: carlo_get('menu'); ?>
      <ul class="flex flex-col gap-4
                text-center
                laptop:flex-row laptop:items-center laptop:gap-8">
        <li class="<?php if( $rub === 'partner') echo ' active-trail' ?>
                  before:block before:mb-4 before:size-1.25 before:mx-auto before:rounded-full before:bg-accent
                  first:before:content-none
                  laptop:flex laptop:items-center laptop:relative laptop:h-full laptop:transition-all
                  laptop:before:content-none">
          <a href="#"
            class="laptop:flex laptop:items-center laptop:relative laptop:h-full
                  laptop:after:transition-transform laptop:after:duration-500 laptop:hover:after:scale-100
                  laptop:after:block laptop:after:origin-left laptop:after:absolute laptop:after:bottom-0 laptop:after:w-full laptop:after:scale-0 laptop:after:h-1 laptop:after:bg-accent"><span>Nos outils</span></a>
        </li>
        <li class="<?php if( $rub === 'actus') echo ' active-trail' ?>
                  before:block before:mb-4 before:size-1.25 before:mx-auto before:rounded-full before:bg-accent
                  first:before:content-none
                  laptop:flex laptop:items-center laptop:relative laptop:h-full laptop:transition-all
                  laptop:before:content-none">
          <a href="#"
            class="laptop:flex laptop:items-center laptop:relative laptop:h-full
                  laptop:after:transition-transform laptop:after:duration-500 laptop:hover:after:scale-100
                  laptop:after:block laptop:after:origin-left laptop:after:absolute laptop:after:bottom-0 laptop:after:w-full laptop:after:scale-0 laptop:after:h-1 laptop:after:bg-accent"><span>Nos offres</span></a>
        </li>
        <li class="<?php if( $rub === 'contact') echo ' active-trail' ?>
                  before:block before:mb-4 before:size-1.25 before:mx-auto before:rounded-full before:bg-accent
                  first:before:content-none
                  laptop:flex laptop:items-center laptop:relative laptop:h-full laptop:transition-all
                  laptop:before:content-none">
          <a href="#"
            class="laptop:flex laptop:items-center laptop:relative laptop:h-full
                  laptop:after:transition-transform laptop:after:duration-500 laptop:hover:after:scale-100
                  laptop:after:block laptop:after:origin-left laptop:after:absolute laptop:after:bottom-0 laptop:after:w-full laptop:after:scale-0 laptop:after:h-1 laptop:after:bg-accent"><span>Nos réalisations</span></a>
        </li>
        <li class="<?php if( $rub === 'who') echo ' active-trail' ?>
                  before:block before:mb-4 before:size-1.25 before:mx-auto before:rounded-full before:bg-accent
                  first:before:content-none
                  laptop:flex laptop:items-center laptop:relative laptop:h-full laptop:transition-all
                  laptop:before:content-none">
          <a href="#"
            class="laptop:flex laptop:items-center laptop:relative laptop:h-full
                  laptop:after:transition-transform laptop:after:duration-500 laptop:hover:after:scale-100
                  laptop:after:block laptop:after:origin-left laptop:after:absolute laptop:after:bottom-0 laptop:after:w-full laptop:after:scale-0 laptop:after:h-1 laptop:after:bg-accent"><span>Qui sommes-nous</span></a>
        </li>
      </ul>



<?php // TODO: carlo_get('linkedin'); ?>
      <div class="px-6 py-13.5
                  bg-secondary
                  text-base
                  laptop:hidden">
        <div class="flex flex-col gap-0.25 py-0.25
                  bg-secondary-light">

          <ul class="flex justify-center items-center gap-4
                    bg-secondary">
            <li class="shrink-0 size-6">
              <svg viewBox="0 0 20 20"><use xlink:href="#svg__social-linkedin"></use></svg>
            </li>
            <li class="relative ml-2 my-4
                      before:block before:absolute before:top-3 before:-left-2 before:mr-2 before:size-1 before:rounded-full before:bg-accent">
              <a href="https://www.linkedin.com/in/tiphaine-vapaille-6a39281/"
                target="_blank">Tiphaine Vapaille</a>
            </li>
            <li class="relative ml-2 my-4
                      before:block before:absolute before:top-3 before:-left-2 before:mr-2 before:size-1 before:rounded-full before:bg-accent">
              <a href="https://www.linkedin.com/in/sophie-beignon-02a60b1/"
                target="_blank">Sophie Beignon</a>
            </li>
          </ul>
          
          <a href="mailto:diapsodie@gmail.com"
            class="block py-3.5
                  bg-secondary
                  font-semibold text-center underline underline-offset-3">diapsodie@gmail.com</a>
        
        </div>
      </div>
      
      <div class="hidden
                  laptop:flex laptop:items-center">
        <?php
          // TODO: dynamic URL
          carlo_render("components/cta", [
              "link" => "#",
              "label" => "Contact",
          ]);
        ?>
      </div>


    </div>
  </nav>




  
  <button type="button"
          class="[ hamburger ]
                group/btn
                relative z-30 size-10 p-2
                laptop:hidden"
          aria-controls="primary-navigation"
          aria-expanded="false">		
    <span class="[ top ]
                block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1.5 w-6 h-0.25
                bg-primary
                transition-all duration-500
                group-[.is-opened]/btn:translate-y-0 group-[.is-opened]/btn:rotate-45 group-[.is-opened]/btn:bg-white"></span>
    <span class="[ mid ]
                block absolute top-1/2 left-1/2 -translate-x-1/2 w-6 h-0.25
                bg-primary
                transition duration-500
                group-[.is-opened]/btn:opacity-0"></span>
    <span class="[ bot ]
                block absolute top-1/2 left-1/2 -translate-x-1/2 translate-y-1.5 w-6 h-0.25
                bg-primary
                transition-all duration-500
                group-[.is-opened]/btn:translate-y-0 group-[.is-opened]/btn:-rotate-45 group-[.is-opened]/btn:bg-white"></span>
  </button>


  
</header>

