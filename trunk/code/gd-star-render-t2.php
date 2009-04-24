<?php

class GDSRRenderT2 {
    function get_template($template_id, $section) {
        include(STARRATING_PATH.'code/t2/gd-star-t2-templates.php');

        if (intval($template_id) == 0) {
            $t = GDSRDB::get_templates("SRB", true, true);
            $template_id = $t->template_id;
        }

        return new gdTemplateRender($template_id);
    }

    function prepare_wbr($widget) {
        global $gdsr, $wpdb;

        $sql = GDSRX::get_totals_standard($widget);
        $data = $wpdb->get_row($sql);

        $data->max_rating = $gdsr->o["stars"];
        if ($data->votes == null) {
            $data->votes = 0;
            $data->voters = 0;
        }
        if ($data->votes > 0) {
            $data->rating = @number_format($data->votes / $data->voters, 1);
            $data->bayes_rating = $gdsr->bayesian_estimate($data->voters, $data->rating);
            $data->percentage = floor((100 / $data->max_rating) * $data->rating);
        }

        return $data;
    }

    function render_srb($template_id, $post_id, $class, $type, $votes, $score, $unit_width, $unit_count, $allow_vote, $user_id, $typecls, $tags_css, $header_text, $debug = '', $wait_msg = '', $time_restirctions = "N", $time_remaining = 0, $time_date = '') {
        $template = GDSRRenderT2::get_template($template_id, "SRB");
        $tpl_render = $template->elm["normal"];
        $tpl_render = html_entity_decode($tpl_render);
        foreach ($tags_css as $tag => $value) $tpl_render = str_replace('%'.$tag.'%', $value, $tpl_render);
        $tpl_render = str_replace("%HEADER_TEXT%", html_entity_decode($header_text), $tpl_render);

        $rating2 = $votes > 0 ? $score / $votes : 0;
        if ($rating2 > $unit_count) $rating2 = $unit_count;
        $rating = @number_format($rating2, 1);

        $rating_width = $rating2 * $unit_width;
        $rater_length = $unit_width * $unit_count;
        $rater_id = $typecls."_rater_".$post_id;
        $loader_id = $typecls."_loader_".$post_id;

        if (in_array("%RATING_STARS%", $template->tag["normal"])) {
            $rating_stars = GDSRRender::rating_stars($rater_id, $class, $rating_width, $allow_vote, $unit_count, $type, $post_id, $user_id, $loader_id, $rater_length, $typecls, $wait_msg, $template_id);
            $tpl_render = str_replace("%RATING_STARS%", $rating_stars, $tpl_render);
        }

        if (in_array("%RATING_TEXT%", $template->tag["normal"])) {
            $rating_text = GDSRRenderT2::render_srt($template->dep["SRT"], $rating, $unit_count, $votes, $post_id, $time_restirctions, $time_remaining, $time_date);
            $rating_text = '<div id="gdr_text_'.$type.$post_id.'">'.$rating_text.'</div>';
            $tpl_render = str_replace("%RATING_TEXT%", $rating_text, $tpl_render);
        }

        $tpl_render = '<div class="ratingblock">'.$tpl_render;
        if ($debug != '') $tpl_render = '<div style="display: none">'.$debug.'</div>'.$tpl_render;
        $tpl_render.= '</div>';

        return $tpl_render;
    }

    function render_crb($template_id, $cmm_id, $class, $type, $votes, $score, $unit_width, $unit_count, $allow_vote, $user_id, $typecls, $tags_css, $header_text, $debug = '', $wait_msg = '') {
        $template = GDSRRenderT2::get_template($template_id, "CRB");
        $tpl_render = $template->elm["normal"];
        $tpl_render = html_entity_decode($tpl_render);
        foreach ($tags_css as $tag => $value) $tpl_render = str_replace('%'.$tag.'%', $value, $tpl_render);
        $tpl_render = str_replace("%CMM_HEADER_TEXT%", html_entity_decode($header_text), $tpl_render);

        $rating2 = $votes > 0 ? $score / $votes : 0;
        if ($rating2 > $unit_count) $rating2 = $unit_count;
        $rating = @number_format($rating2, 1);

        $rating_width = $rating2 * $unit_width;
        $rater_length = $unit_width * $unit_count;
        $rater_id = $typecls."_rater_".$cmm_id;
        $loader_id = $typecls."_loader_".$cmm_id;

        if (in_array("%CMM_RATING_STARS%", $template->tag["normal"])) {
            $rating_stars = GDSRRender::rating_stars($rater_id, $class, $rating_width, $allow_vote, $unit_count, $type, $cmm_id, $user_id, $loader_id, $rater_length, $typecls, $wait_msg, $template_id);
            $tpl_render = str_replace("%CMM_RATING_STARS%", $rating_stars, $tpl_render);
        }

        if (in_array("%CMM_RATING_TEXT%", $template->tag["normal"])) {
            $rating_text = GDSRRenderT2::render_crt($template->dep["CRT"], $rating, $unit_count, $votes, $cmm_id);
            $rating_text = '<div id="gdr_text_'.$type.$cmm_id.'">'.$rating_text.'</div>';
            $tpl_render = str_replace("%CMM_RATING_TEXT%", $rating_text, $tpl_render);
        }

        $tpl_render = '<div class="ratingblock">'.$tpl_render;
        if ($debug != '') $tpl_render = '<div style="display: none">'.$debug.'</div>'.$tpl_render;
        $tpl_render.= '</div>';

        return $tpl_render;
    }

    function render_srt($template, $rating, $unit_count, $votes, $id, $time_restirctions = "N", $time_remaining = 0, $time_date = '') {
        if (($time_restirctions == 'D' || $time_restirctions == 'T') && $time_remaining > 0) {
            $time_parts = GDSRHelper::remaining_time_parts($time_remaining);
            $time_total = GDSRHelper::remaining_time_total($time_remaining);
            $tpl = $template->elm["time_active"];
            $rt = html_entity_decode($tpl);
            $rt = str_replace('%TR_DATE%', $time_date, $rt);
            $rt = str_replace('%TR_YEARS%', $time_parts["year"], $rt);
            $rt = str_replace('%TR_MONTHS%', $time_parts["month"], $rt);
            $rt = str_replace('%TR_DAYS%', $time_parts["day"], $rt);
            $rt = str_replace('%TR_HOURS%', $time_parts["hour"], $rt);
            $rt = str_replace('%TR_MINUTES%', $time_parts["minute"], $rt);
            $rt = str_replace('%TR_SECONDS%', $time_parts["second"], $rt);
            $rt = str_replace('%TOT_DAYS%', $time_total["day"], $rt);
            $rt = str_replace('%TOT_HOURS%', $time_total["hour"], $rt);
            $rt = str_replace('%TOT_MINUTES%', $time_total["minute"], $rt);
        }
        else {
            if ($time_restirctions == 'D' || $time_restirctions == 'T')
                $tpl = $template->elm["time_closed"];
            else
                $tpl = $template->elm["normal"];
            $rt = html_entity_decode($tpl);
        }
        $rt = str_replace('%RATING%', $rating, $rt);
        $rt = str_replace('%MAX_RATING%', $unit_count, $rt);
        $rt = str_replace('%VOTES%', $votes, $rt);
        $rt = str_replace('%ID%', $id, $rt);

        $word_votes = $template->dep["EWV"];
        $tense = $votes == 1 ? $word_votes->elm["singular"] : $word_votes->elm["plural"];
        $rt = str_replace('%WORD_VOTES%', __($tense), $rt);

        return $rt;
    }

    function render_crt($template, $rating, $unit_count, $votes, $id) {
        $tpl = $template->elm["normal"];

        $rt = html_entity_decode($tpl);
        $rt = str_replace('%CMM_RATING%', $rating, $rt);
        $rt = str_replace('%MAX_CMM_RATING%', $unit_count, $rt);
        $rt = str_replace('%CMM_VOTES%', $votes, $rt);
        $rt = str_replace('%ID%', $id, $rt);

        $word_votes = $template->dep["EWV"];
        $tense = $votes == 1 ? $word_votes->elm["singular"] : $word_votes->elm["plural"];
        $rt = str_replace('%WORD_VOTES%', __($tense), $rt);

        return $rt;
    }

    function render_wbr($widget) {
        $template = GDSRRenderT2::get_template($widget["template_id"], "WBR");
        $tpl_render = $template->elm["normal"];
        $tpl_render = html_entity_decode($tpl_render);
        $data = GDSRRenderT2::prepare_wbr($widget);
        
        $rt = str_replace('%PERCENTAGE%', $data->percentage, $tpl_render);
        $rt = str_replace('%RATING%', $data->rating, $rt);
        $rt = str_replace('%MAX_RATING%', $data->max_rating, $rt);
        $rt = str_replace('%VOTES%', $data->voters, $rt);
        $rt = str_replace('%COUNT%', $data->count, $rt);
        $rt = str_replace('%BAYES_RATING%', $data->bayes_rating, $rt);

        $word_votes = $template->dep["EWV"];
        $tense = $data->voters == 1 ? $word_votes->elm["singular"] : $word_votes->elm["plural"];
        $rt = str_replace('%WORD_VOTES%', __($tense), $rt);

        return $rt;
    }
}

?>