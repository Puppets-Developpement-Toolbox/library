
<?php carlo_render(
    "sections/push",
    array_merge(carlo_get(), [
        "slides" => carlo_get("cards"),
        "gap_class" => "gap-6",
        "bg_primary" => true,
        "slide_template" => "components/card:picto",
    ])
); ?>
