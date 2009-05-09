<?php

function split_by_length($string, $chunkLength = 1) {
    $result = array();
    $strLength = strlen($string);
    $x = 0;

    while($x < ($strLength / $chunkLength)){
        $result[] = substr($string, ($x * $chunkLength), $chunkLength);
        $x++;
    }
    return $result;
}

function get_class_head($head, $element) {
    $result = "";
    for ($i = 0; $i < count($head); $i++) {
        $result.= ".".$head[$i];
        if ($element != "") $result.= " ".$element;
        if ($i < count($head) - 1) $result.= ", ";
    }
    echo $result;
}

header('Content-Type: text/css');

$sizes = array(12, 16, 20, 24, 30, 46);
$base_url_local = "../";
$base_url_extra = "../../../gd-star-rating/";
$q = urldecode($_GET["s"]);
$raw = explode("#", $q);
$raw_blocks = split_by_length($raw[0], 3);
unset($raw[0]);

$sets = array();
$blocks = array();
$head = array();
foreach($raw as $r) {
    $set = array();
    $set["location"] = substr($r, 0, 1);
    $set["type"] = substr($r, 1, 1);
    $set["folder"] = substr($r, 2);
    $sets[] = $set;
}

foreach($raw_blocks as $r) {
    $block = array();
    $block["type"] = substr($r, 0, 1);
    $block["size"] = intval(substr($r, 1));
    switch ($block["type"]) {
        case "a":
            $block["name"] = "ratepost";
            $head[] = "ratepost";
            break;
        case "c":
            $block["name"] = "ratecmm";
            $head[] = "ratecmm";
            break;
        case "r":
            $block["name"] = "reviewcmm";
            $head[] = "reviewcmm";
            break;
        case "m":
            $block["name"] = "ratemulti";
            $head[] = "ratemulti";
            break;
    }
    $blocks[] = $block;
}

foreach ($sizes as $size) {
    echo '.starsbar.gdsr-size-'.$size.', .starsbar.gdsr-size-'.$size.' a { height: '.$size.'px; }';
    echo "\r\n";
    echo '.starsbar.gdsr-size-'.$size.' a { height: '.$size.'px; }';
    echo "\r\n";
    for ($i = 1; $i <= 20; $i++) {
        echo '.starsbar.gdsr-size-'.$size.' a.s'.$i.' { width: '.($i * $size).'px; }';
        echo "\r\n";
    }
}

foreach ($blocks as $block) {
    foreach ($sizes as $size) {
        if ($block["name"] != "ratemulti") {
            echo '.'.$block["name"].'.gdsr-size-'.$size.' { width: '.($block["size"] * $size).'px; }';
            echo "\r\n";
        }
    }
}

?>

<?php get_class_head($head, ""); ?> {
  position: relative;
  display: block;
}

<?php get_class_head($head, ".starsbar .gdinner"); ?> {
  width: 0;
}

<?php get_class_head($head, ".starsbar a:active"); ?> {
  text-decoration: none;
  border: 0 !important;
}

<?php get_class_head($head, ".starsbar a:visited"); ?> {
  text-decoration: none;
  border: 0 !important;
}

<?php get_class_head($head, ".starsbar a:hover"); ?> {
  text-decoration: none;
  border: 0 !important;
}

<?php get_class_head($head, ".starsbar a"); ?> {
  position: absolute;
  display: block;
  left: 0;
  top: 0;
  text-decoration: none;
  border: 0 !important;
  cursor: pointer;
  background: none !important;
}
