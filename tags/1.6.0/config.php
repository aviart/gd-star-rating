<?php

/**
 * Full path to wp-config file. Only for WordPress 2.6 or newer and only if the location of wp-content folder is changed.
 */
define('STARRATING_WPCONFIG', '');

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
define('STARRATING_ACCESS_LEVEL', 10);

/**
 * Minimal user level required to access some of the plugins panels.
 */
define('STARRATING_ACCESS_LEVEL_BUILDER', 1);
define('STARRATING_ACCESS_LEVEL_SETUP', 10);

/**
 * Returns the path to wp-config.php file
 * 
 * @return string wp-config.php path
 */
function get_wpconfig() {
	if (STARRATING_WPCONFIG == '') {
	    $d = 0;
	    while (!file_exists(str_repeat('../', $d).'wp-config.php')) 
	        if (++$d > 99) exit;
	    $wpconfig = str_repeat('../', $d).'wp-config.php';
	    return $wpconfig;
    }
    else return STARRATING_WPCONFIG;
}

?>