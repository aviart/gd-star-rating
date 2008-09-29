<style>
.gdsr-table-split {
    border-top: 2px solid lightBlue; 
    width: 100%; 
    margin-top: 10px; 
    padding-top: 10px;
}
.gdsr-table-split-filter {
    border-top: 1px solid lightBlue; 
    width: 100%; 
    margin-top: 4px; 
    padding-top: 4px;
}
</style>

<script>
function gdsrChangeDate(el, index) {
    document.getElementById("gdsr-pd-lastd["+index+"]").style.display = el == "lastd" ? "block" : "none";
    document.getElementById("gdsr-pd-month["+index+"]").style.display = el == "month" ? "block" : "none";
    document.getElementById("gdsr-pd-range["+index+"]").style.display = el == "range" ? "block" : "none";
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
    <td nowrap="nowrap" width="140"><?php _e("Number Of Posts", "gd-star-rating"); ?>:</td>
    <td align="right"><input class="widefat" style="text-align: right; width: 40px" type="text" name="<?php echo $wpfn; ?>[rows]" id="gdstarr-rows" value="<?php echo $wpno["rows"]; ?>" /></td>
    <td width="45"></td>
    <td nowrap="nowrap"><?php _e("Display", "gd-star-rating"); ?>:</td>
    <td align="right">
        <label><select class="widefat" name="<?php echo $wpfn; ?>[select]" id="gdstarr-select" style="width: 110px">
            <option value="postpage"<?php echo $wpno['select'] == 'postpage' ? ' selected="selected"' : ''; ?>><?php _e("Posts & Pages", "gd-star-rating"); ?></option>
            <option value="post"<?php echo $wpno['select'] == 'post' ? ' selected="selected"' : ''; ?>><?php _e("Posts Only", "gd-star-rating"); ?></option>
            <option value="page"<?php echo $wpno['select'] == 'page' ? ' selected="selected"' : ''; ?>><?php _e("Pages Only", "gd-star-rating"); ?></option>
        </select></label>
    </td>
  </tr>
</table>
<table border="0" cellpadding="2" cellspacing="0" width="100%"> 
  <tr>
    <td nowrap="nowrap" width="140"><?php _e("Sorting", "gd-star-rating"); ?>:</td>
    <td align="right">
        <label><select name="<?php echo $wpfn; ?>[column]" id="gdstarr-column" style="width: 110px">
            <option value="rating"<?php echo $wpno['column'] == 'rating' ? ' selected="selected"' : ''; ?>><?php _e("Rating", "gd-star-rating"); ?></option>
            <option value="votes"<?php echo $wpno['column'] == 'votes' ? ' selected="selected"' : ''; ?>><?php _e("Total Votes", "gd-star-rating"); ?></option>
            <option value="id"<?php echo $wpno['column'] == 'id' ? ' selected="selected"' : ''; ?>><?php _e("Post ID", "gd-star-rating"); ?></option>
            <option value="post_title"<?php echo $wpno['column'] == 'post_title' ? ' selected="selected"' : ''; ?>><?php _e("Post Title", "gd-star-rating"); ?></option>
            <option value="review"<?php echo $wpno['column'] == 'review' ? ' selected="selected"' : ''; ?>><?php _e("Review", "gd-star-rating"); ?></option>
        </select></label>
    </td>
    <td width="45"></td>
    <td align="right">
        <label><select name="<?php echo $wpfn; ?>[order]" id="gdstarr-order" style="width: 110px">
            <option value="desc"<?php echo $wpno['order'] == 'desc' ? ' selected="selected"' : ''; ?>><?php _e("Descending", "gd-star-rating"); ?></option>
            <option value="asc"<?php echo $wpno['order'] == 'asc' ? ' selected="selected"' : ''; ?>><?php _e("Ascending", "gd-star-rating"); ?></option>
        </select></label>
    </td>
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
</table>
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
    <td width="100"></td>
    <td nowrap="nowrap" colspan="2">
        <label for="gdpnav-fhIdent" style="text-align:right;"><input class="checkbox" type="checkbox" <?php echo $wpno['hide_empty'] ? 'checked="checked"' : ''; ?> id="gdstarr-hidempty" name="<?php echo $wpfn; ?>[hidempty]" /> <?php _e("Hide articles with no recorded votes.", "gd-star-rating"); ?></label>
        <br />
        <label for="gdpnav-fhIdent" style="text-align:right;"><input class="checkbox" type="checkbox" <?php echo $wpno['hide_noreview'] ? 'checked="checked"' : ''; ?> id="gdstarr-hidenoreview" name="<?php echo $wpfn; ?>[hidenoreview]" /> <?php _e("Hide articles with no review values.", "gd-star-rating"); ?></label>
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
    <td width="100" valign="top"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('gdstarr-divtemplate', '<?php echo $wpnm; ?>')"><?php _e("Template", "gd-star-rating"); ?></a></strong></td>
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
    <td><strong>%RATING%</strong></td><td> : <?php _e("article rating", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%MAX_RATING%</strong></td><td> : <?php _e("maximum rating value", "gd-star-rating"); ?></td>
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
    <td><strong>%TITLE%</strong></td><td> : <?php _e("post/page title", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%PERMALINK%</strong></td><td> : <?php _e("url to post/page", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td colspan="2"><div class="gdsr-table-split-filter"></div></td>
  </tr>
</table>
<table border="0" cellpadding="2" cellspacing="0" width="100%" style="margin-bottom: 10px">
  <tr>
    <td width="100"></td>
    <td nowrap="nowrap"><?php _e("Maximal character length for title", "gd-star-rating"); ?>:</td>
    <td align="right"><input class="widefat" style="text-align: right; width: 40px" type="text" name="<?php echo $wpfn; ?>[title_max]" id="gdstarr-titlemax" value="<?php echo $wpno["tpl_title_length"]; ?>" /></td>
  </tr>
</table>
</div>
<input type="hidden" id="gdstarr-submit" name="<?php echo $wpfn; ?>[submit]" value="1" />
