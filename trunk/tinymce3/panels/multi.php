<div id="multis_panel" class="panel">
<fieldset>
<legend><?php _e("Template", "gd-star-rating"); ?></legend>
    <?php GDSRHelper::render_templates_section("MRB", "srTemplateMRB", 0, 300); ?>
</fieldset>

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

<fieldset>
<legend><?php _e("Average Stars", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Set", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <label><select id="srStarsStyleMURAv" name="srStarsStyleMURAv">
                <?php GDSRHelper::render_styles_select($gdsr_styles, 'oxygen'); ?>
            </select></label>
        </td>
      </tr>
      <tr>
        <td class="gdsrleft"><?php _e("Size", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <label><select id="srStarsSizeMURAv" name="srStarsSizeMURAv" style="width: 130px">
                <option value="12"><?php _e("Mini", "gd-star-rating"); ?></option>
                <option value="20" selected="selected"><?php _e("Small", "gd-star-rating"); ?></option>
                <option value="30"><?php _e("Medium", "gd-star-rating"); ?></option>
                <option value="46"><?php _e("Big", "gd-star-rating"); ?></option>
            </select></label>
        </td>
      </tr>
    </table>
</fieldset>
</div>

<div id="multisreview_panel" class="panel">
<fieldset>
<legend><?php _e("Template", "gd-star-rating"); ?></legend>
    <?php GDSRHelper::render_templates_section("RMB", "srTemplateRMB", 0, 300); ?>
</fieldset>

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

<fieldset>
<legend><?php _e("Elements Stars", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Set", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <label><select id="srStarsStyleMRREl" name="srStarsStyleMRREl">
                <?php GDSRHelper::render_styles_select($gdsr_styles, 'oxygen'); ?>
            </select></label>
        </td>
      </tr>
      <tr>
        <td class="gdsrleft"><?php _e("Size", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <label><select id="srStarsSizeMRREl" name="srStarsSizeMRREl" style="width: 130px">
                <option value="12"><?php _e("Mini", "gd-star-rating"); ?></option>
                <option value="20" selected="selected"><?php _e("Small", "gd-star-rating"); ?></option>
                <option value="30"><?php _e("Medium", "gd-star-rating"); ?></option>
                <option value="46"><?php _e("Big", "gd-star-rating"); ?></option>
            </select></label>
        </td>
      </tr>
    </table>
</fieldset>

<fieldset>
<legend><?php _e("Average Stars", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Set", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <label><select id="srStarsStyleMRRAv" name="srStarsStyleMRRAv">
                <?php GDSRHelper::render_styles_select($gdsr_styles, 'oxygen'); ?>
            </select></label>
        </td>
      </tr>
      <tr>
        <td class="gdsrleft"><?php _e("Size", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <label><select id="srStarsSizeMRRAv" name="srStarsSizeMRRAv" style="width: 130px">
                <option value="12"><?php _e("Mini", "gd-star-rating"); ?></option>
                <option value="20" selected="selected"><?php _e("Small", "gd-star-rating"); ?></option>
                <option value="30"><?php _e("Medium", "gd-star-rating"); ?></option>
                <option value="46"><?php _e("Big", "gd-star-rating"); ?></option>
            </select></label>
        </td>
      </tr>
    </table>
</fieldset>
</div>
