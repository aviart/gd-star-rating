function init() {
	tinyMCEPopup.resizeToInnerSize();
}

function insertStarRatingCode() {
    var tagtext = "";
    var shortcode = document.getElementById('srShortcode').value;
    
    if (shortcode == 'starreview') {
        tagtext = "[starreview]";
    }
    else if (shortcode == 'starrater') {
        tagtext = "[starrater]";
    }
    else {
        tagtext = "[starrating";
        if (document.getElementById('srRows').value != 10)
            tagtext = tagtext + " rows=" + document.getElementById('srRows').value
        if (document.getElementById('srSelect').value != 'postpage')
            tagtext = tagtext + " select='" + document.getElementById('srSelect').value + "'";
        if (document.getElementById('srColumn').value != 'rating')
            tagtext = tagtext + " column='" + document.getElementById('srColumn').value + "'";
        if (document.getElementById('srOrder').value != 'desc')
            tagtext = tagtext + " order='" + document.getElementById('srOrder').value + "'";
        if (document.getElementById('srCategory').value != '0')
            tagtext = tagtext + " category=" + document.getElementById('srCategory').value;
        if (document.getElementById('srGrouping').value != 'post')
            tagtext = tagtext + " grouping='" + document.getElementById('srGrouping').value + "'";
        if (document.getElementById('srShow').value != 'total')
            tagtext = tagtext + " show='" + document.getElementById('srShow').value + "'";
        
        if (document.getElementById('trendRating').value != 'txt') {
            tagtext = tagtext + " trends_rating='" + document.getElementById('trendRating').value + "'";
            if (document.getElementById('trendRatingSet').value != '+')
                tagtext = tagtext + " trends_rating_set='" + document.getElementById('trendRatingSet').value + "'";
        }
        else {
            if (document.getElementById('trendRatingRise').value != '+')
                tagtext = tagtext + " trends_rating_rise='" + document.getElementById('trendRatingRise').value + "'";
            if (document.getElementById('trendRatingSame').value != '=')
                tagtext = tagtext + " trends_rating_same='" + document.getElementById('trendRatingSame').value + "'";
            if (document.getElementById('trendRatingFall').value != '-')
                tagtext = tagtext + " trends_rating_fall='" + document.getElementById('trendRatingFall').value + "'";
        }
        
        if (document.getElementById('trendVoting').value != 'txt') {
            tagtext = tagtext + " trends_voting='" + document.getElementById('trendVoting').value + "'";
            if (document.getElementById('trendVotingSet').value != '+')
                tagtext = tagtext + " trends_voting_set='" + document.getElementById('trendVotingSet').value + "'";
        }
        else {
            if (document.getElementById('trendVotingRise').value != '+')
                tagtext = tagtext + " trends_voting_rise='" + document.getElementById('trendVotingRise').value + "'";
            if (document.getElementById('trendVotingSame').value != '=')
                tagtext = tagtext + " trends_voting_same='" + document.getElementById('trendVotingSame').value + "'";
            if (document.getElementById('trendVotingFall').value != '-')
                tagtext = tagtext + " trends_voting_fall='" + document.getElementById('trendVotingFall').value + "'";
        }
        
        if (!document.getElementById('srHidempty').checked)
           tagtext = tagtext + " hide_empty=0";
        if (document.getElementById('srHidemptyReview').checked)
           tagtext = tagtext + " hide_noreview=1";
        if (document.getElementById('srHidemptyBayes').checked)
           tagtext = tagtext + " bayesian_calculation=1";

        if (document.getElementById('publishDate').value == 'lastd') {
            if (document.getElementById('publishDays').value > 0) {
                tagtext = tagtext + " publish_days=" + document.getElementById('publishDays').value;
            }
        }
        else if (document.getElementById('publishDate').value == 'month') {
            tagtext = tagtext + " publish_date='month'";
            tagtext = tagtext + " publish_month='" + document.getElementById('publishMonth').value + "'";
        }
        else {
            tagtext = tagtext + " publish_date='range'";
            tagtext = tagtext + " publish_range_from='" + document.getElementById('publishRangeFrom').value + "'";
            tagtext = tagtext + " publish_range_to='" + document.getElementById('publishRangeTo').value + "'";
        }
        
        if (document.getElementById('srSType').value == 'built') {
            tagtext = tagtext + " div_class='" + document.getElementById('srClassBuild').value + "'";
        }
        else if (document.getElementById('srSType').value == 'external') {
            tagtext = tagtext + " div_class='" + document.getElementById('srClass').value + "'";
        }

        if (document.getElementById('srStarsStyle').value != 'oxygen')
            tagtext = tagtext + " rating_stars='" + document.getElementById('srStarsStyle').value + "'";
        if (document.getElementById('srStarsSize').value != '20')
            tagtext = tagtext + " rating_size='" + document.getElementById('srStarsSize').value + "'";
        
        if (document.getElementById('srReviewStarsStyle').value != 'oxygen')
            tagtext = tagtext + " review_stars='" + document.getElementById('srReviewStarsStyle').value + "'";
        if (document.getElementById('srReviewStarsSize').value != '20')
            tagtext = tagtext + " review_size='" + document.getElementById('srReviewStarsSize').value + "'";

        tagtext = tagtext + "]";
	}
    
	if (window.tinyMCE) {
		window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.close();
	}
	return;
}
