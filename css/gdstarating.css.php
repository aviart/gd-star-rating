<?php

header('Content-Type: text/css');

function insert_styles($css) {
    $query = explode("|", $css["values"]);

    $style = $query[0];
    $stars = $query[2];
    $size = $query[1];
    $type = $query[3];
    $loc = $query[4];

    if ($loc == 1) $url = "../stars/".$style."/stars".$size.".".$type;
    else $url = "../../../gd-star-rating/stars/".$style."/stars".$size.".".$type;

    echo "\r\n\r\n";
    echo '.'.$css["name"].' { width: '.($stars * $size).'px; }';
    echo "\r\n";
    echo '.'.$css["name"].' .starsbar { height: '.$size.'px; }';
    echo "\r\n";
    echo ".".$css["name"]." .starsbar .gdouter { width: ".($stars * $size)."px; height: ".$size."px; background: url('".$url."') repeat-x 0px 0px; }";
    echo "\r\n";
    echo ".".$css["name"]." .starsbar .gdinner { height: ".$size."px; background: url('".$url."') repeat-x 0px -".(2 * $size)."px; }";
    echo "\r\n";
    if ($css["name"] == "ratemulti") {
        echo ".".$css["name"]." .starsbar .gdcurrent { height: ".$size."px; background: url('".$url."') repeat-x 0px -".($size)."px; }";
        echo "\r\n";
    }
    echo ".".$css["name"]." .starsbar a:hover { background: url('".$url."') repeat-x 0px -".$size."px; }";
    echo "\r\n";
    echo '.'.$css["name"].' .starsbar a { height: '.$size.'px; }';
    echo "\r\n";

    for ($i = 1; $i < $stars + 1; $i++) {
        echo '.'.$css["name"].' .starsbar a.s'.$i.' { width: '.($i * $size).'px; }';
        echo "\r\n";
    }

    if ($css["type"] == "a") echo '.ratingloaderarticle { height: '.$size.'px; }';
    echo "\r\n";
    if ($css["type"] == "c") echo '.ratingloadercomment { height: '.$size.'px; }';
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

$csss = array();
$head = array();
$q = urldecode($_GET["s"]);
$q = explode("#", $q);

foreach ($q as $cs) {
    $css["type"] = substr($cs, 0, 1);
    $css["values"] = substr($cs, 1);
    switch ($css["type"]) {
        case "a":
            $css["name"] = "ratepost";
            $head[] = "ratepost";
            break;
        case "c":
            $css["name"] = "ratecmm";
            $head[] = "ratecmm";
            break;
        case "r":
            $css["name"] = "reviewcmm";
            $head[] = "reviewcmm";
            break;
        case "m":
            $css["name"] = "ratemulti";
            $head[] = "ratemulti";
            break;
    }
    $csss[] = $css;
}

foreach ($csss as $css) insert_styles($css);

get_class_head($head, ""); ?> {
  position: relative;
  display: block;
}

<?php get_class_head($head, ".starsbar .gdinner"); ?> {
  width: 0;
}

<?php get_class_head($head, ".starsbar a:active"); ?> {
  text-decoration: none;
  border: 0;
}

<?php get_class_head($head, ".starsbar a:visited"); ?> {
  text-decoration: none;
  border: 0;
}

<?php get_class_head($head, ".starsbar a:hover"); ?> {
  text-decoration: none;
  border: 0;
}

<?php get_class_head($head, ".starsbar a"); ?> {
  position: absolute;
  display: block;
  left: 0;
  top: 0;
  text-decoration: none;
  border: 0;
  cursor: pointer;
  background: none;
}

.ratemulti .starsbar .gdcurrent { width: 0; top: 0; position: absolute; opacity: 0.4; }
.ratingblockarticle { font-size: 1em; }
.ratingblockcomment { font-size: 0.8em; }
.ratingloaderarticle, .ratingloadercomment { font-size: 12px; text-align: center; vertical-align: middle; }

.starsbar .gdinner { padding: 0; }
.ratingblock td { vertical-align: middle; }
.raterclear { clear: both; }
.raterleft { float: left; }
.raterright { float: right; }
.voted {color: #999;}
.thanks {color: #36AA3D;}
.static {color: #5D3126;}
.rater { top: 0; }

.ratingtextmulti {
    float: left;
}

.ratingbutton {
    float: right;
    padding: 1px 6px;
}

.ratingbutton.gdinactive {
    border: 1px solid gray;
    background-color: lightgray;
}

.ratingbutton.gdactive {
    border: 1px solid black;
    background-color: gray;
    cursor: pointer;
}

.ratingbutton.gdactive a { color: white; }
.ratingbutton.gdinactive a { color: gray; cursor: default; }

.gdmultitable {
    padding: 3px;
    margin: 3px;
    border: 1px solid gray;
}

.gdtblbottom td {
    border-top: 1px dashed gray;
    padding-top: 3px;
}

.gdmultitable td { vertical-align: middle; }

.ratingblock {
	display: block;
	padding-bottom: 4px;
	margin-bottom: 4px;
	margin-top: 8px;
	font-size: 12px;
}

.ratingtext {
	padding-bottom: 4px;
	margin-bottom: 4px;
	margin-top: 8px;
}

/* table class: starrating */
.starrating { width: 100%; }

.starrating thead {
	background-color: #696969;
	color: yellow;
}

.starrating thead td { font-weight: bold; }

.starrating td {
	padding: 2px 5px 2px 5px;
	height: 24px;
	vertical-align: middle;
}

.starrating tr.even { background-color: #fffff0; }

.starrating tr.odd { background-color: #f0f8ff; }

.starrating tbody td.rating {
	text-align: right;
	color: red;
	font-weight: bold;
	width: 40px;
}

.starrating tbody td.votes {
	text-align: right;
	font-weight: bold;
	width: 30px;
}

/* table class: starsimple */
.starsimple { width: 100%; }

.starsimple thead {
	background-color: gray;
	color: yellow;
}

.starsimple thead td { font-weight: bold; }

.starsimple td {
	padding: 2px 5px 2px 5px;
	height: 24px;
	vertical-align: middle;
}

.starsimple tr.even { }

.starsimple tr.odd { }

.starsimple tbody td.rating {
	text-align: right;
	color: red;
	font-weight: bold;
	width: 40px;
}

.starsimple tbody td.votes {
	text-align: right;
	font-weight: bold;
	width: 30px;
}

/* loading indicators */
.loader { margin-left: auto; margin-right: auto; text-align: left; }

.loader.circle { background: url(../gfx/loader/circle.gif) no-repeat left; padding-left: 18px; }
.loader.circle.width { width: 16px; margin-left: auto; margin-right: auto; padding-left: 0px; }

.loader.bar { background: url(../gfx/loader/bar.gif) no-repeat left; padding-left: 216px; }
.loader.bar.width { width: 208px; margin-left: auto; margin-right: auto; padding-left: 0px; }

.loader.arrows { background: url(../gfx/loader/arrows.gif) no-repeat left; padding-left: 18px; }
.loader.arrows.width { width: 16px; margin-left: auto; margin-right: auto; padding-left: 0px; }

.loader.flower { background: url(../gfx/loader/flower.gif) no-repeat left; padding-left: 18px; }
.loader.flower.width { width: 15px; margin-left: auto; margin-right: auto; padding-left: 0px; }

.loader.gauge { background: url(../gfx/loader/gauge.gif) no-repeat left; padding-left: 134px; }
.loader.gauge.width { width: 128px; margin-left: auto; margin-right: auto; padding-left: 0px; }

.loader.squares { background: url(../gfx/loader/squares.gif) no-repeat left; padding-left: 43px; }
.loader.squares.width { width: 43px; margin-left: auto; margin-right: auto; padding-left: 0px; }

/* top rating widget */
.trw-title {
	text-align: center;
	font-size: 16px;
	font-family: "Century Gothic", Arial, Helvetica, sans-serif;
}

.trw-rating {
	font-size: 44px;
	font-family: "Century Gothic", Arial, Helvetica, sans-serif;
	font-weight: bold;
	text-align: center;
}

.trw-footer {
	text-align: center;
	font-size: 11px;
	font-family: "Century Gothic", Geneva, Arial, Helvetica, sans-serif;
}
