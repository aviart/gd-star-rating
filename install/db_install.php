<?php

/*
Name:    gdDBInstall
Version: 1.0.0

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

class gdDBInstall {
    function drop_tables($path) {
        global $wpdb, $table_prefix;
        $path.= "install/tables";
        $files = gdDBInstall::scan_folder($path);
        foreach ($files as $file) {
            if (substr($file, 0, 1) != '.') {
                $file_path = $path."/".$file;
                $table_name = $table_prefix.substr($file, 0, strlen($file) - 4);
                $wpdb->query("drop table ".$table_name);
            }
        }
    }

    function upgrade_tables($path) {
        $path.= "install/tables";
        $files = gdDBInstall::scan_folder($path);
        foreach ($files as $file) {
            if (substr($file, 0, 1) != '.' && is_file($path."/".$file))
                gdDBInstall::upgrade_table($path, $file);
        }
    }

    function check_column($columns, $column) {
        foreach ($columns as $c)
            if ($c->Field == $column) return true;
        return false;
    }

    function upgrade_table($path, $file) {
        global $wpdb, $table_prefix;
        $file_path = $path."/".$file;
        $table_name = $table_prefix.substr($file, 0, strlen($file) - 4);
        $columns = $wpdb->get_results(sprintf("SHOW COLUMNS FROM %s", $table_name));
        $fc = file($file_path);
        $after = '';
        foreach ($fc as $f) {
            $f = trim($f);
            if (substr($f, 0, 1) == "`") {
                $column = substr($f, 1);
                $column = substr($column, 0, strpos($column, "`"));
                if (!gdDBInstall::check_column($columns, $column))
                    gdDBInstall::add_column($table_name, $f, $after);
                $after = $column;
            }
        }
    }

    function add_column($table, $column_info, $position = '') {
        global $wpdb;
        if (substr($column_info, -1) == ",")
            $column_info = substr($column_info, 0, strlen($column_info) - 1);
        if ($position == '') $position = "FIRST";
        else $position = "AFTER ".$position;
        $sql = sprintf("ALTER TABLE %s ADD %s %s", $table, $column_info, $position);
        $wpdb->query($sql);
    }

    function create_tables($path) {
        global $wpdb, $table_prefix;
        $path.= "install/tables";
        $files = gdDBInstall::scan_folder($path);
        foreach ($files as $file) {
            if (substr($file, 0, 1) != '.') {
                $file_path = $path."/".$file;
                $table_name = $table_prefix.substr($file, 0, strlen($file) - 4);
                if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                    $fc = file($file_path);
                    $first = true;
                    $sql = "";
                    foreach ($fc as $f) {
                        if ($first) {
                            $sql.= sprintf($f, $table_prefix);
                            $first = false;
                        }
                        else $sql.= $f;
                    }
                    $wpdb->query($sql);
                }
            }
        }
    }

    function import_data($path) {
        global $wpdb, $table_prefix;
        $path.= "install/data";
        $files = gdDBInstall::scan_folder($path);
        $wpdb->show_errors = true;
        foreach ($files as $file) {
            if (substr($file, 0, 1) != '.') {
                $file_path = $path."/".$file;
                $handle = @fopen($file_path, "r");
                if ($handle) {
                     while (!feof($handle)) {
                         $line = fgets($handle);
                         $sql = sprintf($line, $table_prefix);
                         $wpdb->query($sql);
                     }
                     fclose($handle);
                }
            }
        }
    }

    function scan_folder($path) {
        if (function_exists(scandir)) {
            return scandir($path);
        }
        else {
            $dh = opendir($path);
            while (false !== ($filename = readdir($dh))) {
                $files[] = $filename;
            }
            return $files;
        }
    }
}

?>
