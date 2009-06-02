<?php

/*
Name:    gdWordPress
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

if (!class_exists('gdWPGDSR')) {
    class gdWPGDSR {
        function deactivate_plugin($plugin_name) {
            $current = get_option('active_plugins');
            if(in_array($plugin_name, $current))
                array_splice($current, array_search($plugin_name, $current), 1);
            update_option('active_plugins', $current);
        }

        function get_users_with_role($role) {
          $wp_user_search = new WP_User_Search("", "", $role);
          return $wp_user_search->get_results();
        }
    }

    if (!function_exists(wp_redirect_self)) {
        function wp_redirect_self() {
            wp_redirect($_SERVER['REQUEST_URI']);
        }
    }
}

?>