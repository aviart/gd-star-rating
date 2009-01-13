<?php

$default_preview_class = "wait-preview-holder-multis loader ";
$default_preview_class.= $gdsr_options["wait_loader_multis"]." ";
if ($gdsr_options["wait_show_multis"] == 1)
    $default_preview_class.= "width ";
$default_preview_class.= $gdsr_options["wait_class_multis"];

?>
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Rating", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Stars", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <select style="width: 180px;" name="gdsr_mur_style" id="gdsr_mur_style">
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
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Rating header", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="checkbox" name="gdsr_mur_header" id="gdsr_mur_header"<?php if ($gdsr_options["mur_header"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_header"><?php _e("Display header text.", "gd-star-rating"); ?></label>
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Text to display", "gd-star-rating"); ?>:</td>
                <td>
                    <input type="text" name="gdsr_mur_header_text" id="gdsr_mur_header_text" value="<?php echo wp_specialchars($gdsr_options["mur_header_text"]); ?>" style="width: 170px" />
                </td>
            </tr>
        </table>
        <?php if ($gdsr_options["admin_advanced"] == 1) { ?>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Rating block CSS class", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="text" name="gdsr_mur_classblock" id="gdsr_mur_classblock" value="<?php echo wp_specialchars($gdsr_options["mur_class_block"]); ?>" style="width: 170px" />
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Rating text CSS class", "gd-star-rating"); ?>:</td>
                <td>
                    <input type="text" name="gdsr_mur_classtext" id="gdsr_mur_classtext" value="<?php echo wp_specialchars($gdsr_options["mur_class_text"]); ?>" style="width: 170px" />
                </td>
            </tr>
            <tr>
                <td width="150"><?php _e("Rating table CSS class", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="text" name="gdsr_mur_classtable" id="gdsr_mur_classtable" value="<?php echo wp_specialchars($gdsr_options["mur_class_table"]); ?>" style="width: 170px" />
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Rating button CSS class", "gd-star-rating"); ?>:</td>
                <td>
                    <input type="text" name="gdsr_mur_classbutton" id="gdsr_mur_classbutton" value="<?php echo wp_specialchars($gdsr_options["mur_class_button"]); ?>" style="width: 170px" />
                </td>
            </tr>
        </table>
        <?php } ?>
    </td>
</tr>
<tr><th scope="row"><?php _e("Vote Waiting Message", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Animation indicator", "gd-star-rating"); ?>:</td>
                <td width="200"><?php GDSRHelper::render_loaders("gdsr_wait_loader_multis", $gdsr_options["wait_loader_multis"], 'jqloadermultis'); ?></td>
                <td width="10"></td>
                <td rowspan="3" width="150" valign="top"><?php _e("Preview", "gd-star-rating"); ?>:</td>
                <td rowspan="3" valign="top">
                    <div class="wait-preview-article">
                        <div id="gdsrwaitpreviewmultis" class="<?php echo $default_preview_class; ?>">
                            <?php if ($gdsr_options["wait_show_multis"] == 0) echo $gdsr_options["wait_text_multis"]; ?>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td width="150"><?php _e("Text to display", "gd-star-rating"); ?>:</td>
                <td width="200"><input class="jqloadermultis" type="text" name="gdsr_wait_text_multis" id="gdsr_wait_text_multis" value="<?php echo $gdsr_options["wait_text_multis"]; ?>" style="width: 170px;" /></td>
                <td width="10"></td>
            </tr>
            <tr>
                <td width="150"><?php _e("Additional CSS class", "gd-star-rating"); ?>:</td>
                <td width="200"><input class="jqloadermultis" type="text" name="gdsr_wait_class_multis" id="gdsr_wait_class_multis" value="<?php echo $gdsr_options["wait_class_multis"]; ?>" style="width: 170px;" /></td>
                <td width="10"></td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <input class="jqloadermultis" type="checkbox" name="gdsr_wait_show_multis" id="gdsr_wait_show_multis"<?php if ($gdsr_options["wait_show_multis"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_wait_show_multis"><?php _e("Hide text and show only animation image.", "gd-star-rating"); ?></label>
    </td>
</tr>
</tbody></table>
