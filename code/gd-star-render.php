<?php

class GDSRRender
{
    function rating_stars($rater_id, $class, $rating_width, $allow_vote, $unit_count, $type, $id, $user_id, $loader_id, $rater_length, $typecls, $ajax = false) {
        $rater = '<div id="'.$rater_id.'" class="'.$class.'"><div class="starsbar">';
        $rater.= '<div class="outer" align="left"><div id="gdr_vote_'.$id.'" style="width: '.$rating_width.'px;" class="inner"></div>';
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
        $rater.= '<div id="'.$loader_id.'" style="display: none; width:'.$rater_length.'px;" class="ratingloader'.$typecls.'">please wait...</div>';
        return $rater;
    }
    
    function rating_block_div($rater_stars, $rater_text, $rater_header, $text, $align, $custom_class = "") {
        if ($align != 'none')
            $rater_align = ' align="'.$align.'"';
        else
            $rater_align = '';
                  
        $rater = '<div class="ratingblock '.$custom_class.'"'.$rater_align.'>';
        if ($rater_header != '')
            $rater.= '<div class="ratingheader">'.$rater_header.'</div>';
        
        if ($text == 'top' || $text == 'top_hidden')
            $rater.= $rater_text;
        
        $rater.= $rater_stars;
        
        if ($text == 'bottom' || $text == 'top_bottom')
            $rater.= $rater_text;
        
        $rater.= '</div>';
        return $rater;
    }

    function rating_block_table($rater_stars, $rater_text, $rater_header, $text, $align, $custom_class = "") {
        if ($align != 'none') {
            $rater_header_align = ' style="text-align: '.$align.';"';
            $rater_align = ' align="'.$align.'"';
        }
        else {
            $rater_header_align = '';
            $rater_align = '';
        }
                  
        $rater = '<div class="ratingblock '.$custom_class.'"'.$rater_align.'>';
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
            return '<div class="ratingheader">'.$header_text.'</div>';
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
                $rt = str_replace('%WORD_VOTES%', $tense, $rt);
                $rater_text.= $rt;
                break;
            case 'c':
                $tpl = $template["cmm_rating_text"];
                $rt = html_entity_decode($tpl);
                $rt = str_replace('%CMM_RATING%', $rating1, $rt);
                $rt = str_replace('%MAX_CMM_RATING%', $unit_count, $rt);
                $rt = str_replace('%CMM_VOTES%', $votes, $rt);
                $rt = str_replace('%WORD_VOTES%', $tense, $rt);
                $rater_text.= $rt;
                break;
        }
        $rater_text.= '</div>';
        return $rater_text;
    }
    
    function rating_block($id, $class, $type, $votes, $score, $unit_width, $unit_count, $allow_vote, $user_id, $typecls, $align, $text, $header, $header_text, $custom_css_block = "", $custom_css_text = "", $ajax = false) {
        $template = get_option('gd-star-rating-templates');
        if ($votes == 1) $tense = $template["word_votes_singular"];
        else $tense = $template["word_votes_plural"];

        $rater = '';
        if ($votes > 0) $rating2 = $score / $votes;
        else $rating2 = 0;
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
                    $tpl = $template["article_rating_text"];
                    $rt = html_entity_decode($tpl);
                    $rt = str_replace('%RATING%', $rating1, $rt);
                    $rt = str_replace('%MAX_RATING%', $unit_count, $rt);
                    $rt = str_replace('%VOTES%', $votes, $rt);
                    $rt = str_replace('%WORD_VOTES%', $tense, $rt);
                    $rater_text.= $rt;
                    break;
                case 'c':
                    $tpl = $template["cmm_rating_text"];
                    $rt = html_entity_decode($tpl);
                    $rt = str_replace('%CMM_RATING%', $rating1, $rt);
                    $rt = str_replace('%MAX_CMM_RATING%', $unit_count, $rt);
                    $rt = str_replace('%CMM_VOTES%', $votes, $rt);
                    $rt = str_replace('%WORD_VOTES%', $tense, $rt);
                    $rater_text.= $rt;
                    break;
            }
            $rater_text.= '</div>';
        }
        
        $rater_stars = GDSRRender::rating_stars($rater_id, $class, $rating_width, $allow_vote, $unit_count, $type, $id, $user_id, $loader_id, $rater_length, $typecls, $ajax);

        $rater_header = GDSRRender::rating_header($header, $header_text);
        
        if ($text == "hide" || $text == "top" || $text == "top_hidden" || $text == "bottom" || $text == "bottom_hidden" || $rater_text == '')
            return GDSRRender::rating_block_div($rater_stars, $rater_text, $rater_header, $text, $align, $custom_css_block);
        else
            return GDSRRender::rating_block_table($rater_stars, $rater_text, $rater_header, $text, $align, $custom_css_block);
    }
}

?>