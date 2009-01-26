<?php if (!$extra_folders) { ?>
<div id="notice" class="error">
    <p><?php _e("For cache to work, plugin must be able to access cache folder. Plugin has tried to create folders needed and failed. Until you resolved this issue cache feature can't be used.", "gd-star-rating"); ?></p>
    <p><?php _e("Either make <strong>wp-content</strong> folder writeable or create <strong>gd-star-rating</strong> folder in <strong>wp-content</strong> and make it writeable. Use 0755 chmod setting.", "gd-star-rating"); ?></p>
</div>
<?php } ?>