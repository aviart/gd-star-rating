<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Dashboard", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_integrate_dashboard" id="gdsr_integrate_dashboard"<?php if ($gdsr_options["integrate_dashboard"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_integrate_dashboard"><?php _e("Add summary rating widget to the administration dashboard.", "gd-star-rating"); ?></label>
    </td>
</tr>
<tr><th scope="row"><?php _e("Widgets", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_widget_articles" id="gdsr_widget_articles"<?php if ($gdsr_options["widget_articles"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_widget_articles"><?php _e("GD Star Rating: Post/Page rating widget.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_widget_top" id="gdsr_widget_top"<?php if ($gdsr_options["widget_top"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_widget_top"><?php _e("GD Blog Rating: Blog average rating.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_widget_comments" id="gdsr_widget_comments"<?php if ($gdsr_options["widget_comments"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_widget_comments"><?php _e("GD Comments Rating: Per post comments rating.", "gd-star-rating"); ?></label>
    </td>
</tr>
<tr><th scope="row"><?php _e("Post Edit", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_integrate_post_edit" id="gdsr_integrate_post_edit"<?php if ($gdsr_options["integrate_post_edit"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_integrate_post_edit"><?php _e("Add rating box in the post/page edit sidebar area.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_integrate_tinymce" id="gdsr_integrate_tinymce"<?php if ($gdsr_options["integrate_tinymce"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_integrate_tinymce"><?php _e("Add rating shortcode plugin into tinyMCE visual editor.", "gd-star-rating"); ?></label>
    </td>
</tr>
<tr><th scope="row"><?php _e("Comment Edit", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_integrate_comment_edit" id="gdsr_integrate_comment_edit"<?php if ($gdsr_options["integrate_comment_edit"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_integrate_post_edit"><?php _e("Add rating box on the comment edit page.", "gd-star-rating"); ?></label>
    </td>
</tr>
<tr><th scope="row"><?php _e("RSS Feeds", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_integrate_rss_powered" id="gdsr_integrate_rss_powered"<?php if ($gdsr_options["integrate_rss_powered"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_integrate_rss_powered"><?php _e("Add small 80x15 badge to posts in RSS feed.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_rss" id="gdsr_rss"<?php if ($gdsr_options["rss_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_rss"><?php _e("Add ratings to posts in RSS feeds.", "gd-star-rating"); ?></label>
    </td>
</tr>
</tbody></table>