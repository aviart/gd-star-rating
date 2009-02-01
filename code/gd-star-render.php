<?php

class GDSRRender {
    function rss_rating_block($id, $votes, $score, $unit_set, $unit_width, $unit_count, $render, $text, $header, $header_text) {
        $template = get_option('gd-star-rating-templates');
        if ($votes == 1) $tense = $template["word_votes_singular"];
        else $tense = $template["word_votes_plural"];

        if ($votes > 0) $rating2 = $score / $votes;
        else $rating2 = 0;
        if ($rating2 > $unit_count) $rating2 = $unit_count;
        $rating = @number_format($rating2, 1);

        $rater = GDSRRender::rating_header($header, $header_text);
        if ($rater != '') $rater.= "<br />";

        if ($render == "stars") $rater_text = "";
        else {
            if ($text == 'hide' || ($text == 'top_hidden' && $votes == 0) || ($text == 'bottom_hidden' && $votes == 0) || ($text == 'left_hidden' && $votes == 0) || ($text == 'right_hidden' && $votes == 0))
                $rater_text = "";
            else {
                $tpl = $template["rss_article_rating_text"];
                $rt = html_entity_decode($tpl);
                $rt = str_replace('%RATING%', $rating, $rt);
                $rt = str_replace('%MAX_RATING%', $unit_count, $rt);
                $rt = str_replace('%VOTES%', $votes, $rt);
                $rt = str_replace('%WORD_VOTES%', __($tense), $rt);
                $rt = str_replace('%ID%', $id, $rt);
                $rater_text = $rt;
            }
        }
        if ($render != "text") {
            $url = STARRATING_URL.sprintf("gfx.php?value=%s", $rating);
            $rater_stars = '<img src="'.$url.'" />';
        }
        
        if ($rater_text == "" || $text == "hide" || $text == "top" || $text == "top_hidden" || $text == "bottom" || $text == "bottom_hidden" || $rater_text == '') {
            $rater.= $rater_stars;
            if ($rater_text != "") $rater.= '<br />'.$rater_text;
        }
        else {
            if ($text == "left" || $text == "left_hidden") $rater.= $rater_text.$rater_stars;
            else $rater.= $rater_stars.$rater_text;
        }
        return $rater;
    }

    function rating_stars_local($width, $height, $unit_count, $allow_vote = true, $value = 0, $xtra_cls = '') {
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
    
    function rating_stars_multi() {
    }

    function rating_stars($rater_id, $class, $rating_width, $allow_vote, $unit_count, $type, $id, $user_id, $loader_id, $rater_length, $typecls, $ajax = false, $wait_msg = '') {
        $rater = '<div id="'.$rater_id.'" class="'.$class.'"><div class="starsbar">';
        $rater.= '<div class="gdouter" align="left"><div id="gdr_vote_'.$id.'" style="width: '.$rating_width.'px;" class="gdinner"></div>';
        if ($allow_vote) {
            $rater.= '<div id="gdr_stars_'.$id.'" class="gdsr_rating_as">';
            for ($ic = 0; $ic < $unit_count; $ic++) {
                $ncount = $unit_count - $ic;
                $url = $_SERVER['REQUEST_URI'];
                $url_pos = strpos($url, "?");
                if ($url_pos === false) $first_char = '?';
                else $first_char = '&amp;';

                if (!$ajax) {
                    $vote_get = $url.$first_char.'gdsrvotes='.$ncount.'&amp;gdsrtype='.$type.'&amp;gdsrid='.$id;
                    if ($user_id > 0) $vote_get.= '&amp;gdsruser='.$user_id;
                    $rater.='<a href="'.$vote_get.'" title="'.$ncount.' out of '.$unit_count.'" class="s'.$ncount.'" rel="nofollow" onclick="gdsrWait(\''.$rater_id.'\', \''.$loader_id.'\')"></a>';
                }
                else {
                    $ajax_id = sprintf("gdsrX%sX%sX%sX%sX%sX%s", $id, $ncount, $user_id, $type, $rater_id, $loader_id);
                    $rater.='<a id="'.$ajax_id.'" title="'.$ncount.' out of '.$unit_count.'" class="s'.$ncount.'" rel="nofollow"></a>';
                }
            }
            $rater.= '</div>';
        }
        $rater.= '</div></div></div>';
        $rater.= GDSRRender::rating_wait($loader_id, $rater_length, $typecls, $wait_msg);
        return $rater;
    }

    function rating_wait($loader_id, $rater_length, $typecls, $wait_msg = '') {
        $loader = '<div id="'.$loader_id.'" style="display: none; width:'.$rater_length.'px;" class="ratingloader'.$typecls.'">';
        $loader.= $wait_msg;
        $loader.= '</div>';
        return $loader;
    }

    function rating_block_div($rater_stars, $rater_text, $rater_header, $text, $align, $custom_class = "", $debug = "") {
        if ($align != 'none')
            $rater_align = ' align="'.$align.'"';
        else
            $rater_align = '';
                  
        $rater = '<div class="ratingblock '.$custom_class.'"'.$rater_align.'>';
        if ($debug != '') $rater.= '<div style="display: none">'.$debug.'</div>';
        if ($rater_header != '')
            $rater.= '<div class="ratingheader">'.$rater_header.'</div>';
        
        if ($text == 'top' || $text == 'top_hidden')
            $rater.= $rater_text;

        $rater.= $rater_stars;

        if ($text == 'bottom' || $text == 'bottom_hidden')
            $rater.= $rater_text;
        
        $rater.= '</div>';
        return $rater;
    }

    function rating_block_table($rater_stars, $rater_text, $rater_header, $text, $align, $custom_class = "", $debug = "") {
        if ($align != 'none') {
            $rater_header_align = ' style="text-align: '.$align.';"';
            $rater_align = ' align="'.$align.'"';
        }
        else {
            $rater_header_align = '';
            $rater_align = '';
        }

        $rater = '<div class="ratingblock '.$custom_class.'"'.$rater_align.'>';
        if ($debug != '') $rater.= '<div style="display: none">'.$debug.'</div>';
        $rater.= '<table cellpadding="0" cellspacing="0">';
        if ($rater_header != "") 
            $rater.= '<tr><td colspan="2"'.$rater_header_align.'>'.$rater_header.'</td></tr>';
        if (substr($text, 0, 4) == 'left')
            $rater.= '<tr><td>'.$rater_text.'</td><td>'.$rater_stars.'</td></tr>';
        else
            $rater.= '<tr><td>'.$rater_stars.'</td><td>'.$rater_text.'</td></tr>';
            
        $rater.= '</table>';
        $rater.= '</div>';
        return $rater;
        
    }
    
    function rating_header($header, $header_text = '') {
        if ($header == 1)
            return '<div class="ratingheader">'.html_entity_decode($header_text).'</div>';
        else
            return '';
    }
    
    function rating_text($id, $type, $votes, $score, $unit_count, $typecls, $custom_css_text = "") {
        $template = get_option('gd-star-rating-templates');
        if ($votes == 1) $tense = $template["word_votes_singular"];
        else $tense = $template["word_votes_plural"];
        
        if ($votes > 0) $rating2 = $score / $votes;
        else $rating2 = 0;
        $rating1 = @number_format($rating2, 1);
        if ($custom_css_text != "") $custom_css_text = $custom_css_text.' ';

        $rater_text = '<div id="gdr_text_'.$id.'" class="'.$custom_css_text.$typecls.'">';
        switch ($type)
        {
            case 'a':
                $tpl = $template["article_rating_text"];
                $rt = html_entity_decode($tpl);
                $rt = str_replace('%RATING%', $rating1, $rt);
                $rt = str_replace('%MAX_RATING%', $unit_count, $rt);
                $rt = str_replace('%VOTES%', $votes, $rt);
                $rt = str_replace('%WORD_VOTES%', __($tense), $rt);
                $rt = str_replace('%ID%', $id, $rt);
                $rater_text.= $rt;
                break;
            case 'c':
                $tpl = $template["cmm_rating_text"];
                $rt = html_entity_decode($tpl);
                $rt = str_replace('%CMM_RATING%', $rating1, $rt);
                $rt = str_replace('%MAX_CMM_RATING%', $unit_count, $rt);
                $rt = str_replace('%CMM_VOTES%', $votes, $rt);
                $rt = str_replace('%WORD_VOTES%', __($tense), $rt);
                $rt = str_replace('%ID%', $id, $rt);
                $rater_text.= $rt;
                break;
        }
        $rater_text.= '</div>';
        return $rater_text;
    }
    
    function rating_block($id, $class, $type, $votes, $score, $unit_width, $unit_count, $allow_vote, $user_id, $typecls, $align, $text, $header, $header_text, $custom_css_block = "", $custom_css_text = "", $ajax = false, $debug = '', $wait_msg = '', $time_restirctions = "N", $time_remaining = 0, $time_date = '') {
        $template = get_option('gd-star-rating-templates');
        if ($votes == 1) $tense = $template["word_votes_singular"];
        else $tense = $template["word_votes_plural"];

        $rater = '';
        if ($votes > 0) $rating2 = $score / $votes;
        else $rating2 = 0;
        if ($rating2 > $unit_count) $rating2 = $unit_count;
        $rating1 = @number_format($rating2, 1);
        $rating_width = $rating2 * $unit_width;
        $rater_length = $unit_width * $unit_count; 
        $rater_id = $typecls."_rater_".$id;
        $loader_id = $typecls."_loader_".$id;
        
        if ($text == 'hide' || ($text == 'top_hidden' && $votes == 0) || ($text == 'bottom_hidden' && $votes == 0) || ($text == 'left_hidden' && $votes == 0) || ($text == 'right_hidden' && $votes == 0))
            $rater_text = "";
        else {
            $rater_text = '<div id="gdr_text_'.$id.'" class="'.$custom_css_text.' '.$typecls;
            if (!$allow_vote) $rater_text.=' voted';
            $rater_text.= '">';
            switch ($type)
            {
                case 'a':
                    if (($time_restirctions == 'D' || $time_restirctions == 'T') && $time_remaining > 0) {
                        $time_parts = GDSRHelper::remaining_time_parts($time_remaining);
                        $time_total = GDSRHelper::remaining_time_total($time_remaining);
                        $tpl = $template["time_restricted_active"];
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
                            $tpl = $template["time_restricted_closed"];
                        else
                            $tpl = $template["article_rating_text"];
                        $rt = html_entity_decode($tpl);
                    }
                    $rt = str_replace('%RATING%', $rating1, $rt);
                    $rt = str_replace('%MAX_RATING%', $unit_count, $rt);
                    $rt = str_replace('%VOTES%', $votes, $rt);
                    $rt = str_replace('%WORD_VOTES%', __($tense), $rt);
                    $rt = str_replace('%ID%', $id, $rt);
                    $rater_text.= $rt;
                    break;
                case 'c':
                    $tpl = $template["cmm_rating_text"];
                    $rt = html_entity_decode($tpl);
                    $rt = str_replace('%CMM_RATING%', $rating1, $rt);
                    $rt = str_replace('%MAX_CMM_RATING%', $unit_count, $rt);
                    $rt = str_replace('%CMM_VOTES%', $votes, $rt);
                    $rt = str_replace('%WORD_VOTES%', __($tense), $rt);
                    $rt = str_replace('%ID%', $id, $rt);
                    $rater_text.= $rt;
                    break;
            }
            $rater_text.= '</div>';
        }
        
        $rater_stars = GDSRRender::rating_stars($rater_id, $class, $rating_width, $allow_vote, $unit_count, $type, $id, $user_id, $loader_id, $rater_length, $typecls, $ajax, $wait_msg);

        $rater_header = GDSRRender::rating_header($header, $header_text);
        
        if ($text == "hide" || $text == "top" || $text == "top_hidden" || $text == "bottom" || $text == "bottom_hidden" || $rater_text == '')
            return GDSRRender::rating_block_div($rater_stars, $rater_text, $rater_header, $text, $align, $custom_css_block, $debug);
        else
            return GDSRRender::rating_block_table($rater_stars, $rater_text, $rater_header, $text, $align, $custom_css_block, $debug);
    }

    function multi_rating_block($set, $header, $header_text, $debug = "", $custom_class = "") {
        $rater = '<div class="ratingmulti '.$custom_class.'">';
        if ($debug != '') $rater.= '<div class="gdsrdebug">'.$debug.'</div>';
        $rater.= GDSRRender::rating_header($header, $header_text);
        $rater.= '<table class="multitable">';
        $tr_class = "mtrow";
        foreach ($set->object as $el) {
            $rater.= '<tr class="'.$tr_class.'">';
            $rater.= '<td>'.$el.'</td>';
            $rater.= '<td>';
            $rater.= 'STARS';
            $rater.= '</td>';
            $rater.= '</tr>';
            if ($tr_class == "mtrow") $tr_class.= " alternate";
            else $tr_class = "mtrow";
        }
        $rater.= '</table>';
        $rater.= '</div>';
        return $rater;
    }
}

?>