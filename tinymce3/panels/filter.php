<fieldset>
<legend><?php _e("Basic", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="3" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Include Articles", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <select id="srSelect" name="srSelect" style="width: 130px">
                <option value="postpage"><?php _e("Posts And Pages", "gd-star-rating"); ?></option>
                <option value="post"><?php _e("Posts Only", "gd-star-rating"); ?></option>
                <option value="page"><?php _e("Pages Only", "gd-star-rating"); ?></option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="gdsrleft"><?php _e("Display Votes From", "gd-star-rating"); ?>:</td>
        <td class="gdsrright"><select name="srShow" id="srShow" style="width: 130px">
            <option value="total"><?php _e("Everyone", "gd-star-rating"); ?></option>
            <option value="visitors"><?php _e("Visitors Only", "gd-star-rating"); ?></option>
            <option value="users"><?php _e("Users Only", "gd-star-rating"); ?></option>
        </select></td>
      </tr>
      <tr>
        <td class="gdsrleft"><?php _e("Category", "gd-star-rating"); ?>:</td>
        <td class="gdsrright"><?php GDSRDatabase::get_combo_categories('', 'srCategory'); ?></td>
      </tr>
      <tr>
        <td class="gdsrleft"><?php _e("Minimum Votes", "gd-star-rating"); ?>:</td>
        <td class="gdsrright"><input class="widefat widnumb" style="text-align: right;" type="text" size="8" id="srMinVotes" name="srMinVotes" value="5" /></td>
      </tr>
    </table>
</fieldset>

<fieldset>
<legend><?php _e("Sorting", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="3" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Column", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <select id="srColumn" name="srColumn" style="width: 130px">
                <option value="rating"><?php _e("Rating", "gd-star-rating"); ?></option>
                <option value="votes"><?php _e("Total Votes", "gd-star-rating"); ?></option>
                <option value="id"><?php _e("ID", "gd-star-rating"); ?></option>
                <option value="post_title"><?php _e("Title", "gd-star-rating"); ?></option>
                <option value="review"><?php _e("Review", "gd-star-rating"); ?></option>
                <option value="count"><?php _e("Count", "gd-star-rating"); ?></option>
                <option value="bayes"><?php _e("Bayesian Rating", "gd-star-rating"); ?></option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="gdsrleft"><?php _e("Order", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <select id="srOrder" name="srOrder" style="width: 130px">
                <option value="desc"><?php _e("Descending", "gd-star-rating"); ?></option>
                <option value="asc"><?php _e("Ascending", "gd-star-rating"); ?></option>
            </select>
        </td>
      </tr>
    </table>
</fieldset>

<fieldset>
<legend><?php _e("Last Voted", "gd-star-rating"); ?></legend>
<?php _e("Use only articles voted for in last # days.", "gd-star-rating") ?>
    <table border="0" cellpadding="3" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Enter 0 for all", "gd-star-rating"); ?>:</td>
        <td class="gdsrright"><input class="widefat widnumb" style="text-align: right;" size="8" type="text" name="srLastDate" id="srLastDate" value="0" /></td>
      </tr>
    </table>
</fieldset>

<fieldset>
<legend><?php _e("Date", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="3" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Publish Date", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <select name="publishDate" style="width: 130px" id="publishDate" class="gdsrleft" onchange="gdsrChangeDate(this.options[this.selectedIndex].value, 'tinymce')">
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
            <input class="widefat widnumb" style="text-align: right;" size="8" type="text" name="publishDays" id="publishDays" value="0" />
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
        <td align="right" width="85"><input class="widefat" style="text-align: right; width: 85px" type="text" name="publishRangeFrom" id="publishRangeFrom" value="YYYYMMDD" /></td>
        <td align="center" width="10">-</td>
        <td align="right" width="85"><input class="widefat" style="text-align: right; width: 85px" type="text" name="publishRangeTo" id="publishRangeTo" value="YYYYMMDD" /></td>
      </tr>
    </table>
    </div>
</fieldset>
