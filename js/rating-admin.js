function checkAll(form) {
    for (i = 0, n = form.elements.length; i < n; i++) {
        if(form.elements[i].type == "checkbox" && !(form.elements[i].getAttribute('onclick', 2))) {
            if(form.elements[i].checked == true)
                form.elements[i].checked = false;
            else
                form.elements[i].checked = true;
        }
    }
}
function gdsrTimerChange() {
    var timer = jQuery("#gdsr_timer_type").val();
    jQuery("#gdsr_timer_date").css("display", "none");
    jQuery("#gdsr_timer_countdown").css("display", "none");
    jQuery("#gdsr_timer_date_text").css("display", "none");
    jQuery("#gdsr_timer_countdown_text").css("display", "none");
    if (timer == "D") {
        jQuery("#gdsr_timer_date").css("display", "block");
        jQuery("#gdsr_timer_date_text").css("display", "block");
    }
    if (timer == "T") {
        jQuery("#gdsr_timer_countdown").css("display", "block");
        jQuery("#gdsr_timer_countdown_text").css("display", "block");
    }
}
