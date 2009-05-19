<?php

    require_once("../config.php");
    $wpconfig = get_wpconfig();
    require($wpconfig);
    global $gdsr;
    require_once(STARRATING_PATH."code/helpers.php");

    $gdsr_styles = $gdsr->g->stars;
    $gdsr_trends = $gdsr->g->trend;
    $gdst_multis = GDSRDBMulti::get_multis_tinymce();

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>StarRating</title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo STARRATING_URL ?>tinymce3/tinymce.js"></script>
    <link rel="stylesheet" href="<?php echo STARRATING_URL ?>tinymce3/tinymce.css" type="text/css" media="screen" />
	<base target="_self" />
</head>
<body onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display=''" style="display: none">
<form name="StarRating" action="#">
    <div class="tabs">
        <ul>
            <li id="shortcode_tab" class="current"><span><a href="javascript:mcTabs.displayTab('shortcode_tab','shortcode_panel');" onmousedown="return false;"><?php _e("Insert", "gd-star-rating"); ?></a></span></li>
            <li id="general_tab"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');" onmousedown="return false;"><?php _e("General", "gd-star-rating"); ?></a></span></li>
            <li id="filter_tab"><span><a href="javascript:mcTabs.displayTab('filter_tab','filter_panel');" onmousedown="return false;"><?php _e("Filter", "gd-star-rating"); ?></a></span></li>
            <li id="styles_tab"><span><a href="javascript:mcTabs.displayTab('styles_tab','styles_panel');" onmousedown="return false;"><?php _e("Graphics", "gd-star-rating"); ?></a></span></li>
            <li style="display: none" id="multis_tab"><span><a href="javascript:mcTabs.displayTab('multis_tab','multis_panel');" onmousedown="return false;"><?php _e("Multi Rating", "gd-star-rating"); ?></a></span></li>
            <li style="display: none" id="multisreview_tab"><span><a href="javascript:mcTabs.displayTab('multisreview_tab','multisreview_panel');" onmousedown="return false;"><?php _e("Multi Review", "gd-star-rating"); ?></a></span></li>
            <li style="display: none" id="articlesreview_tab"><span><a href="javascript:mcTabs.displayTab('articlesreview_tab','articlesreview_panel');" onmousedown="return false;"><?php _e("Articles Review", "gd-star-rating"); ?></a></span></li>
            <li style="display: none" id="articlesrater_tab"><span><a href="javascript:mcTabs.displayTab('articlesrater_tab','articlesrater_panel');" onmousedown="return false;"><?php _e("Articles Rating Block", "gd-star-rating"); ?></a></span></li>
            <li style="display: none" id="commentsaggr_tab"><span><a href="javascript:mcTabs.displayTab('commentsaggr_tab','commentsaggr_panel');" onmousedown="return false;"><?php _e("Aggregated Comments", "gd-star-rating"); ?></a></span></li>
        </ul>
    </div>
<div class="panel_wrapper">

<div id="shortcode_panel" class="panel current">
<fieldset>
<legend><?php _e("Shortcode", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="3" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrright">
            <label>
                <select onchange="gdsrChangeShortcode()" id="srShortcode" name="srShortcode" style="width: 200px">
                    <option value="starrating"><?php _e("Advanced", "gd-star-rating"); ?>: StarRating</option>
                    <option value="starrating">--------------------</option>
                    <option value="starratingmulti"><?php _e("Multi", "gd-star-rating"); ?>: StarRatingMulti</option>
                    <option value="starreviewmulti"><?php _e("Multi", "gd-star-rating"); ?>: StarReviewMulti</option>
                    <option value="starrating">--------------------</option>
                    <option value="starreview"><?php _e("Articles", "gd-star-rating"); ?>: StarReview</option>
                    <option value="starrater"><?php _e("Articles", "gd-star-rating"); ?>: StarRater</option>
                    <option value="starrating">--------------------</option>
                    <option value="starcomments"><?php _e("Comments", "gd-star-rating"); ?>: StarComments</option>
                </select>
            </label>
        </td>
      </tr>
    </table>
</fieldset>
<fieldset>
<legend><?php _e("Shortcode Info", "gd-star-rating"); ?></legend>
<p><?php _e("Change shortcode to see additional options you can set.", "gd-star-rating"); ?></p>
</fieldset>
</div>

<?php include(dirname(__FILE__)."/panels/advanced.php"); ?>
<?php include(dirname(__FILE__)."/panels/multi.php"); ?>
<?php include(dirname(__FILE__)."/panels/articles.php"); ?>
<?php include(dirname(__FILE__)."/panels/comments.php"); ?>

</div>

<div class="mceActionPanel">
    <div style="float: left">
        <input type="button" id="cancel" name="cancel" value="<?php _e("Cancel", "gd-star-rating"); ?>" onclick="tinyMCEPopup.close();" />
    </div>

    <div style="float: right">
        <input type="submit" id="insert" name="insert" value="<?php _e("Insert", "gd-star-rating"); ?>" onclick="insertStarRatingCode();" />
    </div>
</div>
</form>
</body>
</html>