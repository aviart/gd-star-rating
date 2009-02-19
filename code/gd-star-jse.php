<?php if ($edit_std) { ?>
function gdsrTimerChange() {
    var timer = jQuery("#gdsr_timer_type").val();
    jQuery("#gdsr_timer_date").css("display", "none");
    jQuery("#gdsr_timer_countdown").css("display", "none");
    if (timer == "D") jQuery("#gdsr_timer_date").css("display", "block");
    if (timer == "T") jQuery("#gdsr_timer_countdown").css("display", "block");
}
<?php } ?>

<?php if ($edit_mur) { ?>

<?php } ?>
