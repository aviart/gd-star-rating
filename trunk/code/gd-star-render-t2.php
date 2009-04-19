<?php

class GDSRRenderT2 {
    function render_srb($template_id, $post_id, $class, $type, $votes, $score, $unit_width, $unit_count, $allow_vote, $user_id, $typecls) {
        include($this->plugin_path.'templates/tpl_list.php');
        $template = new gdTemplateRender($template_id);

        wp_gdsr_dump("MSTR", $template);

        $rating2 = $votes > 0 ? $score / $votes : 0;
        if ($rating2 > $unit_count) $rating2 = $unit_count;
        $rating = @number_format($rating2, 1);

        $rating_width = $rating2 * $unit_width;
        $rater_length = $unit_width * $unit_count;
        $rater_id = $typecls."_rater_".$id;
        $loader_id = $typecls."_loader_".$id;

    }
}

?>