function gdsrEmpty() { }

function gdsrTimerChange(id) {
    var timer = jQuery("#gdsr_timer_type"+id).val();
    jQuery("#gdsr_timer_date"+id).css("display", "none");
    jQuery("#gdsr_timer_countdown"+id).css("display", "none");
    if (timer == "D") jQuery("#gdsr_timer_date"+id).css("display", "block");
    if (timer == "T") jQuery("#gdsr_timer_countdown"+id).css("display", "block");
}

function gdsrPostEditSection(id) {
    jQuery("#gdsr-bullet-"+id).toggleClass("gdsr-bullet-opened");
    jQuery("#gdsr-section-"+id).toggle("fast");
}

function gdsrRepeat(str, i) {
   if (isNaN(i) || i <= 0) return "";
   return str + gdsrRepeat(str, i-1);
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
    var empty = gdsrRepeat("0X", slc);
    empty = empty.substr(empty, empty.length - 1);
    jQuery('#gdsr_int_multi_' + pid + '_' + sid).val(empty);
}
