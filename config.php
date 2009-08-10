<?php

/**
 * Full path to wp-load file. Use only if the location of wp-content folder is changed.
 * 
 * example: define('STARRATING_WPLOAD', '/home/path/to/wp-load.php');
 */
define('STARRATING_WPLOAD', '');

/**
 * Full path to a text file used to save debug info. File must be writeable.
 */
define('STARRATING_LOG_PATH', dirname(__FILE__).'/debug.txt');

/**
 * Name of the table for T2 templates without prefix.
 */
define('STARRATING_TPLT2_TABLE', 'gdsr_templates');

/**
 * Minimal user level required to access all plugins panels except Front and Builder.
 */
define('STARRATING_ACCESS_LEVEL', 9);

/**
 * Minimal user level required to access some of the plugins panels.
 */
define('STARRATING_ACCESS_LEVEL_BUILDER', 1);
define('STARRATING_ACCESS_LEVEL_SETUP', 9);

/**
 * Main admin account user ID's with access to special Security panel. If not set, all administrators will have access to it. You can set it to single account or to list of accounts, separated by commas ('1,6,23,12').
 */
define('STARRATING_ACCESS_ADMIN_USERIDS', '0');

/**
 * Returns the path to wp-config.php file
 * 
 * @return string wp-load.php path
 */
function get_wpload_path() {
    if (STARRATING_WPLOAD == '') {
        $d = 0;
        while (!file_exists(str_repeat('../', $d).'wp-load.php'))
            if (++$d > 16) exit;
        return str_repeat('../', $d).'wp-load.php';
    } else return STARRATING_WPLOAD;
}

?>