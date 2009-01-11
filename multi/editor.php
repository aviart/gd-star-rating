<?php

if ($gdsr == "munew") {
    $set = new GDMultiSingle();
}
else {
    $edit_id = $_GET["id"];
    $set = gd_get_multi_set($edit_id);
}

?>

<div class="wrap">
<form method="post">
<input type="hidden" id="gdsr_action" name="gdsr_action" value="save" />
<input type="hidden" id="gdsr_ms_id" name="gdsr_ms_id" value="<?php echo $set->multi_id; ?>" />
<div class="gdsr">
<h2>GD Star Rating: <?php _e("Multi Sets Editor", "gd-star-rating"); ?></h2>

<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Name", "gd-star-rating"); ?></th>
    <td>
        <input type="text" name="gdsr_ms_name" id="gdsr_ms_name" value="<?php echo $set->name; ?>" style="width: 300px" />
    </td>
</tr>
<tr><th scope="row"><?php _e("Description", "gd-star-rating"); ?></th>
    <td>
        <input type="text" name="gdsr_ms_description" id="gdsr_ms_description" value="<?php echo $set->description; ?>" style="width: 700px" />
    </td>
</tr>
<tr><th scope="row"><?php _e("Number Of Stars", "gd-star-rating"); ?></th>
    <td>
        <select<?php if ($gdsr == "muedit") echo ' disabled="disabled"'; ?> style="width: 70px;" name="gdsr_ms_stars" id="gdsr_ms_stars">
            <?php GDSRHelper::render_stars_select($set->stars); ?>
        </select>
    </td>
</tr>
<tr><th scope="row"><?php _e("Elements", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <?php for ($i = 0; $i < count($set->object); $i++) { $counter = $i + 1; if ($counter < 10) $counter = "0".$counter;  ?>
            <tr>
                <td width="50">[ <?php echo $counter; ?> ]</td>
                <td width="100"><?php _e("Name", "gd-star-rating"); ?>:</td>
                <td><input type="text" name="gdsr_ms_element[<?php echo $i; ?>]" id="gdsr_ms_element_<?php echo $i; ?>" value="<?php echo $set->object[$i]; ?>" style="width: 200px" /></td>
                <td width="20"></td>
                <td width="100"><?php _e("Weight", "gd-star-rating"); ?>:</td>
                <td><input type="text" name="gdsr_ms_weight[<?php echo $i; ?>]" id="gdsr_ms_weight_<?php echo $i; ?>" value="<?php echo $set->weight[$i]; ?>" style="width: 50px; text-align: right;" /></td>
            </tr>
            <?php } ?>
        </table>
    </td>
</tr>
<tr><th scope="row"><?php _e("Important Notice", "gd-star-rating"); ?></th>
    <td>
        <?php _e("Once the set is created, you can't change number of elements in the set or number of stars! You can edit names and weight of the elements, name and description of the set.", "gd-star-rating"); ?>
    </td>
</tr>
</tbody></table>

<p class="submit"><input type="submit" value="<?php _e("Save Multi Set", "gd-star-rating"); ?>" name="gdsr_saving"/></p>
</div>
</form>
</div>
