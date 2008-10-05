<?php 

    if ($_POST['gdsr_action'] == 'save') :
        $gdsr_options["admin_width"] = $_POST['gdsr_admin_width'];
        $gdsr_options["admin_rows"] = $_POST['gdsr_admin_rows'];
        $gdsr_options["admin_advanced"] = isset($_POST['gdsr_admin_advanced']) ? 1 : 0;

        $gdsr_options["ajax"] = isset($_POST['gdsr_ajax']) ? 1 : 0;
        $gdsr_options["widget_articles"] = isset($_POST['gdsr_widget_articles']) ? 1 : 0;
        $gdsr_options["display_pages"] = isset($_POST['gdsr_pages']) ? 1 : 0;
        $gdsr_options["display_posts"] = isset($_POST['gdsr_posts']) ? 1 : 0;
        $gdsr_options["display_archive"] = isset($_POST['gdsr_archive']) ? 1 : 0;
        $gdsr_options["display_home"] = isset($_POST['gdsr_home']) ? 1 : 0;
        $gdsr_options["display_comment"] = isset($_POST['gdsr_dispcomment']) ? 1 : 0;
        $gdsr_options["moderation_active"] = isset($_POST['gdsr_modactive']) ? 1 : 0;
        $gdsr_options["ie_png_fix"] = isset($_POST['gdsr_iepngfix']) ? 1 : 0;
        
        $gdsr_options["preview_active"] = $_POST['gdsr_preview'];
        $gdsr_options["review_active"] = isset($_POST['gdsr_reviewactive']) ? 1 : 0;
        $gdsr_options["comments_active"] = isset($_POST['gdsr_commentsactive']) ? 1 : 0;
        $gdsr_options["hide_empty_rating"] = isset($_POST['gdsr_haderating']) ? 1 : 0;
        $gdsr_options["cookies"] = isset($_POST['gdsr_cookies']) ? 1 : 0;
        $gdsr_options["cmm_cookies"] = isset($_POST['gdsr_cmm_cookies']) ? 1 : 0;
        $gdsr_options["author_vote"] = isset($_POST['gdsr_authorvote']) ? 1 : 0;
        $gdsr_options["cmm_author_vote"] = isset($_POST['gdsr_cmm_authorvote']) ? 1 : 0;
        
        $gdsr_options["style"] = $_POST['gdsr_style'];
        $gdsr_options["size"] = $_POST['gdsr_size'];
        $gdsr_options["text"] = $_POST['gdsr_text'];
        $gdsr_options["align"] = $_POST['gdsr_align'];
        $gdsr_options["header"] = isset($_POST['gdsr_header']) ? 1 : 0;
        $gdsr_options["header_text"] = stripslashes(htmlentities($_POST['gdsr_header_text'], ENT_QUOTES, 'UTF-8'));
        $gdsr_options["class_block"] = $_POST['gdsr_classblock'];
        $gdsr_options["class_text"] = $_POST['gdsr_classtext'];
        
        $gdsr_options["cmm_style"] = $_POST['gdsr_cmm_style'];
        $gdsr_options["cmm_size"] = $_POST['gdsr_cmm_size'];
        $gdsr_options["cmm_text"] = $_POST['gdsr_cmm_text'];
        $gdsr_options["cmm_align"] = $_POST['gdsr_cmm_align'];
        $gdsr_options["cmm_header"] = isset($_POST['gdsr_cmm_header']) ? 1 : 0;
        $gdsr_options["cmm_header_text"] = stripslashes(htmlentities($_POST['gdsr_cmm_header_text'], ENT_QUOTES, 'UTF-8'));
        $gdsr_options["cmm_class_block"] = $_POST['gdsr_cmm_classblock'];
        $gdsr_options["cmm_class_text"] = $_POST['gdsr_cmm_classtext'];
        
        $gdsr_options["review_style"] = $_POST['gdsr_review_style'];
        $gdsr_options["review_size"] = $_POST['gdsr_review_size'];
        $gdsr_options["review_text"] = $_POST['gdsr_review_text'];
        $gdsr_options["review_align"] = $_POST['gdsr_review_align'];
        $gdsr_options["review_header"] = isset($_POST['gdsr_review_header']) ? 1 : 0;

        $gdsr_options["review_header_text"] = stripslashes(htmlentities($_POST['gdsr_review_header_text'], ENT_QUOTES, 'UTF-8'));
        $gdsr_options["review_class_block"] = $_POST['gdsr_review_classblock'];
                           
        $gdsr_options["cmm_review_style"] = $_POST['gdsr_cmm_review_style'];
        $gdsr_options["cmm_review_size"] = $_POST['gdsr_cmm_review_size'];
        $gdsr_options["cmm_review_text"] = $_POST['gdsr_cmm_review_text'];
        $gdsr_options["cmm_review_align"] = $_POST['gdsr_cmm_review_align'];
        $gdsr_options["cmm_review_header"] = isset($_POST['gdsr_cmm_review_header']) ? 1 : 0;
        $gdsr_options["cmm_review_header_text"] = stripslashes(htmlentities(gd_addslashes($_POST['gdsr_cmm_review_header_text'], ENT_QUOTES, 'UTF-8')));
        $gdsr_options["cmm_review_class_block"] = $_POST['gdsr_cmm_review_classblock'];
                           
        $gdsr_options["default_voterules_articles"] = $_POST['gdsr_default_vote_articles'];
        $gdsr_options["default_voterules_comments"] = $_POST['gdsr_default_vote_comments'];
        $gdsr_options["default_moderation_articles"] = $_POST['gdsr_default_mod_articles'];
        $gdsr_options["default_moderation_comments"] = $_POST['gdsr_default_mod_comments'];
        
        $gdsr_oldstars = $gdsr_options["stars"];
        $gdsr_newstars = $_POST['gdsr_stars'];
        $gdsr_cmm_oldstars = $gdsr_options["cmm_stars"];
        $gdsr_cmm_newstars = $_POST['gdsr_cmm_stars'];
        $gdsr_review_oldstars = $gdsr_options["review_stars"];
        $gdsr_review_newstars = $_POST['gdsr_review_stars'];
        $gdsr_cmm_review_oldstars = $gdsr_options["cmm_review_stars"];
        $gdsr_cmm_review_newstars = $_POST['gdsr_cmm_review_stars'];
        
        if ($gdsr_options["stars"] != $gdsr_newstars) $recalculate_articles = true;
        else $recalculate_articles = false;
        if ($gdsr_options["cmm_stars"] != $gdsr_cmm_newstars) $recalculate_comment = true;
        else $recalculate_comment = false;
        if ($gdsr_options["review_stars"] != $gdsr_review_newstars) $recalculate_reviews = true;
        else $recalculate_reviews = false;
        if ($gdsr_options["cmm_review_stars"] != $gdsr_cmm_review_newstars) $recalculate_cmm_reviews = true;
        else $recalculate_cmm_reviews = false;
        
        $gdsr_options["stars"] = $gdsr_newstars;
        $gdsr_options["cmm_stars"] = $gdsr_cmm_newstars;
        $gdsr_options["review_stars"] = $gdsr_review_newstars;
        $gdsr_options["cmm_review_stars"] = $gdsr_cmm_review_newstars;
        
        update_option("gd-star-rating", $gdsr_options);

?>

<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>Settings saved.</strong></p></div>

<?php endif; ?>

<script>
function gdsrStyleSelection() {
    var gdsrAllImages = [ <?php GDSRHelper::render_styles_js($gdsr_styles); ?> ];
    var gdsrAllTypes = [ <?php GDSRHelper::render_styles_types_js($gdsr_styles); ?> ];
    var gdsrStyle = document.getElementById("gdsr_style_preview");
    var gdsrSize = document.getElementById("gdsr_size_preview");
    gdsrStyle = gdsrStyle.options[gdsrStyle.selectedIndex].value;
    gdsrSize = gdsrSize.options[gdsrSize.selectedIndex].value;

    var gdsrImage_Black = document.getElementById("gdsr_preview_black");
    var gdsrImage_Red = document.getElementById("gdsr_preview_red");
    var gdsrImage_Green = document.getElementById("gdsr_preview_green");
    var gdsrImage_White = document.getElementById("gdsr_preview_white");
    var gdsrImage_Blue = document.getElementById("gdsr_preview_blue");
    var gdsrImage_Yellow = document.getElementById("gdsr_preview_yellow");
    var gdsrImage_Gray = document.getElementById("gdsr_preview_gray");
    var gdsrImage_Picture = document.getElementById("gdsr_preview_picture");
    gdsrImage_Black.src = "<?php echo $gdsr_root_url ?>stars/" + gdsrAllImages[gdsrStyle] + "/stars" + gdsrSize + "." + gdsrAllTypes[gdsrStyle];
    gdsrImage_Red.src = "<?php echo $gdsr_root_url ?>stars/" + gdsrAllImages[gdsrStyle] + "/stars" + gdsrSize + "." + gdsrAllTypes[gdsrStyle];
    gdsrImage_Green.src = "<?php echo $gdsr_root_url ?>stars/" + gdsrAllImages[gdsrStyle] + "/stars" + gdsrSize + "." + gdsrAllTypes[gdsrStyle];
    gdsrImage_White.src = "<?php echo $gdsr_root_url ?>stars/" + gdsrAllImages[gdsrStyle] + "/stars" + gdsrSize + "." + gdsrAllTypes[gdsrStyle];
    gdsrImage_Blue.src = "<?php echo $gdsr_root_url ?>stars/" + gdsrAllImages[gdsrStyle] + "/stars" + gdsrSize + "." + gdsrAllTypes[gdsrStyle];
    gdsrImage_Yellow.src = "<?php echo $gdsr_root_url ?>stars/" + gdsrAllImages[gdsrStyle] + "/stars" + gdsrSize + "." + gdsrAllTypes[gdsrStyle];
    gdsrImage_Gray.src = "<?php echo $gdsr_root_url ?>stars/" + gdsrAllImages[gdsrStyle] + "/stars" + gdsrSize + "." + gdsrAllTypes[gdsrStyle];
    gdsrImage_Picture.src = "<?php echo $gdsr_root_url ?>stars/" + gdsrAllImages[gdsrStyle] + "/stars" + gdsrSize + "." + gdsrAllTypes[gdsrStyle];
}

function gdsrShowHidePreview() {
    var preview = document.getElementById("gdsr-preview");
    var message = document.getElementById("gdsr-preview-msg");
    var hidden = document.getElementById("gdsr_preview");
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
<form method="post">
<input type="hidden" id="gdsr_action" name="gdsr_action" value="save" />
<input type="hidden" id="gdsr_preview" name="gdsr_preview" value="<?php echo $gdsr_options["preview_active"]; ?>" />
<div class="wrap"><h2>GD Star Rating: <?php _e("Settings", "gd-star-rating"); ?></h2>
<div class="gdsr">

<div id="gdsr_tabs" class="gdsrtabs">
<ul>
    <li><a href="#fragment-1"><span><?php _e("General", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-2"><span><?php _e("Articles (Posts And Pages)", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-3"><span><?php _e("Comments", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-4"><span><?php _e("Statistics", "gd-star-rating"); ?></span></a></li>
</ul>
<div style="clear: both"></div>
<div id="fragment-4">
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Trend Calculations", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="360"><?php _e("Calculate for last number of days", "gd-star-rating"); ?>:</td>
                <td><input type="text" name="gdsr_trend_last" id="gdsr_trend_last" value="<?php echo $gdsr_options["trend_last"]; ?>" style="width: 70px; text-align: right;" /></td>
            </tr>
            <tr>
                <td width="360"><?php _e("Calculate over how many days before", "gd-star-rating"); ?>:</td>
                <td><input type="text" name="gdsr_trend_over" id="gdsr_trend_over" value="<?php echo $gdsr_options["trend_over"]; ?>" style="width: 70px; text-align: right;" /> [0 to include comlete history]</td>
            </tr>
        </table>
    </td>
</tr>
<tr><th scope="row"><?php _e("Bayesian Estimate Mean", "gd-star-rating"); ?></th>
    <td>
    </td>
</tr>
</tbody></table>
</div>
<div id="fragment-1">

<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Administration Settings", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Maximum screen width", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <select name="gdsr_admin_width" id="gdsr_admin_width" style="width: 180px; text-align: center">
                        <option value="980"<?php echo $gdsr_options["admin_width"] == '980' ? ' selected="selected"' : ''; ?>>1024 px</option>
                        <option value="1240"<?php echo $gdsr_options["admin_width"] == '1240' ? ' selected="selected"' : ''; ?>>1280 px</option>
                        <option value="1400"<?php echo $gdsr_options["admin_width"] == '1400' ? ' selected="selected"' : ''; ?>>1440 px</option>
                        <option value="1640"<?php echo $gdsr_options["admin_width"] == '1640' ? ' selected="selected"' : ''; ?>>1680 px</option>
                        <option value="1880"<?php echo $gdsr_options["admin_width"] == '1880' ? ' selected="selected"' : ''; ?>>1920 px</option>
                    </select>
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Rows for display", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <select name="gdsr_admin_rows" id="gdsr_admin_rows" style="width: 180px; text-align: center">
                        <option value="5"<?php echo $gdsr_options["admin_rows"] == '5' ? ' selected="selected"' : ''; ?>>5</option>
                        <option value="10"<?php echo $gdsr_options["admin_rows"] == '10' ? ' selected="selected"' : ''; ?>>10</option>
                        <option value="20"<?php echo $gdsr_options["admin_rows"] == '20' ? ' selected="selected"' : ''; ?>>20</option>
                        <option value="50"<?php echo $gdsr_options["admin_rows"] == '50' ? ' selected="selected"' : ''; ?>>50</option>
                        <option value="100"<?php echo $gdsr_options["admin_rows"] == '100' ? ' selected="selected"' : ''; ?>>100</option>
                    </select>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <input type="checkbox" name="gdsr_admin_advanced" id="gdsr_admin_advanced"<?php if ($gdsr_options["admin_advanced"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_admin_advanced"><?php _e("Display Advanced Settings.", "gd-star-rating"); ?></label>
    </td>
</tr>
<tr><th scope="row"><?php _e("Plugin Features", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_ajax" id="gdsr_ajax"<?php if ($gdsr_options["ajax"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_ajax"><?php _e("AJAX enabled rating.", "gd-star-rating"); ?></label>
        <div class="gdsr-table-split"></div>
        <input type="checkbox" name="gdsr_modactive" id="gdsr_modactive"<?php if ($gdsr_options["moderation_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_modactive"><?php _e("Moderation options and handling.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_reviewactive" id="gdsr_reviewactive"<?php if ($gdsr_options["review_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_modactive"><?php _e("Review Rating.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_commentsactive" id="gdsr_commentsactive"<?php if ($gdsr_options["comments_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_commentsactive"><?php _e("Comments Rating.", "gd-star-rating"); ?></label>
        <div class="gdsr-table-split"></div>
        <input type="checkbox" name="gdsr_iepngfix" id="gdsr_iepngfix"<?php if ($gdsr_options["ie_png_fix"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_commentsactive"><?php _e("Use IE6 PNG Transparency Fix.", "gd-star-rating"); ?></label>
    </td>
</tr>
<tr><th scope="row"><?php _e("Widgets", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_widget_articles" id="gdsr_widget_articles"<?php if ($gdsr_options["widget_articles"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_ajax"><?php _e("GD Star Rating: Post/Page rating widget.", "gd-star-rating"); ?></label>
    </td>
</tr>
</tbody></table>
<table class="form-table"><tbody>
<tr><th scope="row"><a href="javascript:gdsrShowHidePreview()"><?php _e("Stars Preview", "gd-star-rating"); ?></a></th>
    <td><div id="gdsr-preview" style="display: <?php echo $gdsr_options["preview_active"] == 1 ? "block" : "none" ?>;">
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td width="150" style="padding: 0; border: 0; height: 28px"><?php _e("Stars", "gd-star-rating"); ?>:</td>
                <td width="200" align="left" style="padding: 0; border: 0; vertical-align: top;">
                    <select style="width: 180px;" name="gdsr_style_preview" id="gdsr_style_preview" onchange="gdsrStyleSelection()">
                        <?php GDSRHelper::render_styles_select($gdsr_styles); ?>
                    </select>
                </td>
                <td width="20" style="padding: 0; border: 0; vertical-align: top;" rowspan="2"></td>
                <td style="padding: 0; border: 0; vertical-align: top;" rowspan="3">
                    <table cellpadding="0" width="400" cellspacing="0" class="previewtable">
                        <tr>
                            <td class="gdsr-preview" style="background-color: black;"><img src="#" id="gdsr_preview_black" /></td>
                            <td class="gdsr-preview-space"></td>
                            <td class="gdsr-preview" style="background-color: red;"><img src="#" id="gdsr_preview_red" /></td>
                            <td class="gdsr-preview-space"></td>
                            <td class="gdsr-preview" style="background-color: green;"><img src="#" id="gdsr_preview_green" /></td>
                            <td class="gdsr-preview-space"></td>
                            <td class="gdsr-preview" style="background-color: white;"><img src="#" id="gdsr_preview_white" /></td>
                            <td class="gdsr-preview-space"></td>
                            <td class="gdsr-preview" style="background-color: blue;"><img src="#" id="gdsr_preview_blue" /></td>
                            <td class="gdsr-preview-space"></td>
                            <td class="gdsr-preview" style="background-color: yellow;"><img src="#" id="gdsr_preview_yellow" /></td>
                            <td class="gdsr-preview-space"></td>
                            <td class="gdsr-preview" style="background-color: gray;"><img src="#" id="gdsr_preview_gray" /></td>
                            <td class="gdsr-preview-space" ></td>
                            <td class="gdsr-preview gdsr-preview-pic"><img src="#" id="gdsr_preview_picture" /></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr valign="top">
                <td width="150" style="padding: 0; border: 0; vertical-align: top;"><?php _e("Size", "gd-star-rating"); ?>:</td>
                <td width="200" align="left" style="padding: 0; border: 0; vertical-align: top;">
                    <?php GDSRHelper::render_star_sizes("gdsr_size_preview", 30, 180, ' onchange="gdsrStyleSelection()"'); ?>
                </td>
            </tr>
        </table></div><div id="gdsr-preview-msg" style="display: <?php echo $gdsr_options["preview_active"] == 1 ? "none" : "block" ?>;"><?php _e("Click on this section header title to show preview panel.", "gd-star-rating"); ?></div>
    </td>
</tr>
</tbody></table>

</div>
<div id="fragment-2">

<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Rating", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Stars", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <select style="width: 180px;" name="gdsr_style" id="gdsr_style">
                <?php GDSRHelper::render_styles_select($gdsr_styles, $gdsr_options["style"]); ?>
                </select>
                </td>
                <td width="10"></td>
                <td width="150" align="left">
                <?php GDSRHelper::render_star_sizes("gdsr_size", $gdsr_options["size"]); ?>
                </td>
                <td width="10"></td>
                <td width="100"><?php _e("Number Of Stars", "gd-star-rating"); ?>:</td>
                <td width="80" align="left">
                <select style="width: 70px;" name="gdsr_stars" id="gdsr_stars">
                <?php GDSRHelper::render_stars_select($gdsr_options["stars"]); ?>
                </select>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Rating Alignment", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_alignment("gdsr_align", $gdsr_options["align"]); ?>
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Rating Text Placement", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_placement("gdsr_text", $gdsr_options["text"]); ?>
                </td>
            </tr>
            <tr>
                <td width="150"><?php _e("Rating Header", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="checkbox" name="gdsr_header" id="gdsr_header"<?php if ($gdsr_options["header"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_header"><?php _e("Display header text.", "gd-star-rating"); ?></label>
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Text to display", "gd-star-rating"); ?>:</td>
                <td>
                    <input type="text" name="gdsr_header_text" id="gdsr_header_text" value="<?php echo wp_specialchars($gdsr_options["header_text"]); ?>" style="width: 170px" />
                </td>
            </tr>
            <?php if ($gdsr_options["admin_advanced"] == 1) { ?>
            <tr>
                <td width="150"><?php _e("Rating Block CSS Class", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="text" name="gdsr_classblock" id="gdsr_classblock" value="<?php echo wp_specialchars($gdsr_options["class_block"]); ?>" style="width: 170px" />
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Rating Text CSS Class", "gd-star-rating"); ?>:</td>
                <td>
                    <input type="text" name="gdsr_classtext" id="gdsr_classtext" value="<?php echo wp_specialchars($gdsr_options["class_text"]); ?>" style="width: 170px" />
                </td>
            </tr>
            <?php } ?>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Default Vote Rule", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_rules_combo("gdsr_default_vote_articles", $gdsr_options["default_voterules_articles"]); ?>
                </td>
                <td width="10"></td>
            <?php if ($gdsr_options["moderation_active"] == 1) { ?>
                <td width="150"><?php _e("Default Moderation Rule", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_moderation_combo("gdsr_default_mod_articles", $gdsr_options["default_moderation_articles"]); ?>
                </td>
            <?php } ?>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150" valign="top"><?php _e("Auto Insert Rating Code", "gd-star-rating"); ?>:</td>
                <td width="200" valign="top">
                    <input type="checkbox" name="gdsr_posts" id="gdsr_posts"<?php if ($gdsr_options["display_posts"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_posts"><?php _e("For individual posts.", "gd-star-rating"); ?></label>
                    <br />
                    <input type="checkbox" name="gdsr_pages" id="gdsr_pages"<?php if ($gdsr_options["display_pages"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_pages"><?php _e("For individual pages.", "gd-star-rating"); ?></label>
                </td>
                <td width="10"></td>
                <td valign="top">
                    <input type="checkbox" name="gdsr_archive" id="gdsr_archive"<?php if ($gdsr_options["display_archive"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_archive"><?php _e("For posts displayed in Archives.", "gd-star-rating"); ?></label>
                    <br />
                    <input type="checkbox" name="gdsr_home" id="gdsr_home"<?php if ($gdsr_options["display_home"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_home"><?php _e("For posts displayed on Front Page.", "gd-star-rating"); ?></label>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <input type="checkbox" name="gdsr_cookies" id="gdsr_cookies"<?php if ($gdsr_options["cookies"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_cookies"><?php _e("Save cookies to prevent duplicate voting.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_authorvote" id="gdsr_authorvote"<?php if ($gdsr_options["author_vote"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_authorvote"><?php _e("Prevent article author to vote.", "gd-star-rating"); ?> <?php _e("This is only for registered users.", "gd-star-rating"); ?></label>
    </td>
</tr>
<?php if ($gdsr_options["review_active"] == 1) { ?>
<tr><th scope="row"><?php _e("Review", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Stars", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <select style="width: 180px;" name="gdsr_review_style" id="gdsr_review_style">
                <?php GDSRHelper::render_styles_select($gdsr_styles, $gdsr_options["review_style"]); ?>
                </select>
                </td>
                <td width="10"></td>
                <td width="150" align="left">
                <?php GDSRHelper::render_star_sizes("gdsr_review_size", $gdsr_options["review_size"]); ?>
                </td>
                <td width="10"></td>
                <td width="100"><?php _e("Number Of Stars", "gd-star-rating"); ?>:</td>
                <td width="80" align="left">
                <select style="width: 70px;" name="gdsr_review_stars" id="gdsr_review_stars">
                <?php GDSRHelper::render_stars_select($gdsr_options["review_stars"]); ?>
                </select>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Rating Alignment", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_alignment("gdsr_review_align", $gdsr_options["review_align"]); ?>
                </td>
            </tr>
            <tr>
                <td width="150"><?php _e("Rating Header", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="checkbox" name="gdsr_review_header" id="gdsr_review_header"<?php if ($gdsr_options["review_header"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_review_header"><?php _e("Display header text.", "gd-star-rating"); ?></label>
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Text to display", "gd-star-rating"); ?>:</td>
                <td>
                    <input type="text" name="gdsr_review_header_text" id="gdsr_review_header_text" value="<?php echo wp_specialchars($gdsr_options["review_header_text"]); ?>" style="width: 170px" />
                </td>
            </tr>
            <tr>
                <td width="150"><?php _e("Rating Block CSS Class", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="text" name="gdsr_review_classblock" id="gdsr_review_classblock" value="<?php echo wp_specialchars($gdsr_options["review_class_block"]); ?>" style="width: 170px" />
                </td>
            </tr>
        </table>
    </td>
</tr>
<?php } ?>
</tbody></table>


</div>
<div id="fragment-3">

<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Rating", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Stars", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <select style="width: 180px;" name="gdsr_cmm_style" id="gdsr_cmm_style">
                <?php GDSRHelper::render_styles_select($gdsr_styles, $gdsr_options["cmm_style"]); ?>
                </select>
                </td>
                <td width="10"></td>
                <td width="150" align="left">
                <?php GDSRHelper::render_star_sizes("gdsr_cmm_size", $gdsr_options["cmm_size"]); ?>
                </td>
                <td width="10"></td>
                <td width="100"><?php _e("Number Of Stars", "gd-star-rating"); ?>:</td>
                <td width="80" align="left">
                <select style="width: 70px;" name="gdsr_cmm_stars" id="gdsr_cmm_stars">
                <?php GDSRHelper::render_stars_select($gdsr_options["cmm_stars"]); ?>
                </select>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Rating Alignment", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_alignment("gdsr_cmm_align", $gdsr_options["cmm_align"]); ?>
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Rating Text Placement", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_placement("gdsr_cmm_text", $gdsr_options["cmm_text"]); ?>
                </td>
            </tr>
            <tr>
                <td width="150"><?php _e("Rating Header", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="checkbox" name="gdsr_cmm_header" id="gdsr_cmm_header"<?php if ($gdsr_options["cmm_header"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_cmm_header"><?php _e("Display header text.", "gd-star-rating"); ?></label>
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Text to display", "gd-star-rating"); ?>:</td>
                <td>
                    <input type="text" name="gdsr_cmm_header_text" id="gdsr_cmm_header_text" value="<?php echo wp_specialchars($gdsr_options["cmm_header_text"]); ?>" style="width: 170px" />
                </td>
            </tr>
            <?php if ($gdsr_options["admin_advanced"] == 1) { ?>
            <tr>
                <td width="150"><?php _e("Rating Block CSS Class", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="text" name="gdsr_cmm_classblock" id="gdsr_cmm_classblock" value="<?php echo wp_specialchars($gdsr_options["cmm_class_block"]); ?>" style="width: 170px" />
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Rating Text CSS Class", "gd-star-rating"); ?>:</td>
                <td>
                    <input type="text" name="gdsr_cmm_classtext" id="gdsr_cmm_classtext" value="<?php echo wp_specialchars($gdsr_options["cmm_class_text"]); ?>" style="width: 170px" />
                </td>
            </tr>
            <?php } ?>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Default Vote Rule", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_rules_combo("gdsr_default_vote_comments", $gdsr_options["default_voterules_comments"]); ?>
                </td>
                <td width="10"></td>
            <?php if ($gdsr_options["moderation_active"] == 1) { ?>
                <td width="150"><?php _e("Default Moderation Rule", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_moderation_combo("gdsr_default_mod_comments", $gdsr_options["default_moderation_comments"]); ?>
                </td>
            <?php } ?>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150" valign="top"><?php _e("Auto Insert Rating Code", "gd-star-rating"); ?>:</td>
                <td valign="top" width="200">
                    <input type="checkbox" name="gdsr_dispcomment" id="gdsr_dispcomment"<?php if ($gdsr_options["display_comment"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_dispcomment"><?php _e("For comments.", "gd-star-rating"); ?></label>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <input type="checkbox" name="gdsr_cmm_cookies" id="gdsr_cmm_cookies"<?php if ($gdsr_options["cmm_cookies"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_cmm_cookies"><?php _e("Save cookies to prevent duplicate voting.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_cmm_authorvote" id="gdsr_cmm_authorvote"<?php if ($gdsr_options["cmm_author_vote"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_cmm_authorvote"><?php _e("Prevent comment author to vote.", "gd-star-rating"); ?> <?php _e("This is only for registered users.", "gd-star-rating"); ?></label>
    </td>
</tr>
<?php if ($gdsr_options["review_active"] == 1 && 1 == 2) { ?>
<tr><th scope="row"><?php _e("Review", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Stars", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <select style="width: 180px;" name="gdsr_cmm_review_style" id="gdsr_cmm_review_style">
                <?php GDSRHelper::render_styles_select($gdsr_styles, $gdsr_options["cmm_review_style"]); ?>
                </select>
                </td>
                <td width="10"></td>
                <td width="150" align="left">
                <?php GDSRHelper::render_star_sizes("gdsr_cmm_review_size", $gdsr_options["cmm_review_size"]); ?>
                </td>
                <td width="10"></td>
                <td width="100"><?php _e("Number Of Stars", "gd-star-rating"); ?>:</td>
                <td width="80" align="left">
                <select style="width: 70px;" name="gdsr_cmm_review_stars" id="gdsr_cmm_review_stars">
                <?php GDSRHelper::render_stars_select($gdsr_options["cmm_review_stars"]); ?>
                </select>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Rating Alignment", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_alignment("gdsr_cmm_review_align", $gdsr_options["cmm_review_align"]); ?>
                </td>
            </tr>
            <tr>
                <td width="150"><?php _e("Rating Header", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="checkbox" name="gdsr_cmm_review_header" id="gdsr_cmm_review_header"<?php if ($gdsr_options["cmm_review_header"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_review_header"><?php _e("Display header text.", "gd-star-rating"); ?></label>
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Text to display", "gd-star-rating"); ?>:</td>
                <td>
                    <input type="text" name="gdsr_cmm_review_header_text" id="gdsr_cmm_review_header_text" value="<?php echo wp_specialchars($gdsr_options["cmm_review_header_text"]); ?>" style="width: 170px" />
                </td>
            </tr>
            <tr>
                <td width="150"><?php _e("Rating Block CSS Class", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="text" name="gdsr_cmm_review_classblock" id="gdsr_cmm_review_classblock" value="<?php echo wp_specialchars($gdsr_options["cmm_review_class_block"]); ?>" style="width: 170px" />
                </td>
            </tr>
        </table>
    </td>
</tr>
<?php } ?>
</tbody></table>


</div>
</div>

</div>
<p class="submit"><input type="submit" value="<?php _e("Save Settings", "gd-star-rating"); ?>" name="gdsr_saving"/></p>
</div>
</form>

<script>
    gdsrStyleSelection(0);
</script>