<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Widgets", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_widget_articles" id="gdsr_widget_articles"<?php if ($gdsr_options["widget_articles"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_widget_articles"><?php _e("GD Star Rating: Post/Page rating widget.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_widget_top" id="gdsr_widget_top"<?php if ($gdsr_options["widget_top"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_widget_top"><?php _e("GD Blog Rating: Blog average rating.", "gd-star-rating"); ?></label>
    </td>
</tr>
</tbody></table>
