<?php

/*
Name:    gdGoogleRichSnippets
Version: 1.6.0
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: http://www.gdragon.info/

== Copyright ==

Copyright 2008-2009 Milan Petrovic (email: milan@gdragon.info)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists('gdGoogleRichSnippetsGDSR')) {
    /**
     * Class for generating Google Rich Snippets elements.
     */
    class gdGoogleRichSnippetsGDSR {
        var $snippet_type;

        /**
         * Constructor
         *
         * @param string $snippet_type microformat or rdf
         */
        function gdGoogleRichSnippetsGDSR($snippet_type = "microformat") {
            $this->snippet_type = $snippet_type;
        }

        /**
         * Render snippet with thumbs rating.
         *
         * @param array $options settings for snippet
         * @return string rendered snippet code
         */
        function snippet_stars_percentage($options = array()) {
            $default = array("title" => "", "rating" => 0, "votes" => "", "review_excerpt" => "", "hidden" => true);
            $options = wp_parse_args($options, $default);

            $tpl = '';
            if ($this->snippet_type == "microformat") {
                $tpl.= '<span class="hreview-aggregate"'.($options["hidden"] ? ' style="display: none !important;"' : '').'><span class="item"><span class="fn">%TITLE%</span></span>';
                $tpl.= '<span class="rating"><span class="rating">%RATING%%</span>';
                $tpl.= '<span class="count">%VOTES%</span>';
                $tpl.= '<span class="summary">%REVIEW_EXCERPT%</span>';
                $tpl.= '</span></span>';
            } else if ($this->snippet_type == "rdf") {

            }

            $tpl = apply_filters("gdsr_snippet_template_stars_percentage", $tpl);

            $tpl = str_replace("%TITLE%", $options["title"], $tpl);
            $tpl = str_replace("%RATING%", $options["rating"], $tpl);
            $tpl = str_replace("%VOTES%", $options["votes"], $tpl);
            $tpl = str_replace("%REVIEW_EXCERPT%", $options["review_excerpt"], $tpl);

            return $tpl;
        }

        /**
         * Render snippet with rating.
         *
         * @param array $options settings for snippet
         * @return string rendered snippet code
         */
        function snippet_stars_rating($options = array()) {
            $default = array("title" => "", "rating" => 0, "max_rating" => 5, "votes" => "", "review_excerpt" => "", "hidden" => true);
            $options = wp_parse_args($options, $default);

            $tpl = '';
            if ($this->snippet_type == "microformat") {
                $tpl.= '<span class="hreview-aggregate"'.($options["hidden"] ? ' style="display: none !important;"' : '').'><span class="item"><span class="fn">%TITLE%</span></span>';
                $tpl.= '<span class="rating"><span class="average">%RATING%</span><span class="best">%MAX_RATING%</span>';
                $tpl.= '<span class="count">%VOTES%</span>';
                $tpl.= '<span class="summary">%REVIEW_EXCERPT%</span>';
                $tpl.= '</span></span>';
            } else if ($this->snippet_type == "rdf") {

            }

            $tpl = apply_filters("gdsr_snippet_template_stars_rating", $tpl);

            $tpl = str_replace("%TITLE%", $options["title"], $tpl);
            $tpl = str_replace("%RATING%", $options["rating"], $tpl);
            $tpl = str_replace("%MAX_RATING%", $options["max_rating"], $tpl);
            $tpl = str_replace("%VOTES%", $options["votes"], $tpl);
            $tpl = str_replace("%REVIEW_EXCERPT%", $options["review_excerpt"], $tpl);

            return $tpl;
        }

        /**
         * Render snippet with review.
         *
         * @param array $options settings for snippet
         * @return string rendered snippet code
         */
        function snippet_stars_review($options = array()) {
            $default = array("title" => "", "rating" => 0, "max_rating" => 5, "reviewer" => "", "review_date" => "", "review_excerpt" => "", "hidden" => true);
            $options = wp_parse_args($options, $default);

            $tpl = '';
            if ($this->snippet_type == "microformat") {
                $tpl.= '<span class="hreview"'.($options["hidden"] ? ' style="display: none !important;"' : '').'><span class="item"><span class="fn">%TITLE%</span></span>';
                $tpl.= '<span class="rating"><span class="value">%RATING%</span><span class="best">%MAX_RATING%</span>';
                $tpl.= '<span class="dtreviewed">%REVIEW_DATE%</span>';
                $tpl.= '<span class="reviewer">%REVIEWER%</span>';
                $tpl.= '<span class="summary">%REVIEW_EXCERPT%</span>';
                $tpl.= '</span></span>';
            } else if ($this->snippet_type == "rdf") {
            }

            $tpl = apply_filters("gdsr_snippet_template_stars_review", $tpl);

            $tpl = str_replace("%TITLE%", $options["title"], $tpl);
            $tpl = str_replace("%RATING%", $options["rating"], $tpl);
            $tpl = str_replace("%MAX_RATING%", $options["max_rating"], $tpl);
            $tpl = str_replace("%REVIEWER%", $options["reviewer"], $tpl);
            $tpl = str_replace("%REVIEW_DATE%", $options["review_date"], $tpl);
            $tpl = str_replace("%REVIEW_EXCERPT%", $options["review_excerpt"], $tpl);

            return $tpl;
        }
    }
}

?>