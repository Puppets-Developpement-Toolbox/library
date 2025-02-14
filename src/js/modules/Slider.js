


import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';



const CreateSlider = (wrapper) => {

  const hasSwiper = document.querySelectorAll(wrapper);

  if (hasSwiper.length > 0) {
    hasSwiper.forEach((slider) => {
// console.log(slider)
if (slider.classList.contains('slider-cards')) console.log(slider)

      const dashboard = slider.querySelector('.swiper__dashboard');
      const initialSlide = (slider.classList.contains('slider-quotes')) ? (slider.querySelectorAll('.swiper-slide').length - 1) : 0;
      
      let loop = true;
      if (slider.classList.contains('slider-horizontal') || slider.classList.contains('slider-cards')) {
        loop = false;
      }

      let slidesPerView = 1;
      if (slider.classList.contains('slider-horizontal') || slider.classList.contains('slider-cards')) {
        slidesPerView = 'auto';
      }
      
      let spaceBetween = 0;
      if (slider.classList.contains('slider-horizontal')) {
        spaceBetween = 24;
      } else if (slider.classList.contains('slider-cards')) {
        spaceBetween = 32;
      }

      const swiper = new Swiper(slider.querySelector('.swiper'), {
        
        // Init
        loop: loop,
        lazy: true,
        slidesPerView: slidesPerView,
        spaceBetween: spaceBetween,
        initialSlide: initialSlide,
        
        // Contrôles
        grabCursor: true,
        mousewheel: false,
        pagination: {
          el: dashboard.querySelector('.swiper-pagination'),
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
          nextEl: dashboard.querySelector('.swiper-button-next'),
          prevEl: dashboard.querySelector('.swiper-button-prev'),
        },
      });

    });
  }

};





CreateSlider('.has-slider');
