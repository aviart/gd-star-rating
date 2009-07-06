<tr><th scope="row"><?php _e("Spider BOT's", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="250" valign="top">
                <textarea style="width: 230px; height: 170px;" name="gdsr_bots"><?php echo join("\r\n", $gdsr_bots); ?></textarea>
                </td>
                <td valign="top">
                <?php _e("Each line must contain only one BOT name to ensure proper detection of search engines.", "gd-star-rating"); ?>
                </td>
            </tr>
        </table>
    </td>
</tr>
</tbody></table>
