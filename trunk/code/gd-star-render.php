<?php

class GDSRRender {
    function rating_stars($rater_id, $class, $rating_width, $allow_vote, $unit_count, $type, $id, $user_id, $loader_id, $rater_length, $typecls, $wait_msg = '', $template_id = 0) {
        $rater = '<div id="'.$rater_id.'" class="'.$class.'"><div class="starsbar">';
        $rater.= '<div class="gdouter" align="left"><div id="gdr_vote_'.$type.$id.'" style="width: '.$rating_width.'px;" class="gdinner"></div>';
        if ($allow_vote) {
            $rater.= '<div id="gdr_stars_'.$type.$id.'" class="gdsr_rating_as">';
            for ($ic = 0; $ic < $unit_count; $ic++) {
                $ncount = $unit_count - $ic;
                $url = $_SERVER['REQUEST_URI'];
                $url_pos = strpos($url, "?");
                if ($url_pos === false) $first_char = '?';
                else $first_char = '&amp;';
                $ajax_id = sprintf("gdsrX%sX%sX%sX%sX%sX%sX%s", $id, $ncount, $user_id, $type, $rater_id, $loader_id, $template_id);
                $rater.='<a id="'.$ajax_id.'" title="'.$ncount.' out of '.$unit_count.'" class="s'.$ncount.'" rel="nofollow"></a>';
            }
            $rater.= '</div>';
        }
        $rater.= '</div></div></div>';
        if ($allow_vote) $rater.= GDSRRender::rating_wait($loader_id, $rater_length."px", $typecls, $wait_msg);
        return $rater;
    }

    function rating_wait($loader_id, $rater_length, $typecls, $wait_msg = '') {
        $loader = '<div id="'.$loader_id.'" style="display: none; width:'.$rater_length.';" class="ratingloader'.$typecls.'">';
        $loader.= $wait_msg;
        $loader.= '</div>';
        return $loader;
    }

    function render_static_stars($star_style, $star_size, $star_max, $rating) {
        switch (STARRATING_STARS_GENERATOR) {
            case "GFX":
                return GDSRRender::render_static_stars_gfx($star_style, $star_size, $star_max, $rating);
                break;
            default:
            case "DIV":
                return GDSRRender::render_static_stars_div($star_style, $star_size, $star_max, $rating);
                break;
        }
    }

    function render_static_stars_div($star_style, $star_size, $star_max, $rating) {
        global $gdsr;

        $gfx = $gdsr->g->find_stars($star_style);
        $star_path = $gfx->get_url($star_size);
        $full_width = $star_size * $star_max;
        $rate_width = $star_size * $rating;
        
        return sprintf('<div style="%s"><div style="%s"></div></div>',
            sprintf('text-align:left; padding: 0; margin: 0; background: url(%s); height: %spx; width: %spx;', $star_path, $star_size, $full_width),
            sprintf('background: url(%s) bottom left; padding: 0; margin: 0; height: %spx; width: %spx;', $star_path, $star_size, $rate_width)
        );
    }

    function render_static_stars_gfx($star_style, $star_size, $star_max, $rating) {
        $url = STARRATING_URL.sprintf("gfx.php?value=%s&set=%s&size=%s&max=%s", $rating, $star_style, $star_size, $star_max);
        return sprintf('<img src="%s" />', $url);
    }

    // old functions
    function rating_stars_multi($post_id, $set_id, $id, $height, $unit_count, $allow_vote = true, $value = 0, $xtra_cls = '', $review_mode = false) {
        if ($review_mode) $current = $value;
        else $current = 0;
        $rater.= '<div id="gdsr_mur_stars_'.$post_id.'_'.$set_id.'_'.$id.'" class="ratemulti"><div class="starsbar">';
        $rater.= '<div class="gdouter" align="left" style="width: '.($unit_count * $height).'px">';
        $rater.= '<div id="gdsr_mur_stars_rated_'.$post_id.'_'.$set_id.'_'.$id.'" style="width: '.($value * $height).'px;" class="gdinner"></div>';
        if ($allow_vote) {
            $rater.= '<div id="gdsr_mur_stars_current_'.$post_id.'_'.$set_id.'_'.$id.'" style="width: '.($current * $height).'px;" class="gdcurrent"></div>';
            $rater.= '<div id="gdr_stars_mur_rating_'.$post_id.'_'.$set_id.'_'.$id.'" class="gdsr_multis_as">';
            for ($ic = 0; $ic < $unit_count; $ic++) {
                $ncount = $unit_count - $ic;
                $rater.='<a id="gdsrX'.$post_id.'X'.$set_id.'X'.$id.'X'.$ncount.'X'.$height.'" title="'.$ncount.' out of '.$unit_count.'" class="s'.$ncount.' '.$xtra_cls.'" rel="nofollow"></a>';
            }
            $rater.= '</div>';
        }
        $rater.= '</div></div></div>';
        return $rater;
    }

    function rating_stars_local($height, $unit_count, $allow_vote = true, $value = 0, $xtra_cls = '') {
        $rater = '<input type="hidden" id="gdsr_cmm_review" name="gdsr_cmm_review" value="0" />';
        $rater.= '<div id="gdsr_cmm_stars" class="reviewcmm"><div class="starsbar">';
        $rater.= '<div class="gdouter" align="left"><div id="gdsr_cmm_stars_rated" style="width: '.$value.'px;" class="gdinner"></div>';
        if ($allow_vote) {
            $rater.= '<div id="gdr_stars_cmm_review" class="gdsr_review_as">';
            for ($ic = 0; $ic < $unit_count; $ic++) {
                $ncount = $unit_count - $ic;
                $rater.='<a id="gdsrX'.$ncount.'X'.$height.'" title="'.$ncount.' out of '.$unit_count.'" class="s'.$ncount.' '.$xtra_cls.'" rel="nofollow"></a>';
            }
            $rater.= '</div>';
        }
        $rater.= '</div></div></div>';
        return $rater;
    }

    function rating_header($header, $header_text = '') {
        if ($header == 1)
            return '<div class="ratingheader">'.html_entity_decode($header_text).'</div>';
        else
            return '';
    }

    function multi_rating_review($votes, $post_id, $set, $height, $allow_vote = true, $class_block = "gdsr-review-block", $class_table = "gdsr-review-table") {
        return GDSRRender::multi_rating_block("", $allow_vote, $votes, false, $post_id, $set, $height, 0, "", $class_block, "", $class_table, "", "N", 0, "", false, "", true);
    }

    function multi_rating_block($wait_msg, $allow_vote, $votes, $debug, $post_id, $set, $height, $header, $header_text, $custom_class_block = "", $custom_class_text = "", $custom_class_table = "", $custom_class_button = "", $time_restirctions = "N", $time_remaining = 0, $time_date = "", $button_active = true, $button_text = "Submit", $review_mode = false) {
        $rater = '<div id="gdsr_mur_block_'.$post_id.'_'.$set->multi_id.'" class="ratingmulti '.$custom_class_block.'">';
        if ($debug != '') $rater.= '<div style="display: none">'.$debug.'</div>';

        $empty_value = str_repeat("0X", count($set->object));
        $empty_value = substr($empty_value, 0, strlen($empty_value) - 1);

        if ($review_mode) {
            $original_value = "";
            foreach($votes as $vote) $original_value.= number_format($vote["rating"], 0)."X";
            $original_value = substr($original_value, 0, strlen($original_value) - 1);
            $rater.= '<input type="hidden" id="gdsr_mur_review_'.$post_id.'_'.$set->multi_id.'" name="gdsrmurreview['.$post_id.']['.$set->id.']" value="'.$original_value.'" />';
            $empty_value = $original_value;
        }
        if ($allow_vote) $rater.= '<input type="hidden" id="gdsr_multi_'.$post_id.'_'.$set->multi_id.'" name="gdsrmulti['.$post_id.']['.$set->id.']" value="'.$empty_value.'" />';
        $rater.= GDSRRender::rating_header($header, $header_text);
        $rater.= '<table class="gdmultitable '.$custom_class_table.'" cellpadding="0" cellspacing="0">';
        $tr_class = "mtrow";
        $i = 0;
        $weighted = 0;
        $total_votes = 0;
        $weight_norm = array_sum($set->weight);
        foreach ($set->object as $el) {
            $rater.= '<tr class="'.$tr_class.'"><td>'.$el.':</td><td>';
            $rater.= GDSRRender::rating_stars_multi($post_id, $set->multi_id, $i, $height, $set->stars, $allow_vote, $votes[$i]["rating"], "", $review_mode);
            $rater.= '</td></tr>';
            if ($tr_class == "mtrow") $tr_class.= " alternate";
            else $tr_class = "mtrow";
            $weighted += ( $votes[$i]["rating"] * $set->weight[$i] ) / $weight_norm;
            $total_votes += $votes[$i]["votes"];
            $i++;
        }
        if (!$review_mode) {
            $templates = get_option('gd-star-rating-templates');
            $rating = @number_format($weighted, 1);
            $total_votes = @number_format($total_votes / $i, 0);
            if ($total_votes == 1) $tense = $templates["word_votes_singular"];
            else $tense = $templates["word_votes_plural"];

            if (($time_restirctions == 'D' || $time_restirctions == 'T') && $time_remaining > 0) {
                $time_parts = GDSRHelper::remaining_time_parts($time_remaining);
                $time_total = GDSRHelper::remaining_time_total($time_remaining);
                $tpl = $templates["multis_time_restricted_active"];
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
                    $tpl = $templates["multis_time_restricted_closed"];
                else
                    $tpl = $templates["multis_rating_text"];
                $rt = html_entity_decode($tpl);
            }
            $rt = str_replace('%RATING%', $rating, $rt);
            $rt = str_replace('%MAX_RATING%', $set->stars, $rt);
            $rt = str_replace('%VOTES%', $total_votes, $rt);
            $rt = str_replace('%WORD_VOTES%', __($tense), $rt);
            $rt = str_replace('%ID%', $post_id, $rt);

            $rater.= '<tr class="gdtblbottom"><td colspan="2">';
            $rater.= '<div id="gdsr_mur_text_'.$post_id.'_'.$set->multi_id.'">';
                $rater.= '<div class="ratingtextmulti '.$custom_class_text.($allow_vote ? "" : " voted").'">'.$rt.'</div>';
                if ($allow_vote && $button_active) $rater.= '<div class="ratingbutton gdinactive gdsr_multisbutton_as '.$custom_class_button.'" id="gdsr_button_'.$post_id.'_'.$set->multi_id.'"><a rel="nofollow">'.$button_text.'</a></div>';
            $rater.= '</div>';
            if ($allow_vote) $rater.= GDSRRender::rating_wait("gdsr_mur_loader_".$post_id."_".$set->multi_id, "100%", $typecls, $wait_msg);
            $rater.= '</td></tr>';
        }
        $rater.= '</table></div>';
        return $rater;
    }
}

?>