<?php

    require_once("../gd-star-config.php");
    $wpconfig = get_wpconfig();
    require($wpconfig);

    header('Content-Type: application/javascript');

?>

function gdsrWait(rater, loader) {
	jQuery("#"+rater).css("display", "none");
	jQuery("#"+loader).css("display", "block");
}

function gdsrVote(id, votes, user, vtype, rater, loader) {
    gdsrWait(rater, loader);
    jQuery.getJSON('<?php echo STARRATING_URL; ?>gd-star-ajax.php', {vote_id: id, vote_value: votes, vote_user: user, vote_type: vtype }, function(json) {
        gdsrWait(loader, rater);
        jQuery("#gdr_stars_"+id).html("");
        jQuery("#gdr_vote_"+id).css("width", json.value);
        jQuery("#gdr_text_"+id).addClass("voted");
        jQuery("#gdr_text_"+id).html(json.rater);
    });
}