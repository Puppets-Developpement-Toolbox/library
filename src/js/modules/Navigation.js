


import Device from './../utils/device';
import Fade from './../utils/fade-in-out';



const Navigation = () => {
  
  const btn = document.querySelector('body > header .hamburger');
  const mainNav = document.querySelector('#main-nav');

  let device = Device();
  let anim = null;
  
  if (!device.laptop) {
    btn.addEventListener('click', (e) => {
      btn.classList.toggle('is-opened');
      anim = (btn.classList.contains('is-opened')) ? 'in' : 'out';
      Fade(mainNav, anim)
    });
  }

};// const Navigation = () => {





Navigation();
