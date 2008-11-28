<?php

define('STARRATING_WPCONFIG', '');
define('STARRATING_LOG_PATH', 'c:/gd_star_rating_log.txt');

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