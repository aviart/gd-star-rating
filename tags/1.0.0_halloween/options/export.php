<script>
function gdsrGetExportUser() {
    var us = jQuery("input[@name='gdsr_export_user']:checked").val();
    var de = jQuery("input[@name='gdsr_export_data']:checked").val();
    var ip = jQuery("input[@name='gdsr_export_ip']:checked").val();
    var ua = jQuery("input[@name='gdsr_export_agent']:checked").val();
    var url = "<?php echo STARRATING_URL; ?>/gd-star-export.php?ex=user&us=" + us + "&de=" + de;
    if (jQuery("input[@name='gdsr_export_ip']:checked").val() == "on") url = url + "&ip=on";
    if (jQuery("input[@name='gdsr_export_agent']:checked").val() == "on") url = url + "&ua=on";
    if (jQuery("input[@name='gdsr_export_post_title']:checked").val() == "on") url = url + "&pt=on";
    if (jQuery("input[@name='gdsr_export_post_author']:checked").val() == "on") url = url + "&pa=on";
    if (jQuery("input[@name='gdsr_export_post-date']:checked").val() == "on") url = url + "&pd=on";
    if (jQuery("input[@name='gdsr_export_comment_author']:checked").val() == "on") url = url + "&ca=on";
    if (jQuery("input[@name='gdsr_export_comment-date']:checked").val() == "on") url = url + "&cd=on";
    window.location = url;
}
</script>
<div class="wrap"><h2>GD Star Rating: <?php _e("Export Data", "gd-star-rating"); ?></h2>
<div class="gdsr">

<div id="gdsr_tabs" class="gdsrtabs">
<ul>
    <li><a href="#fragment-1"><span><?php _e("Users Data", "gd-star-rating"); ?></span></a></li>
    <!--<li><a href="#fragment-2"><span><?php _e("Posts Data", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-3"><span><?php _e("Comments Data", "gd-star-rating"); ?></span></a></li>-->
</ul>
<div style="clear: both"></div>

<div id="fragment-1">
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("User Info", "gd-star-rating"); ?></th>
    <td>
        <input type="radio" name="gdsr_export_user" value="min" checked="checked" /><label> <?php _e("Minimal: only user id is exported", "gd-star-rating"); ?></label><br />
        <input type="radio" name="gdsr_export_user" value="nor" /><label> <?php _e("Normal: user id, name and email are exported", "gd-star-rating"); ?></label><br />
    </td>
</tr>
<tr><th scope="row"><?php _e("Data", "gd-star-rating"); ?></th>
    <td>
        <input type="radio" name="gdsr_export_data" value="article" checked="checked" /><label> <?php _e("Post / Page votes", "gd-star-rating"); ?></label><br />
        <input type="radio" name="gdsr_export_data" value="comment" /><label> <?php _e("Comments votes", "gd-star-rating"); ?></label><br />
    </td>
</tr>
<tr><th scope="row"><?php _e("Additional Columns", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_export_ip" /><label> <?php _e("IP", "gd-star-rating"); ?></label><br />
        <input type="checkbox" name="gdsr_export_agent" /><label> <?php _e("User Agent", "gd-star-rating"); ?></label>
        <div class="gdsr-table-split"></div>
        <input type="checkbox" name="gdsr_export_post_title" /><label> <?php _e("Post/Page title", "gd-star-rating"); ?></label><br />
        <input type="checkbox" name="gdsr_export_post_date" /><label> <?php _e("Post/Page date", "gd-star-rating"); ?></label>
        <div class="gdsr-table-split"></div>
        <input type="checkbox" name="gdsr_export_comment_author" /><label> <?php _e("Comment author", "gd-star-rating"); ?></label><br />
        <input type="checkbox" name="gdsr_export_comment_date" /><label> <?php _e("Comment date", "gd-star-rating"); ?></label>
    </td>
</tr>
</tbody></table>
<p class="submit"><input onclick="gdsrGetExportUser()" type="button" value="<?php _e("Export Data", "gd-star-rating"); ?>" name="gdsr_export_user"/></p>
</div>

<!--<div id="fragment-2">
</div>

<div id="fragment-3">-->
</div>

</div>
</div>