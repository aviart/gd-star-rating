<script>
function gdsrChangeDate(el, index) {
    document.getElementById("gdsr-pd-lastd["+index+"]").style.display = el == "lastd" ? "block" : "none";
    document.getElementById("gdsr-pd-month["+index+"]").style.display = el == "month" ? "block" : "none";
    document.getElementById("gdsr-pd-range["+index+"]").style.display = el == "range" ? "block" : "none";
}

function gdsrChangeTrend(trend, el, index) {
    document.getElementById("gdsr-"+trend+"-txt["+index+"]").style.display = el == "txt" ? "block" : "none";
    document.getElementById("gdsr-"+trend+"-img["+index+"]").style.display = el == "img" ? "block" : "none";
}

function gdsrShowHidePreview(gdid, index) {
    var preview = document.getElementById(gdid+'-on['+index+']');
    var message = document.getElementById(gdid+'-off['+index+']');
    var hidden = document.getElementById(gdid+'['+index+']');

    if (preview.style.display == "block") {
        preview.style.display = "none";
        message.style.display = "block";
        hidden.value = "0";
    }
    else {
        preview.style.display = "block";
        message.style.display = "none";
        hidden.value = "1";
    }
}
</script>

<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td nowrap="nowrap" width="140"><?php _e("Title", "gd-star-rating"); ?>:</td>
    <td align="right"><input class="widefat" style="width: 260px" type="text" name="<?php echo $wpfn; ?>[title]" id="gdstarr-title" value="<?php echo $wpno["title"]; ?>" /></td>
  </tr>
</table>
<table border="0" cellpadding="2" cellspacing="0" width="100%"> 
  <tr>
    <td width="140" nowrap="nowrap"><?php _e("Show Widget To", "gd-star-rating"); ?>:</td>
    <td align="right">
        <label><select name="<?php echo $wpfn; ?>[display]" id="gdstarr-display" style="width: 110px">
            <option value="all"<?php echo $wpno['display'] == 'all' ? ' selected="selected"' : ''; ?>><?php _e("Everyone", "gd-star-rating"); ?></option>
            <option value="visitors"<?php echo $wpno['display'] == 'visitors' ? ' selected="selected"' : ''; ?>><?php _e("Visitors Only", "gd-star-rating"); ?></option>
            <option value="users"<?php echo $wpno['display'] == 'users' ? ' selected="selected"' : ''; ?>><?php _e("Users Only", "gd-star-rating"); ?></option>
            <option value="hide"<?php echo $wpno['display'] == 'hide' ? ' selected="selected"' : ''; ?>><?php _e("Hide Widget", "gd-star-rating"); ?></option>
        </select></label>
    </td>
  </tr>
  <tr>
    <td width="140" nowrap="nowrap"><?php _e("Items Grouping", "gd-star-rating"); ?>:</td>
    <td align="right">
        <label><select name="<?php echo $wpfn; ?>[grouping]" id="gdstarr-grouping" style="width: 110px">
            <option value="post"<?php echo $wpno['grouping'] == 'post' ? ' selected="selected"' : ''; ?>><?php _e("No grouping", "gd-star-rating"); ?></option>
            <option value="user"<?php echo $wpno['grouping'] == 'user' ? ' selected="selected"' : ''; ?>><?php _e("User based", "gd-star-rating"); ?></option>
            <option value="category"<?php echo $wpno['grouping'] == 'category' ? ' selected="selected"' : ''; ?>><?php _e("Category based", "gd-star-rating"); ?></option>
        </select></label>
    </td>
  </tr>
</table>
<div class="gdsr-table-split"></div>

<input type="hidden" id="gdstarr-divtrend[<?php echo $wpnm; ?>]" name="<?php echo $wpfn; ?>[div_trend]" value="<?php echo $wpno['div_trend'] ?>" />
<div id="gdstarr-divtrend-off[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['div_trend'] == '1' ? 'none' : 'block' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%"> 
  <tr>
    <td width="100" valign="top"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('gdstarr-divtrend', '<?php echo $wpnm; ?>')"><?php _e("Trend", "gd-star-rating"); ?></a></strong></td>
    <td align="right"><?php _e("Click on the header title to display the options.", "gd-star-rating"); ?></td>
  </tr>
</table>
</div>
<div id="gdstarr-divtrend-on[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['div_trend'] == '1' ? 'block' : 'none' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%"> 
  <tr>
    <td width="100" valign="top"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('gdstarr-divtrend', '<?php echo $wpnm; ?>')"><?php _e("Trend", "gd-star-rating"); ?></a></strong></td>
    <td width="150" nowrap="nowrap"><?php _e("Rating trend display as", "gd-star-rating"); ?>:</td>
    <td align="right">
        <select name="<?php echo $wpfn; ?>[trends_rating]" style="width: 110px" id="gdstarr-trend-rating" onchange="gdsrChangeTrend('tr', this.options[this.selectedIndex].value, '<?php echo $wpnm; ?>')">
            <option value="txt"<?php echo $wpno['trends_rating'] == 'txt' ? ' selected="selected"' : ''; ?>><?php _e("Text", "gd-star-rating"); ?></option>
            <option value="img"<?php echo $wpno['trends_rating'] == 'img' ? ' selected="selected"' : ''; ?>><?php _e("Image", "gd-star-rating"); ?></option>
        </select>
    </td>
  </tr>
</table>  
<div id="gdsr-tr-txt[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['trends_rating'] == 'txt' ? 'block' : 'none' ?>">
    <table border="0" cellpadding="2" cellspacing="0" width="100%"> 
      <tr>
        <td width="100"></td>
        <td><?php _e("Up", "gd-star-rating"); ?>:</td>
        <td width="50" align="right"><input class="widefat" style="width: 35px" type="text" name="<?php echo $wpfn; ?>[trends_rating_rise]" id="gdstarr-trendsratingrise" value="<?php echo $wpno["trends_rating_rise"]; ?>" /></td>
        <td><?php _e("Equal", "gd-star-rating"); ?>:</td>
        <td width="50" align="right"><input class="widefat" style="width: 35px" type="text" name="<?php echo $wpfn; ?>[trends_rating_same]" id="gdstarr-trendsratingsame" value="<?php echo $wpno["trends_rating_same"]; ?>" /></td>
        <td><?php _e("Down", "gd-star-rating"); ?>:</td>
        <td width="50" align="right"><input class="widefat" style="width: 35px" type="text" name="<?php echo $wpfn; ?>[trends_rating_fall]" id="gdstarr-trendsratingfall" value="<?php echo $wpno["trends_rating_fall"]; ?>" /></td>
      </tr>
    </table>  
</div>
<div id="gdsr-tr-img[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['trends_rating'] == 'img' ? 'block' : 'none' ?>">
    <table border="0" cellpadding="2" cellspacing="0" width="100%"> 
      <tr>
        <td width="100"></td>
        <td><?php _e("Image set", "gd-star-rating"); ?>:</td>
        <td align="right">
            <select style="width: 180px;" name="<?php echo $wpfn; ?>[trends_rating_set]" id="gdstarr-trendsratingset">
                <?php GDSRHelper::render_styles_select($wptr, $wpno["trends_rating_set"]); ?>
            </select>
        </td>
      </tr>
    </table>  
</div>
<table border="0" cellpadding="2" cellspacing="0" width="100%"> 
  <tr>
    <td width="100"></td>
    <td width="150" nowrap="nowrap"><?php _e("Voting trend display as", "gd-star-rating"); ?>:</td>
    <td align="right">
        <select name="<?php echo $wpfn; ?>[trends_voting]" style="width: 110px" id="gdstarr-trend-voting" onchange="gdsrChangeTrend('tv', this.options[this.selectedIndex].value, '<?php echo $wpnm; ?>')">
            <option value="txt"<?php echo $wpno['trends_voting'] == 'txt' ? ' selected="selected"' : ''; ?>><?php _e("Text", "gd-star-rating"); ?></option>
            <option value="img"<?php echo $wpno['trends_voting'] == 'img' ? ' selected="selected"' : ''; ?>><?php _e("Image", "gd-star-rating"); ?></option>
        </select>
    </td>
  </tr>
</table>
<div id="gdsr-tv-txt[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['trends_voting'] == 'txt' ? 'block' : 'none' ?>">
    <table border="0" cellpadding="2" cellspacing="0" width="100%"> 
      <tr>
        <td width="100"></td>
        <td><?php _e("Up", "gd-star-rating"); ?>:</td>
        <td width="50" align="right"><input class="widefat" style="width: 35px" type="text" name="<?php echo $wpfn; ?>[trends_voting_rise]" id="gdstarr-trendsvotingrise" value="<?php echo $wpno["trends_voting_rise"]; ?>" /></td>
        <td><?php _e("Equal", "gd-star-rating"); ?>:</td>
        <td width="50" align="right"><input class="widefat" style="width: 35px" type="text" name="<?php echo $wpfn; ?>[trends_voting_same]" id="gdstarr-trendsvotingsame" value="<?php echo $wpno["trends_voting_same"]; ?>" /></td>
        <td><?php _e("Down", "gd-star-rating"); ?>:</td>
        <td width="50" align="right"><input class="widefat" style="width: 35px" type="text" name="<?php echo $wpfn; ?>[trends_voting_fall]" id="gdstarr-trendsvotingfall" value="<?php echo $wpno["trends_voting_fall"]; ?>" /></td>
      </tr>
    </table>  
</div>
<div id="gdsr-tv-img[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['trends_voting'] == 'img' ? 'block' : 'none' ?>">
    <table border="0" cellpadding="2" cellspacing="0" width="100%"> 
      <tr>
        <td width="100"></td>
        <td><?php _e("Image set", "gd-star-rating"); ?>:</td>
        <td align="right">
            <select style="width: 180px;" name="<?php echo $wpfn; ?>[trends_voting_set]" id="gdstarr-trendsvotingset">
                <?php GDSRHelper::render_styles_select($wptr, $wpno["trends_voting_set"]); ?>
            </select>
        </td>
      </tr>
    </table>  
</div>
</div>
<div class="gdsr-table-split"></div>

<input type="hidden" id="gdstarr-divfilter[<?php echo $wpnm; ?>]" name="<?php echo $wpfn; ?>[div_filter]" value="<?php echo $wpno['div_filter'] ?>" />
<div id="gdstarr-divfilter-off[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['div_filter'] == '1' ? 'none' : 'block' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%"> 
  <tr>
    <td width="100" valign="top"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('gdstarr-divfilter', '<?php echo $wpnm; ?>')"><?php _e("Filter", "gd-star-rating"); ?></a></strong></td>
    <td align="right"><?php _e("Click on the header title to display the options.", "gd-star-rating"); ?></td>
  </tr>
</table>
</div>
<div id="gdstarr-divfilter-on[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['div_filter'] == '1' ? 'block' : 'none' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%"> 
  <tr>
    <td width="100" valign="top"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('gdstarr-divfilter', '<?php echo $wpnm; ?>')"><?php _e("Filter", "gd-star-rating"); ?></a></strong></td>
    <td width="150" nowrap="nowrap"><?php _e("Include Articles", "gd-star-rating"); ?>:</td>
    <td align="right">
        <label><select class="widefat" name="<?php echo $wpfn; ?>[select]" id="gdstarr-select" style="width: 110px">
            <option value="postpage"<?php echo $wpno['select'] == 'postpage' ? ' selected="selected"' : ''; ?>><?php _e("Posts & Pages", "gd-star-rating"); ?></option>
            <option value="post"<?php echo $wpno['select'] == 'post' ? ' selected="selected"' : ''; ?>><?php _e("Posts Only", "gd-star-rating"); ?></option>
            <option value="page"<?php echo $wpno['select'] == 'page' ? ' selected="selected"' : ''; ?>><?php _e("Pages Only", "gd-star-rating"); ?></option>
        </select></label>
    </td>
  </tr>
  <tr>
    <td width="100" valign="top"></td>
    <td width="150" nowrap="nowrap"><?php _e("Display Votes From", "gd-star-rating"); ?>:</td>
    <td align="right">
        <label><select name="<?php echo $wpfn; ?>[show]" id="gdstarr-show" style="width: 110px">
            <option value="total"<?php echo $wpno['show'] == 'all' ? ' selected="selected"' : ''; ?>><?php _e("Everyone", "gd-star-rating"); ?></option>
            <option value="visitors"<?php echo $wpno['show'] == 'visitors' ? ' selected="selected"' : ''; ?>><?php _e("Visitors Only", "gd-star-rating"); ?></option>
            <option value="users"<?php echo $wpno['show'] == 'users' ? ' selected="selected"' : ''; ?>><?php _e("Users Only", "gd-star-rating"); ?></option>
        </select></label>
    </td>
  </tr>
  <tr>
    <td width="100" valign="top"></td>
    <td width="150" nowrap="nowrap"><?php _e("Number Of Posts", "gd-star-rating"); ?>:</td>
    <td align="right">
        <input class="widefat" style="text-align: right; width: 40px" type="text" name="<?php echo $wpfn; ?>[rows]" id="gdstarr-rows" value="<?php echo $wpno["rows"]; ?>" />
    </td>
  </tr>
  <tr>
    <td width="100" valign="top"></td>
    <td width="150" nowrap="nowrap"><?php _e("Sorting Column", "gd-star-rating"); ?>:</td>
    <td align="right">
        <select name="<?php echo $wpfn; ?>[column]" id="gdstarr-column" style="width: 110px">
            <option value="rating"<?php echo $wpno['column'] == 'rating' ? ' selected="selected"' : ''; ?>><?php _e("Rating", "gd-star-rating"); ?></option>
            <option value="votes"<?php echo $wpno['column'] == 'votes' ? ' selected="selected"' : ''; ?>><?php _e("Total Votes", "gd-star-rating"); ?></option>
            <option value="id"<?php echo $wpno['column'] == 'id' ? ' selected="selected"' : ''; ?>><?php _e("ID", "gd-star-rating"); ?></option>
            <option value="post_title"<?php echo $wpno['column'] == 'post_title' ? ' selected="selected"' : ''; ?>><?php _e("Title", "gd-star-rating"); ?></option>
            <option value="review"<?php echo $wpno['column'] == 'review' ? ' selected="selected"' : ''; ?>><?php _e("Review", "gd-star-rating"); ?></option>
            <option value="count"<?php echo $wpno['column'] == 'review' ? ' selected="selected"' : ''; ?>><?php _e("Count", "gd-star-rating"); ?></option>
            <option value="bayes"<?php echo $wpno['column'] == 'bayes' ? ' selected="selected"' : ''; ?>><?php _e("Bayesian Rating", "gd-star-rating"); ?></option>
        </select>
    </td>
  </tr>
  <tr>
    <td width="100" valign="top"></td>
    <td width="150" nowrap="nowrap"><?php _e("Sorting Order", "gd-star-rating"); ?>:</td>
    <td align="right">
        <select name="<?php echo $wpfn; ?>[order]" id="gdstarr-order" style="width: 110px">
            <option value="desc"<?php echo $wpno['order'] == 'desc' ? ' selected="selected"' : ''; ?>><?php _e("Descending", "gd-star-rating"); ?></option>
            <option value="asc"<?php echo $wpno['order'] == 'asc' ? ' selected="selected"' : ''; ?>><?php _e("Ascending", "gd-star-rating"); ?></option>
        </select>
    </td>
  </tr>
  <tr>
    <td width="100"></td>
    <td nowrap="nowrap" colspan="2" height="25">
        <label for="gdstarr-bayesiancalculation" style="text-align:right;"><input class="checkbox" type="checkbox" <?php echo $wpno['bayesian_calculation'] ? 'checked="checked"' : ''; ?> id="gdstarr-bayesiancalculation" name="<?php echo $wpfn; ?>[bayesian_calculation]" /> <?php _e("Bayesian minumum votes required.", "gd-star-rating"); ?></label>
    </td>
  </tr>
  <tr>
    <td width="100"></td>
    <td nowrap="nowrap" colspan="2" height="25">
        <label for="gdstarr-hidempty" style="text-align:right;"><input class="checkbox" type="checkbox" <?php echo $wpno['hide_empty'] ? 'checked="checked"' : ''; ?> id="gdstarr-hidempty" name="<?php echo $wpfn; ?>[hidempty]" /> <?php _e("Hide articles with no recorded votes.", "gd-star-rating"); ?></label>
    </td>
  </tr>
  <tr>
    <td width="100"></td>
    <td nowrap="nowrap" colspan="2" height="25">
        <label for="gdstarr-hidenoreview" style="text-align:right;"><input class="checkbox" type="checkbox" <?php echo $wpno['hide_noreview'] ? 'checked="checked"' : ''; ?> id="gdstarr-hidenoreview" name="<?php echo $wpfn; ?>[hidenoreview]" /> <?php _e("Hide articles with no review values.", "gd-star-rating"); ?></label>
    </td>
  </tr>
  <tr>
    <td width="100"></td>
    <td nowrap="nowrap" colspan="2">
        <div class="gdsr-table-split-filter"></div>
    </td>
  </tr>
  <tr>
    <td width="100"></td>
    <td width="150" nowrap="nowrap"><?php _e("Category", "gd-star-rating"); ?>:</td>
    <td align="right">
        <label><?php GDSRDatabase::get_combo_categories($wpno['category'], $wpfn.'[category]'); ?></label>
    </td>
  </tr>
  <tr>
    <td width="100"></td>
    <td nowrap="nowrap" colspan="2">
        <div class="gdsr-table-split-filter"></div>
    </td>
  </tr>
  <tr>
    <td width="100"></td>
    <td width="150" nowrap="nowrap"><?php _e("Publish Date", "gd-star-rating"); ?>:</td>
    <td align="right">
        <select name="<?php echo $wpfn; ?>[publish_date]" style="width: 110px" id="gdstarr-publishdate" onchange="gdsrChangeDate(this.options[this.selectedIndex].value, '<?php echo $wpnm; ?>')">
            <option value="lastd"<?php echo $wpno['publish_date'] == 'lastd' ? ' selected="selected"' : ''; ?>><?php _e("Last # days", "gd-star-rating"); ?></option>
            <option value="month"<?php echo $wpno['publish_date'] == 'month' ? ' selected="selected"' : ''; ?>><?php _e("Exact month", "gd-star-rating"); ?></option>
            <option value="range"<?php echo $wpno['publish_date'] == 'range' ? ' selected="selected"' : ''; ?>><?php _e("Date range", "gd-star-rating"); ?></option>
        </select>
    </td>
  </tr>
</table>
<div id="gdsr-pd-lastd[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['publish_date'] == 'lastd' ? 'block' : 'none' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%"> 
  <tr>
    <td width="100"></td>
    <td width="150" nowrap="nowrap"><?php _e("Number Of Days", "gd-star-rating"); ?>:</td>
    <td align="right">
        <input class="widefat" style="text-align: right; width: 102px" type="text" name="<?php echo $wpfn; ?>[publish_days]" id="gdstarr-publishdays" value="<?php echo $wpno["publish_days"]; ?>" />
    </td>
  </tr>
</table>
</div>
<div id="gdsr-pd-month[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['publish_date'] == 'month' ? 'block' : 'none' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%"> 
  <tr>
    <td width="100"></td>
    <td width="150" nowrap="nowrap"><?php _e("Month", "gd-star-rating"); ?>:</td>
    <td align="right">
        <?php GDSRDatabase::get_combo_months($wpno['publish_month'], $wpfn."[publish_month]"); ?>
    </td>
  </tr>
</table>
</div>
<div id="gdsr-pd-range[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['publish_date'] == 'range' ? 'block' : 'none' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%"> 
  <tr>
    <td width="100"></td>
    <td nowrap="nowrap"><?php _e("Range", "gd-star-rating"); ?>:</td>
    <td align="right" width="85"><input class="widefat" style="text-align: right; width: 85px" type="text" name="<?php echo $wpfn; ?>[publish_range_from]" id="gdstarr-publishrangefrom" value="<?php echo $wpno["publish_range_from"]; ?>" /></td>
    <td align="center" width="10">-</td>
    <td align="right" width="85"><input class="widefat" style="text-align: right; width: 85px" type="text" name="<?php echo $wpfn; ?>[publish_range_to]" id="gdstarr-publishrangeto" value="<?php echo $wpno["publish_range_to"]; ?>" /></td>
  </tr>
</table>
</div>
</div>
<div class="gdsr-table-split"></div>

<input type="hidden" id="gdstarr-divtemplate[<?php echo $wpnm; ?>]" name="<?php echo $wpfn; ?>[div_template]" value="<?php echo $wpno['div_template'] ?>" />
<div id="gdstarr-divtemplate-off[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['div_template'] == '1' ? 'none' : 'block' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%" style="margin-bottom: 10px"> 
  <tr>
    <td width="100" valign="top"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('gdstarr-divtemplate', '<?php echo $wpnm; ?>')"><?php _e("Template", "gd-star-rating"); ?></a></strong></td>
    <td align="right"><?php _e("Click on the header title to display the options.", "gd-star-rating"); ?></td>
  </tr>
</table>
</div>
<div id="gdstarr-divtemplate-on[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['div_template'] == '1' ? 'block' : 'none' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td width="100"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('gdstarr-divtemplate', '<?php echo $wpnm; ?>')"><?php _e("Template", "gd-star-rating"); ?></a></strong></td>
    <td nowrap="nowrap"><?php _e("Maximal character length for title", "gd-star-rating"); ?>:</td>
    <td align="right"><input class="widefat" style="text-align: right; width: 40px" type="text" name="<?php echo $wpfn; ?>[title_max]" id="gdstarr-titlemax" value="<?php echo $wpno["tpl_title_length"]; ?>" /></td>
  </tr>
</table>
<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td width="100"></td>
    <td colspan="2"><div class="gdsr-table-split-filter"></div></td>
  </tr>
</table>
<table border="0" cellpadding="2" cellspacing="0" width="100%"> 
  <tr>
    <td width="100" valign="top"></td>
    <td width="80" nowrap="nowrap"><?php _e("Header", "gd-star-rating"); ?>:</td>
    <td align="right"><input class="widefat" style="width: 200px" type="text" name="<?php echo $wpfn; ?>[tpl_header]" id="gdstarr-tplheader" value="<?php echo wp_specialchars($wpno["tpl_header"]); ?>" /></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td width="80" nowrap="nowrap"><?php _e("Item", "gd-star-rating"); ?>:</td>
    <td align="right"><input class="widefat" style="width: 200px" type="text" name="<?php echo $wpfn; ?>[tpl_item]" id="gdstarr-tplitem" value="<?php echo wp_specialchars($wpno["tpl_item"]); ?>" /></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td width="80" nowrap="nowrap"><?php _e("Footer", "gd-star-rating"); ?>:</td>
    <td align="right"><input class="widefat" style="width: 200px" type="text" name="<?php echo $wpfn; ?>[tpl_footer]" id="gdstarr-tplfooter" value="<?php echo wp_specialchars($wpno["tpl_footer"]); ?>" /></td>
  </tr>
</table>
<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td width="100"></td>
    <td colspan="2"><div class="gdsr-table-split-filter"></div></td>
  </tr>
</table>
<input type="hidden" id="gdstarr-divelemets[<?php echo $wpnm; ?>]" name="<?php echo $wpfn; ?>[div_elements]" value="<?php echo $wpno['div_elements'] ?>" />
<div id="gdstarr-divelemets-off[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['div_elements'] == '1' ? 'none' : 'block' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%" style="margin-bottom: 10px"> 
  <tr>
    <td width="100" valign="top"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('gdstarr-divelemets', '<?php echo $wpnm; ?>')"><?php _e("Elements", "gd-star-rating"); ?></a></strong></td>
    <td align="right"><?php _e("Click on the header title to display the options.", "gd-star-rating"); ?></td>
  </tr>
</table>
</div>
<div id="gdstarr-divelemets-on[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['div_elements'] == '1' ? 'block' : 'none' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%" style="margin-bottom: 10px">
  <tr>
    <td width="100" valign="top"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('gdstarr-divelemets', '<?php echo $wpnm; ?>')"><?php _e("Elements", "gd-star-rating"); ?></a></strong></td>
    <td><strong>%RATING%</strong></td><td> : <?php _e("article rating", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%MAX_RATING%</strong></td><td> : <?php _e("maximum rating value", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100" valign="top"></td>
    <td><strong>%BAYES_RATING%</strong></td><td> : <?php _e("bayesian article rating", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%VOTES%</strong></td><td> : <?php _e("total votes for article", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%REVIEW%</strong></td><td> : <?php _e("article review", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%MAX_REVIEW%</strong></td><td> : <?php _e("maximum review value", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%TITLE%</strong></td><td> : <?php _e("item title", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%RATE_TREND%</strong></td><td> : <?php _e("rating trend", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%VOTE_TREND%</strong></td><td> : <?php _e("voting trend", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%PERMALINK%</strong></td><td> : <?php _e("item url", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%ID%</strong></td><td> : <?php _e("item id", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%COUNT%</strong></td><td> : <?php _e("number of posts/pages", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%WORD_VOTES%</strong></td><td> : <?php _e("singular/plural word vote", "gd-star-rating"); ?></td>
  </tr>
</table>
</div>
</div>
<input type="hidden" id="gdstarr-submit" name="<?php echo $wpfn; ?>[submit]" value="1" />
