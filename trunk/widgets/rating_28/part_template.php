<input type="hidden" id="gdstarr-divtemplate[<?php echo $wpnm; ?>]" name="<?php echo $wpfn; ?>[div_template]" value="<?php echo $wpno['div_template'] ?>" />
<div id="gdstarr-divtemplate-off[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['div_template'] == '1' ? 'none' : 'block' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%" style="margin-bottom: 10px">
  <tr>
    <td width="100" valign="top"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('gdstarr-divtemplate', '<?php echo $wpnm; ?>')"><?php _e("Template", "gd-star-rating"); ?></a></strong></td>
    <td align="right"><?php _e("Click on the header title to display the options.", "gd-star-rating"); ?></td>
  </tr>
</table>
</div>
<div id="gdstarr-divtemplate-on[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['div_template'] == '1' ? 'block' : 'none' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td width="100"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('gdstarr-divtemplate', '<?php echo $wpnm; ?>')"><?php _e("Template", "gd-star-rating"); ?></a></strong></td>
    <td nowrap="nowrap"><?php _e("Maximal character length for title", "gd-star-rating"); ?>:</td>
    <td align="right"><input class="widefat" style="text-align: right; width: 40px" type="text" name="<?php echo $wpfn; ?>[title_max]" id="gdstarr-titlemax" value="<?php echo $wpno["tpl_title_length"]; ?>" /></td>
  </tr>
</table>
<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td width="100"></td>
    <td colspan="2"><div class="gdsr-table-split-filter"></div></td>
  </tr>
</table>
<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td width="100" valign="top"></td>
    <td width="80" nowrap="nowrap"><?php _e("Header", "gd-star-rating"); ?>:</td>
    <td align="right"><input class="widefat" style="width: 200px" type="text" name="<?php echo $wpfn; ?>[tpl_header]" id="gdstarr-tplheader" value="<?php echo wp_specialchars($wpno["tpl_header"]); ?>" /></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td width="80" nowrap="nowrap"><?php _e("Item", "gd-star-rating"); ?>:</td>
    <td align="right"><input class="widefat" style="width: 200px" type="text" name="<?php echo $wpfn; ?>[tpl_item]" id="gdstarr-tplitem" value="<?php echo wp_specialchars($wpno["tpl_item"]); ?>" /></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td width="80" nowrap="nowrap"><?php _e("Footer", "gd-star-rating"); ?>:</td>
    <td align="right"><input class="widefat" style="width: 200px" type="text" name="<?php echo $wpfn; ?>[tpl_footer]" id="gdstarr-tplfooter" value="<?php echo wp_specialchars($wpno["tpl_footer"]); ?>" /></td>
  </tr>
</table>
<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td width="100"></td>
    <td colspan="2"><div class="gdsr-table-split-filter"></div></td>
  </tr>
</table>
<input type="hidden" id="gdstarr-divelemets[<?php echo $wpnm; ?>]" name="<?php echo $wpfn; ?>[div_elements]" value="<?php echo $wpno['div_elements'] ?>" />
<div id="gdstarr-divelemets-off[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['div_elements'] == '1' ? 'none' : 'block' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%" style="margin-bottom: 10px">
  <tr>
    <td width="100" valign="top"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('gdstarr-divelemets', '<?php echo $wpnm; ?>')"><?php _e("Elements", "gd-star-rating"); ?></a></strong></td>
    <td align="right"><?php _e("Click on the header title to display the options.", "gd-star-rating"); ?></td>
  </tr>
</table>
</div>
<div id="gdstarr-divelemets-on[<?php echo $wpnm; ?>]" style="display: <?php echo $wpno['div_elements'] == '1' ? 'block' : 'none' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%" style="margin-bottom: 10px">
  <tr>
    <td width="100" valign="top"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('gdstarr-divelemets', '<?php echo $wpnm; ?>')"><?php _e("Elements", "gd-star-rating"); ?></a></strong></td>
    <td><strong>%RATING%</strong></td><td> : <?php _e("article rating", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%MAX_RATING%</strong></td><td> : <?php _e("maximum rating value", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100" valign="top"></td>
    <td><strong>%BAYES_RATING%</strong></td><td> : <?php _e("bayesian article rating", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%STARS%</strong></td><td> : <?php _e("rating stars", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100" valign="top"></td>
    <td><strong>%BAYES_STARS%</strong></td><td> : <?php _e("bayesian rating stars", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%VOTES%</strong></td><td> : <?php _e("total votes for article", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%REVIEW%</strong></td><td> : <?php _e("article review", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%MAX_REVIEW%</strong></td><td> : <?php _e("maximum review value", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%TITLE%</strong></td><td> : <?php _e("item title", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%RATE_TREND%</strong></td><td> : <?php _e("rating trend", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%VOTE_TREND%</strong></td><td> : <?php _e("voting trend", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%PERMALINK%</strong></td><td> : <?php _e("item url", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%ID%</strong></td><td> : <?php _e("item id", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%COUNT%</strong></td><td> : <?php _e("number of posts/pages", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%WORD_VOTES%</strong></td><td> : <?php _e("singular/plural word vote", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%IMAGE%</strong></td><td> : <?php _e("image for the post/page", "gd-star-rating"); ?></td>
  </tr>
</table>
</div>
</div>
