<?php

    require_once("./gd-star-config.php");
    $wpconfig = get_wpconfig();
    require($wpconfig);

    global $gdsr;

    $input["set"] = $gdsr->o["rss_style"];
    $input["size"] = $gdsr->o["rss_size"];
    $input["stars"] = $gdsr->o["stars"];
    $input["value"] = $_GET["value"];
    
    $gfx_set = $gdsr->g->find_stars($input["set"]);
    if ($gdsr->is_cached) {
        $image_name = GDSRGenerator::get_image_name($input["set"], $input["size"], $input["stars"], $input["value"]);
        $image_path = STARRATING_CACHE_PATH.$image_name.".".$gfx_set->type;
        GDSRGenerator::image($gfx_set->get_path($input["size"]), $input["size"], $input["stars"], $input["value"], $image_path);
    }
    else {
        GDSRGenerator::image_nocache($gfx_set->get_path($input["size"]), $input["size"], $input["stars"], $input["value"]);
    }

?>
