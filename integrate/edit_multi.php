<?php 
    
    if ($multi_id == 0)
        _e("You don't have any multi rating sets defined. Create at least one set.", "gd-star-rating");
    else {
        _e("To change the active set, you need to select the set you want from the list on the left, and save this article to accept the change.", "gd-star-rating");
        _e("All values for all sets will be saved in the database. This allows different multi rating sets reviews.", "gd-star-rating");
        echo '<table width="100%" cellspacing="0" cellpadding="0"><tr><td width="35%" class="gdsr-mur-review-info">';
        echo __("Set ID", "gd-star-rating").": <strong>".$multi_id."</strong><br />";
        echo __("Set Name", "gd-star-rating").": <strong>".$set->name."</strong>";
        echo '<div class="gdsr-table-split-edit"></div>';

?>

        <input onclick="gdsrMultiClear(<?php echo $multi_id; ?>, <?php echo $post_id; ?>, <?php echo count($set->object); ?>)" type="button" class="gdsr-input-button" value="<?php _e("Clear", "gd-star-rating"); ?>" />
        <input onclick="gdsrMultiRevert(<?php echo $multi_id; ?>, <?php echo $post_id; ?>, <?php echo count($set->object); ?>)" type="button" class="gdsr-input-button" value="<?php _e("Revert", "gd-star-rating"); ?>" />

<?php

        echo '</td><td width="65%" class="gdsr-mur-review-stars">';
        echo GDSRRender::multi_rating_review($votes, $post_id, $set, 20);
        echo '</td></tr></table>';
    }
    
?>
