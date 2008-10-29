<?php
    require_once(ABSPATH . WPINC . '/rss.php');
?>
<div class="wrap">
<h2>GD Star Rating</h2>

<?php

    $votes_articles = GDSRDB::front_page_article_totals();
    $votes_comments = GDSRDB::front_page_comment_totals();
    $moderation = GDSRDB::front_page_moderation_totals();
    
    $moderation_articles = 0;
    $moderation_comments = 0;
    
    if (is_array($moderation)) {
        foreach ($moderation as $m) {
            if ($m->vote_type == 'article')
                $moderate_articles = $m->queue;
            else
                $moderation_comments = $m->queue;
        }
    }
?>

<div id="rightnow">
    <h3 class="reallynow"><span>Quick Rating Facts:</span><br class="clear"/></h3>
    <p class="youhave">
    <?php 
        printf(__("Registered users rated %s articles with average rating of %s and %s comments with average rating of %s. Visitors rated %s articles with average rating of %s and %s comments with average rating of %s.", "gd-star-rating"), 
            '<strong>'.$votes_articles->votersu.'</strong>', 
            '<strong><span style="color: red">'.@number_format($votes_articles->votesu / $votes_articles->votersu, 1).'</span></strong>',
            '<strong>'.$votes_comments->votersu.'</strong>', 
            '<strong><span style="color: red">'.@number_format($votes_comments->votesu / $votes_comments->votersu, 1).'</span></strong>',
            '<strong>'.$votes_articles->votersv.'</strong>', 
            '<strong><span style="color: red">'.@number_format($votes_articles->votesv / $votes_articles->votersv, 1).'</span></strong>',
            '<strong>'.$votes_comments->votersv.'</strong>', 
            '<strong><span style="color: red">'.@number_format($votes_comments->votesv / $votes_comments->votersv, 1).'</span></strong>'
        );
        if ($options["moderation_active"] == 1)
            printf(__(" There are %s article and %s comments ratings waiting in moderation queue.", "gd-star-rating"), 
                '<strong><span style="color: red">'.$moderation_articles.'</span></strong>',
                '<strong><span style="color: red">'.$moderation_comments.'</span></strong>'
            );
    ?>
    </p>
    <p class="youare">
        Work in progress... <span style="color: red">Any ideas?</span>
    </p>
</div>

<div id="dashboard-widgets">
    <div id="dashboard_primary" class="dashboard-widget-holder widget_rss wp_dashboard_empty">
        <div class="dashboard-widget" style="height:35em">
        <h3 class="dashboard-widget-title"><span><?php _e("Latest News", "gd-star-rating") ?></span>
        <small>
            <a target="_blank" href="http://wp.gdragon.info/">See All</a> | <img class="rss-icon" alt="rss icon" src="<?php bloginfo('home'); echo '/'.WPINC; ?>/images/rss.png"/>
            <a href="http://wp.gdragon.info/feed/">RSS</a>
        </small>
        <br class="clear"/></h3>
        <div class="dashboard-widget-content">
        <?php
          $rss = fetch_rss('http://wp.gdragon.info/category/plugins/gd-star-rating/feed/');
          
          if ( isset($rss->items) && 0 != count($rss->items) )
          {
            echo '<ul>';
            $rss->items = array_slice($rss->items, 0, 3);
            foreach ($rss->items as $item)
            {
            ?>
              <li><a target="_blank" class="rsswidget" title='' href='<?php echo wp_filter_kses($item['link']); ?>'><?php echo wp_specialchars($item['title']); ?></a><span class="rss-date"><?php echo human_time_diff(strtotime($item['pubdate'], time())); ?></span>
              <div class="rssSummary"><?php echo '<strong>'.date("F, jS", strtotime($item['pubdate'])).'</strong> - '.$item['description']; ?></div></li>
            <?php
            }
            echo '</ul>';
          }
          else
          {
            ?>
            <p><?php printf(__("An error occured while loading newsfeed. Go to the %sfront page%s to check for updates.", "gd-star-rating"), '<a href="http://wp.gdragon.info/">', '</a>') ?></p>
            <?php
          }
        ?>
        </div>
        </div>
    </div>
    <div id="dashboard_plugins" class="dashboard-widget-holder wp_dashboard_empty">
        <div class="dashboard-widget" style="height:35em">
        <h3 class="dashboard-widget-title"><?php _e("Important Links", "gd-star-rating") ?></h3>
        <div class="dashboard-widget-content">
        </div>
        </div>
    </div>
    <div id="dashboard_secondary" class="dashboard-widget-holder full wp_dashboard_empty">
        <div class="dashboard-widget">
            <h3 class="dashboard-widget-title">
                <?php _e("Version", "gd-star-rating"); ?>: <font style="color: red;"><?php echo $options["version"]; ?></font> | 
                <?php _e("Status", "gd-star-rating"); ?>: <font style="color: red;"><?php echo $options["status"]; ?></font> | 
                <?php _e("Release Date", "gd-star-rating"); ?>: <font style="color: red;"><?php echo $options["date"]; ?></font>
            </h3>
        </div>
    </div>
    <br class="clear"/>
</div>


</div>