


import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';



const CreateSlider = (wrapper) => {

  const hasSwiper = document.querySelectorAll(wrapper);

  if (hasSwiper.length > 0) {
    hasSwiper.forEach((slider) => {
// console.log(slider)
// if (slider.classList.contains('slider-horizontal')) console.log(slider)

      const loop = (slider.classList.contains('slider-horizontal')) ? false : true;
      const slidesPerView = (slider.classList.contains('slider-horizontal')) ? 'auto' : 1;
      const spaceBetween = (slider.classList.contains('slider-horizontal')) ? 24 : 0;
      const swiper = new Swiper(slider.querySelector('.swiper'), {
        
        // Init
        loop: false,
        lazy: true,
        slidesPerView: slidesPerView,
        spaceBetween: spaceBetween,
        
        // Contrôles
        grabCursor: true,
        mousewheel: false,
        pagination: {
          el: slider.querySelector('.swiper-pagination'),
          clickable: true,
          renderBullet: (index, className) => {
            return `<button type="button"
                            class="${ className }
                                  size-2
                                  rounded-full !bg-transparent !border-1 !border-gray-dark !opacity-100
                                  transition duration-300"></button>`;
          },
        },
        navigation: {
          nextEl: slider.querySelector('.swiper-button-next'),
          prevEl: slider.querySelector('.swiper-button-prev'),
        },
      });


    });
  }

};





CreateSlider('.has-slider');
