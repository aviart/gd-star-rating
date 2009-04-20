<div id="multis_panel" class="panel">
<fieldset>
<legend><?php _e("Multi Ratings", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Set", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <label><select id="srMultiRatingSet" name="srMultiRatingSet" style="width: 200px">
                <?php GDSRHelper::render_styles_select($gdst_multis, 1); ?>
            </select></label>
        </td>
      </tr>
    </table>
</fieldset>
<fieldset>
<legend><?php _e("Settings", "gd-star-rating"); ?></legend>
    <input type="checkbox" size="5" id="srMultiRead" name="srMultiRead" /><label for="srMultiRead"> <?php _e("Display rating block as read only.", "gd-star-rating"); ?></label><br />
</fieldset>
</div>

<div id="multisreview_panel" class="panel">
<fieldset>
<legend><?php _e("Multi Reviews", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Set", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <label><select id="srMultiRatingSet" name="srsrMultiRatingSet" style="width: 200px">
                <option value="0"><?php _e("Default", "gd-star-rating"); ?></option>
                <?php GDSRHelper::render_styles_select($gdst_multis, 1); ?>
            </select></label>
        </td>
      </tr>
    </table>
</fieldset>
</div>
