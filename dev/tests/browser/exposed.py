"""Scénario de test des sections à répéteur et des champs meta: dans l'éditeur (type `restaurant`).

Usage : lando browser tests/browser/exposed.py
Couvre : un élément ajouté à un répéteur n'est pas proposé dans le panneau (ses textes se saisissent
dans le bloc) et la saisie en place est enregistrée ; le panneau Document des champs meta: les
enregistre. Le restaurant créé est supprimé à la fin. Voir aussi tests/php/exposed.php.
CARLO_SITE et CARLO_CHROMIUM permettent de le lancer sur un autre site / navigateur.
"""
import sys

from wp import ADMIN, SITE, admin_page, shot

failures = []


def check(label, ok, detail=None):
    print(("✓ " if ok else "✗ ") + label + (f" : {detail}" if detail is not None and not ok else ""))
    if not ok:
        failures.append(label)


BLOCKS = """() => {
  const walk = (bs) => bs.flatMap(b => [{id: b.clientId, name: b.name}, ...walk(b.innerBlocks)]);
  return walk(wp.data.select('core/block-editor').getBlocks());
}"""

post_id = None
with admin_page() as page:
    nonce = None
    try:
        page.goto(f"{ADMIN}/post-new.php?post_type=restaurant", wait_until="domcontentloaded")
        page.wait_for_function("window.wp?.data?.select('core/block-editor').getBlocks().length > 0", timeout=60000)
        page.keyboard.press("Escape")  # guide de bienvenue éventuel
        post_id = page.evaluate("wp.data.select('core/editor').getCurrentPostId()")
        nonce = page.evaluate("wp.apiFetch.nonceMiddleware.nonce")
        canvas = page.frame_locator('iframe[name="editor-canvas"]')

        # La région est remplie à la première ouverture ; sinon on y insère la section.
        page.wait_for_timeout(3000)
        blocks = page.evaluate(BLOCKS)
        if not any(b["name"].endswith("horaires") for b in blocks):
            region = next(b["id"] for b in blocks if b["name"] == "carlo/region")
            page.evaluate("""(r) => wp.data.dispatch('core/block-editor').insertBlock(wp.blocks.createBlock('carlo/sections-horaires'), 0, r)""", region)
            page.wait_for_timeout(1500)
        horaires = next(b["id"] for b in page.evaluate(BLOCKS) if b["name"].endswith("horaires"))
        attrs = lambda: page.evaluate("(id) => wp.data.select('core/block-editor').getBlock(id).attributes", horaires)

        # 1. Répéteur : l'élément ajouté n'expose jamais de champ texte dans le panneau
        page.evaluate("(id) => wp.data.dispatch('core/block-editor').selectBlock(id)", horaires)
        page.evaluate("wp.data.dispatch('core/edit-post').openGeneralSidebar('edit-post/block')")
        inspector = page.locator(".block-editor-block-inspector")
        inspector.get_by_role("button", name="Ajouter").first.click()
        # Échantillonné pendant le rendu de l'aperçu : les champs ne doivent ni apparaître ni disparaître.
        seen = []
        for _ in range(8):
            seen.append(inspector.locator(".carlo-field__item input").count())
            page.wait_for_timeout(300)
        check("élément ajouté : aucun champ texte dans le panneau (pas de disparition en pleine frappe)", set(seen) == {0}, seen)
        check("élément ajouté : présent dans les attributs", attrs().get("hours") == [{}], attrs())
        check("élément ajouté : champs de saisie dans l'aperçu", canvas.locator("[data-carlo-field]").count() == 2)

        # 2. Saisie en place, enregistrée
        for field, text in (("hours.0.day", "Lundi"), ("hours.0.time", "9h-18h")):
            canvas.locator(f'[data-carlo-field="{field}"]').first.click()
            page.keyboard.type(text)
            page.wait_for_timeout(1000)
        check("saisie en place enregistrée dans les attributs", attrs().get("hours") == [{"day": "Lundi", "time": "9h-18h"}], attrs())
        check("l'aperçu reflète la saisie", "Lundi : 9h-18h" in canvas.locator(".carlo-preview").inner_text())
        shot(page, "exposed-repeater")

        # 3. Champs meta: dans le panneau Document
        page.evaluate("wp.data.dispatch('core/edit-post').openGeneralSidebar('edit-post/document')")
        panel = page.locator(".carlo-meta")
        check("panneau des champs meta: présent", panel.count() == 1)
        panel.get_by_label("Identifiant Google My Business").fill("abc123")
        panel.get_by_label("Accepte les réservations").check()
        shot(page, "exposed-meta")
        page.evaluate("wp.data.dispatch('core/editor').editPost({ title: 'Test exposé', status: 'publish' })")
        page.evaluate("wp.data.dispatch('core/editor').savePost()")
        page.wait_for_function("!wp.data.select('core/editor').isSavingPost()", timeout=30000)
        saved = page.request.get(f"{SITE}/?rest_route=/wp/v2/restaurant/{post_id}&context=edit", headers={"X-WP-Nonce": nonce}).json()
        meta = saved.get("meta", {})
        check("meta enregistrés (API REST)", meta.get("google_place_id") == "abc123" and meta.get("accepts_reservations") is True, meta)
        check("section enregistrée avec le contenu", "Lundi" in saved.get("content", {}).get("raw", ""))

        errors = [e for e in page.errors if "404" not in e and "502" not in e]  # 502 : serveur Vite des assets, voir stories.py
        check("aucune erreur JS", not errors, errors)
    finally:
        if post_id and nonce:
            response = page.request.delete(f"{SITE}/?rest_route=/wp/v2/restaurant/{post_id}&force=true", headers={"X-WP-Nonce": nonce})
            if not response.ok:
                print(f"! restaurant de test {post_id} non supprimé : à supprimer à la main")

print(f"\n{len(failures)} échec(s)" if failures else "\nTout est vert.")
sys.exit(1 if failures else 0)
