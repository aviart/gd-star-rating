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
        tagtext = "[starrating ";
	    var rows = document.getElementById('srRows').value;
        var select = document.getElementById('srSelect').value;
        var column = document.getElementById('srColumn').value;
        var order = document.getElementById('srOrder').value;
        var header = document.getElementById('srHeader').value == "on" ? 1 : 0;
        var votes = document.getElementById('srVotes').value == "on" ? 1 : 0;
        var review = document.getElementById('srReview').value == "on" ? 1 : 0;
        var rating = document.getElementById('srRating').value == "on" ? 1 : 0;
        var links = document.getElementById('srLinks').value == "on" ? 1 : 0;
        var hidempty = document.getElementById('srHidempty').value == "on" ? 1 : 0;

        var clss = document.getElementById('srSType').value;
        if (clss == 'built') clss = document.getElementById('srClassBuild').value
        else clss = document.getElementById('srClass').value;
        
        var style = document.getElementById('srRType').value;
        var reviewStyle = document.getElementById('srVType').value;
        var category = document.getElementById('srCategory').value;
        var show = document.getElementById('srShow').value;

	    tagtext = tagtext + "rows=" + rows + " select='" + select + "' column='" + column + "' order='" + order + "' class='" + clss + "' category=" + category + " ";
        tagtext = tagtext + "header=" + header + " hidempty=" + hidempty + " votes=" + votes + " review=" + review + " rating=" + rating + " links=" + links + " rating_style='" + style + "' show='" + show + "'";
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
