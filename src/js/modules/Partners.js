


const padd = 16;
const speed = 0.5;



const Partners = () => {

  const hasPartners = document.querySelectorAll('.section__partners');
  

  if (hasPartners) {
    hasPartners.forEach((strip) => {

      const partnersTrain = strip.querySelector('.partners');
      const originals = Array.from(strip.querySelectorAll('.partner'));
      let partners = [...originals];
      
      const partnerDims = originals.map((partner) => ({
        width: (partner.getBoundingClientRect().width !== 0) ? partner.getBoundingClientRect().width : 200,
        height: (partner.getBoundingClientRect().height !== 0) ? partner.getBoundingClientRect().height : 'auto'
      }));

      const partnersFill = () => {
        const windowWidth = window.innerWidth;
        let stripWidth = partners.reduce((sum, partner) => sum + partner.getBoundingClientRect().width + padd, 0);
        
        while (stripWidth < windowWidth) {
          originals.forEach((originalPartner) => {
            const clonedPartner = originalPartner.cloneNode(true);
            partnersTrain.appendChild(clonedPartner);
            partners.push(clonedPartner);
          });

          stripWidth = partners.reduce((accumulator, partner) => accumulator + partner.getBoundingClientRect().width + padd, 0);
        }
      };

      partnersFill();
      
      const partnersOrder = Array.from(partners);
      let stripWidth = 0;
      
      partners.forEach((partner, index) => {
        const originalIndex = index % originals.length;
        partner.style.width = `${ partnerDims[originalIndex].width }px`;
        partner.style.height = `${ partnerDims[originalIndex].height }px`;
        partner.style.left = `${stripWidth}px`;
        stripWidth += partnerDims[originalIndex].width + padd;
      });



      const Move = () => {
        const trainRect = partnersTrain.getBoundingClientRect();

        partners.forEach((partner, index) => {
          let currentLeft = parseFloat(partner.style.left);
          
          currentLeft -= speed;
          partner.style.left = `${currentLeft}px`;

          const partnerRect = partner.getBoundingClientRect();
          if (partnerRect.right < 0) {
            const lastPartner = partnersOrder[partnersOrder.length - 1];
            const lastPartnerRect = lastPartner.getBoundingClientRect();
            const newLeft = lastPartnerRect.right + padd;
            
            partner.style.left = `${newLeft - trainRect.left}px`;
            
            partnersOrder.push(partnersOrder.shift());
          }
        });

        requestAnimationFrame(Move);
      };

      Move();

    });
  }

};




Partners();
