<input type="hidden" id="<?php echo $this->get_field_id('div_image'); ?>" name="<?php echo $this->get_field_name('div_image'); ?>" value="<?php echo $instance['div_image'] ?>" />
<div id="<?php echo $this->get_field_id('div_image'); ?>-off" style="display: <?php echo $instance['div_image'] == '1' ? 'none' : 'block' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td width="100" valign="top"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('<?php echo $this->get_field_id('div_image'); ?>')"><?php _e("Avatar", "gd-star-rating"); ?></a></strong></td>
    <td align="right"><?php _e("Click on the header title to display the options.", "gd-star-rating"); ?></td>
  </tr>
</table>
</div>
<div id="<?php echo $this->get_field_id('div_image'); ?>-on" style="display: <?php echo $instance['div_image'] == '1' ? 'block' : 'none' ?>">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td width="100" valign="top"><strong><a style="text-decoration: none" href="javascript:gdsrShowHidePreview('<?php echo $this->get_field_id('div_image'); ?>')"><?php _e("Avatar", "gd-star-rating"); ?></a></strong></td>
    <td width="150" nowrap="nowrap"><?php _e("Width", "gd-star-rating"); ?>:</td>
    <td align="right"><input class="widefat" style="text-align: right; width: 40px" type="text" name="<?php echo $this->get_field_name('avatar_x'); ?>" id="<?php echo $this->get_field_id('avatar_x'); ?>" value="<?php echo $instance["avatar_x"]; ?>" /></td>
  </tr>
  <tr>
    <td width="100" valign="top"></td>
    <td width="150" nowrap="nowrap"><?php _e("Height", "gd-star-rating"); ?>:</td>
    <td align="right"><input class="widefat" style="text-align: right; width: 40px" type="text" name="<?php echo $this->get_field_name('avatar_y'); ?>" id="<?php echo $this->get_field_id('avatar_y'); ?>" value="<?php echo $instance["avatar_y"]; ?>" /></td>
  </tr>
</table>
</div>
</div>
<div class="gdsr-table-split"></div>
