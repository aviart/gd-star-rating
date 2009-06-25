<fieldset>
<legend><?php _e("Template", "gd-star-rating"); ?></legend>
    <?php gdTemplateHelper::render_templates_section("SRR", "srTemplateSRR", 0, 300); ?>
</fieldset>

<fieldset>
<legend><?php _e("Post Image", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Get Image From", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <select id="srImageFrom" name="srImageFrom" onchange="gdsrChangeImage(this.options[this.selectedIndex].value, 'tinymce')">
                <option value="none"><?php _e("No image", "gd-star-rating"); ?></option>
                <option value="custom"><?php _e("Custom field", "gd-star-rating"); ?></option>
                <option value="content"><?php _e("Post content", "gd-star-rating"); ?></option>
            </select>
        </td>
      </tr>
    </table>
    <div id="gdsr-pi-none[tinymce]" style="display: block">
    <?php _e("If you use %IMAGE% tag in template and this option is selected, image will not be rendered.", "gd-star-rating"); ?>
    </div>
    <div id="gdsr-pi-custom[tinymce]" style="display: none">
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Custom Field", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <?php GDSRHelper::render_custom_fields("srImageCustom", "", 200); ?>
        </td>
      </tr>
    </table>
    </div>
    <div id="gdsr-pi-content[tinymce]" style="display: none">
    <?php _e("First image from post content will be used for %IMAGE% tag.", "gd-star-rating"); ?>
    </div>
</fieldset>

<fieldset>
<legend><?php _e("Rating Stars", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Set", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <select id="srStarsStyle" name="srStarsStyle">
                <?php GDSRHelper::render_styles_select($gdsr_styles, 'oxygen'); ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="gdsrleft"><?php _e("Size", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <label><?php GDSRHelper::render_star_sizes_tinymce("srStarsSize"); ?></label>
        </td>
      </tr>
    </table>
</fieldset>

<fieldset>
<legend><?php _e("Review Stars", "gd-star-rating"); ?></legend>
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Set", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <select id="srReviewStarsStyle" name="srReviewStarsStyle">
                <?php GDSRHelper::render_styles_select($gdsr_styles, 'oxygen'); ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="gdsrleft"><?php _e("Size", "gd-star-rating"); ?>:</td>
        <td class="gdsrright">
            <?php GDSRHelper::render_star_sizes_tinymce("srReviewStarsSize"); ?>
        </td>
      </tr>
    </table>
</fieldset>
