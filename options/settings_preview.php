<script>
function gdsrStyleSelection(preview) {
    var gdsrAllImages = [ <?php GDSRHelper::render_styles_js($gdsr_styles); ?> ];
    var gdsrAllTypes = [ <?php GDSRHelper::render_styles_types_js($gdsr_styles); ?> ];

    var gdsrAllTrendsImages = [ <?php GDSRHelper::render_styles_js($gdsr_trends); ?> ];
    var gdsrAllTrendsTypes = [ <?php GDSRHelper::render_styles_types_js($gdsr_trends); ?> ];
    
    var gdsrBase = "#gdsr_preview";
    var gdsrStyle = "";
    var gdsrSize = "";
    var gdsrImage = "";
    
    if (preview == "trends") {
        gdsrBase = gdsrBase + "_trends";
        gdsrStyle = jQuery("#gdsr_style_preview_trends").val();
        gdsrImage = "<?php echo $gdsr_root_url ?>trends/" + gdsrAllTrendsImages[gdsrStyle] + "/trend" + "." + gdsrAllTrendsTypes[gdsrStyle];;
    }
    else {
        gdsrStyle = jQuery("#gdsr_style_preview").val();
        gdsrSize = jQuery("#gdsr_size_preview").val();
        gdsrImage = "<?php echo $gdsr_root_url ?>stars/" + gdsrAllImages[gdsrStyle] + "/stars" + gdsrSize + "." + gdsrAllTypes[gdsrStyle];
    }
    
    jQuery(gdsrBase+"_black").attr("src", gdsrImage);
    jQuery(gdsrBase+"_red").attr("src", gdsrImage);
    jQuery(gdsrBase+"_green").attr("src", gdsrImage);
    jQuery(gdsrBase+"_white").attr("src", gdsrImage);
    jQuery(gdsrBase+"_blue").attr("src", gdsrImage);
    jQuery(gdsrBase+"_yellow").attr("src", gdsrImage);
    jQuery(gdsrBase+"_gray").attr("src", gdsrImage);
    jQuery(gdsrBase+"_picture").attr("src", gdsrImage);
}

function gdsrShowHidePreview(what) {
    var preview = document.getElementById("gdsr-preview-"+what);
    var message = document.getElementById("gdsr-preview-"+what+"-msg");
    var hidden = document.getElementById("gdsr_preview_"+what);
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

<input type="hidden" id="gdsr_preview_stars" name="gdsr_preview_stars" value="<?php echo $gdsr_options["preview_active"]; ?>" />
<input type="hidden" id="gdsr_preview_trends" name="gdsr_preview_trends" value="<?php echo $gdsr_options["preview_trends_active"]; ?>" />

<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Update Sets", "gd-star-rating"); ?></th>
    <td>
    </td>
</tr>
<tr><th scope="row"><a href="javascript:gdsrShowHidePreview('stars')"><?php _e("Stars", "gd-star-rating"); ?></a></th>
    <td><div id="gdsr-preview-stars" style="display: <?php echo $gdsr_options["preview_active"] == 1 ? "block" : "none" ?>;">
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td width="150" style="padding: 0; border: 0; height: 28px; vertical-align: top;"><?php _e("Stars", "gd-star-rating"); ?>:</td>
                <td width="200" align="left" style="padding: 0; border: 0; vertical-align: top;">
                    <select style="width: 180px;" name="gdsr_style_preview" id="gdsr_style_preview" onchange="gdsrStyleSelection('stars')">
                        <?php GDSRHelper::render_styles_select($gdsr_styles); ?>
                    </select>
                </td>
                <td width="20" style="padding: 0; border: 0; vertical-align: top;" rowspan="2"></td>
                <td style="padding: 0; border: 0; vertical-align: top;" rowspan="2">
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
                    <?php GDSRHelper::render_star_sizes("gdsr_size_preview", 30, 180, ' onchange="gdsrStyleSelection(\'stars\')"'); ?>
                </td>
            </tr>
        </table>
        </div>
        <div id="gdsr-preview-stars-msg" style="display: <?php echo $gdsr_options["preview_active"] == 1 ? "none" : "block" ?>;">
            <?php _e("Click on this section header title to show preview panel.", "gd-star-rating"); ?>
        </div>
    </td>
</tr>
<tr><th scope="row"><a href="javascript:gdsrShowHidePreview('trends')"><?php _e("Trends", "gd-star-rating"); ?></a></th>
    <td><div id="gdsr-preview-trends" style="display: <?php echo $gdsr_options["preview_trends_active"] == 1 ? "block" : "none" ?>;">

        <table cellpadding="0" cellspacing="0">
            <tr>
                <td width="150" style="padding: 0; border: 0; height: 28px; vertical-align: top;"><?php _e("Trends", "gd-star-rating"); ?>:</td>
                <td width="200" align="left" style="padding: 0; border: 0; vertical-align: top;">
                    <select style="width: 180px;" name="gdsr_style_preview_trends" id="gdsr_style_preview_trends" onchange="gdsrStyleSelection('trends')">
                        <?php GDSRHelper::render_styles_select($gdsr_trends); ?>
                    </select>
                </td>
                <td width="20" style="padding: 0; border: 0; vertical-align: top;" rowspan="2"></td>
                <td style="padding: 0; border: 0; vertical-align: top;">
                    <table cellpadding="0" width="400" cellspacing="0" class="previewtable">
                        <tr>
                            <td class="gdsr-preview-trends" style="background-color: black;"><img src="#" id="gdsr_preview_trends_black" /></td>
                            <td class="gdsr-preview-space"></td>
                            <td class="gdsr-preview-trends" style="background-color: red;"><img src="#" id="gdsr_preview_trends_red" /></td>
                            <td class="gdsr-preview-space"></td>
                            <td class="gdsr-preview-trends" style="background-color: green;"><img src="#" id="gdsr_preview_trends_green" /></td>
                            <td class="gdsr-preview-space"></td>
                            <td class="gdsr-preview-trends" style="background-color: white;"><img src="#" id="gdsr_preview_trends_white" /></td>
                            <td class="gdsr-preview-space"></td>
                            <td class="gdsr-preview-trends" style="background-color: blue;"><img src="#" id="gdsr_preview_trends_blue" /></td>
                            <td class="gdsr-preview-space"></td>
                            <td class="gdsr-preview-trends" style="background-color: yellow;"><img src="#" id="gdsr_preview_trends_yellow" /></td>
                            <td class="gdsr-preview-space"></td>
                            <td class="gdsr-preview-trends" style="background-color: gray;"><img src="#" id="gdsr_preview_trends_gray" /></td>
                            <td class="gdsr-preview-space" ></td>
                            <td class="gdsr-preview-trends gdsr-preview-pic"><img src="#" id="gdsr_preview_trends_picture" /></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        </div>
        <div id="gdsr-preview-trends-msg" style="display: <?php echo $gdsr_options["preview_trends_active"] == 1 ? "none" : "block" ?>;">
            <?php _e("Click on this section header title to show preview panel.", "gd-star-rating"); ?>
        </div>
    </td>
</tr>
</tbody></table>
