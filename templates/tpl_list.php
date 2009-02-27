<?php

$tpls = new gdTemplates();

$t = new gdTemplate("SRB", __("Standard Ratings Block", "gd-star-rating"));
$t->add_element("%RATING%", __("article rating", "gd-star-rating"));
$t->add_element("%MAX_RATING%", __("maximum rating value", "gd-star-rating"));
$t->add_element("%VOTES%", __("total votes for article", "gd-star-rating"));
$t->add_element("%ID%", __("post/page id", "gd-star-rating"));
$t->add_element("%WORD_VOTES%", __("singular/plural word votes", "gd-star-rating"));
$t->add_element("%TR_YEARS%", __("remaining years", "gd-star-rating"));
$t->add_element("%TR_MONTHS%", __("remaining months", "gd-star-rating"));
$t->add_element("%TR_DAYS%", __("remaining days", "gd-star-rating"));
$t->add_element("%TR_HOURS%", __("remaining hours", "gd-star-rating"));
$t->add_element("%TR_MINUTES%", __("remaining minutes", "gd-star-rating"));
$t->add_element("%TR_SECONDS%", __("remaining seconds", "gd-star-rating"));
$t->add_element("%TR_DATE%", __("end voting date", "gd-star-rating"));
$t->add_element("%TOT_DAYS%", __("total remaining days", "gd-star-rating"));
$t->add_element("%TOT_HOURS%", __("total remaining hours", "gd-star-rating"));
$t->add_element("%TOT_MINUTES%", __("total remaining minutes", "gd-star-rating"));
$t->add_part(__("Normal", "gd-star-rating"), "normal", "", array("%RATING%", "%MAX_RATING%", "%VOTES%", "%ID%", "%WORD_VOTES%"));
$t->add_part(__("Time Restricted Active", "gd-star-rating"), "time_active", "", "all");
$t->add_part(__("Time Restricted Closed", "gd-star-rating"), "time_closed", "", array("%RATING%", "%MAX_RATING%", "%VOTES%", "%ID%", "%WORD_VOTES%"));
$tpls->add_template($t);

$t = new gdTemplate("SRR", __("Standard Ratings Results", "gd-star-rating"));
$t->add_element("%RATING%", __("article rating", "gd-star-rating"));
$t->add_element("%MAX_RATING%", __("maximum rating value", "gd-star-rating"));
$t->add_element("%REVIEW%", __("article review", "gd-star-rating"));
$t->add_element("%MAX_REVIEW%", __("maximum review value", "gd-star-rating"));
$t->add_element("%VOTES%", __("total votes for article", "gd-star-rating"));
$t->add_element("%TITLE%", __("post/page title", "gd-star-rating"));
$t->add_element("%PERMALINK%", __("url to post/page", "gd-star-rating"));
$t->add_element("%STARS%", __("rating stars", "gd-star-rating"));
$t->add_element("%BAYES_RATING%", __("bayesian estimate mean rating", "gd-star-rating"));
$t->add_element("%BAYES_STARS%", __("bayesian estimate mean rating stars", "gd-star-rating"));
$t->add_element("%RATE_TREND%", __("article rating trend", "gd-star-rating"));
$t->add_element("%VOTE_TREND%", __("article voting trend", "gd-star-rating"));
$t->add_element("%REVIEW_STARS%", __("article review stars", "gd-star-rating"));
$t->add_element("%COUNT%", __("number of posts/pages", "gd-star-rating"));
$t->add_element("%ID%", __("post/page id", "gd-star-rating"));
$t->add_element("%WORD_VOTES%", __("singular/plural word votes", "gd-star-rating"));
$t->add_element("%TABLE_ROW_CLASS%", __("class for alternating table rows", "gd-star-rating"));
$t->add_part(__("Header", "gd-star-rating"), "header", "", "none");
$t->add_part(__("Item", "gd-star-rating"), "item", "", "all", "area");
$t->add_part(__("Footer", "gd-star-rating"), "footer", "", "none");
$tpls->add_template($t);

$t = new gdTemplate("CRB", __("Comments Ratings Block", "gd-star-rating"));
$t->add_element("%CMM_RATING%", __("comment rating", "gd-star-rating"));
$t->add_element("%MAX_CMM_RATING%", __("maximum comment rating value", "gd-star-rating"));
$t->add_element("%CMM_VOTES%", __("total votes for comment", "gd-star-rating"));
$t->add_element("%WORD_VOTES%", __("singular/plural word votes", "gd-star-rating"));
$t->add_part(__("Normal", "gd-star-rating"), "normal", "", "all");
$tpls->add_template($t);

$t = new gdTemplate("SRS", __("Standard RSS Block", "gd-star-rating"));
$t->add_element("%RATING%", __("article rating", "gd-star-rating"));
$t->add_element("%MAX_RATING%", __("maximum rating value", "gd-star-rating"));
$t->add_element("%VOTES%", __("total votes for article", "gd-star-rating"));
$t->add_element("%ID%", __("post/page id", "gd-star-rating"));
$t->add_element("%WORD_VOTES%", __("singular/plural word votes", "gd-star-rating"));
$t->add_part(__("Normal", "gd-star-rating"), "normal", "", "all");
$tpls->add_template($t);

$t = new gdTemplate("CAR", __("Comments Aggregated Rating", "gd-star-rating"));
$t->add_element("%CMM_RATING%", __("comment rating", "gd-star-rating"));
$t->add_element("%MAX_CMM_RATING%", __("maximum comment rating value", "gd-star-rating"));
$t->add_element("%CMM_VOTES%", __("total votes for all comment", "gd-star-rating"));
$t->add_element("%WORD_VOTES%", __("singular/plural word votes", "gd-star-rating"));
$t->add_part(__("Normal", "gd-star-rating"), "normal", "", "all");
$tpls->add_template($t);

?>
