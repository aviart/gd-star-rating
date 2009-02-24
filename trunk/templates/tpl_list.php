<?php

$tpls = new gdTemplates();
$t = new gdTemplate("STR", __("Standard Ratings"));
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
$tpls->add_template($t);

?>
