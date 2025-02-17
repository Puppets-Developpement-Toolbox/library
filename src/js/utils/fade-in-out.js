


const FADE_INCREMENT = 0.1;



const Fade = (p_elt, p_type) => {

  let opacity = p_type === 'in' ? 0 : 1;

  if (p_type === 'in') {
    p_elt.style.opacity = 0;
    p_elt.style.display = 'block';
  }

  return new Promise((resolve) => {
    const fade = () => {

      if (p_type === 'in') {
        opacity += FADE_INCREMENT;
        if (opacity < 1) {
          p_elt.style.opacity = opacity;
          requestAnimationFrame(fade);
        } else {
          resolve();
        }
        
      } else {
        opacity -= FADE_INCREMENT;
        if (opacity > 0) {
          p_elt.style.opacity = opacity;
          requestAnimationFrame(fade);
        } else {
          p_elt.style.display = 'none';
          resolve();
        }
      }

    };// const fade = () => {

    fade();
  });// return new Promise((resolve) => {
};// const Fade = (p_elt, p_type) => {





export default Fade;
