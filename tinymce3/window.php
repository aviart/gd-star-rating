<?php

    require_once("../gd-star-config.php");
    $wpconfig = get_wpconfig();
    require($wpconfig);
    require_once("../code/gd-star-functions.php");
    include("../stars/stars.php");
    global $gdsr;
    $gdsr_styles = $gdsr->g->stars;
    $gdsr_trends = $gdsr->g->trend;

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>StarRating</title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo STARRATING_URL ?>tinymce3/tinymce.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo STARRATING_URL ?>tinymce3/control.js"></script>
    <link rel="stylesheet" href="<?php echo STARRATING_URL ?>tinymce3/tinymce.css" type="text/css" media="screen" />
	<base target="_self" />
</head>
<body onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display=''" style="display: none">
<form name="StarRating" action="#">
    <div class="tabs">
        <ul>
            <li id="shortcode_tab" class="current"><span><a href="javascript:mcTabs.displayTab('shortcode_tab','shortcode_panel');" onmousedown="return false;"><?php _e("Shortcode", "gd-star-rating"); ?></a></span></li>
            <li id="general_tab"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');" onmousedown="return false;"><?php _e("General", "gd-star-rating"); ?></a></span></li>
            <li id="filter_tab"><span><a href="javascript:mcTabs.displayTab('filter_tab','filter_panel');" onmousedown="return false;"><?php _e("Filter", "gd-star-rating"); ?></a></span></li>
            <li id="styles_tab"><span><a href="javascript:mcTabs.displayTab('styles_tab','styles_panel');" onmousedown="return false;"><?php _e("Rendering", "gd-star-rating"); ?></a></span></li>
        </ul>
    </div>
<div class="panel_wrapper">

<div id="shortcode_panel" class="panel current">
<fieldset>
<legend><?php _e("Insert Shortcode", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="3" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrright">
            <label><select id="srShortcode" name="srShortcode" style="width: 150px">
                <option value="starrating">Advanced: StarRating</option>
                <option value="starrating">--------------------</option>
                <option value="starreview">Simple: StarReview</option>
                <option value="starrater">Simple: StarRater</option>
            </select></label>
        </td>
      </tr>
    </table>
</fieldset>
<fieldset>
<legend><?php _e("Shortcode Info", "gd-star-rating"); ?></legend>
<p><?php _e("All the options in this options panel are for the StarRating panel. StarReview and StarRater don't have any options.", "gd-star-rating"); ?></p>
<br />
<p><?php _e("StarReview will render stars representing review value assigned to the post or page.", "gd-star-rating"); ?></p>
<br />
<p><?php _e("StarRater will render actual rating block if you choose not to have it automatically inserted. This way you can position it wherever you want in the contnents.", "gd-star-rating"); ?></p>
</fieldset>
</div>

<div id="general_panel" class="panel">
<fieldset>
<legend><?php _e("Display", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="3" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Number Of Posts", "gd-star-rating"); ?>:</td>
        <td class="gdsrright"><input type="text" size="8" id="srRows" name="srRows" value="10" /></td>
      </tr>
      <tr>
        <td class="gdsrleft"><?php _e("Items Grouping", "gd-star-rating"); ?>:</td>
        <td class="gdsrright"><label><select name="<?php echo $wpfn; ?>[grouping]" id="gdstarr-grouping" style="width: 110px">
            <option value="post"<?php echo $wpno['grouping'] == 'post' ? ' selected="selected"' : ''; ?>><?php _e("No grouping", "gd-star-rating"); ?></option>
            <option value="user"<?php echo $wpno['grouping'] == 'user' ? ' selected="selected"' : ''; ?>><?php _e("User based", "gd-star-rating"); ?></option>
            <option value="category"<?php echo $wpno['grouping'] == 'category' ? ' selected="selected"' : ''; ?>><?php _e("Category based", "gd-star-rating"); ?></option>
        </select></label></td>
      </tr>
    </table>
</fieldset>

<fieldset>
<legend><?php _e("Trend", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="3" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Rating trend display as", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <select name="trendRating" style="width: 110px" id="trendRating" onchange="gdsrChangeTrend('tr', this.options[this.selectedIndex].value, 'tinymce')">
                <option value="txt"><?php _e("Text", "gd-star-rating"); ?></option>
                <option value="img"><?php _e("Image", "gd-star-rating"); ?></option>
            </select>
        </td>
      </tr>
    </table>  
    <div id="gdsr-tr-txt[tinymce]" style="display: block">
        <table border="0" cellpadding="3" cellspacing="0" width="100%">
          <tr>
            <td class="gdsrleft"></td>
            <td class="gdsrreg"><?php _e("Up", "gd-star-rating"); ?>:</td>
            <td width="40" class="gdsrright"><input class="widefat" style="width: 35px" type="text" name="trendRatingRise" id="trendRatingRise" value="+" /></td>
            <td class="gdsrreg"><?php _e("Equal", "gd-star-rating"); ?>:</td>
            <td width="40" class="gdsrright"><input class="widefat" style="width: 35px" type="text" name="trendRatingSame" id="trendRatingSame" value="=" /></td>
            <td class="gdsrreg"><?php _e("Down", "gd-star-rating"); ?>:</td>
            <td width="40" class="gdsrright"><input class="widefat" style="width: 35px" type="text" name="trendRatingFall" id="trendRatingFall" value="-" /></td>
          </tr>
        </table>  
    </div>
    <div id="gdsr-tr-img[tinymce]" style="display: none">
        <table border="0" cellpadding="3" cellspacing="0" width="100%">
          <tr>
            <td class="gdsrleft"><?php _e("Image set", "gd-star-rating"); ?>:</td>
            <td class="gdsrright">
                <select name="trendRatingSet" id="trendRatingSet">
                    <?php GDSRHelper::render_styles_select($gdsr_trends); ?>
                </select>
            </td>
          </tr>
        </table>  
    </div>
    <table border="0" cellpadding="3" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Voting trend display as", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <select name="trendVoting" style="width: 110px" id="trendVoting" onchange="gdsrChangeTrend('tv', this.options[this.selectedIndex].value, 'tinymce')">
                <option value="txt"><?php _e("Text", "gd-star-rating"); ?></option>
                <option value="img"><?php _e("Image", "gd-star-rating"); ?></option>
            </select>
        </td>
      </tr>
    </table>
    <div id="gdsr-tv-txt[tinymce]" style="display: block">
        <table border="0" cellpadding="3" cellspacing="0" width="100%">
          <tr>
            <td class="gdsrleft"></td>
            <td class="gdsrreg"><?php _e("Up", "gd-star-rating"); ?>:</td>
            <td width="40" class="gdsrright"><input class="widefat" style="width: 35px" type="text" name="trendVotingRise" id="trendVotingRise" value="+" /></td>
            <td class="gdsrreg"><?php _e("Equal", "gd-star-rating"); ?>:</td>
            <td width="40" class="gdsrright"><input class="widefat" style="width: 35px" type="text" name="trendVotingSame" id="trendVotingSame" value="=" /></td>
            <td class="gdsrreg"><?php _e("Down", "gd-star-rating"); ?>:</td>
            <td width="40" class="gdsrright"><input class="widefat" style="width: 35px" type="text" name="trendVotingFall" id="trendVotingFall" value="-" /></td>
          </tr>
        </table>  
    </div>
    <div id="gdsr-tv-img[tinymce]" style="display: none">
        <table border="0" cellpadding="3" cellspacing="0" width="100%">
          <tr>
            <td class="gdsrleft"><?php _e("Image set", "gd-star-rating"); ?>:</td>
            <td class="gdsrright">
                <select name="trendVotingSet" id="trendVotingSet">
                    <?php GDSRHelper::render_styles_select($gdsr_trends); ?>
                </select>
            </td>
          </tr>
        </table>  
    </div>
</fieldset>

<fieldset>
<legend><?php _e("Hiding", "gd-star-rating"); ?></legend>
    <input type="checkbox" size="5" id="srHidemptyBayes" name="srHidemptyBayes" checked /><label for="srHidemptyBayes"> <?php _e("Bayesian minumum votes required.", "gd-star-rating"); ?></label><br />
    <input type="checkbox" size="5" id="srHidempty" name="srHidempty" checked /><label for="srHidempty"> <?php _e("Hide articles with no recorded votes.", "gd-star-rating"); ?></label><br />
    <input type="checkbox" size="5" id="srHidemptyReview" name="srHidemptyReview" checked /><label for="srHidemptyReview"> <?php _e("Hide articles with no review values.", "gd-star-rating"); ?></label>
</fieldset>

</div>

<div id="filter_panel" class="panel">

<fieldset>
<legend><?php _e("Basic", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="3" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Include Articles", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <label><select id="srSelect" name="srSelect" style="width: 130px">
                <option value="postpage"><?php _e("Posts And Pages", "gd-star-rating"); ?></option>
                <option value="post"><?php _e("Posts Only", "gd-star-rating"); ?></option>
                <option value="page"><?php _e("Pages Only", "gd-star-rating"); ?></option>
            </select></label>
        </td>
      </tr>
      <tr>
        <td class="gdsrleft"><?php _e("Display Votes From", "gd-star-rating"); ?>:</td>
        <td class="gdsrright"><label><select name="srShow" id="srShow" style="width: 130px">
            <option value="total"><?php _e("Everyone", "gd-star-rating"); ?></option>
            <option value="visitors"><?php _e("Visitors Only", "gd-star-rating"); ?></option>
            <option value="users"><?php _e("Users Only", "gd-star-rating"); ?></option>
        </select></label></td>
      </tr>
      <tr>
        <td nowrap="nowrap"><?php _e("Category", "gd-star-rating"); ?>:</td>
        <td><?php GDSRDatabase::get_combo_categories('', 'srCategory'); ?></td>
      </tr>
    </table>
</fieldset>

<fieldset>
<legend><?php _e("Sorting", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="3" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Column", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <label><select id="srColumn" name="srColumn" style="width: 130px">
            <option value="rating"><?php _e("Rating", "gd-star-rating"); ?></option>
            <option value="votes"><?php _e("Total Votes", "gd-star-rating"); ?></option>
            <option value="id"><?php _e("ID", "gd-star-rating"); ?></option>
            <option value="post_title"><?php _e("Title", "gd-star-rating"); ?></option>
            <option value="review"><?php _e("Review", "gd-star-rating"); ?></option>
            <option value="count"><?php _e("Count", "gd-star-rating"); ?></option>
            <option value="bayes"><?php _e("Bayesian Rating", "gd-star-rating"); ?></option>
            </select></label>
        </td>
      </tr>
      <tr>
        <td class="gdsrleft"><?php _e("Order", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <label><select id="srOrder" name="srOrder" style="width: 130px">
                <option value="desc"><?php _e("Descending", "gd-star-rating"); ?></option>
                <option value="asc"><?php _e("Ascending", "gd-star-rating"); ?></option>
            </select></label>
        </td>
      </tr>
    </table>
</fieldset>

<fieldset>
<legend><?php _e("Date", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="3" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Publish Date", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <select name="publishDate" style="width: 130px" id="class="gdsrleft"" onchange="gdsrChangeDate(this.options[this.selectedIndex].value, 'tinymce')">
                <option value="lastd"><?php _e("Last # days", "gd-star-rating"); ?></option>
                <option value="month"><?php _e("Exact month", "gd-star-rating"); ?></option>
                <option value="range"><?php _e("Date range", "gd-star-rating"); ?></option>
            </select>
        </td>
      </tr>
    </table>
<div id="gdsr-pd-lastd[tinymce]" style="display: block">
    <table border="0" cellpadding="2" cellspacing="0" width="100%"> 
      <tr>
        <td class="gdsrleft"><?php _e("Number Of Days", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <input class="widefat" style="text-align: right; width: 102px" type="text" name="publishDays" id="publishDays" value="0" />
        </td>
      </tr>
    </table>
    </div>
    <div id="gdsr-pd-month[tinymce]" style="display: none">
    <table border="0" cellpadding="2" cellspacing="0" width="100%"> 
      <tr>
        <td class="gdsrleft"><?php _e("Month", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <?php GDSRDatabase::get_combo_months("0", "publishMonth"); ?>
        </td>
      </tr>
    </table>
    </div>
    <div id="gdsr-pd-range[tinymce]" style="display: none">
    <table border="0" cellpadding="2" cellspacing="0" width="100%"> 
      <tr>
        <td class="gdsrleft"><?php _e("Range", "gd-star-rating"); ?>:</td>
        <td align="right" width="85"><input class="widefat" style="text-align: right; width: 85px" type="text" name="publishRangeFrom" id="publishRangeFrom" value="YYYY-MM-DD" /></td>
        <td align="center" width="10">-</td>
        <td align="right" width="85"><input class="widefat" style="text-align: right; width: 85px" type="text" name="publishRangeTo" id="publishRangeTo" value="YYYY-MM-DD" /></td>
      </tr>
    </table>
</div>
</fieldset>

</div>

<div id="styles_panel" class="panel">
<fieldset>
<legend><?php _e("Stars", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
      <tr>
        <td nowrap="nowrap"><?php _e("Rating as", "gd-star-rating"); ?>:</td>
        <td>
            <label><select id="srRType" name="srRType">
                <option value="number"><?php _e("Number", "gd-star-rating"); ?></option>
                <option value="stars"><?php _e("Stars", "gd-star-rating"); ?></option>
            </select></label>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap"><?php _e("Rating Stars", "gd-star-rating"); ?>:</td>
        <td>
            <label><select id="srStarsStyle" name="srStarsStyle">
                <?php GDSRHelper::render_styles_select($gdsr_styles); ?>
            </select></label>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap"><?php _e("Rating Size", "gd-star-rating"); ?>:</td>
        <td>
            <label><select id="srStarsSize" name="srStarsSize">
                <option value="12"><?php _e("Mini", "gd-star-rating"); ?></option>
                <option value="20"><?php _e("Small", "gd-star-rating"); ?></option>
                <option value="30"><?php _e("Medium", "gd-star-rating"); ?></option>
                <option value="46"><?php _e("Big", "gd-star-rating"); ?></option>
            </select></label>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap"><?php _e("Review as", "gd-star-rating"); ?>:</td>
        <td>
            <label><select id="srVType" name="srVType">
                <option value="number"><?php _e("Number", "gd-star-rating"); ?></option>
                <option value="stars"><?php _e("Stars", "gd-star-rating"); ?></option>
            </select></label>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap"><?php _e("Review Stars", "gd-star-rating"); ?>:</td>
        <td>
            <label><select id="srReviewStarsStyle" name="srReviewStarsStyle">
                <?php GDSRHelper::render_styles_select($gdsr_styles); ?>
            </select></label>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap"><?php _e("Review Size", "gd-star-rating"); ?>:</td>
        <td>
            <label><select id="srReviewStarsSize" name="srReviewStarsSize">
                <option value="12"><?php _e("Mini", "gd-star-rating"); ?></option>
                <option value="20"><?php _e("Small", "gd-star-rating"); ?></option>
                <option value="30"><?php _e("Medium", "gd-star-rating"); ?></option>
                <option value="46"><?php _e("Big", "gd-star-rating"); ?></option>
            </select></label>
        </td>
      </tr>
    </table>
</fieldset>


    <table border="0" cellpadding="4" cellspacing="0" width="100%">
      <tr>
        <td nowrap="nowrap"><?php _e("Style Class", "gd-star-rating"); ?>:</td>
        <td>
            <label><select id="srSType" name="srSType">
                <option value="built"><?php _e("Built In", "gd-star-rating"); ?></option>
                <option value="external"><?php _e("External", "gd-star-rating"); ?></option>
            </select></label>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap"><?php _e("Built In Class", "gd-star-rating"); ?>:</td>
        <td>
            <label><select id="srClassBuild" name="srClassBuild">
                <?php GDSRHelper::render_class_select($gdsr_classes); ?>
            </select></label>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap"><?php _e("External Class", "gd-star-rating"); ?>:</td>
        <td><input type="text" size="15" id="srClass" name="srClass" value="starrating" /></td>
      </tr>
    </table>
</div>

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