<?php

    require_once("../gd-star-config.php");
    $wpconfig = get_wpconfig();
    require($wpconfig);
    require_once("../code/gd-star-functions.php");
    include("../stars/stars.php");
    global $gdsr;
    $gdsr_styles = $gdsr->g->stars;

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>StarRating</title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo STARRATING_URL ?>tinymce3/tinymce.js"></script>
	<base target="_self" />
</head>
<body id="link" onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display=''" style="display: none">
<form name="StarRating" action="#">
    <div class="tabs">
        <ul>
            <li id="shortcode_tab" class="current"><span><a href="javascript:mcTabs.displayTab('shortcode_tab','shortcode_panel');" onmousedown="return false;"><?php _e("Shortcode", "gd-star-rating"); ?></a></span></li>
            <li id="filter_tab"><span><a href="javascript:mcTabs.displayTab('filter_tab','filter_panel');" onmousedown="return false;"><?php _e("Filter", "gd-star-rating"); ?></a></span></li>
            <li id="display_tab"><span><a href="javascript:mcTabs.displayTab('display_tab','display_panel');" onmousedown="return false;"><?php _e("Display", "gd-star-rating"); ?></a></span></li>
            <li id="stars_tab"><span><a href="javascript:mcTabs.displayTab('stars_tab','stars_panel');" onmousedown="return false;"><?php _e("Stars", "gd-star-rating"); ?></a></span></li>
            <li id="styles_tab"><span><a href="javascript:mcTabs.displayTab('styles_tab','styles_panel');" onmousedown="return false;"><?php _e("Styles", "gd-star-rating"); ?></a></span></li>
        </ul>
    </div>
<div class="panel_wrapper">
<div id="shortcode_panel" class="panel current">
    <table border="0" cellpadding="3" cellspacing="0" width="100%">
      <tr>
        <td nowrap="nowrap"><?php _e("Insert Shortcode", "gd-star-rating"); ?>:</td>
        <td align="right">
            <label><select id="srShortcode" name="srShortcode" style="width: 150px">
                <option value="starrating">StarRating</option>
                <option value="starreview">StarReview</option>
                <option value="starrater">StarRater</option>
                <option value="starratercomment">StarRater Comment</option>
            </select></label>
        </td>
      </tr>
    </table>
    <br />All the options in this options panel are for the StarRating panel. StarReview and StarRater don't have any options.
</div>
<div id="filter_panel" class="panel">
    <table border="0" cellpadding="3" cellspacing="0" width="100%">
      <tr>
        <td nowrap="nowrap"><?php _e("Category", "gd-star-rating"); ?>:</td>
        <td><?php GDSRDatabase::get_combo_categories('', 'srCategory'); ?></td>
      </tr>
      <tr>
        <td nowrap="nowrap"><?php _e("Votes From", "gd-star-rating"); ?>:</td>
        <td><label><select name="srShow" id="srShow" style="width: 130px">
            <option value="total"><?php _e("Everyone", "gd-star-rating"); ?></option>
            <option value="visitors"><?php _e("Visitors Only", "gd-star-rating"); ?></option>
            <option value="users"><?php _e("Users Only", "gd-star-rating"); ?></option>
        </select></label></td>
      </tr>
      <tr>
        <td nowrap="nowrap" colspan="2"><input type="checkbox" size="5" id="srHidempty" name="srHidempty" checked /><label for="srHeader"> <?php _e("Hide articles with no recorded votes.", "gd-star-rating"); ?></label></td>
      </tr>
      <tr>
        <td nowrap="nowrap"><?php _e("Display", "gd-star-rating"); ?>:</td>
        <td>
            <label><select id="srSelect" name="srSelect">
                <option value="postpage"><?php _e("Posts And Pages", "gd-star-rating"); ?></option>
                <option value="post"><?php _e("Posts Only", "gd-star-rating"); ?></option>
                <option value="page"><?php _e("Pages Only", "gd-star-rating"); ?></option>
            </select></label>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap"><?php _e("Sorting", "gd-star-rating"); ?>:</td>
        <td>
            <label><select id="srColumn" name="srColumn">
                <option value="rating"><?php _e("Rating", "gd-star-rating"); ?></option>
                <option value="review"><?php _e("Review", "gd-star-rating"); ?></option>
                <option value="votes"><?php _e("Total Votes", "gd-star-rating"); ?></option>
                <option value="id"><?php _e("Post ID", "gd-star-rating"); ?></option>
                <option value="post_title"><?php _e("Post Title", "gd-star-rating"); ?></option>
            </select></label>
            <label><select id="srOrder" name="srOrder">
                <option value="desc"><?php _e("Descending", "gd-star-rating"); ?></option>
                <option value="asc"><?php _e("Ascending", "gd-star-rating"); ?></option>
            </select></label>
        </td>
      </tr>
    </table>
</div>
<div id="display_panel" class="panel">
    <table border="0" cellpadding="3" cellspacing="0" width="100%">
      <tr>
        <td nowrap="nowrap"><?php _e("Number Of Posts", "gd-star-rating"); ?>:</td>
        <td><input type="text" size="8" id="srRows" name="srRows" value="10" /></td>
      </tr>
    </table>
    <table border="0" cellpadding="3" cellspacing="0">
      <tr>
        <td nowrap="nowrap"><input type="checkbox" size="5" id="srHeader" name="srHeader" checked /><label for="srHeader"> <?php _e("Show Table Header", "gd-star-rating"); ?></label></td>
      </tr>
      <tr>
        <td nowrap="nowrap"><input type="checkbox" size="5" id="srVotes" name="srVotes" checked /><label for="srVotes"> <?php _e("Show Column with Votes", "gd-star-rating"); ?></label></td>
      </tr>
      <tr>
        <td nowrap="nowrap"><input type="checkbox" size="5" id="srReview" name="srReview" checked /><label for="srReview"> <?php _e("Show Column with Review", "gd-star-rating"); ?></label></td>
      </tr>
      <tr>
        <td nowrap="nowrap"><input type="checkbox" size="5" id="srRating" name="srRating" checked /><label for="srRating"> <?php _e("Show Column with Rating", "gd-star-rating"); ?></label></td>
      </tr>
      <tr>
        <td nowrap="nowrap"><input type="checkbox" size="5" id="srLinks" name="srLinks" checked /><label for="srRating"> <?php _e("Post names link to actual posts", "gd-star-rating"); ?></label></td>
      </tr>
    </table>
</div>
<div id="stars_panel" class="panel">
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
</div>
<div id="styles_panel" class="panel">
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