<div id="articlesreview_panel" class="panel">
<fieldset>
<legend><?php _e("Articles Review Rating", "gd-star-rating"); ?></legend>
<p><?php _e("StarReview will render stars representing review value assigned to the post or page.", "gd-star-rating"); ?></p>
<p><?php _e("There are no settings for this shortcode yet. This will be updated soon.", "gd-star-rating"); ?></p>
</fieldset>
</div>

<div id="articlesrater_panel" class="panel">
<fieldset>
<legend><?php _e("Articles Rating Block", "gd-star-rating"); ?></legend>
<p><?php _e("StarRater will render actual rating block if you choose not to have it automatically inserted. This way you can position it wherever you want in the contnents.", "gd-star-rating"); ?></p>
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
      <tr>
        <td class="gdsrleft"><?php _e("Template", "gd-star-rating"); ?>:</td>
        <td class="gdsrright"><?php GDSRHelper::render_templates_section("SRB", "srRatingBlockTemplate", 0, 200); ?></td>
      </tr>
    </table>
</fieldset>
</div>
