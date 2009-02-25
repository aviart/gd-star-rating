<?php

include(dirname(__FILE__)."/tpl_list.php");

if ($id == 0) $section = $_POST["tpl_section"];
else $section = "SRR";

$template = $tpls->get_list($section);

?>

<div class="wrap"><h2 class="gdptlogopage">GD Star Rating: <?php _e("Template Editor", "gd-star-rating"); ?></h2>
<form method="post">
<div class="gdsr">
<table width="100%" cellpadding="0" cellspacing="7"><tr>
<td class="tpl-editor-title">
    <h3><?php _e("Editor"); ?></h3>
</td><td class="tpl-editor-title">
    <h3><?php _e("Elements"); ?></h3>
</td>
</tr><tr>
<td class="tpl-editor-form-td">
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("General", "gd-star-rating"); ?></th>
    <td>
        
    </td>
</tr>
<tr><th scope="row"><?php _e("Templates", "gd-star-rating"); ?></th>
    <td>

    </td>
</tr>
</tbody></table>
</td><td class="tpl-editor-list-td">
<?php

foreach ($template->elements as $el) {
    echo '<div class="tpl-element-single">';
    echo '<p class="tpl-element-tag">'.$el->tag.'</p>';
    echo '<p class="tpl-element-desc">'.$el->description.'</p>';
    echo '</div>';
}

?>
</td>
</tr></table>
</div>
</form>
</div>