function gdsrChangeShortcode(calledfrom) {
    var shortcode = document.getElementById("srShortcode").selectedIndex;
    var adminIndex = 0;
    document.getElementById("general_tab").style.display = "none";
    document.getElementById("filter_tab").style.display = "none";
    document.getElementById("styles_tab").style.display = "none";
    document.getElementById("multis_tab").style.display = "none";
    document.getElementById("multisreview_tab").style.display = "none";
    document.getElementById("articlesreview_tab").style.display = "none";
    document.getElementById("articlesrater_tab").style.display = "none";
    document.getElementById("commentsaggr_tab").style.display = "none";
    document.getElementById("blograting_tab").style.display = "none";
    switch (shortcode) {
        case 0:
            document.getElementById("general_tab").style.display = "block";
            document.getElementById("filter_tab").style.display = "block";
            document.getElementById("styles_tab").style.display = "block";
            break;
        case 3:
            document.getElementById("multis_tab").style.display = "block";
            adminIndex = 3;
            break;
        case 4:
            document.getElementById("multisreview_tab").style.display = "block";
            adminIndex = 4;
            break;
        case 56:
            document.getElementById("articlesreview_tab").style.display = "block";
            adminIndex = 5;
            break;
        case 7:
            document.getElementById("articlesrater_tab").style.display = "block";
            adminIndex = 6;
            break;
        case 9:
            document.getElementById("commentsaggr_tab").style.display = "block";
            adminIndex = 7;
            break;
        case 1:
            document.getElementById("blograting_tab").style.display = "block";
            adminIndex = 8;
            break;
    }
    if (calledfrom == 'admin') jQuery("#gdsr_tabs > ul").tabs("select", adminIndex);
}

function gdsrAdminGetShortcode() {
    var shortcode = insertStarRatingCode();
    jQuery("#gdsr-builder-shortcode").val(shortcode[0]);
    jQuery("#gdsr-builder-function").val(shortcode[1]);
}

function gdsrChangeTrend(trend, el, index) {
    document.getElementById("gdsr-"+trend+"-txt["+index+"]").style.display = el == "txt" ? "block" : "none";
    document.getElementById("gdsr-"+trend+"-img["+index+"]").style.display = el == "img" ? "block" : "none";
}

function gdsrChangeSource(el, index) {
    document.getElementById("gdsr-src-multi["+index+"]").style.display = el == "multis" ? "block" : "none";
}

function gdsrChangeDate(el, index) {
    document.getElementById("gdsr-pd-lastd["+index+"]").style.display = el == "lastd" ? "block" : "none";
    document.getElementById("gdsr-pd-month["+index+"]").style.display = el == "month" ? "block" : "none";
    document.getElementById("gdsr-pd-range["+index+"]").style.display = el == "range" ? "block" : "none";
}

function gdsrChangeImage(el, index) {
    document.getElementById("gdsr-pi-none["+index+"]").style.display = el == "none" ? "block" : "none";
    document.getElementById("gdsr-pi-custom["+index+"]").style.display = el == "custom" ? "block" : "none";
    document.getElementById("gdsr-pi-content["+index+"]").style.display = el == "content" ? "block" : "none";
}

function init() {
	tinyMCEPopup.resizeToInnerSize();
}

function insertStarRatingCode() {
    var tagtext = "";
    var funtext = "";
    var shortcode = document.getElementById('srShortcode').value;
    
    if (shortcode == 'blograting') {
        tagtext = "[blograting";
        tagtext = tagtext + " template_id=" + document.getElementById('srTemplateWBR').value;
        funa = new Array();
        funtext = "wp_gdsr_render_blog_rating_widget(array(";
        funa.push("'template_id' => " + document.getElementById('srTemplateWBR').value);
        if (document.getElementById('srSelectBR').value != 'postpage') {
            tagtext = tagtext + " select='" + document.getElementById('srSelectBR').value + "'";
            funa.push("'select' => '" + document.getElementById('srSelectBR').value + "'");
        }
        if (document.getElementById('srShowBR').value != 'total') {
            tagtext = tagtext + " show='" + document.getElementById('srShowBR').value + "'";
            funa.push("'show' => '" + document.getElementById('srShowBR').value + "'");
        }
        tagtext = tagtext + "]";
        funtext = funtext + funa.join(", ") + "));"
    } else if (shortcode == 'starreview') {
        tagtext = "[starreview";
        tagtext = tagtext + " tpl=" + document.getElementById('srTemplateRSB').value;
        tagtext = tagtext + "]";
        funtext = "wp_gdsr_render_review(0, ";
        funtext = funtext + document.getElementById('srTemplateRSB').value + ");"
    } else if (shortcode == 'starcomments') {
        tagtext = "[starcomments";
        funtext = "wp_gdsr_render_comment_aggregation(0, " + document.getElementById('srTemplateCAR').value;
        tagtext = tagtext + " tpl=" + document.getElementById('srTemplateCAR').value;
        if (document.getElementById('srCagShow').value != 'total') {
            tagtext = tagtext + " show='" + document.getElementById('srCagShow').value + "'";
            funtext = funtext + ", '" + document.getElementById('srCagShow').value + "'";
        }
        tagtext = tagtext + "]";
        funtext = funtext + ");"
    } else if (shortcode == 'starrater') {
        tagtext = "[starrater tpl=";
        funtext = "wp_gdsr_render_article(" + document.getElementById('srRatingBlockTemplate').value;
        tagtext = tagtext + document.getElementById('srRatingBlockTemplate').value;
        if (document.getElementById('srArticleRead').checked) {
           tagtext = tagtext + " read_only=1";
           funtext = funtext + ", true";
        }
        tagtext = tagtext + "]";
        funtext = funtext + ");"
    } else if (shortcode == 'starreviewmulti') {
        tagtext = "[starreviewmulti id=";
        funtext = "wp_gdsr_show_multi_review(" + document.getElementById('srMultiReviewSet').value;
        funtext = funtext + ", " + document.getElementById('srRatingBlockTemplate').value;
        tagtext = tagtext + document.getElementById('srMultiReviewSet').value;
        tagtext = tagtext + " tpl=" + document.getElementById('srTemplateRMB').value;
        if (document.getElementById('srStarsStyleMRREl').value != 'oxygen') {
            tagtext = tagtext + " element_stars='" + document.getElementById('srStarsStyleMRREl').value + "'";
        }
        if (document.getElementById('srStarsSizeMRREl').value != '20') {
            tagtext = tagtext + " element_size='" + document.getElementById('srStarsSizeMRREl').value + "'";
        }
        if (document.getElementById('srStarsStyleMRRAv').value != 'oxygen') {
            tagtext = tagtext + " average_stars='" + document.getElementById('srStarsStyleMRRAv').value + "'";
        }
        if (document.getElementById('srStarsSizeMRRAv').value != '20') {
            tagtext = tagtext + " average_size='" + document.getElementById('srStarsSizeMRRAv').value + "'";
        }
        funtext = funtext + ", '" + document.getElementById('srStarsStyleMRREl').value;
        funtext = funtext + "', " + document.getElementById('srStarsSizeMRREl').value;
        funtext = funtext + ", 'oxygen_gif'";
        funtext = funtext + ", '" + document.getElementById('srStarsStyleMRRAv').value;
        funtext = funtext + "', " + document.getElementById('srStarsSizeMRRAv').value;
        funtext = funtext + ", 'oxygen_gif'";
        tagtext = tagtext + "]";
        funtext = funtext + ");"
    } else if (shortcode == 'starratingmulti') {
        tagtext = '[starratingmulti id=';
        funtext = "wp_gdsr_render_multi(" + document.getElementById('srMultiRatingSet').value;
        funtext = funtext + ", " + document.getElementById('srTemplateMRB').value;
        tagtext = tagtext + document.getElementById('srMultiRatingSet').value;
        tagtext = tagtext + " tpl=" + document.getElementById('srTemplateMRB').value;
        if (document.getElementById('srMultiRead').checked) {
            tagtext = tagtext + " read_only=1";
            funtext = funtext + ", true";
        } else funtext = funtext + ", false";
        funtext = funtext + ", 0, '" + document.getElementById('srStarsStyleMUREl').value;
        funtext = funtext + "', " + document.getElementById('srStarsSizeMUREl').value;
        funtext = funtext + ", 'oxygen_gif'";
        funtext = funtext + ", '" + document.getElementById('srStarsStyleMURAv').value;
        funtext = funtext + "', " + document.getElementById('srStarsSizeMURAv').value;
        funtext = funtext + ", 'oxygen_gif'";
        if (document.getElementById('srStarsStyleMUREl').value != 'oxygen') {
            tagtext = tagtext + " element_stars='" + document.getElementById('srStarsStyleMUREl').value + "'";
        }
        if (document.getElementById('srStarsSizeMUREl').value != '20') {
            tagtext = tagtext + " element_size='" + document.getElementById('srStarsSizeMUREl').value + "'";
        }
        if (document.getElementById('srStarsStyleMURAv').value != 'oxygen') {
            tagtext = tagtext + " average_stars='" + document.getElementById('srStarsStyleMURAv').value + "'";
        }
        if (document.getElementById('srStarsSizeMURAv').value != '20') {
            tagtext = tagtext + " average_size='" + document.getElementById('srStarsSizeMURAv').value + "'";
        }
        tagtext = tagtext + "]";
        funtext = funtext + ");"
    } else {
        tagtext = "[starrating";
        funtext = "wp_gdsr_render_star_rating_widget(array(";
        funa = new Array();
        tagtext = tagtext + " template_id=" + document.getElementById('srTemplateSRR').value;
        funa.push("'template_id' => " + document.getElementById('srTemplateSRR').value);
        if (document.getElementById('srRows').value != 10) {
            tagtext = tagtext + " rows=" + document.getElementById('srRows').value;
            funa.push("'rows' => " + document.getElementById('srRows').value);
        }
        if (document.getElementById('srSelect').value != 'postpage') {
            tagtext = tagtext + " select='" + document.getElementById('srSelect').value + "'";
            funa.push("'select' => '" + document.getElementById('srSelect').value + "'");
        }
        if (document.getElementById('srColumn').value != 'rating') {
            tagtext = tagtext + " column='" + document.getElementById('srColumn').value + "'";
            funa.push("'column' => '" + document.getElementById('srColumn').value + "'");
        }
        if (document.getElementById('srOrder').value != 'desc') {
            tagtext = tagtext + " order='" + document.getElementById('srOrder').value + "'";
            funa.push("'order' => '" + document.getElementById('srOrder').value + "'");
        }
        if (document.getElementById('srLastDate').value != 0) {
            tagtext = tagtext + " last_voted_days=" + document.getElementById('srLastDate').value;
            funa.push("'last_voted_days' => " + document.getElementById('srLastDate').value);
        }
        if (document.getElementById('srCategory').value != '0') {
            tagtext = tagtext + " category=" + document.getElementById('srCategory').value;
            funa.push("'category' => " + document.getElementById('srCategory').value);
        }
        if (document.getElementById('srGrouping').value != 'post') {
            tagtext = tagtext + " grouping='" + document.getElementById('srGrouping').value + "'";
            funa.push("'grouping' => '" + document.getElementById('srGrouping').value + "'");
        }
        if (document.getElementById('srShow').value != 'total') {
            tagtext = tagtext + " show='" + document.getElementById('srShow').value + "'";
            funa.push("'show' => '" + document.getElementById('srShow').value + "'");
        }
        if (document.getElementById('trendRating').value != 'txt') {
            tagtext = tagtext + " trends_rating='" + document.getElementById('trendRating').value + "'";
            funa.push("'trends_rating' => '" + document.getElementById('trendRating').value + "'");
            if (document.getElementById('trendRatingSet').value != '+') {
                tagtext = tagtext + " trends_rating_set='" + document.getElementById('trendRatingSet').value + "'";
                funa.push("'trends_rating_set' => '" + document.getElementById('trendRatingSet').value + "'");
            }
        }
        else {
            if (document.getElementById('trendRatingRise').value != '+') {
                tagtext = tagtext + " trends_rating_rise='" + document.getElementById('trendRatingRise').value + "'";
                funa.push("'trends_rating_rise' => '" + document.getElementById('trendRatingRise').value + "'");
            }
            if (document.getElementById('trendRatingSame').value != '=') {
                tagtext = tagtext + " trends_rating_same='" + document.getElementById('trendRatingSame').value + "'";
                funa.push("'trends_rating_same' => '" + document.getElementById('trendRatingSame').value + "'");
            }
            if (document.getElementById('trendRatingFall').value != '-') {
                tagtext = tagtext + " trends_rating_fall='" + document.getElementById('trendRatingFall').value + "'";
                funa.push("'trends_rating_fall' => '" + document.getElementById('trendRatingFall').value + "'");
            }
        }

        if (document.getElementById('trendVoting').value != 'txt') {
            tagtext = tagtext + " trends_voting='" + document.getElementById('trendVoting').value + "'";
            funa.push("'trends_voting' => '" + document.getElementById('trendVoting').value + "'");
            if (document.getElementById('trendVotingSet').value != '+') {
                tagtext = tagtext + " trends_voting_set='" + document.getElementById('trendVotingSet').value + "'";
                funa.push("'trends_voting_set' => '" + document.getElementById('trendVotingSet').value + "'");
            }
        }
        else {
            if (document.getElementById('trendVotingRise').value != '+') {
                tagtext = tagtext + " trends_voting_rise='" + document.getElementById('trendVotingRise').value + "'";
                funa.push("'trends_voting_rise' => '" + document.getElementById('trendVotingRise').value + "'");
            }
            if (document.getElementById('trendVotingSame').value != '=') {
                tagtext = tagtext + " trends_voting_same='" + document.getElementById('trendVotingSame').value + "'";
                funa.push("'trends_voting_same' => '" + document.getElementById('trendVotingSame').value + "'");
            }
            if (document.getElementById('trendVotingFall').value != '-') {
                tagtext = tagtext + " trends_voting_fall='" + document.getElementById('trendVotingFall').value + "'";
                funa.push("'trends_voting_fall' => '" + document.getElementById('trendVotingFall').value + "'");
            }
        }

        if (!document.getElementById('srHidempty').checked) {
            tagtext = tagtext + " hide_empty=0";
            funa.push("'hide_empty' => false");
        }
        if (document.getElementById('srHidemptyReview').checked) {
            tagtext = tagtext + " hide_noreview=1";
            funa.push("'hide_noreview' => true");
        }
        if (document.getElementById('srHidemptyBayes').checked) {
            tagtext = tagtext + " bayesian_calculation=1";
            funa.push("'bayesian_calculation' => true");
        }
        if (document.getElementById('srMinVotes').value != 5) {
            tagtext = tagtext + " min_votes=" + document.getElementById('srMinVotes').value;
            funa.push("'min_votes' => " + document.getElementById('srMinVotes').value);
        }
        if (document.getElementById('srDataSource').value != 'standard') {
            tagtext = tagtext + " source='" + document.getElementById('srDataSource').value + "'";
            tagtext = tagtext + " source_set=" + document.getElementById('srMultiSet').value;
            funa.push("'source' => '" + document.getElementById('srDataSource').value + "'");
            funa.push("'source_set' => " + document.getElementById('srMultiSet').value);
        }

        if (document.getElementById('srImageFrom').value != 'none') {
            tagtext = tagtext + " image_from='" + document.getElementById('srImageFrom').value + "'";
            funa.push("'image_from' => '" + document.getElementById('srImageFrom').value + "'");
            if (document.getElementById('srImageFrom').value == 'custom') {
                tagtext = tagtext + " image_custom='" + document.getElementById('srImageCustom').value + "'";
                funa.push("'image_custom' => '" + document.getElementById('srImageCustom').value + "'");
            }
        }

        if (document.getElementById('publishDate').value == 'lastd') {
            if (document.getElementById('publishDays').value > 0) {
                tagtext = tagtext + " publish_days=" + document.getElementById('publishDays').value;
                funa.push("'publish_days' => " + document.getElementById('publishDays').value);
            }
        }
        else if (document.getElementById('publishDate').value == 'month') {
            tagtext = tagtext + " publish_date='month'";
            tagtext = tagtext + " publish_month='" + document.getElementById('publishMonth').value + "'";
            funa.push("'publish_date' => 'month'");
            funa.push("'publish_month' => '" + document.getElementById('publishMonth').value + "'");
        }
        else {
            tagtext = tagtext + " publish_date='range'";
            tagtext = tagtext + " publish_range_from='" + document.getElementById('publishRangeFrom').value + "'";
            tagtext = tagtext + " publish_range_to='" + document.getElementById('publishRangeTo').value + "'";
            funa.push("'publish_date' => 'range'");
            funa.push("'publish_range_from' => '" + document.getElementById('publishRangeFrom').value + "'");
            funa.push("'publish_range_to' => '" + document.getElementById('publishRangeTo').value + "'");
        }

        if (document.getElementById('srStarsStyle').value != 'oxygen') {
            tagtext = tagtext + " rating_stars='" + document.getElementById('srStarsStyle').value + "'";
            funa.push("'rating_stars' => '" + document.getElementById('srStarsStyle').value + "'");
        }
        if (document.getElementById('srStarsSize').value != '20') {
            tagtext = tagtext + " rating_size='" + document.getElementById('srStarsSize').value + "'";
            funa.push("'rating_size' => '" + document.getElementById('srStarsSize').value + "'");
        }
        if (document.getElementById('srReviewStarsStyle').value != 'oxygen') {
            tagtext = tagtext + " review_stars='" + document.getElementById('srReviewStarsStyle').value + "'";
            funa.push("'review_stars' => '" + document.getElementById('srReviewStarsStyle').value + "'");
        }
        if (document.getElementById('srReviewStarsSize').value != '20') {
            tagtext = tagtext + " review_size='" + document.getElementById('srReviewStarsSize').value + "'";
            funa.push("'review_size' => '" + document.getElementById('srReviewStarsSize').value + "'");
        }

        tagtext = tagtext + "]";
        funtext = funtext + funa.join(", ") + "));"
	}

	if (window.tinyMCE) {
		window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.close();
        return;
	} else return new Array(tagtext, funtext);
}