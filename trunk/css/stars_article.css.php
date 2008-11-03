<?php

$query = urldecode($_GET["stars"]);
$query = explode("|", $query);

$style = $query[0];
$stars = $query[2];
$size = $query[1];
$type = $query[3];
$loc = $query[4];
$text = $size - 3;
if ($text > 14)
    $text = 14;

if ($loc == 1)
    $url = "../stars/".$style."/stars".$size.".".$type;
else
    $url = "../../../gd-star-rating/stars/".$style."/stars".$size.".".$type;

header('Content-Type: text/css');

?>

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

.ratepost .starsbar a:active, .ratepost .starsbar a:visible, .ratepost .starsbar a:hover {
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
    font-size: <?php echo $text; ?>px;
    text-align: center;
    vertical-align: middle;
}
