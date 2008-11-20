<div class="gdsr">
<div class="wrap"><h2>GD Star Rating: <?php _e("IP's", "gd-star-rating"); ?></h2>

<div id="gdsr_tabs" class="gdsrtabs">
<ul>
    <li><a href="#fragment-1"><span><?php _e("Ban New", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-2"><span><?php _e("Banned", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-3"><span><?php _e("BOT List", "gd-star-rating"); ?></span></a></li>
</ul>
<div style="clear: both"></div>
<div id="fragment-1">
<?php include STARRATING_PATH."options/ips_options.php"; ?>
</div>
<div id="fragment-2">
<?php include STARRATING_PATH."options/ips_list.php"; ?>
</div>
<div id="fragment-3">
<?php include STARRATING_PATH."options/ips_bots.php"; ?>
</div>
</div>
</div>
</div>
