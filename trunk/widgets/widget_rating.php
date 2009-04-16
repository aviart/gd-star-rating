<script>
function gdsrChangeDate(el, index) {
    document.getElementById("gdsr-pd-lastd["+index+"]").style.display = el == "lastd" ? "block" : "none";
    document.getElementById("gdsr-pd-month["+index+"]").style.display = el == "month" ? "block" : "none";
    document.getElementById("gdsr-pd-range["+index+"]").style.display = el == "range" ? "block" : "none";
}

function gdsrChangeTrend(trend, el, index) {
    document.getElementById("gdsr-"+trend+"-txt["+index+"]").style.display = el == "txt" ? "block" : "none";
    document.getElementById("gdsr-"+trend+"-img["+index+"]").style.display = el == "img" ? "block" : "none";
}

function gdsrShowHidePreview(gdid, index) {
    var preview = document.getElementById(gdid+'-on['+index+']');
    var message = document.getElementById(gdid+'-off['+index+']');
    var hidden = document.getElementById(gdid+'['+index+']');

    if (preview.style.display == "block") {
        preview.style.display = "none";
        message.style.display = "block";
        hidden.value = "0";
    }
    else {
        preview.style.display = "block";
        message.style.display = "none";
        hidden.value = "1";
    }
}
</script>

<?php include(STARRATING_PATH.'widgets/rating/part_basic.php'); ?>
<?php include(STARRATING_PATH.'widgets/rating/part_trend.php'); ?>
<?php include(STARRATING_PATH.'widgets/rating/part_filter.php'); ?>
<?php include(STARRATING_PATH.'widgets/rating/part_image.php'); ?>
<?php include(STARRATING_PATH.'widgets/rating/part_stars.php'); ?>
<?php include(STARRATING_PATH.'widgets/rating/part_template.php'); ?>

<input type="hidden" id="gdstarr-submit" name="<?php echo $wpfn; ?>[submit]" value="1" />
