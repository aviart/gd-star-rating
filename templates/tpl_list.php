<?php

$tpls = new gdTemplates();

$t = new gdTemplate("SRB", __("Standard Ratings Block"));
$t->add_element("%RATING%", __("article rating"));
$t->add_element("%MAX_RATING%", __("maximum rating value"));
$t->add_element("%VOTES%", __("total votes for article"));
$t->add_element("%ID%", __("post/page id"));
$t->add_element("%WORD_VOTES%", __("singular/plural word votes"));
$t->add_element("%TR_YEARS%", __("remaining years"));
$t->add_element("%TR_MONTHS%", __("remaining months"));
$t->add_element("%TR_DAYS%", __("remaining days"));
$t->add_element("%TR_HOURS%", __("remaining hours"));
$t->add_element("%TR_MINUTES%", __("remaining minutes"));
$t->add_element("%TR_SECONDS%", __("remaining seconds"));
$t->add_element("%TR_DATE%", __("end voting date"));
$t->add_element("%TOT_DAYS%", __("total remaining days"));
$t->add_element("%TOT_HOURS%", __("total remaining hours"));
$t->add_element("%TOT_MINUTES%", __("total remaining minutes"));
$t->add_part(__("Normal"), __(""), array("%RATING%", "%MAX_RATING%", "%VOTES%", "%ID%", "%WORD_VOTES%"));
$t->add_part(__("Time Restricted: Active"), __(""), "all");
$t->add_part(__("Time Restricted: Closed"), __(""), array("%RATING%", "%MAX_RATING%", "%VOTES%", "%ID%", "%WORD_VOTES%"));
$tpls->add_template($t);

$t = new gdTemplate("SRR", __("Standard Ratings Results"));
$t->add_element("%RATING%", __("article rating"));
$t->add_element("%MAX_RATING%", __("maximum rating value"));
$t->add_element("%REVIEW%", __("article review"));
$t->add_element("%MAX_REVIEW%", __("maximum review value"));
$t->add_element("%VOTES%", __("total votes for article"));
$t->add_element("%TITLE%", __("post/page title"));
$t->add_element("%PERMALINK%", __("url to post/page"));
$t->add_element("%STARS%", __("rating stars"));
$t->add_element("%BAYES_RATING%", __("bayesian estimate mean rating"));
$t->add_element("%BAYES_STARS%", __("bayesian estimate mean rating stars"));
$t->add_element("%RATE_TREND%", __("article rating trend"));
$t->add_element("%VOTE_TREND%", __("article voting trend"));
$t->add_element("%REVIEW_STARS%", __("article review stars"));
$t->add_element("%COUNT%", __("number of posts/pages"));
$t->add_element("%ID%", __("post/page id"));
$t->add_element("%WORD_VOTES%", __("singular/plural word votes"));
$t->add_element("%TABLE_ROW_CLASS%", __("class for alternating table rows"));
$t->add_part(__("Header"), __(""), "none");
$t->add_part(__("Item"), __(""), "all");
$t->add_part(__("Footer"), __(""), "none");
$tpls->add_template($t);

?>
