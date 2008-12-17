<?php

require_once("../gd-star-config.php");
$wpconfig = get_wpconfig();
require($wpconfig);

global $gdsr;
if ($gdsr->use_nonce) {
    $nonce = $_REQUEST['_wpnonce'];
    require_once(ABSPATH.WPINC."/pluggable.php");
    if (!wp_verify_nonce($nonce, 'gdsr_chart_l8')) die("Security check");
}

$id = $_GET["id"];
$show = $_GET["show"];

$data = GDSRChart::votes_trend_daily($id);

$vote = array();
$rate = array();
$date = array();

foreach ($data as $day => $el) {
    if ($show == "user") {
        $voters = $el["user_voters"];
        $votes = $el["user_votes"];
    }
    else if ($show == "visitor") {
        $voters = $el["visitor_voters"];
        $votes = $el["visitor_votes"];
    }
    else {
        $voters = $el["visitor_voters"] + $el["user_voters"];
        $votes = $el["visitor_votes"] + $el["user_votes"];
    }
    $vote[] = $voters;
    $date[] = $day;
    if ($voters > 0) $rate[] = number_format($votes / $voters, 1);
    else $rate[] = 0;
}

include(STARRATING_CHART_PATH."pchart/pData.class");
include(STARRATING_CHART_PATH."pchart/pChart.class");

$DataSet = new pData;
$DataSet->AddPoint($vote, "Serie1");
$DataSet->AddPoint($rate, "Serie2");

$DataSet->AddAllSeries();
$DataSet->SetAbsciseLabelSerie();
$DataSet->SetSerieName("Votes", "Serie1");
$DataSet->SetSerieName("Rating", "Serie2");

$Test = new pChart(750,380);
$Test->setFontProperties(STARRATING_CHART_PATH."fonts/quicksand.ttf",8);
$Test->setGraphArea(50,40,700,330);
$Test->drawFilledRoundedRectangle(7,7,743,373,5,240,240,240);
$Test->drawRoundedRectangle(5,5,745,375,5,230,230,230);
$Test->drawGraphArea(255,255,255,TRUE);
$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,90,2);
$Test->drawGrid(4,TRUE,230,230,230,50);

$Test->setFontProperties(STARRATING_CHART_PATH."fonts/quicksand.ttf",6);
$Test->drawTreshold(0,143,55,72,TRUE,TRUE);

// Draw the line graph
$Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
$Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),2,1,255,255,255);

// Finish the graph
$Test->setFontProperties(STARRATING_CHART_PATH."fonts/quicksand.ttf",8);
$Test->drawLegend(600,30,$DataSet->GetDataDescription(),255,255,255);
$Test->setFontProperties(STARRATING_CHART_PATH."fonts/quicksand.ttf",10);
$Test->drawTitle(50,22,"Example 9",50,50,50,585);
$Test->Stroke();

?>
