<?php

$query = urldecode($_GET["stars"]);
$query = explode("|", $query);

$style = $query[0];
$stars = $query[2];
$size = $query[1];
$type = $query[3];
$text = $size - 3;
if ($text > 15)
    $text = 15;

$url = "../stars/".$style."/stars".$size.".".$type;

header('Content-Type: text/css');

?>

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

.ratepost .starsbar a:active, .ratepost .starsbar a:visible, .ratepost .starsbar a:hover {
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
    font-size: <?php echo $text; ?>px;
    text-align: center;
    vertical-align: middle;
}
