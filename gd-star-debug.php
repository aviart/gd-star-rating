<?php

class GDDebug
{
    var $log_file;
    var $active = false;

    /**
     * Constructor
     *
     * @param string $log_url
     */
    function GDDebug($log_url = '') {
        if ($log_url == '')
            $this->log_file = STARRATING_LOG_PATH;
        else
            $this->log_file = $log_url;

        if ($this->log_file != '') {
            if (file_exists($this->log_file) && is_writable($this->log_file)) {
                $this->active = true;
            }
        }
    }

    /**
    * Writes a object dump into the log file
    *
    * @param string $msg log entry message
    * @param mixed $object object to dump
    * @param string $block adds start or end dump limiters { none | start | end }
    * @param string $mode file open mode
    */
    function dump($msg, $object, $block = "none", $mode = "a+") {
        if ($this->active) {
            $obj = print_r($object, true);
            $f = fopen($this->log_file, $mode);
            if ($block == "start")
                fwrite ($f, "-- DUMP BLOCK STARTED ---------------------------------- \r\n");
            fwrite ($f, sprintf("[%s] : %s\r\n", current_time('mysql'), $msg));
            fwrite ($f, "$obj");
            fwrite ($f, "\r\n");
            if ($block == "end")
                fwrite ($f, "-------------------------------------------------------- \r\n");
            fclose($f);
        }
    }
}

/**
* Writes a object dump into the log file
*
* @param string $msg log entry message
* @param mixed $object object to dump
* @param string $block adds start or end dump limiters { none | start | end }
* @param string $mode file open mode
*/
function wp_gdsr_dump($msg, $obj, $block = "none", $mode = "a+") {
    if (STARRATING_DEBUG_ACTIVE == 1) {
        global $gd_debug;
        $gd_debug->dump($msg, $obj, $block, $mode);
    }
}

?>
