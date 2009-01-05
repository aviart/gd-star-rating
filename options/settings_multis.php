<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Rating", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Stars", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <select style="width: 180px;" name="gdsr_style" id="gdsr_mur_style">
                <?php GDSRHelper::render_styles_select($gdsr_gfx->stars, $gdsr_options["mur_style"]); ?>
                </select>
                </td>
                <td width="10"></td>
                <td width="150" align="left">
                <?php GDSRHelper::render_star_sizes("gdsr_mur_size", $gdsr_options["mur_size"]); ?>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
    </td>
</tr>
</tbody></table>
