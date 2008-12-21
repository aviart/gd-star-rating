<?php

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
            if (substr($file, 0, 1) != '.')
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
        wp_gdsr_dump('columns', $columns);
        $fc = file($file_path);
        $after = '';
        foreach ($fc as $f) {
            $f = trim($f);
            if (substr($f, 0, 1) == "`") {
                if (!gdDBInstall::check_column($columns, $column)) {
                }
            }
        }
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
            $dh  = opendir($path);
            while (false !== ($filename = readdir($dh))) {
                $files[] = $filename;
            }
            return $files;
        }
    }
}

?>
