<?php

$tpls = new gdTemplates();

$t = new gdTemplate("EWV", __("Element Word Votes", "gd-star-rating"), "%WORD_VOTES%");
$t->add_part(__("Singular", "gd-star-rating"), "singular", "", "none");
$t->add_part(__("Plural", "gd-star-rating"), "plural", "", "none");
$tpls->add_template($t);

$t = new gdTemplate("ETR", __("Element For Alternating Table Rows", "gd-star-rating"), "%TABLE_ROW_CLASS%");
$t->add_part(__("Odd", "gd-star-rating"), "odd", "", "none");
$t->add_part(__("Even", "gd-star-rating"), "even", "", "none");
$tpls->add_template($t);

$t = new gdTemplate("SRT", __("Standard Ratings Text", "gd-star-rating"), "%RATING_TEXT%");
$t->add_template("EWV", "%WORD_VOTES%");
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
$t->add_template("EWV", "%WORD_VOTES%");
$t->add_template("ETR", "%TABLE_ROW_CLASS%");
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
$t->add_element("%IMAGE%", __("image for the post/page", "gd-star-rating"));
$t->add_element("%AUTHOR_NAME%", __("name of the post/page author", "gd-star-rating"));
$t->add_element("%AUTHOR_LINK%", __("url to authors archive", "gd-star-rating"));
$t->add_element("%WORD_VOTES%", __("singular/plural word votes", "gd-star-rating"));
$t->add_element("%TABLE_ROW_CLASS%", __("class for alternating table rows", "gd-star-rating"));
$t->add_part(__("Header", "gd-star-rating"), "header", "", "none");
$t->add_part(__("Item", "gd-star-rating"), "item", "", "all", "area");
$t->add_part(__("Footer", "gd-star-rating"), "footer", "", "none");
$tpls->add_template($t);

$t = new gdTemplate("CRT", __("Comments Ratings Text", "gd-star-rating"), "%CMM_RATING_TEXT%");
$t->add_template("EWV", "%WORD_VOTES%");
$t->add_element("%CMM_RATING%", __("comment rating", "gd-star-rating"));
$t->add_element("%MAX_CMM_RATING%", __("maximum comment rating value", "gd-star-rating"));
$t->add_element("%CMM_VOTES%", __("total votes for comment", "gd-star-rating"));
$t->add_element("%WORD_VOTES%", __("singular/plural word votes", "gd-star-rating"));
$t->add_part(__("Normal", "gd-star-rating"), "normal", "", "all");
$tpls->add_template($t);

$t = new gdTemplate("SSB", __("Standard RSS Rating Block", "gd-star-rating"));
$t->add_template("EWV", "%WORD_VOTES%");
$t->add_element("%RATING_STARS%", __("rating stars", "gd-star-rating"));
$t->add_element("%RATING%", __("article rating", "gd-star-rating"));
$t->add_element("%MAX_RATING%", __("maximum rating value", "gd-star-rating"));
$t->add_element("%VOTES%", __("total votes for article", "gd-star-rating"));
$t->add_element("%ID%", __("post/page id", "gd-star-rating"));
$t->add_element("%HEADER_TEXT%", __("rating header text", "gd-star-rating"));
$t->add_element("%WORD_VOTES%", __("singular/plural word votes", "gd-star-rating"));
$t->add_part(__("Normal", "gd-star-rating"), "normal", "", "all");
$tpls->add_template($t);

$t = new gdTemplate("CAR", __("Comments Aggregated Rating", "gd-star-rating"));
$t->add_template("EWV", "%WORD_VOTES%");
$t->add_element("%CMM_RATING%", __("comment rating", "gd-star-rating"));
$t->add_element("%MAX_CMM_RATING%", __("maximum comment rating value", "gd-star-rating"));
$t->add_element("%CMM_COUNT%", __("total comments", "gd-star-rating"));
$t->add_element("%CMM_VOTES%", __("total votes for all comment", "gd-star-rating"));
$t->add_element("%CMM_STARS%", __("comment rating stars", "gd-star-rating"));
$t->add_element("%WORD_VOTES%", __("singular/plural word votes", "gd-star-rating"));
$t->add_part(__("Normal", "gd-star-rating"), "normal", "", "all");
$tpls->add_template($t);

$t = new gdTemplate("MRT", __("Multi Ratings Text", "gd-star-rating"), "%MUR_RATING_TEXT%");
$t->add_template("EWV", "%WORD_VOTES%");
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

$t = new gdTemplate("SRB", __("Standard Ratings Block", "gd-star-rating"));
$t->add_template("SRT", "%RATING_TEXT%");
$t->add_element("%HEADER_TEXT%", __("rating header text", "gd-star-rating"));
$t->add_element("%RATING_STARS%", __("rating stars", "gd-star-rating"));
$t->add_element("%RATING_TEXT%", __("rating text", "gd-star-rating"));
$t->add_element("%CSS_BLOCK%", __("css class for whole block", "gd-star-rating"));
$t->add_element("%CSS_HEADER%", __("css class for header", "gd-star-rating"));
$t->add_element("%CSS_STARS%", __("css class for stars", "gd-star-rating"));
$t->add_element("%CSS_TEXT%", __("css class for rating text", "gd-star-rating"));
$t->add_part(__("Normal", "gd-star-rating"), "normal", "", "all", "area");
$tpls->add_template($t);

$t = new gdTemplate("CRB", __("Comments Ratings Block", "gd-star-rating"));
$t->add_template("CRT", "%CMM_RATING_TEXT%");
$t->add_element("%CMM_HEADER_TEXT%", __("rating header text", "gd-star-rating"));
$t->add_element("%CMM_RATING_STARS%", __("rating stars", "gd-star-rating"));
$t->add_element("%CMM_RATING_TEXT%", __("rating text", "gd-star-rating"));
$t->add_element("%CMM_CSS_BLOCK%", __("css class for whole block", "gd-star-rating"));
$t->add_element("%CMM_CSS_HEADER%", __("css class for header", "gd-star-rating"));
$t->add_element("%CMM_CSS_STARS%", __("css class for stars", "gd-star-rating"));
$t->add_element("%CMM_CSS_TEXT%", __("css class for rating text", "gd-star-rating"));
$t->add_part(__("Normal", "gd-star-rating"), "normal", "", "all", "area");
$tpls->add_template($t);

$t = new gdTemplate("WSR", __("Widget Star Rating", "gd-star-rating"));
$t->add_template("EWV", "%WORD_VOTES%");
$t->add_template("ETR", "%TABLE_ROW_CLASS%");
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
$t->add_element("%IMAGE%", __("image for the post/page", "gd-star-rating"));
$t->add_element("%AUTHOR_NAME%", __("name of the post/page author", "gd-star-rating"));
$t->add_element("%AUTHOR_LINK%", __("url to authors archive", "gd-star-rating"));
$t->add_element("%WORD_VOTES%", __("singular/plural word votes", "gd-star-rating"));
$t->add_element("%TABLE_ROW_CLASS%", __("class for alternating table rows", "gd-star-rating"));
$t->add_part(__("Header", "gd-star-rating"), "header", "", "none");
$t->add_part(__("Item", "gd-star-rating"), "item", "", "all", "area");
$t->add_part(__("Footer", "gd-star-rating"), "footer", "", "none");
$tpls->add_template($t);

$t = new gdTemplate("WBR", __("Widget Blog Rating", "gd-star-rating"));
$t->add_template("EWV", "%WORD_VOTES%");
$t->add_element("%RATING%", __("article rating", "gd-star-rating"));
$t->add_element("%MAX_RATING%", __("maximum rating value", "gd-star-rating"));
$t->add_element("%BAYES_RATING%", __("bayesian estimate mean rating", "gd-star-rating"));
$t->add_element("%VOTES%", __("total votes for article", "gd-star-rating"));
$t->add_element("%COUNT%", __("number of posts/pages", "gd-star-rating"));
$t->add_element("%PERCENTAGE%", __("article percentage rating", "gd-star-rating"));
$t->add_element("%WORD_VOTES%", __("singular/plural word votes", "gd-star-rating"));
$t->add_part(__("Normal", "gd-star-rating"), "normal", "", "all", "area");
$tpls->add_template($t);

$t = new gdTemplate("WCR", __("Widget Comments Rating", "gd-star-rating"));
$t->add_template("EWV", "%WORD_VOTES%");
$t->add_element("%CMM_RATING%", __("article rating", "gd-star-rating"));
$t->add_element("%MAX_CMM_RATING%", __("maximum rating value", "gd-star-rating"));
$t->add_element("%CMM_VOTES%", __("total votes for article", "gd-star-rating"));
$t->add_element("%COMMENT%", __("comment contents", "gd-star-rating"));
$t->add_element("%PERMALINK%", __("url to comment", "gd-star-rating"));
$t->add_element("%STARS%", __("rating stars", "gd-star-rating"));
$t->add_element("%WIDGET_TITLE%", __("widget title", "gd-star-rating"));
$t->add_element("%POST_TITLE%", __("post title", "gd-star-rating"));
$t->add_element("%POST_ID%", __("post id", "gd-star-rating"));
$t->add_element("%ID%", __("comment id", "gd-star-rating"));
$t->add_element("%AUTHOR_NAME%", __("name of the comments author", "gd-star-rating"));
$t->add_element("%AUTHOR_LINK%", __("authors url", "gd-star-rating"));
$t->add_element("%AUTHOR_AVATAR%", __("authors avatar", "gd-star-rating"));
$t->add_element("%WORD_VOTES%", __("singular/plural word votes", "gd-star-rating"));
$t->add_element("%TABLE_ROW_CLASS%", __("class for alternating table rows", "gd-star-rating"));
$t->add_part(__("Title", "gd-star-rating"), "title", array("%POST_ID%", "%POST_TITLE%"));
$t->add_part(__("Header", "gd-star-rating"), "header", "", "none");
$t->add_part(__("Item", "gd-star-rating"), "item", "", "all", "area");
$t->add_part(__("Footer", "gd-star-rating"), "footer", "", "none");
$tpls->add_template($t);

?>
