<?php

/*
Name:    gdFunctions
Version: 1.0.5
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: http://wp.gdragon.info/

== Copyright ==

Copyright 2008 Milan Petrovic (email : milan@gdragon.info)

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

if (!class_exists('gdFunctions')) {
    class gdFunctions {
        function prefill_attributes($defaults, $attributes) {
            $attributes = (array)$attributes;
            $result = array();
            foreach($defaults as $name => $default) {
                if (array_key_exists($name, $attributes)) $result[$name] = $attributes[$name];
                else $result[$name] = $default;
            }
            return $result;
        }

        function recalculate_size($size) {
            switch (strtolower(substr($size, -1))) {
                case "k":
                    return $size * 1024;
                    break;
                case "m":
                    return $size * 1024 * 1024;
                    break;
                case "g":
                    return $size * 1024 * 1024 * 1024;
                    break;
                case "t":
                    return $size * 1024 * 1024 * 1024 * 1024;
                    break;
            }
            return $size;
        }

        function draw_pager($total_pages, $current_page, $url, $query = "page") {
            $pages = array();
            $break_first = -1;
            $break_last = -1;
            if ($total_pages < 10) for ($i = 0; $i < $total_pages; $i++) $pages[] = $i + 1;
            else {

                $island_start = $current_page - 1;
                $island_end = $current_page + 1;

                if ($current_page == 1) $island_end = 3;
                if ($current_page == $total_pages) $island_start = $island_start - 1;

                if ($island_start > 4) {
                    for ($i = 0; $i < 3; $i++) $pages[] = $i + 1;
                    $break_first = 3;
                }
                else {
                    for ($i = 0; $i < $island_end; $i++) $pages[] = $i + 1;
                }

                if ($island_end < $total_pages - 4) {
                    for ($i = 0; $i < 3; $i++) $pages[] = $i + $total_pages - 2;
                    $break_last = $total_pages - 2;
                }
                else {
                    for ($i = 0; $i < $total_pages - $island_start + 1; $i++) $pages[] = $island_start + $i;
                }

                if ($island_start > 4 && $island_end < $total_pages - 4) {
                    for ($i = 0; $i < 3; $i++) $pages[] = $island_start + $i;
                }
            }
            sort($pages, SORT_NUMERIC);
            $render = '';
            foreach ($pages as $page) {
                if ($page == $break_last)
                    $render.= "... ";
                if ($page == $current_page)
                    $render.= sprintf('<span class="page-numbers current">%s</span>', $page);
                else
                    $render.= sprintf('<a class="page-numbers" href="%s&%s=%s">%s</a>', $url, $query, $page, $page);
                if ($page == $break_first)
                    $render.= "... ";
            }

            if ($current_page > 1) $render.= sprintf('<a class="next page-numbers" href="%s&%s=%s">Previous</a>', $url, $query, $current_page - 1);
            if ($current_page < $total_pages) $render.= sprintf('<a class="next page-numbers" href="%s&%s=%s">Next</a>', $url, $query, $current_page + 1);

            return $render;
        }

        function column_sort_vars($column, $sort_order, $sort_column) {
            $col["url"] = '&sc='.$column;
            $col["cls"] = '';
            if ($sort_column == $column) {
                if ($sort_order == "asc") {
                    $col["url"].= '&so=desc';
                    $col["cls"] = ' class="sort-order-up"';
                }
                else {
                    $col["url"].= '&so=asc';
                    $col["cls"] = ' class="sort-order-down"';
                }
            }
            else $col["url"].= '&so=asc';
            return $col;
        }

        function mysql_version($full = false) {
            if ($full)
                return mysql_get_server_info();
            else
                return substr(mysql_get_server_info(), 0, 1);
        }

        function mysql_pre_4_1() {
            $mysql = str_replace(".", "", substr(mysql_get_server_info(), 0, 3));
            return $mysql < 41;
        }

        function php_version($full = false) {
            if ($full)
                return phpversion();
            else
                return substr(phpversion(), 0, 1);
        }

        function add_slashes($input) {
            if (get_magic_quotes_gpc()) return $input;
            else return addslashes($input);
        }
    }
}
?>
