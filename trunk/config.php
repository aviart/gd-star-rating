<?php

$gdsr_config_extra = dirname(dirname(__FILE__))."/gdsr-config.php";
if (file_exists($gdsr_config_extra)) require_once($gdsr_config_extra);

/**
 * Full path to wp-load file. Use only if the location of wp-content folder is changed.
 * 
 * example: define('STARRATING_WPLOAD', '/home/path/to/wp-load.php');
 */
if (!defined('STARRATING_WPLOAD')) define('STARRATING_WPLOAD', '');

/**
 * Full path to a text file used to save debug info. File must be writeable.
 */
if (!defined('STARRATING_LOG_PATH')) define('STARRATING_LOG_PATH', dirname(__FILE__).'/debug.txt');

/**
 * Name of the table for T2 templates without prefix.
 */
define('STARRATING_TPLT2_TABLE', 'gdsr_templates');

/**
 * Returns the path to wp-config.php file
 * 
 * @return string wp-load.php path
 */
function get_gdsr_wpload_path() {
    if (STARRATING_WPLOAD == '') {
        $d = 0;
        while (!file_exists(str_repeat('../', $d).'wp-load.php'))
            if (++$d > 16) exit;
        return str_repeat('../', $d).'wp-load.php';
    } else return STARRATING_WPLOAD;
}

?>