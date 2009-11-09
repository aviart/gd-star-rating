<?php

/**
 * Renders Google Rich Snippet code block.
 *
 * @param string $settings rendering parameters
 * @param bool $echo echo results or return it as a string
 * @return string html with rendered contents
 */
function gdsr_render_google_rich_snippets($settings = array(), $echo = true) {
    global $gdsr;

    $defaults = array("active" => 1, "format" => "microformat", "source" => "standard_rating", "post_id" => 0);
    $settings = wp_parse_args($settings, $defaults);
    $settings = apply_filters('gdsr_fn_render_google_rich_snippets', $settings);
    if ($settings["post_id"] == 0) global $post;
    else $post = get_post($settings["post_id"]);
    $render = $gdsr->f->render_google_rich_snippet($post, $settings);

    if ($echo) echo $render; else return $render;
}

/**
 * Renders custom stars image (or div block).
 *
 * @param string $settings rendering parameters
 * @param bool $echo echo results or return it as a string
 * @return string html with rendered contents
 */
function gdsr_render_stars_custom($settings = array(), $echo = true) {
    global $gdsr;

    $defaults = array("vote" => 0, "max_value" => 10, "style" => "oxygen",
        "style_ie6" => "oxygen_gif", "size" => 20, "star_factor" => 1);
    $settings = wp_parse_args($settings, $defaults);
    $settings = apply_filters('gdsr_fn_render_stars_custom', $settings);
    $render = $gdsr->f->render_stars_custom_value($settings);

    if ($echo) echo $render; else return $render;
}

/**
 * Renders widget-like element based on the $widget settings array
 *
 * @param array $widget settings to use for rendering
 * @param bool $echo echo results or return it as a string
 * @return string html with rendered contents
 */
function gdsr_render_star_rating_widget($widget = array(), $echo = true) {
    global $gdsr;

    $widget = wp_parse_args((array)$widget, $gdsr->default_widget);
    if ($echo) echo GDSRRenderT2::render_wsr($widget);
    else return GDSRRenderT2::render_wsr($widget);
}

/**
 * Renders blog rating widget element based on the $widget settings array
 *
 * @param array $widget settings to use for rendering
 * @param bool $echo echo results or return it as a string
 * @return string html with rendered contents
 */
function gdsr_render_blog_rating_widget($widget = array(), $echo = true) {
    global $gdsr;

    $widget = wp_parse_args((array)$widget, $gdsr->default_widget_top);
    if ($echo) echo GDSRRenderT2::render_wbr($widget);
    else return GDSRRenderT2::render_wbr($widget);
}

/**
 * Renders comments rating widget element based on the $widget settings array
 *
 * @param array $widget settings to use for rendering
 * @param bool $echo echo results or return it as a string
 * @return string html with rendered contents
 */
function gdsr_render_comments_rating_widget($widget = array(), $echo = true) {
    global $gdsr;

    $widget = wp_parse_args((array)$widget, $gdsr->default_widget_comments);
    if ($echo) echo GDSRRenderT2::render_wcr($widget);
    else return GDSRRenderT2::render_wcr($widget);
}

?>