<?php

$q = urldecode($_GET["s"]);
$q = explode("#", $q);
$cmm = count($q) > 1;

header('Content-Type: text/css');

$query = explode("|", $q[0]);
$style = $query[0];
$stars = $query[2];
$size = $query[1];
$type = $query[3];
$loc = $query[4];

if ($loc == 1)
    $url = "../stars/".$style."/stars".$size.".".$type;
else
    $url = "../../../gd-star-rating/stars/".$style."/stars".$size.".".$type;

?>

/* articles */
.ratepost {
  width: <?php echo $stars * $size; ?>px;
  position: relative;
  display: block;
}

.ratepost .starsbar {
  height: <?php echo $size; ?>px;
}

.ratepost .starsbar .outer {
  width: <?php echo $stars * $size; ?>px;
  height: <?php echo $size; ?>px;
  background: url('<?php echo $url; ?>') repeat-x 0px 0px;
}

.ratepost .starsbar .inner {
  width: 0;
  height: <?php echo $size; ?>px;
  background: url('<?php echo $url; ?>') repeat-x 0px -<?php echo $size * 2; ?>px;
}

.ratepost .starsbar a:active, .ratepost .starsbar a:visited, .ratepost .starsbar a:hover {
  text-decoration: none;
  border: 0;
}

.ratepost .starsbar a {
  position: absolute;
  display: block;
  left: 0;
  top: 0;
  text-decoration: none;
  border: 0;
  height: <?php echo $size; ?>px;
  cursor: pointer;
}

.ratepost .starsbar a:hover {
  background: url('<?php echo $url; ?>') repeat-x 0px -<?php echo $size; ?>px;
}

<?php

for ($i = 1; $i < $stars + 1; $i++)
    echo ".ratepost .starsbar a.s".$i." { width: ".($i * $size)."px; }\r\n";

?>

.ratingblockarticle {
    font-size: 1em;
}

.ratingloaderarticle {
    height: <?php echo $size; ?>px;
    font-size: 12px;
    text-align: center;
    vertical-align: middle;
}

<?php if ($cmm) { ?>

<?php

$query = explode("|", $q[1]);
$style = $query[0];
$stars = $query[2];
$size = $query[1];
$type = $query[3];
$loc = $query[4];

if ($loc == 1)
    $url = "../stars/".$style."/stars".$size.".".$type;
else
    $url = "../../../gd-star-rating/stars/".$style."/stars".$size.".".$type;

?>

/* comments */
.ratecmm {
  width: <?php echo $stars * $size; ?>px;
  position: relative;
  display: block;
}

.ratecmm .starsbar {
  position: relative;
  height: <?php echo $size; ?>px;
}

.ratecmm .starsbar .outer {
  width: <?php echo $stars * $size; ?>px;
  height: <?php echo $size; ?>px;
  background: url('<?php echo $url; ?>') repeat-x 0px 0px;
}

.ratecmm .starsbar .inner {
/*  position: absolute;*/
  width: 0;
  height: <?php echo $size; ?>px;
  background: url('<?php echo $url; ?>') repeat-x 0px -<?php echo $size * 2; ?>px;
}

.ratepost .starsbar a:active, .ratepost .starsbar a:visited, .ratepost .starsbar a:hover {
  text-decoration: none;
  border: 0;
}

.ratecmm .starsbar a {
  position: absolute;
  display: block;
  left: 0;
  top: 0;
  text-decoration: none;
  border: 0;
  height: <?php echo $size; ?>px;
  cursor: pointer;
}

.ratecmm .starsbar a:hover {
  background: url('<?php echo $url; ?>') repeat-x 0px -<?php echo $size; ?>px;
}

<?php

for ($i = 1; $i < $stars + 1; $i++)
    echo ".ratecmm .starsbar a.s".$i." { width: ".($i * $size)."px; }\r\n";

?>

.ratingblockcomment {
    font-size: 0.8em;
}

.ratingloadercomment {
    height: <?php echo $size; ?>px;
    font-size: 12px;
    text-align: center;
    vertical-align: middle;
}

<?php

$query = explode("|", $q[2]);
$style = $query[0];
$stars = $query[2];
$size = $query[1];
$type = $query[3];
$loc = $query[4];

if ($loc == 1)
    $url = "../stars/".$style."/stars".$size.".".$type;
else
    $url = "../../../gd-star-rating/stars/".$style."/stars".$size.".".$type;

?>

/* comments review */
.reviewcmm {
  width: <?php echo $stars * $size; ?>px;
  position: relative;
  display: block;
}

.reviewcmm .starsbar {
  position: relative;
  height: <?php echo $size; ?>px;
}

.reviewcmm .starsbar .outer {
  width: <?php echo $stars * $size; ?>px;
  height: <?php echo $size; ?>px;
  background: url('<?php echo $url; ?>') repeat-x 0px 0px;
}

.reviewcmm .starsbar .inner {
/*  position: absolute;*/
  width: 0;
  height: <?php echo $size; ?>px;
  background: url('<?php echo $url; ?>') repeat-x 0px -<?php echo $size * 2; ?>px;
}

.ratepost .starsbar a:active, .ratepost .starsbar a:visited, .ratepost .starsbar a:hover {
  text-decoration: none;
  border: 0;
}

.reviewcmm .starsbar a {
  position: absolute;
  display: block;
  left: 0;
  top: 0;
  text-decoration: none;
  border: 0;
  height: <?php echo $size; ?>px;
  cursor: pointer;
}

.reviewcmm .starsbar a:hover {
  background: url('<?php echo $url; ?>') repeat-x 0px -<?php echo $size; ?>px;
}

<?php

for ($i = 1; $i < $stars + 1; $i++)
    echo ".reviewcmm .starsbar a.s".$i." { width: ".($i * $size)."px; }\r\n";

?>

.ratingblockcomment {
    font-size: 0.8em;
}

.ratingloadercomment {
    height: <?php echo $size; ?>px;
    font-size: 12px;
    text-align: center;
    vertical-align: middle;
}

<?php } ?>

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

/* rating stars */
.gdsrdebug { display: none; }

.ratingblock td { vertical-align: middle; }

.raterclear { clear: both; }

.raterleft { float: left; }

.raterright { float: right; }

.voted {color: #999;}
.thanks {color: #36AA3D;}
.static {color: #5D3126;}
.rater { top: 0; }

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
.loader {
    margin-left: auto;
    margin-right: auto;
    text-align: left;
    height: <?php echo $size; ?>px;
}

<?php if ($size > 20) { ?>
.loaderinner { padding-top: <?php echo ($size / 2) - 10; ?>px; }
<?php } ?>

.loader.circle {
    background: url(../gfx/loader/circle.gif) no-repeat left;
	padding-left: 18px;
}

.loader.circle.width {
    width: 16px;
    margin-left: auto;
    margin-right: auto;
    padding-left: 0px;
}

.loader.bar {
    background: url(../gfx/loader/bar.gif) no-repeat left;
	padding-left: 216px;
}

.loader.bar.width {
    width: 208px;
    margin-left: auto;
    margin-right: auto;
    padding-left: 0px;
}

.loader.arrows {
    background: url(../gfx/loader/arrows.gif) no-repeat left;
	padding-left: 18px;
}

.loader.arrows.width {
    width: 16px;
    margin-left: auto;
    margin-right: auto;
    padding-left: 0px;
}

.loader.flower {
    background: url(../gfx/loader/flower.gif) no-repeat left;
	padding-left: 18px;
}

.loader.flower.width {
    width: 15px;
    margin-left: auto;
    margin-right: auto;
    padding-left: 0px;
}

.loader.gauge {
    background: url(../gfx/loader/gauge.gif) no-repeat left;
	padding-left: 134px;
}

.loader.gauge.width {
    width: 128px;
    margin-left: auto;
    margin-right: auto;
    padding-left: 0px;
}

.loader.squares {
    background: url(../gfx/loader/squares.gif) no-repeat left;
	padding-left: 43px;
}

.loader.squares.width {
    width: 43px;
    margin-left: auto;
    margin-right: auto;
    padding-left: 0px;
}