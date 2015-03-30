# Introduction #

Here is the list of SQL expressions for creating tables for plugin. Since WordPress uses table prefix, all queries here use prefix _wp, so if you use something else, you need to change this._

## Table structure for table gdsr\_data\_article ##

```
CREATE TABLE `wp_gdsr_data_article` (
  `post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `rules_articles` char(1) DEFAULT 'A,
  `rules_comments` char(1) DEFAULT 'A',
  `moderate_articles` char(1) DEFAULT 'N',
  `moderate_comments` char(1) DEFAULT 'N',
  `is_page` char(1) DEFAULT '0',
  `user_voters` int(11) DEFAULT '0',
  `user_votes` decimal(11,1) DEFAULT '0.0',
  `visitor_voters` int(11) DEFAULT '0',
  `visitor_votes` decimal(11,1) DEFAULT '0.0',
  `review` decimal(3,1) DEFAULT '-1.0',
  `review_text` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT '0',
  `user_recc_plus` int(11) DEFAULT '0',
  `user_recc_minus` int(11) DEFAULT '0',
  `visitor_recc_plus` int(11) DEFAULT '0',
  `visitor_recc_minus` int(11) DEFAULT '0',
  `expiry_type` char(1) NOT NULL DEFAULT 'N',
  `expiry_value` varchar(32) NOT NULL DEFAULT '',
  `last_voted` timestamp NULL DEFAULT NULL,
  `last_voted_recc` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_id`)
);
```

## Table structure for table gdsr\_data\_category ##

```
CREATE TABLE `wp_gdsr_data_category` (
  `category_id` int(11) unsigned NOT NULL DEFAULT '0',
  `rules_articles` char(1) DEFAULT '0',
  `rules_comments` char(1) DEFAULT '0',
  `moderate_articles` char(1) DEFAULT '0',
  `moderate_comments` char(1) DEFAULT '0',
  `expiry_type` char(1) NOT NULL DEFAULT 'N',
  `expiry_value` varchar(32) NOT NULL DEFAULT '',
  `cmm_integration_set` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`)
);
```

## Table structure for table gdsr\_data\_comment ##

```
CREATE TABLE `wp_gdsr_data_comment` (
  `comment_id` int(11) unsigned NOT NULL DEFAULT '0',
  `post_id` int(11) DEFAULT '-1',
  `is_locked` char(1) DEFAULT '0',
  `user_voters` int(11) DEFAULT '0',
  `user_votes` decimal(11,1) DEFAULT '0.0',
  `visitor_voters` int(11) DEFAULT '0',
  `visitor_votes` decimal(11,1) DEFAULT '0.0',
  `review` decimal(3,1) DEFAULT '-1.0',
  `review_text` varchar(255) DEFAULT NULL,
  `user_recc_plus` int(11) DEFAULT '0',
  `user_recc_minus` int(11) DEFAULT '0',
  `visitor_recc_plus` int(11) DEFAULT '0',
  `visitor_recc_minus` int(11) DEFAULT '0',
  `last_voted` timestamp NULL DEFAULT NULL,
  `last_voted_recc` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`comment_id`)
);
```

## Table structure for table gdsr\_ips ##

```
CREATE TABLE `wp_gdsr_ips` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `status` varchar(1) default 'B',
  `mode` varchar(1) default 'S',
  `ip` varchar(128) default NULL,
  PRIMARY KEY  (`id`)
);
```

## Table structure for table gdsr\_moderate ##

```
CREATE TABLE `wp_gdsr_moderate` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) DEFAULT NULL,
  `vote_type` varchar(10) DEFAULT 'article',
  `multi_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `vote` int(11) DEFAULT '0',
  `object` text NOT NULL,
  `voted` datetime DEFAULT NULL,
  `ip` varchar(32) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`record_id`),
  KEY idx_id_mod (id),
  KEY idx_vote_mod (vote_type),
  KEY idx_multi_mod (multi_id),
  KEY idx_user_mod (user_id)
);
```

## Table structure for table gdsr\_multis ##

```
CREATE TABLE `wp_gdsr_multis` (
  `multi_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `stars` int(11) NOT NULL DEFAULT '10',
  `object` text NOT NULL,
  `weight` text NOT NULL,
  `auto_insert` varchar(4) NOT NULL DEFAULT 'no',
  `auto_location` varchar(8) NOT NULL DEFAULT 'bottom',
  `auto_categories` text NOT NULL,
  `rules` char(1) DEFAULT 'A',
  `moderate` char(1) DEFAULT 'N',
  PRIMARY KEY (`multi_id`)
);
```

## Table structure for table gdsr\_multis\_data ##

```
CREATE TABLE `wp_gdsr_multis_data` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `multi_id` int(11) NOT NULL,
  `average_rating_users` decimal(3,1) NOT NULL DEFAULT '0.0',
  `average_rating_visitors` decimal(3,1) NOT NULL DEFAULT '0.0',
  `total_votes_users` int(11) NOT NULL DEFAULT '0',
  `total_votes_visitors` int(11) NOT NULL DEFAULT '0',
  `average_review` decimal(3,1) NOT NULL DEFAULT '0.0',
  `last_voted` timestamp NULL DEFAULT NULL,
  `rules` char(1) DEFAULT 'A',
  `moderate` char(1) DEFAULT 'N',
  `expiry_type` char(1) NOT NULL DEFAULT 'N',
  `expiry_value` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY post_id (post_id),
  KEY idx_post_mdt (post_id),
  KEY idx_multi_mdt (multi_id)
);
```

## Table structure for table gdsr\_multis\_trend ##

```
CREATE TABLE `wp_gdsr_multis_trend` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `multi_id` int(11) NOT NULL,
  `vote_date` varchar(10) DEFAULT NULL,
  `average_rating_users` decimal(3,1) NOT NULL DEFAULT '0.0',
  `average_rating_visitors` decimal(3,1) NOT NULL DEFAULT '0.0',
  `total_votes_users` int(11) NOT NULL DEFAULT '0',
  `total_votes_visitors` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY idx_post_mtt (post_id),
  KEY idx_multi_mtt (multi_id)
);
```

## Table structure for table gdsr\_multis\_values ##

```
CREATE TABLE `wp_gdsr_multis_values` (
  `id` bigint(20) unsigned NOT NULL,
  `source` varchar(3) NOT NULL DEFAULT 'dta',
  `item_id` int(11) NOT NULL,
  `user_voters` int(11) DEFAULT '0',
  `user_votes` decimal(11,1) DEFAULT '0.0',
  `visitor_voters` int(11) DEFAULT '0',
  `visitor_votes` decimal(11,1) DEFAULT '0.0',
  KEY `id` (`id`),
  KEY `item_id` (`item_id`),
  KEY `source` (`source`)
);
```

## Table structure for table gdsr\_templates ##

```
CREATE TABLE `wp_gdsr_templates` (
  `template_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(3) NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `elements` text NOT NULL,
  `dependencies` text NOT NULL,
  `preinstalled` varchar(1) NOT NULL DEFAULT '0',
  `default` varchar(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`template_id`)
);
```

## Table structure for table gdsr\_votes\_log ##

```
CREATE TABLE `wp_gdsr_votes_log` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) DEFAULT NULL,
  `vote_type` varchar(10) DEFAULT 'article',
  `multi_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `vote` int(11) DEFAULT '0',
  `object` text NOT NULL,
  `voted` datetime DEFAULT NULL,
  `ip` varchar(32) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `comment_id` bigint(20) unsigned DEFAULT '0',
  PRIMARY KEY (`record_id`),
  KEY idx_id_log (id),
  KEY idx_vote_log (vote_type),
  KEY idx_multi_log (multi_id),
  KEY idx_user_log (user_id)
);
```

## Table structure for table gdsr\_votes\_trend ##

```
CREATE TABLE `wp_gdsr_votes_trend` (
  `id` int(11) DEFAULT NULL,
  `vote_type` varchar(10) DEFAULT 'article',
  `user_voters` int(11) DEFAULT '0',
  `user_votes` int(11) DEFAULT '0',
  `visitor_voters` int(11) DEFAULT '0',
  `visitor_votes` int(11) DEFAULT '0',
  `vote_date` varchar(10) DEFAULT NULL,
  KEY idx_id_trd (id),
  KEY idx_vote_trd (vote_type)
);
```