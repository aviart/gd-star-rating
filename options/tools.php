<div class="gdsr">
<div class="wrap"><h2>GD Star Rating: <?php _e("Tools", "gd-star-rating"); ?></h2>
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Stars and Trends Graphics", "gd-star-rating"); ?></th>
    <td>
        <form method="post">
        <?php _e("Rescan graphics folders for new stars and trends images.", "gd-star-rating"); ?><br />
        <input type="submit" class="inputbutton" value="<?php _e("Rescan", "gd-star-rating"); ?>" name="gdsr_preview_scan" id="gdsr_preview_scan" />
        <div class="gdsr-table-split"></div>
        Last scan was executed on: <strong><?php echo $gdsr_gfx->last_scan; ?></strong>
        </form>
    </td>
</tr>
<tr><th scope="row"><?php _e("Database Cleanup", "gd-star-rating"); ?></th>
    <td>
        <form method="post">

        <input type="submit" class="inputbutton" value="<?php _e("Clean", "gd-star-rating"); ?>" name="gdsr_preview_scan" id="gdsr_preview_scan" />
        <div class="gdsr-table-split"></div>
        Last cleanup was executed on: <strong><?php echo $gdsr_options['database_cleanup']; ?></strong>
        </form>
    </td>
</tr>
</tbody></table>
</div>
</div>