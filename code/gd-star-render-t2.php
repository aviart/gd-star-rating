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

    function prepare_data_retrieve($widget, $min = 0) {
        global $wpdb;
        if ($widget["source"] == "standard")
            $sql = GDSRX::get_widget_standard($widget, $min);
        else
            $sql = GDSRX::get_widget_multis($widget, $min);
        return $wpdb->get_results($sql);
    }

    function prepare_wsr($widget, $template) {
        global $gdsr;
        $bayesian_calculated = !(strpos($template, "%BAYES_RATING%") === false);
        $t_rate = !(strpos($template, "%RATE_TREND%") === false);
        $t_vote = !(strpos($template, "%VOTE_TREND%") === false);
        $a_name = !(strpos($template, "%AUTHOR_NAME%") === false);
        $a_link = !(strpos($template, "%AUTHOR_LINK%") === false);

        if ($widget["column"] == "bayes" && !$bayesian_calculated) $widget["column"] == "rating";
        $all_rows = GDSRRenderT2::prepare_data_retrieve($widget, $gdsr->o["bayesian_minimal"]);

        if (count($all_rows) > 0) {
            $trends = array();
            $trends_calculated = false;
            if ($t_rate || $t_vote) {
                $idx = array();
                foreach ($all_rows as $row) {
                    switch ($widget["grouping"]) {
                        case "post":
                            $id = $row->post_id;
                            break;
                        case "category":
                            $id = $row->term_id;
                            break;
                        case "user":
                            $id = $row->id;
                            break;
                    }
                    $idx[] = $id;
                }
                $trends = GDSRX::get_trend_calculation(join(", ", $idx), $widget["grouping"], $widget['show'], $gdsr->o["trend_last"], $gdsr->o["trend_over"]);
                $trends_calculated = true;
            }

            $new_rows = array();
            foreach ($all_rows as $row) {
                if ($widget["image_from"] == "content") {
                    $row->image = gdFunctionsGDSR::get_image_from_text($row->post_content);
                }
                else if ($widget["image_from"] == "custom") {
                    $row->image = get_post_meta($row->post_id, $widget["image_custom"], true);
                }
                else $row->image = "";
                if ($widget['show'] == "total") {
                    $row->votes = $row->user_votes + $row->visitor_votes;
                    $row->voters = $row->user_voters + $row->visitor_voters;
                }
                if ($widget['show'] == "visitors") {
                    $row->votes = $row->visitor_votes;
                    $row->voters = $row->visitor_voters;
                }
                if ($widget['show'] == "users") {
                    $row->votes = $row->user_votes ;
                    $row->voters = $row->user_voters;
                }

                if ($row->voters == 0) $row->rating = 0;
                else $row->rating = @number_format($row->votes / $row->voters, 1);

                if ($bayesian_calculated)
                    $row->bayesian = $gdsr->bayesian_estimate($row->voters, $row->rating);
                else
                    $row->bayesian = -1;
                $new_rows[] = $row;
            }

            if ($widget["column"] == "bayes" && $bayesian_calculated)
                usort($new_rows, "gd_sort_bayesian_".$widget["order"]);

            $tr_class = $gdsr->x["table_row_even"];
            if ($trends_calculated) {
                $set_rating = $gdsr->g->find_trend($widget["trends_rating_set"]);
                $set_voting = $gdsr->g->find_trend($widget["trends_voting_set"]);
            }

            $all_rows = array();
            foreach ($new_rows as $row) {
                $row->table_row_class = $tr_class;
                if (strlen($row->title) > $widget["tpl_title_length"] - 3 && $widget["tpl_title_length"] > 0)
                    $row->title = substr($row->title, 0, $widget["tpl_title_length"] - 3)." ...";

                if ($a_link || $a_name && intval($row->author) > 0) {
                    $user = get_userdata($row->author);
                    $row->author_name = $user->display_name;
                    $row->author_url = get_bloginfo("url")."/author/".$user->user_login;
                }

                if ($trends_calculated) {
                    $empty = $gdsr->e;

                    switch ($widget["grouping"]) {
                        case "post":
                            $id = $row->post_id;
                            break;
                        case "category":
                            $id = $row->term_id;
                            break;
                        case "user":
                            $id = $row->id;
                            break;
                    }
                    $t = $trends[$id];
                    switch ($widget["trends_rating"]) {
                        case "img":
                            $rate_url = $set_rating->get_url();
                            switch ($t->trend_rating) {
                                case -1:
                                    $image_loc = "bottom";
                                    break;
                                case 0:
                                    $image_loc = "center";
                                    break;
                                case 1:
                                    $image_loc = "top";
                                    break;
                            }
                            $image_bg = sprintf('background: url(%s) %s no-repeat; height: %spx; width: %spx;', $rate_url, $image_loc, $set_rating->size, $set_rating->size);
                            $row->item_trend_rating = sprintf('<img class="trend" src="%s" style="%s" width="%s" height="%s"></img>', $gdsr->e, $image_bg, $set_rating->size, $set_rating->size);
                            break;
                        case "txt":
                            switch ($t->trend_rating) {
                                case -1:
                                    $row->item_trend_rating = $widget["trends_rating_fall"];
                                    break;
                                case 0:
                                    $row->item_trend_rating = $widget["trends_rating_same"];
                                    break;
                                case 1:
                                    $row->item_trend_rating = $widget["trends_rating_rise"];
                                    break;
                            }
                            break;
                    }
                    switch ($widget["trends_voting"]) {
                        case "img":
                            $vote_url = $set_voting->get_url();
                            switch ($t->trend_voting) {
                                case -1:
                                    $image_loc = "bottom";
                                    break;
                                case 0:
                                    $image_loc = "center";
                                    break;
                                case 1:
                                    $image_loc = "top";
                                    break;
                            }
                            $image_bg = sprintf('background: url(%s) %s no-repeat; height: %spx; width: %spx;', $vote_url, $image_loc, $set_voting->size, $set_voting->size);
                            $row->item_trend_voting = sprintf('<img class="trend" src="%s" style="%s" width="%s" height="%s"></img>', $gdsr->e, $image_bg, $set_voting->size, $set_voting->size);
                            break;
                        case "txt":
                            switch ($t->trend_voting) {
                                case -1:
                                    $row->item_trend_voting = $widget["trends_voting_fall"];
                                    break;
                                case 0:
                                    $row->item_trend_voting = $widget["trends_voting_same"];
                                    break;
                                case 1:
                                    $row->item_trend_voting = $widget["trends_voting_rise"];
                                    break;
                            }
                            break;
                    }
                }

                switch ($widget["grouping"]) {
                    case "post":
                        $row->permalink = get_permalink($row->post_id);
                        break;
                    case "category":
                        $row->permalink = get_category_link($row->term_id);
                        break;
                    case "user":
                        $row->permalink = get_bloginfo('url')."/index.php?author=".$row->id;
                        break;
                }

                if ($row->voters > 1) $row->tense = $gdsr->x["word_votes_plural"];
                else $row->tense = $gdsr->x["word_votes_singular"];

                if (!(strpos($template, "%STARS%") === false)) $row->rating_stars = GDSRRender::render_static_stars($widget['rating_stars'], $widget['rating_size'], $gdsr->o["stars"], $row->rating);
                if (!(strpos($template, "%BAYES_STARS%") === false) && $row->bayesian > -1) $row->bayesian_stars = GDSRRender::render_static_stars($widget['rating_stars'], $widget['rating_size'], $gdsr->o["stars"], $row->bayesian);
                if (!(strpos($template, "%REVIEW_STARS%") === false) && $row->review > -1) $row->review_stars = GDSRRender::render_static_stars($widget['rating_stars'], $widget['rating_size'], $gdsr->o["stars"], $row->review);

                if ($tr_class == $gdsr->x["table_row_even"])
                    $tr_class = $gdsr->x["table_row_odd"];
                else
                    $tr_class = $gdsr->x["table_row_even"];

                $all_rows[] = $row;
            }
        }

        $all_rows = apply_filters('gdsr_widget_data_prepare', $all_rows);
        return $all_rows;
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

    function render_wsr($widget) {
        $template = GDSRRenderT2::get_template($widget["template_id"], "WBR");
        $tpl_render = html_entity_decode($template->elm["header"]);
        $rt = html_entity_decode($template->elm["item"]);
        $all_rows = GDSRRenderT2::prepare_wsr($widget, $rt);

        if (count($all_rows) > 0) {
            foreach ($all_rows as $row) {
                $title = $row->title;
                if (strlen($title) == 0) $title = __("(no title)", "gd-star-rating");

                $rt = str_replace('%RATING%', $row->rating, $rt);
                $rt = str_replace('%MAX_RATING%', $this->o["stars"], $rt);
                $rt = str_replace('%VOTES%', $row->voters, $rt);
                $rt = str_replace('%REVIEW%', $row->review, $rt);
                $rt = str_replace('%MAX_REVIEW%', $this->o["review_stars"], $rt);
                $rt = str_replace('%TITLE%', __($title), $rt);
                $rt = str_replace('%PERMALINK%', $row->permalink, $rt);
                $rt = str_replace('%ID%', $row->post_id, $rt);
                $rt = str_replace('%COUNT%', $row->counter, $rt);
                $rt = str_replace('%WORD_VOTES%', __($row->tense), $rt);
                $rt = str_replace('%BAYES_RATING%', $row->bayesian, $rt);
                $rt = str_replace('%BAYES_STARS%', $row->bayesian_stars, $rt);
                $rt = str_replace('%STARS%', $row->rating_stars, $rt);
                $rt = str_replace('%REVIEW_STARS%', $row->review_stars, $rt);
                $rt = str_replace('%RATE_TREND%', $row->item_trend_rating, $rt);
                $rt = str_replace('%VOTE_TREND%', $row->item_trend_voting, $rt);
                $rt = str_replace('%TABLE_ROW_CLASS%', $row->table_row_class, $rt);
                $rt = str_replace('%IMAGE%', $row->image, $rt);
                $rt = str_replace('%AUTHOR_NAME%', $row->author_name, $rt);
                $rt = str_replace('%AUTHOR_LINK%', $row->author_url, $rt);
            }
            $tpl_render.= $rt;
        }

        $tpl_render.= html_entity_decode($template->elm["footer"]);

        return $tpl_render;
    }
}

?>