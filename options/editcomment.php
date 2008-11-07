<?php
global $comment, $gdsr;
$review = GDSRDatabase::get_comment_review($comment->comment_ID); 
?>
<div id="gdsr_comment_review_edit" style="display: none">
    <div id="gdsrreviewdiv" class="stuffbox">
        <h3>GD Star Rating</h3>
        <div class="inside">
        <?php wp_gdsr_new_comment_review($review); ?>
        <input type="hidden" id="gdsr_comment_edit" name="gdsr_comment_edit" value="edit" />
        </div>
    </div>
</div>
<script type="text/javascript">
    function gdsr_move_editbox() {
        document.getElementById("uridiv").parentNode.insertBefore(document.getElementById("gdsr_comment_review_edit"), document.getElementById("uridiv").nextSibling);
        document.getElementById("gdsr_comment_review_edit").style.display="block";
    }
    if (window.addEventListener){ 
       window.addEventListener("load", gdsr_move_editbox, false); 
     } else if (obj.attachEvent){ 
       var r = obj.attachEvent("onload", gdsr_move_editbox); 
     }
</script>
