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
        
        var hidempty = document.getElementById('srHidempty').value == "on" ? 1 : 0;

        var clss = document.getElementById('srSType').value;
        if (clss == 'built') clss = document.getElementById('srClassBuild').value
        else clss = document.getElementById('srClass').value;
        
        var style = document.getElementById('srRType').value;
        var reviewStyle = document.getElementById('srVType').value;
        var show = document.getElementById('srShow').value;

	    tagtext = tagtext + " class='" + clss + "'";
        tagtext = tagtext + " hidempty=" + hidempty + " rating_style='" + style + "' show='" + show + "'";
        if (style == 'stars') {
            stars = document.getElementById('srStarsStyle').value;
            ssize = document.getElementById('srStarsSize').value;
            tagtext = tagtext + " rating_stars='" + stars + "' rating_size=" + ssize;
        }
        if (reviewStyle == 'stars') {
            stars = document.getElementById('srReviewStarsStyle').value;
            ssize = document.getElementById('srReviewStarsSize').value;
            tagtext = tagtext + " review_stars='" + stars + "' review_size=" + ssize;
        }
        tagtext = tagtext + "]";
	}
    
	if (window.tinyMCE) {
		window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.close();
	}
	return;
}
