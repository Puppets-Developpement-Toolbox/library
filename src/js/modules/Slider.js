


import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';



const CreateSlider = (wrapper) => {

// console.log(wrapper)
// console.log(document.querySelector(`${ wrapper } .swiper`));
  const hasSwiper = document.querySelectorAll(wrapper);

  if (hasSwiper.length > 0) {
    const swiper = new Swiper('.swiper', {
      loop: true,
      lazy: true,
      
      // Contrôles
      grabCursor: true,
      mousewheel: false,
      pagination: {
        el: '.swiper__dashboard .swiper-pagination',
        clickable: true,
        renderBullet: (index, className) => {
          return `<button class="${ className }
                                size-2
                                rounded-full !bg-transparent !border-1 !border-gray-dark !opacity-100
                                transition duration-300"></button>`;
        },
      },
      navigation: {
        nextEl: '.swiper__dashboard .swiper-button-next',
        prevEl: '.swiper__dashboard .swiper-button-prev',
      },
    });    
  }

};





CreateSlider('.section__text-image');
