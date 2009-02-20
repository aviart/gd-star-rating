<?php 
    
    if ($multi_id == 0)
        _e("You first need to select which multi set you want to use for review rating.");
    else {
        echo '<table width="100%" cellspacing="0" cellpadding="0"><tr><td width="35%">';
        echo __("Multi Set ID").": <strong>".$multi_id."</strong><br />";
        echo __("Multi Set Name").": <strong>".$set->name."</strong>";
        echo '<div class="gdsr-table-split-edit"></div>';
        echo __("");
        echo '</td><td width="65%">';

        echo GDSRRender::multi_rating_review($votes, $post_id, $set, 20);

        echo '</td></tr></table>';
    }
    
?>
