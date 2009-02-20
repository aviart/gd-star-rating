<?php 
    
    if ($multi_id == 0)
        _e("You first need to select which multi set you want to use for review rating.");
    else {
        echo '<table width="100%" cellspacing="0" cellpadding="0"><tr><td width="35%" class="gdsr-mur-review-info">';
        echo __("Set ID").": <strong>".$multi_id."</strong><br />";
        echo __("Set Name").": <strong>".$set->name."</strong>";
        echo '<div class="gdsr-table-split-edit"></div>';

?>

        <input onclick="gdsrMultiClear(<?php echo $multi_id; ?>, <?php echo $post_id; ?>, <?php echo count($set->object); ?>)" type="button" class="gdsr-input-button" value="<?php _e("Clear"); ?>" />
        <input onclick="gdsrMultiRevert(<?php echo $multi_id; ?>, <?php echo $post_id; ?>, <?php echo count($set->object); ?>)" type="button" class="gdsr-input-button" value="<?php _e("Revert"); ?>" />

<?php

        echo '</td><td width="65%" class="gdsr-mur-review-stars">';
        echo GDSRRender::multi_rating_review($votes, $post_id, $set, 20);
        echo '</td></tr></table>';
    }
    
?>
