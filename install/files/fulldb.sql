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

--
-- Dumping data for table `abs_ads`
--

INSERT INTO `abs_ads` (`id`, `zone_id`, `adnetwork_title`, `code`, `countries`, `order`, `status`) VALUES
(1, 1, 'GameCPM', '<iframe src="http://www.emzikids.com/abs_ad/728x90.html" width="728" height="90" frameborder="0" scrolling="no"></iframe>', 'ad,ae,af,ag,ai,al,am,an,ao,ap,aq,ar,as,at,au,aw,ax,az,ba,bb,bd,be,bf,bg,bh,bi,bj,bm,bn,bo,br,bs,bt,bv,bw,by,bz,ca,cc,cd,cf,cg,ch,ci,ck,cl,cm,cn,co,cr,cs,cu,cv,cx,cy,cz,de,dj,dk,dm,do,dz,ec,ee,eg,eh,er,es,et,eu,fi,fj,fk,fm,fo,fr,ga,gd,ge,gf,gh,gi,gl,gm,gn,gp,gq,gr,gs,gt,gu,gw,gy,hk,hm,hn,hr,ht,hu,id,ie,il,in,io,iq,ir,is,it,jm,jo,jp,ke,kg,kh,ki,km,kn,kp,kr,kw,ky,kz,la,lb,lc,li,lk,lr,ls,lt,lu,lv,ly,ma,mc,md,me,mg,mh,mk,ml,mm,mn,mo,mp,mq,mr,ms,mt,mu,mv,mw,mx,my,mz,na,nc,ne,nf,ng,ni,nl,no,np,nr,nt,nu,nz,om,pa,pe,pf,pg,ph,pk,pl,pm,pn,pr,ps,pt,pw,py,qa,re,ro,rs,ru,rw,sa,sb,sc,sd,se,sg,sh,si,sj,sk,sl,sm,sn,so,sr,st,sv,sy,sz,tc,td,tf,tg,th,tj,tk,tl,tm,tn,to,tr,tt,tv,tw,tz,ua,ug,uk,us,uy,uz,va,vc,ve,vg,vi,vn,vu,wf,ws,ye,yt,yu,za,zm,zw', 0, '1'),
(2, 2, 'GameCPM', '<iframe src="http://www.emzikids.com/abs_ad/300x250.html" width="300" height="250" frameborder="0" scrolling="no"></iframe>', 'ad,ae,af,ag,ai,al,am,an,ao,ap,aq,ar,as,at,au,aw,ax,az,ba,bb,bd,be,bf,bg,bh,bi,bj,bm,bn,bo,br,bs,bt,bv,bw,by,bz,ca,cc,cd,cf,cg,ch,ci,ck,cl,cm,cn,co,cr,cs,cu,cv,cx,cy,cz,de,dj,dk,dm,do,dz,ec,ee,eg,eh,er,es,et,eu,fi,fj,fk,fm,fo,fr,ga,gd,ge,gf,gh,gi,gl,gm,gn,gp,gq,gr,gs,gt,gu,gw,gy,hk,hm,hn,hr,ht,hu,id,ie,il,in,io,iq,ir,is,it,jm,jo,jp,ke,kg,kh,ki,km,kn,kp,kr,kw,ky,kz,la,lb,lc,li,lk,lr,ls,lt,lu,lv,ly,ma,mc,md,me,mg,mh,mk,ml,mm,mn,mo,mp,mq,mr,ms,mt,mu,mv,mw,mx,my,mz,na,nc,ne,nf,ng,ni,nl,no,np,nr,nt,nu,nz,om,pa,pe,pf,pg,ph,pk,pl,pm,pn,pr,ps,pt,pw,py,qa,re,ro,rs,ru,rw,sa,sb,sc,sd,se,sg,sh,si,sj,sk,sl,sm,sn,so,sr,st,sv,sy,sz,tc,td,tf,tg,th,tj,tk,tl,tm,tn,to,tr,tt,tv,tw,tz,ua,ug,uk,us,uy,uz,va,vc,ve,vg,vi,vn,vu,wf,ws,ye,yt,yu,za,zm,zw', 0, '1'),
(3, 3, 'GameCPM', '<iframe src="http://www.emzikids.com/abs_ad/336x280.html" width="336" height="280" frameborder="0" scrolling="no"></iframe>', 'ad,ae,af,ag,ai,al,am,an,ao,ap,aq,ar,as,at,au,aw,ax,az,ba,bb,bd,be,bf,bg,bh,bi,bj,bm,bn,bo,br,bs,bt,bv,bw,by,bz,ca,cc,cd,cf,cg,ch,ci,ck,cl,cm,cn,co,cr,cs,cu,cv,cx,cy,cz,de,dj,dk,dm,do,dz,ec,ee,eg,eh,er,es,et,eu,fi,fj,fk,fm,fo,fr,ga,gd,ge,gf,gh,gi,gl,gm,gn,gp,gq,gr,gs,gt,gu,gw,gy,hk,hm,hn,hr,ht,hu,id,ie,il,in,io,iq,ir,is,it,jm,jo,jp,ke,kg,kh,ki,km,kn,kp,kr,kw,ky,kz,la,lb,lc,li,lk,lr,ls,lt,lu,lv,ly,ma,mc,md,me,mg,mh,mk,ml,mm,mn,mo,mp,mq,mr,ms,mt,mu,mv,mw,mx,my,mz,na,nc,ne,nf,ng,ni,nl,no,np,nr,nt,nu,nz,om,pa,pe,pf,pg,ph,pk,pl,pm,pn,pr,ps,pt,pw,py,qa,re,ro,rs,ru,rw,sa,sb,sc,sd,se,sg,sh,si,sj,sk,sl,sm,sn,so,sr,st,sv,sy,sz,tc,td,tf,tg,th,tj,tk,tl,tm,tn,to,tr,tt,tv,tw,tz,ua,ug,uk,us,uy,uz,va,vc,ve,vg,vi,vn,vu,wf,ws,ye,yt,yu,za,zm,zw', 0, '1'),
(4, 4, 'GameCPM', '<iframe src="http://www.emzikids.com/abs_ad/160x600.html" width="160" height="600" frameborder="0" scrolling="no"></iframe>', 'ad,ae,af,ag,ai,al,am,an,ao,ap,aq,ar,as,at,au,aw,ax,az,ba,bb,bd,be,bf,bg,bh,bi,bj,bm,bn,bo,br,bs,bt,bv,bw,by,bz,ca,cc,cd,cf,cg,ch,ci,ck,cl,cm,cn,co,cr,cs,cu,cv,cx,cy,cz,de,dj,dk,dm,do,dz,ec,ee,eg,eh,er,es,et,eu,fi,fj,fk,fm,fo,fr,ga,gd,ge,gf,gh,gi,gl,gm,gn,gp,gq,gr,gs,gt,gu,gw,gy,hk,hm,hn,hr,ht,hu,id,ie,il,in,io,iq,ir,is,it,jm,jo,jp,ke,kg,kh,ki,km,kn,kp,kr,kw,ky,kz,la,lb,lc,li,lk,lr,ls,lt,lu,lv,ly,ma,mc,md,me,mg,mh,mk,ml,mm,mn,mo,mp,mq,mr,ms,mt,mu,mv,mw,mx,my,mz,na,nc,ne,nf,ng,ni,nl,no,np,nr,nt,nu,nz,om,pa,pe,pf,pg,ph,pk,pl,pm,pn,pr,ps,pt,pw,py,qa,re,ro,rs,ru,rw,sa,sb,sc,sd,se,sg,sh,si,sj,sk,sl,sm,sn,so,sr,st,sv,sy,sz,tc,td,tf,tg,th,tj,tk,tl,tm,tn,to,tr,tt,tv,tw,tz,ua,ug,uk,us,uy,uz,va,vc,ve,vg,vi,vn,vu,wf,ws,ye,yt,yu,za,zm,zw', 0, '1'),
(5, 5, 'GameCPM', '<iframe src="http://www.emzikids.com/abs_ad/468x60.html" width="468" height="60" frameborder="0" scrolling="no"></iframe>', 'ad,ae,af,ag,ai,al,am,an,ao,ap,aq,ar,as,at,au,aw,ax,az,ba,bb,bd,be,bf,bg,bh,bi,bj,bm,bn,bo,br,bs,bt,bv,bw,by,bz,ca,cc,cd,cf,cg,ch,ci,ck,cl,cm,cn,co,cr,cs,cu,cv,cx,cy,cz,de,dj,dk,dm,do,dz,ec,ee,eg,eh,er,es,et,eu,fi,fj,fk,fm,fo,fr,ga,gd,ge,gf,gh,gi,gl,gm,gn,gp,gq,gr,gs,gt,gu,gw,gy,hk,hm,hn,hr,ht,hu,id,ie,il,in,io,iq,ir,is,it,jm,jo,jp,ke,kg,kh,ki,km,kn,kp,kr,kw,ky,kz,la,lb,lc,li,lk,lr,ls,lt,lu,lv,ly,ma,mc,md,me,mg,mh,mk,ml,mm,mn,mo,mp,mq,mr,ms,mt,mu,mv,mw,mx,my,mz,na,nc,ne,nf,ng,ni,nl,no,np,nr,nt,nu,nz,om,pa,pe,pf,pg,ph,pk,pl,pm,pn,pr,ps,pt,pw,py,qa,re,ro,rs,ru,rw,sa,sb,sc,sd,se,sg,sh,si,sj,sk,sl,sm,sn,so,sr,st,sv,sy,sz,tc,td,tf,tg,th,tj,tk,tl,tm,tn,to,tr,tt,tv,tw,tz,ua,ug,uk,us,uy,uz,va,vc,ve,vg,vi,vn,vu,wf,ws,ye,yt,yu,za,zm,zw', 0, '1');


--
-- Dumping data for table `abs_blocks`
--

INSERT INTO `abs_blocks` (`id`, `block_title`, `block_content`, `status`) VALUES
(1, 'Homepage About Us Text', '<p>Here you can put the text...</p>', '1');

--
-- Dumping data for table `abs_links`
--

INSERT INTO `abs_links` (`id`, `link_type`, `local_title`, `local_url`, `show_page_url`, `partner_title`, `partner_url`, `partner_email`, `last_check`, `link_exists`, `expire_date`, `insert_time`, `priority`, `position`, `status`) VALUES
(1, 1, NULL, NULL, NULL, 'Top free online games', 'http://emzigames.com', 'iraitc@gmail.com', 0, NULL, '2040-12-31', 0, 1, '3', 1),
(2, 1, NULL, NULL, NULL, 'Free online games for kids', 'http://emzikids.com', 'iraitc@gmail.com', 0, NULL, '2040-12-31', 0, 1, '3', 1);

--
--
-- Dumping data for table `abs_members_group`
--

INSERT INTO `abs_members_group` (`id`, `group_name`) VALUES
(1, 'Administrator'),
(2, 'Site Users'),
(3, 'Admins Group'),
(4, 'Game Editor'),
(5, 'FaceBook Members'),
(6, 'Google Members');


--
-- Dumping data for table `abs_pages`
--

INSERT INTO `abs_pages` (`pid`, `page_title`, `seo_title`, `meta_keywords`, `meta_description`, `page_content`, `page_visit`, `page_access`, `status`) VALUES
(1, 'About us', 'about-us', '', '', '<p>Here you can put the text about your site</p>', 0, '1', '1'),
(2, 'Privacy Policy', 'privacy-policy', '', '', '<p>Here you can put your privacy policy</p>', 0, '1', '1'),
(3, 'Terms and Conditions', 'terms-and-conditions', '', '', '<p>Here you can put the terms and conditions...</p>', 0, '1', '1');


--
-- Dumping data for table `abs_settings`
--

INSERT INTO `abs_settings` (`cat`, `key`, `val`, `comment_en`, `comment_fa`) VALUES
	('sitemap', 'sitemap_generating', 'on', NULL, NULL),
	('sitemap', 'sitemap_file_name', 'sitemap.xml', NULL, NULL),
	('members', 'membership_system', 'on', NULL, NULL),
	('members', 'membership_approval_system', 'auto', NULL, NULL),
	('members', 'members_avatar_uploading', '1', NULL, NULL),
	('members', 'members_max_avatar_filesize', '50', NULL, NULL),
	('members', 'members_banned_ips', '', NULL, NULL),
	('members', 'members_captcha_system', 'enable', NULL, NULL),
	('trade', 'active_trading', 'off', NULL, NULL),
	('trade', 'max_visitor_played', '4', NULL, NULL),
	('trade', 'default_trade_ratio', '1.2', NULL, NULL),
	('trade', 'trade_recive_page', 'pre', NULL, NULL),
	('trade', 'send_url_if_no_trader', '', NULL, NULL),
	('main', 'site_name', 'Your Site Title', NULL, NULL),
	('main', 'site_template', 'default', NULL, NULL),
	('main', 'smtp_email_from', '', NULL, NULL),
	('main', 'smtp_email_from_name', '', NULL, NULL),
	('main', 'site_language', 'en', NULL, NULL),
	('main', 'show_prepage', '1', NULL, NULL),
	('main', 'geo_stats', '0', NULL, NULL),
	('main', 'close_site', '0', NULL, NULL),
	('main', 'close_site_messages', '<B>Closed for maintenance</b><br>\r\nThis site is temporarily closed for maintenance<br>\r\nWe apologize for any inconvenience<br>\r\nThank you for your patience.<br>', NULL, NULL),
	('main', 'getdimension_after_uploading', '1', NULL, NULL),
	('feed', 'feed_auto_downloader', 'enable', NULL, NULL),
	('feed', 'feed_thumb_size', '150x150', NULL, NULL),
	('feed', 'daily_game_installation', '5', NULL, NULL),
	('cache', 'cache', 'enable', NULL, NULL),
	('cache', 'cache_time', '1800', NULL, NULL),
	('cache', 'images_cdn', '', NULL, NULL),
	('cache', 'js_cdn', '', NULL, NULL),
	('cache', 'css_cdn', '', NULL, NULL),
	('seo', 'seo_homepage_title', 'ArcadeBooster Games', NULL, NULL),
	('seo', 'seo_homepage_keywords', 'play, online games, flash games, arcade', NULL, NULL),
	('seo', 'seo_homepage_description', '', NULL, NULL),
	('seo', 'seo_new_games_page_heading', 'Latest Games', NULL, NULL),
	('seo', 'seo_new_games_page_title', 'Latest Games - {site_name}', NULL, NULL),
	('seo', 'seo_new_games_page_keywords', '', NULL, NULL),
	('seo', 'seo_new_games_page_description', '', NULL, NULL),
	('seo', 'seo_category_page_title', '{category} Games - Page {page_number} - {site_name}', NULL, NULL),
	('seo', 'seo_category_page_keywords', '{category_tags}', NULL, NULL),
	('seo', 'seo_category_page_description', '{category_desc}', NULL, NULL),
	('seo', 'seo_popular_games_page_heading', 'Popular Games', NULL, NULL),
	('seo', 'seo_popular_games_page_title', 'Popular Games - {site_name}', NULL, NULL),
	('seo', 'seo_popular_games_page_keywords', '', NULL, NULL),
	('seo', 'seo_popular_games_page_description', '', NULL, NULL),
	('seo', 'seo_top_rated_games_page_heading', 'Top Rated Games', NULL, NULL),
	('seo', 'seo_top_rated_games_page_title', 'Top Rated Games - {site_name}', NULL, NULL),
	('seo', 'seo_top_rated_games_page_keywords', '', NULL, NULL),
	('seo', 'seo_top_rated_games_page_description', '', NULL, NULL),
	('seo', 'seo_pre-play_page_heading', 'Play {game_title}', NULL, NULL),
	('seo', 'seo_pre-play_page_title', 'Play {game_title} - {site_name}', NULL, NULL),
	('seo', 'seo_pre-play_page_keywords', 'play, {game_tags}, {site_name}', NULL, NULL),
	('seo', 'seo_pre-play_page_description', '{game_desc}', NULL, NULL),
	('seo', 'seo_play_page_heading', 'Play {game_title}', NULL, NULL),
	('seo', 'seo_play_page_title', 'Play {game_title} - {site_name}', NULL, NULL),
	('seo', 'seo_play_page_keywords', 'play, {game_tags}, {site_name}', NULL, NULL),
	('seo', 'seo_play_page_description', '{game_desc}', NULL, NULL),
	('seo', 'seo_search_page_title', 'Search Results for \"{search_text}\"', NULL, NULL),
	('seo', 'seo_search_page_keywords', '[seo_homepage_keywords]', NULL, NULL),
	('seo', 'seo_search_page_description', '[seo_homepage_description]', NULL, NULL),
	('seo', 'seo_search_page_heading', '{site_name} - Search Results for {search_text}', NULL, NULL),
	('seo', 'seo_category_new_games_heading', 'Latest {category_title} Games', NULL, NULL),
	('seo', 'seo_category_popular_games_heading', 'Popular {category_title} Games', NULL, NULL),
	('seo', 'seo_category_top_rated_games_heading', 'Top Rated {category_title} Games', NULL, NULL),
	('seo', 'seo_category_more_games', 'More {category_title} Games', NULL, NULL),
   ('seo', 'seo_tag_page_title', '{tag_name} Games - Page {page_number} - {site_name}', NULL, NULL),
   ('seo', 'meta_description_length', '175', NULL, NULL),
   ('seo', 'meta_description_source', 'new', NULL, NULL),
	('comments', 'game_comments', 'on', NULL, NULL),
	('comments', 'comments_approval', 'off', NULL, NULL),
	('comments', 'comments_per_page', '100', NULL, NULL),
	('comments', 'comments_bad_words_filter', 'off', NULL, NULL),
	('comments', 'comments_bad_words_list', '', NULL, NULL),
	('scripts', 'scripts_google_analytics_code', '', NULL, NULL),
	('scripts', 'scripts_header', '', NULL, NULL),
	('scripts', 'scripts_body', '', NULL, NULL),
	('scripts', 'scripts_footer', '', NULL, NULL),
	('gamecpm', 'gamecpm_publisher_password', '', NULL, NULL),
	('gamecpm', 'gamecpm_publisher_id', '', NULL, NULL),
	('gamecpm', 'gamecpm_publisher_site', '', NULL, NULL),
	('gamecpm', 'gamecpm_get_earning_stats', 'on', NULL, NULL),
	('gamecpm', 'gamecpm_publisher_username', '', NULL, NULL);


--
-- Dumping data for table `abs_zones`
--

INSERT INTO `abs_zones` (`id`, `zone_name`, `type`, `adsize`, `show_ad`) VALUES
(1, '728x90-leaderboard', 'banner', '728x90', 2),
(2, '300x250-medium-rectangle', 'banner', '300x250', 2),
(3, '336x280-large-rectangle', 'banner', '336x280', 2),
(4, '160x600-wide-skyscraper', 'banner', '160x600', 2),
(5, '468x60-small-rectangle', 'banner', '468x60', 2);

