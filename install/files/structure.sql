CREATE TABLE IF NOT EXISTS `abs_ab_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `site` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `imps` int(11) DEFAULT '0',
  `clicks` int(11) DEFAULT '0',
  `earning` double(8,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UniqueFields` (`date`,`site`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_id` int(11) DEFAULT '0',
  `adnetwork_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` text COLLATE utf8_unicode_ci,
  `countries` text COLLATE utf8_unicode_ci,
  `order` tinyint(3) DEFAULT '1',
  `status` varchar(1) CHARACTER SET utf8 DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `block_content` text COLLATE utf8_unicode_ci,
  `status` char(1) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_categories` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seo_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `feed_tag_matching` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `featured` int(1) NOT NULL DEFAULT '0',
  `is_active` char(1) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT '0',
  `group` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `type` char(1) CHARACTER SET utf8 DEFAULT '0',
  `user_avatar` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `time` int(11) DEFAULT '0',
  `reviewed` char(1) COLLATE utf8_unicode_ci DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `response` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_favorite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `game_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_games` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `game_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seo_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `game_categories` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `game_description` text COLLATE utf8_unicode_ci,
  `game_meta_description` text COLLATE utf8_unicode_ci,
  `game_keywords` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `game_tags` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ribbon_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ribbon_expiration` int(11) DEFAULT '0',
  `game_instruction` text COLLATE utf8_unicode_ci,
  `game_controls` text COLLATE utf8_unicode_ci,
  `game_img` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `featured_img` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `game_file` text COLLATE utf8_unicode_ci NOT NULL,
  `game_url_parameters` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `game_show_slide` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `game_slide_image` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `game_width` smallint(4) NOT NULL DEFAULT '0',
  `game_height` smallint(4) NOT NULL DEFAULT '0',
  `game_is_featured` char(1) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `game_image_source` char(1) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `game_file_source` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `game_total_hits` int(11) NOT NULL DEFAULT '0',
  `game_today_hits` int(11) NOT NULL,
  `game_last_hit` int(11) NOT NULL DEFAULT '0',
  `game_rating` float NOT NULL DEFAULT '0',
  `game_votes` int(11) NOT NULL DEFAULT '0',
  `game_adddate` int(11) NOT NULL DEFAULT '0',
  `game_upddate` int(11) DEFAULT NULL,
  `game_source` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `game_source_id` int(11) DEFAULT '0',
  `game_is_active` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_games_broken` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `user` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  `status` char(1) CHARACTER SET utf8 DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_games_feed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) DEFAULT '0',
  `fsource` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seoname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flash_file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumbnail_100x100` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumbnail_150x150` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumbnail_180x135` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumbnail_90x120` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumbnail_hex` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `short_disc` text COLLATE utf8_unicode_ci,
  `full_disc` text COLLATE utf8_unicode_ci,
  `instruction` text CHARACTER SET utf8,
  `controls` text CHARACTER SET utf8,
  `tags` text COLLATE utf8_unicode_ci,
  `genres` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `width` int(6) DEFAULT '0',
  `height` int(6) DEFAULT '0',
  `has_ads` char(1) CHARACTER SET utf8 DEFAULT '0',
  `revenue_sharing` char(1) COLLATE utf8_unicode_ci DEFAULT '0',
  `create_time` int(11) DEFAULT '0',
  `create_date` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `insert_date` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `status` char(1) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewIndex` (`fid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_games_mobile` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `game_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seo_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `game_categories` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `game_description` text COLLATE utf8_unicode_ci NOT NULL,
  `game_meta_description` text COLLATE utf8_unicode_ci,
  `game_keywords` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `game_tags` text COLLATE utf8_unicode_ci NOT NULL,
  `game_instruction` text COLLATE utf8_unicode_ci NOT NULL,
  `game_controls` text COLLATE utf8_unicode_ci,
  `game_android_link` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `game_ios_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `game_html5_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `game_is_featured` int(5) NOT NULL DEFAULT '0',
  `game_img` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `featured_img` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `game_image_source` tinyint(3) NOT NULL DEFAULT '0',
  `game_total_hits` int(11) NOT NULL DEFAULT '0',
  `game_today_hits` int(11) NOT NULL DEFAULT '0',
  `game_last_hit` int(11) NOT NULL DEFAULT '0',
  `game_rating` float NOT NULL DEFAULT '0',
  `game_votes` int(11) NOT NULL DEFAULT '0',
  `game_adddate` int(11) NOT NULL DEFAULT '0',
  `game_upddate` int(11) DEFAULT NULL,
  `game_source` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `game_source_id` int(11) DEFAULT '0',
  `game_is_active` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_games_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) DEFAULT '0',
  `ip` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

CREATE TABLE IF NOT EXISTS `abs_games_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seo_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_iprange` (
  `ip` int(11) unsigned NOT NULL DEFAULT '0',
  `country` char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link_type` tinyint(4) DEFAULT '0',
  `local_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `local_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_page_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `partner_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `partner_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `partner_email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_check` int(11) DEFAULT '0',
  `link_exists` tinyint(3) DEFAULT NULL,
  `expire_date` varchar(10) CHARACTER SET utf8 DEFAULT '0',
  `insert_time` int(11) DEFAULT '0',
  `priority` tinyint(4) DEFAULT '0',
  `position` char(1) COLLATE utf8_unicode_ci DEFAULT '0',
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `group` tinyint(1) DEFAULT '0',
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quote` text COLLATE utf8_unicode_ci,
  `gender` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `msn` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `skipe` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `aim` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `yahoo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_talk` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastlogin` int(20) DEFAULT NULL,
  `login` int(11) DEFAULT '0',
  `regdate` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip_info_range` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip_info_country` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(3) DEFAULT '0',
  `confirm` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usernameUnique` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_members_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_pages` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `page_title` text COLLATE utf8_unicode_ci NOT NULL,
  `seo_title` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8_unicode_ci NOT NULL,
  `page_content` text COLLATE utf8_unicode_ci NOT NULL,
  `page_visit` int(11) DEFAULT '0',
  `page_access` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `status` char(3) COLLATE utf8_unicode_ci DEFAULT '0',
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_post_log` (
  `post_type` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `post_title` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `insert_time` int(11) DEFAULT '0',
  `ws_time` int(11) DEFAULT '0',
  `ws_date` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_read` char(1) CHARACTER SET utf8 DEFAULT '0',
  PRIMARY KEY (`post_type`,`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_settings` (
  `cat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `val` text COLLATE utf8_unicode_ci,
  `comment_en` tinytext COLLATE utf8_unicode_ci,
  `comment_fa` tinytext COLLATE utf8_unicode_ci,
  UNIQUE KEY `UniqueKeyField` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_submitted_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `game_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `game_categories` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `game_description` text COLLATE utf8_unicode_ci,
  `game_instruction` text COLLATE utf8_unicode_ci,
  `game_controls` text COLLATE utf8_unicode_ci,
  `game_tags` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `game_img` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `game_file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `game_width` smallint(4) DEFAULT NULL,
  `game_height` smallint(4) DEFAULT NULL,
  `addtime` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_trade_history` (
  `tid` int(11) NOT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `tier1_in` int(11) NOT NULL DEFAULT '0',
  `tier2_in` int(11) NOT NULL DEFAULT '0',
  `tier3_in` int(11) NOT NULL DEFAULT '0',
  `tier1_out` int(11) NOT NULL DEFAULT '0',
  `tier2_out` int(11) NOT NULL DEFAULT '0',
  `tier3_out` int(11) NOT NULL DEFAULT '0',
  `convert` int(11) NOT NULL DEFAULT '0',
  `pageview_avg` double(8,2) NOT NULL DEFAULT '0.00',
  `bounce_rate` double(8,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_trade_plugs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) DEFAULT '0',
  `tid` int(11) DEFAULT '0',
  `tdid` int(11) DEFAULT '0',
  `url` varchar(300) CHARACTER SET utf8 DEFAULT NULL,
  `status` char(1) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Unique` (`gid`,`tid`,`tdid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_traders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `trader_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tier1_in_today` int(11) NOT NULL DEFAULT '0',
  `tier2_in_today` int(11) NOT NULL DEFAULT '0',
  `tier3_in_today` int(11) NOT NULL DEFAULT '0',
  `tier1_out_today` int(11) NOT NULL DEFAULT '0',
  `tier2_out_today` int(11) NOT NULL DEFAULT '0',
  `tier3_out_today` int(11) NOT NULL DEFAULT '0',
  `tier1_in_overall` int(11) NOT NULL DEFAULT '0',
  `tier2_in_overall` int(11) NOT NULL DEFAULT '0',
  `tier3_in_overall` int(11) NOT NULL DEFAULT '0',
  `tier1_out_overall` int(11) NOT NULL DEFAULT '0',
  `tier2_out_overall` int(11) NOT NULL DEFAULT '0',
  `tier3_out_overall` int(11) NOT NULL DEFAULT '0',
  `convert_today` int(11) DEFAULT '0',
  `convert_overall` int(11) DEFAULT '0',
  `tier1_credits` double(10,2) DEFAULT '0.00',
  `tier2_credits` double(10,2) DEFAULT '0.00',
  `tier3_credits` double(10,2) DEFAULT '0.00',
  `trade_ratio` double(4,2) DEFAULT NULL,
  `daily_cap` int(11) DEFAULT '9999',
  `forced_hits` int(11) DEFAULT '0',
  `send_to_homepage` char(3) COLLATE utf8_unicode_ci DEFAULT '1',
  `speed` tinyint(3) DEFAULT '1',
  `status` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_traders_domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT '0',
  `site_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_traders_geo` (
  `tid` int(11) DEFAULT NULL,
  `country_code` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `in_today` int(11) DEFAULT '0',
  `out_today` int(11) DEFAULT '0',
  `in_total` int(11) DEFAULT '0',
  `out_total` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_visit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_code` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `referrer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_view` int(11) DEFAULT '0',
  `tier` char(1) COLLATE utf8_unicode_ci DEFAULT '3',
  `date` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `ctime` int(11) DEFAULT '0',
  `utime` int(11) DEFAULT '0',
  `trader_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_fields` (`ip`,`referrer`,`date`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_visit_countries_daily` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_code` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visit` int(11) DEFAULT '0',
  `pageview` int(11) DEFAULT '0',
  `pageview_avg` double(8,2) DEFAULT '0.00',
  `bounce_rate` double(8,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_visit_daily` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tier1_visit` int(11) DEFAULT NULL,
  `tier2_visit` int(11) DEFAULT NULL,
  `tier3_visit` int(11) DEFAULT NULL,
  `tier1_pageview` int(11) DEFAULT NULL,
  `tier2_pageview` int(11) DEFAULT NULL,
  `tier3_pageview` int(11) DEFAULT NULL,
  `pageview_avg` float DEFAULT NULL,
  `bounce_rate` float DEFAULT NULL,
  `game_hits` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `abs_zones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adsize` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_ad` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

