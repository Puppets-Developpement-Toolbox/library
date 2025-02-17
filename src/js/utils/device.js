

const Device = () => {

  // ---------- Init

  let device = {
    phone: false,
    tablet: false,
    laptop: false
  };

  // ---------- Traitement

  if (window.innerWidth < 768) {
    device.phone = true;
    device.tablet = false;
    device.laptop = false;
  } else if (window.innerWidth >= 768 && window.innerWidth < 1280) {
    device.phone = false;
    device.tablet = true;
    device.laptop = false;
  } else if (window.innerWidth >= 1280) {
    device.phone = false;
    device.tablet = false;
    device.laptop = true;
  } else {
    console.error('function Device() failed');
  }

  // ---------- Output
  return device;
  
}; // const Device = () => {



export default Device;
