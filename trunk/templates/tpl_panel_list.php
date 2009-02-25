<?php

include(dirname(__FILE__)."/tpl_list.php");

$filter_section = "";

if ($_POST["gdsr_filter"] == __("Filter", "gd-star-rating")) {
    $filter_section = $_POST["filter_section"];
}


?>

<div class="wrap"><h2 class="gdptlogopage">GD Star Rating: <?php _e("Templates", "gd-star-rating"); ?></h2>
<div class="gdsr">

<div class="tablenav">
    <div class="alignleft">
        <form method="post">
            <?php GDSRHelper::render_templates_sections("filter_section", $tpls->list_sections(), true, $filter_section) ?>
            <input class="button-secondary delete" type="submit" name="gdsr_filter" value="<?php _e("Filter", "gd-star-rating"); ?>" />
        </form>
    </div>
    <div class="tablenav-pages">
    </div>
</div>
<br class="clear"/>

<table class="widefat">
    <thead>
        <tr>
            <th scope="col" width="33"><?php _e("ID", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Name", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Section", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Description", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Options", "gd-star-rating"); ?></th>
        </tr>
    </thead>
    <tbody>

<?php

    $templates = GDSRDB::get_templates($filter_section);

?>

    </tbody>
</table>
<br class="clear"/>

<div class="tablenav">
    <div class="alignleft">
        <form method="post">
            <table cellpadding="0" cellspacing="0"><tr><td>
            <?php _e("New template for:"); ?> </td><td>
            <?php GDSRHelper::render_templates_sections("tpl_section", $tpls->list_sections(), false) ?>
            <input class="button-secondary delete" type="submit" name="gdsr_create" value="<?php _e("Create", "gd-star-rating"); ?>" />
            </td></tr></table>
        </form>
    </div>
    <div class="tablenav-pages">
    </div>
</div>
<br class="clear"/>
</div>

</div>
</div>