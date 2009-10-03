<?php

/**
* Writes a object dump into the log file
*
* @param string $msg log entry message
* @param mixed $object object to dump
* @param string $block adds start or end dump limiters { none | start | end }
* @param string $mode file open mode
* @param bool $force force writing into debug file even if the debug directive is inactive
*/
function wp_gdsr_dump($msg, $obj, $block = "none", $mode = "a+", $force = false) {
    if (STARRATING_DEBUG_ACTIVE == 1 || $force) {
        global $gd_debug;
        $gd_debug->dump($msg, $obj, $block, $mode);
    }
}

/**
* Truncates log file to zero lenght deleting all data inside.
*/
function wp_gdsr_debug_clean() {
    global $gd_debug;
    $gd_debug->truncate();
}

/**
 * Gets the multi rating set.
 *
 * @param int $id set id
 * @return GDMultiSingle multi rating set
 */
function gd_get_multi_set($id = 0) {
    $set = GDSRDBMulti::get_multi_set($id);
    if (count($set) > 0) {
        $set->object = unserialize($set->object);
        $set->weight = unserialize($set->weight);
        return $set;
    }
    else return null;
}

/**
 * Determines if the browser accessing the page is MS Internet Explorer 6
 *
 * @return bool true if the browser is IE6
 */
function is_msie6() {
    $agent = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match("/msie/i", $agent) && !preg_match("/opera/i", $agent)) {
        $val = explode(" ", stristr($agent, "msie"));
        $version = substr($val[1], 0, 1);
        if ($version < 7) return true;
        else return false;
    }
    return false;
}

?>