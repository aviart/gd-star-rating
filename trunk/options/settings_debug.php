<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Inline Debug Info", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_debug_inline" id="gdsr_debug_inline"<?php if ($gdsr_options["debug_inline"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_debug_inline"><?php _e("Add small block with essential debug info into each rating block.", "gd-star-rating"); ?></label>
        <div class="gdsr-table-split"></div>
        <strong><?php _e("Important", "gd-star-rating"); ?>: </strong><?php _e(sprintf("I strongly recommend leaving this option active. It doesn't disrupt the page, it's hidden and very small."), "gd-star-rating"); ?>
    </td>
</tr>
<tr><th scope="row"><?php _e("Log Into File", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_debug_active" id="gdsr_debug_active"<?php if ($gdsr_options["debug_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_debug_active"><?php _e("Log into file various debug information.", "gd-star-rating"); ?></label>
        <div class="gdsr-table-split"></div>
        <strong><?php _e("Important", "gd-star-rating"); ?>: </strong><?php _e(sprintf("Plugin must have write access to a text file. Path to this file needs to be set in %s file.", '<em style="color:red">gd-star-config.php</em>'), "gd-star-rating"); ?>
    </td>
</tr>
</tbody></table>
