function gdsrEmpty() { }

function gdsrTimerChange() {
    var timer = jQuery("#gdsr_timer_type").val();
    jQuery("#gdsr_timer_date").css("display", "none");
    jQuery("#gdsr_timer_countdown").css("display", "none");
    if (timer == "D") jQuery("#gdsr_timer_date").css("display", "block");
    if (timer == "T") jQuery("#gdsr_timer_countdown").css("display", "block");
}

function repeat(str, i) {
   if (isNaN(i) || i <= 0) return "";
   return str + repeat(str, i-1);
}

function gdsrMultiRevert(sid, pid, slc) {
    for (var i = 0; i < slc; i++) {
        var val = jQuery('#gdsr_mur_stars_rated_' + pid + '_' + sid + '_' + i).css("width");
        jQuery('#gdsr_murvw_stars_current_' + pid + '_' + sid + '_' + i).css("width", val);
    }
    jQuery('#gdsr_int_multi_' + pid + '_' + sid).val(jQuery('#gdsr_mur_review_' + pid + '_' + sid).val());
}

function gdsrMultiClear(sid, pid, slc) {
    jQuery(".gdcurrent").css("width", "0px");
    var empty = repeat("0X", slc);
    empty = empty.substr(empty, empty.length - 1);
    jQuery('#gdsr_int_multi_' + pid + '_' + sid).val(empty);
}
