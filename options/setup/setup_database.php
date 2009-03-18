<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Reinstall", "gd-star-rating"); ?></th>
    <td>
        <form method="post" onsubmit="return areYouSure()">
        <?php _e("All database tables will be deleted, and installed again.", "gd-star-rating"); ?><br />
        <input type="submit" value="<?php _e("Reinstall", "gd-star-rating"); ?>" name="gdsr_reinstall" class="inputbutton" />
        <div class="gdsr-table-split"></div>
        <?php _e("This operation is not reversable. Backup your data if you want to be able to restore it later.", "gd-star-rating"); ?>
        </form>
    </td>
</tr>
</tbody></table>
