<?php carlo_render('global/html_start') ?>
<?php carlo_context_add('current_page', 'typography') ?>

<main class="page-content" id="main">
  <section class="w-2/3 max-w-[1200px] mx-auto my-[100px]">

    <h1 class="[ h1 ]
              mt-20 mb-10 pb-10
              border-b-2 border-b-black">Colors</h1>
              
    <h2 class="[ h3 ]
              mt-10 mb-5">Primary brand</h2>
    <ul class="grid grid-cols-5 gap-5">
      <li class="overflow-clip pt-[90px]
                rounded-lg border-[1px] border-[#9747FF] bg-white">
        <p class="p-5 bg-white text-sm">
          <strong>White</strong><br>
          #FFFFFF<br>
          rgb(255, 255, 255)<br>
          hsl(0, 0, 100)
        </p>
      </li>
      <li class="overflow-clip pt-[90px]
                rounded-lg border-[1px] border-[#9747FF] bg-black">
        <p class="p-5 bg-white text-sm">
          <strong>Black</strong><br>
          #121212<br>
          rgb(18, 18, 18)<br>
          hsl(0, 0, 7)
        </p>
      </li>
      <li class="overflow-clip pt-[90px]
                rounded-lg border-[1px] border-[#9747FF] bg-accent">
        <p class="p-5 bg-white text-sm">
          <strong>Accent</strong><br>
          #F9B943<br>
          rgb(249, 185, 67)<br>
          hsl(39, 94, 62)
        </p>
      </li>
      <li class="overflow-clip pt-[90px]
                rounded-lg border-[1px] border-[#9747FF] bg-primary-light">
        <p class="p-5 bg-white text-sm">
          <strong>Primary light</strong><br>
          #45C0EB<br>
          rgb(69, 192, 235)<br>
          hsl(196, 81, 60)
        </p>
      </li>
      <li class="overflow-clip pt-[90px]
                rounded-lg border-[1px] border-[#9747FF] bg-primary">
        <p class="p-5 bg-white text-sm">
          <strong>Primary</strong><br>
          #232660<br>
          rgb(35, 38, 96)<br>
          hsl(237, 47, 26)
        </p>
      </li>
    </ul>

    <h2 class="[ h3 ]
              mt-10 mb-5">Secondary brand</h2>
    <ul class="grid grid-cols-5 gap-5">
      <li class="overflow-clip pt-[90px]
                rounded-lg border-[1px] border-[#9747FF] bg-secondary-light">
        <p class="p-5 bg-white text-sm">
          <strong>Secondary Light</strong><br>
          #8C8EAB<br>
          rgb(140, 142, 171)<br>
          hsl(236, 16, 61)
        </p>
      </li>
      <li class="overflow-clip pt-[90px]
                rounded-lg border-[1px] border-[#9747FF] bg-secondary">
        <p class="p-5 bg-white text-sm">
          <strong>Secondary light</strong><br>
          #585A86<br>
          rgb(88, 90, 134)<br>
          hsl(237, 21, 44)
        </p>
      </li>
      <li class="overflow-clip pt-[90px]
                rounded-lg border-[1px] border-[#9747FF] bg-secondary-dark">
        <p class="p-5 bg-white text-sm">
          <strong>Secondary Dark</strong><br>
          #393C70<br>
          rgb(57, 60, 112)<br>
          hsl(237, 33, 33)
        </p>
      </li>
    </ul>

    <h2 class="[ h3 ]
              mt-10 mb-5">Gray</h2>
    <ul class="grid grid-cols-5 gap-5">
      <li class="overflow-clip pt-[90px]
                rounded-lg border-[1px] border-[#9747FF] bg-gray-light">
        <p class="p-5 bg-white text-sm">
          <strong>Gray Light</strong><br>
          #F6F6F6<br>
          rgb(246, 246, 246)<br>
          hsl(0, 0, 96)
        </p>
      </li>
      <li class="overflow-clip pt-[90px]
                rounded-lg border-[1px] border-[#9747FF] bg-gray-dark">
        <p class="p-5 bg-white text-sm">
          <strong>Gray Dark</strong><br>
          #9C9C9C<br>
          rgb(156, 156, 156)<br>
          hsl(0, 0, 61)
        </p>
      </li>
    </ul>





    <h1 class="[ h1 ]
              mt-20 mb-10 pb-10
              border-b-2 border-b-black">Icons</h1>





    <h1 class="[ h1 ]
              mt-20 mb-10 pb-10
              border-b-2 border-b-black">Typography</h1>

    <ul class="flex flex-col gap-5">
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">Quotation Mark</p>
        <p>Bold<br>
          Desk : 64px / 1 / 0 / normalcase<br>
          Mob : 56px / 1 / 0 / normalcase
        </p>
        <p class="quotation-mark">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">h1</p>
        <p>SemiBold<br>
          Desk : 56px / 1 / 0 / normalcase<br>
          Mob : 38px / 1 / 0 / normalcase
        </p>
        <p class="h1">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">h1 italic</p>
        <p>ExtraLight Italic<br>
          Desk : 56px / 1 / 0 / normalcase<br>
          Mob : 38px / 1 / 0 / normalcase
        </p>
        <p class="h1 italic">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">h2</p>
        <p>SemiBold<br>
          Desk : 48px / 1.1 / 0 / normalcase<br>
          Mob : 34px / 1.1 / 0 / normalcase
        </p>
        <p class="h2">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">h2 italic</p>
        <p>ExtraLight Italic<br>
          Desk : 48px / 1.1 / 0 / normalcase<br>
          Mob : 34px / 1.1 / 0 / normalcase
        </p>
        <p class="h2 italic">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">h3</p>
        <p>Bold<br>
          Desk : 32px / 1.1 / 0 / normalcase<br>
          Mob : 28px / 1.1 / 0 / normalcase
        </p>
        <p class="h3">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">h3 italic</p>
        <p>ExtraLight Italic<br>
          Desk : 32px / 1.1 / 0 / normalcase<br>
          Mob : 28px / 1.1 / 0 / normalcase
        </p>
        <p class="h3 italic">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">quote</p>
        <p>Light Italic<br>
          Desk : 32px / 1.2 / 0 / normalcase<br>
          Mob : 24px / 1.2 / 0 / normalcase
        </p>
        <p class="quote">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">h4</p>
        <p>Bold<br>
          Desk : 24px / 1.2 / 0 / normalcase<br>
          Mob : 20px / 1.2 / 0 / normalcase
        </p>
        <p class="h4">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">h4 italic</p>
        <p>Light Italic<br>
          Desk : 24px / 1.2 / 0 / normalcase<br>
          Mob : 20px / 1.2 / 0 / normalcase
        </p>
        <p class="h4 italic">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">h5</p>
        <p>Light<br>
          Desk : 20px / 1.3 / 0 / normalcase<br>
          Mob : 18px / 1.3 / 0 / normalcase
        </p>
        <p class="h5">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">h5 emphase</p>
        <p>SemiBold<br>
          Desk : 20px / 1.3 / 0 / normalcase<br>
          Mob : 18px / 1.3 / 0 / normalcase
        </p>
        <p class="h5"><strong>Lorem ipsum</strong></p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">h5 italic</p>
        <p>Light Italic<br>
          Desk : 20px / 1.3 / 0 / normalcase<br>
          Mob : 18px / 1.3 / 0 / normalcase
        </p>
        <p class="h5 italic">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">nav phone number</p>
        <p>Bold<br>
          Desk : 18px / 1 / 0 / normalcase<br>
          Mob : 
        </p>
        <p class="nav phone-number">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">nav</p>
        <p>Regular<br>
          Desk : 16px / 1 / 0 / normalcase<br>
          Mob : 20px / 1.5 / 0 / normalcase
        </p>
        <p class="nav">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">nav emphase</p>
        <p>Bold<br>
          Desk : 16px / 1 / 0 / normalcase<br>
          Mob : 20px / 1.5 / 0 / normalcase
        </p>
        <p class="nav emphase">Lorem ipsum</p>
      </li>
    </ul>

    <h2 class="[ h3 ]
              my-10">Commons</h2>
    <ul class="flex flex-col gap-5">
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">kicker-subtitle</p>
        <p>Regular<br>
          16px / 1.2 / 0 / uppercase 
        </p>
        <p class="kicker-subtitle">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">body large</p>
        <p>Light<br>
          18px / 1.4 / 0 / normalcase
        </p>
        <p class="large">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]"><strong>body large emphase</strong></p>
        <p>SemiBold<br>
          18px / 1.4 / 0 / normalcase
        </p>
        <p class="large">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">body medium</p>
        <p>Light<br>
          16px / 1.5 / 0 / normalcase
        </p>
        <p>Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">body medium emphase</p>
        <p>SemiBold<br>
          16px / 1.5 / 0 / normalcase
        </p>
        <p><strong>Lorem ipsum</strong></p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">button</p>
        <p>Regular<br>
          16px / 1.5 / 0 / normalcase
        </p>
        <p class="button">Lorem ipsum</p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">button emphase</p>
        <p>Bold<br>
          16px / 1.5 / 0 / normalcase
        </p>
        <p class="button emphase"><strong>Lorem ipsum</strong></p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">body small</p>
        <p>Regular<br>
          14px / 1.5 / 0 / uppercase
        </p>
        <p><small>Lorem ipsum</small></p>
      </li>
      <li class="grid grid-cols-3 gap-5">
        <p class="text-[#9747FF]">body small emphase</p>
        <p>Bold<br>
          14px / 1.5 / 0 / uppercase
        </p>
        <p><small><strong>Lorem ipsum</strong></small></p>
      </li>
    </ul>
   </section>
</main>

<?php carlo_render('global/html_end') ?>