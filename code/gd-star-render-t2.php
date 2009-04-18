<?php

class GDSRRenderT2 {
    function render_srb($template_id, $post_id, $class, $type, $votes, $score, $unit_width, $unit_count, $allow_vote) {
        include($this->plugin_path.'templates/tpl_list.php');
        $template = new gdTemplateRender($template_id);
    }
}

?>