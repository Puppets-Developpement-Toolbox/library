


<?php // dump(carlo_get()) ?>

<footer class="group/on-primary
              flex flex-col gap-8 mt-17.5 py-8 px-6
              bg-primary
              text-white
              laptop:pb-16">
  <div class="w-full max-w-300 mx-auto">
    


    <figure class="laptop:pb-4 laptop:mb-10 laptop:border-b-1 laptop:border-b-secondary-light">
      <span class="block w-48
                  laptop:w-56">
        <svg viewBox="0 0 224 42"><use xlink:href="#svg__logo-white"></use></svg>
      </span>
    </figure>



    <div class="laptop:grid laptop:grid-cols-12">

      <section class="[ contact ]
                      group/block
                      py-8 px-10
                      bg-secondary-dark
                      transition-color duration-500
                      laptop:col-span-4 laptop:hover:bg-white">
        
        <span class="block mb-4 pb-4
                    border-b-1 border-b-secondary-light
                    font-semibold uppercase
                    transition duration-500
                    group-hover/block:border-b-primary group-hover/block:text-primary">Nous contacter</span>
        
        <div class="flex gap-4
                    transition duration-500
                    group-hover/block:text-primary">
          <span class="shrink-0 size-6">
            <svg viewBox="0 0 20 20"><use xlink:href="#svg__social-linkedin"></use></svg>
          </span>

          <ul class="[ fade-not-hovered ]
                    flex flex-col gap-2 mb-4">
<?php // TODO: carlo_get('linkedin'); ?>
            <li class="block relative ml-4
                      before:block before:absolute before:top-3 before:-left-4 before:mr-2 before:size-1 before:rounded-full before:bg-accent">
              <a href="https://www.linkedin.com/in/tiphaine-vapaille-6a39281/"
                target="_blank">Tiphaine Vapaille</a>
            </li>
            <li class="block relative ml-4
                      before:block before:absolute before:top-3 before:-left-4 before:mr-2 before:size-1 before:rounded-full before:bg-accent">
              <a href="https://www.linkedin.com/in/sophie-beignon-02a60b1/"
                target="_blank">Sophie Beignon</a>
            </li>
            <li class="mt-2">
              <a href="mailto:diapsodie@gmail.com"
                class="font-semibold underline underline-offset-3">diapsodie@gmail.com</a>
            </li>
          </ul>
        </div>

      </section>




      <div class="laptop:col-span-6 laptop:col-start-7 laptop:grid laptop:grid-cols-3 laptop:gap-[10%]">
          
        <section class="[ main-links ]
                        flex flex-col gap-4">
          <header class="font-semibold uppercase text-secondary">Liens principaux</header>
          <ul class="[ fade-not-hovered ]
                      flex flex-col gap-4">
<?php // TODO: carlo_get() for menu ?>
            <li><a href="#">Accueil</a></li>
            <li><a href="#">Nos outils</a></li>
            <li><a href="#">Nos offres</a></li>
            <li><a href="#">Nos réalisations</a></li>
            <li><a href="#">Qui sommes-nous ?</a></li>
          </ul>
        </section>





        <section class="[ useful-links ]
                        flex flex-col gap-4">
          <header class="font-semibold uppercase text-secondary">Liens utiles</header>
          <ul class="[ fade-not-hovered ]
                      flex flex-col gap-4">
<?php // TODO: carlo_get() for menu ?>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Blog</a></li>
          </ul>
        </section>





        <section class="[ legal-links ]
                        flex flex-col gap-4">
          <header class="font-semibold uppercase text-secondary">Liens principaux</header>
          <ul class="[ fade-not-hovered ]
                      flex flex-col gap-4">
<?php // TODO: carlo_get() for menu ?>
            <li><a href="#">Mentions légales</a></li>
            <li><a href="#">Confidentialité</a></li>
            <li><a href="#">Politique de gestion des cookies</a></li>
            <li><a href="#">CGV - CGU</a></li>
          </ul>
        </section>
      </div>

    </div>



  </div>
</footer>