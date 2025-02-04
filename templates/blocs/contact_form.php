<section class="section contact-form <?= carlo_get('deco_color') ?>">
  <div class="desc">
    <h2 class="t2">
      <?= carlo_get('title') ?>
    </h2>
    <div class="text">
      <?= carlo_get('description') ?>
    </div>
  </div>
  <div class="form">
    <form>
      <fieldset>
        <div class="form-type-textfield">
          <label for="firstname">Votre prénom</label>
          <input type="text" name="firstname" id="firstname" class="required" required placeholder="Prénom *">
        </div>
        <div class="form-type-textfield">
          <label for="form-type-textfield-m">Votre nom</label>
          <input type="text" name="form-type-textfield-m" id="form-type-textfield-m" class="required" required placeholder="Nom *">
        </div>
        <div class="form-type-textfield">
          <label for="form-type-textfield">Société</label>
          <input type="text" name="form-type-textfield" id="form-type-textfield" placeholder="Entreprise">
        </div>
        <div class="form-type-textfield">
          <label for="email-m">Votre email</label>
          <input type="email" name="email-m" id="email-m" class="required" required placeholder="Email *">
        </div>
        <div class="form-type-textfield">
          <label for="tel">Votre téléphone</label>
          <input type="tel" name="tel" id="tel" class="required" required placeholder="Téléphone *">
        </div>
        <div class="form-type-textarea">
          <label for="textarea2">Votre message</label>
          <textarea name="textarea2" id="textarea2" rows="10" cols="50" class="required" required placeholder="Votre message *"></textarea>
        </div>
        <div class="form-type-checkbox">
          <input type="checkbox" name="mandatory-checkbox" id="mandatory-checkbox">
          <label tabindex="0" for="mandatory-checkbox">Je donne mon accord pour être contacté par l’association Innovation Factory</label>
        </div>
      </fieldset>
      
      <fieldset>
        <button type="submit" id="form-submit" class="btn js-submit">
          <span>
            Envoyer le message
          </span>
        </button>
        
        <div class="form-success">
          Votre message a été envoyé avec succès
        </div>
      </fieldset>
      <div class="infos">
        * Les champs marqués par une étoile sont obligatoires.
      </div>
    </form>
  </div>
</section>