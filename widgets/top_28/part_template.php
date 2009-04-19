<input type="hidden" id="<?php echo $this->get_field_id('div_template'); ?>" name="<?php echo $this->get_field_name('div_template'); ?>" value="<?php echo $instance['div_template'] ?>" />
<div id="<?php echo $this->get_field_id('div_template'); ?>-off" style="display: <?php echo $instance['div_template'] == '1' ? 'none' : 'block' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%" style="margin-bottom: 10px">
  <tr>
    <td width="100" valign="top"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('<?php echo $this->get_field_id('div_template'); ?>')"><?php _e("Template", "gd-star-rating"); ?></a></strong></td>
    <td align="right"><?php _e("Click on the header title to display the options.", "gd-star-rating"); ?></td>
  </tr>
</table>
</div>
<div id="<?php echo $this->get_field_id('div_template'); ?>-on" style="display: <?php echo $instance['div_template'] == '1' ? 'block' : 'none' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td width="100"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('<?php echo $this->get_field_id('div_template'); ?>')"><?php _e("Template", "gd-star-rating"); ?></a></strong></td>
    <td align="right"><input class="widefat" style="width: 295px" type="text" name="<?php echo $this->get_field_name('template'); ?>" id="<?php echo $this->get_field_id('template'); ?>" value="<?php echo wp_specialchars($instance["template"]); ?>" /></td>
  </tr>
</table>
<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td width="100"></td>
    <td colspan="2"><div class="gdsr-table-split-filter"></div></td>
  </tr>
</table>
<input type="hidden" id="<?php echo $this->get_field_id('div_elements'); ?>" name="<?php echo $wpfn; ?>[div_elements]" value="<?php echo $instance['div_elements'] ?>" />
<div id="<?php echo $this->get_field_id('div_elements'); ?>-off" style="display: <?php echo $instance['div_elements'] == '1' ? 'none' : 'block' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%" style="margin-bottom: 10px">
  <tr>
    <td width="100" valign="top"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('<?php echo $this->get_field_id('div_elements'); ?>')"><?php _e("Elements", "gd-star-rating"); ?></a></strong></td>
    <td align="right"><?php _e("Click on the header title to display the options.", "gd-star-rating"); ?></td>
  </tr>
</table>
</div>
<div id="<?php echo $this->get_field_id('div_elements'); ?>-on" style="display: <?php echo $instance['div_elements'] == '1' ? 'block' : 'none' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%" style="margin-bottom: 10px">
  <tr>
    <td width="100" valign="top"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('<?php echo $this->get_field_id('div_elements'); ?>')"><?php _e("Elements", "gd-star-rating"); ?></a></strong></td>
    <td><strong>%RATING%</strong></td><td> : <?php _e("article rating", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%PERCENTAGE%</strong></td><td> : <?php _e("article percentage rating", "gd-star-rating"); ?></td>
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
    <td><strong>%VOTES%</strong></td><td> : <?php _e("total votes for article", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%COUNT%</strong></td><td> : <?php _e("number of posts/pages", "gd-star-rating"); ?></td>
  </tr>
  <tr>
    <td width="100"></td>
    <td><strong>%WORD_VOTES%</strong></td><td> : <?php _e("singular/plural word vote", "gd-star-rating"); ?></td>
  </tr>
</table>
</div>
</div>
