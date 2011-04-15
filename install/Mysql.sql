CREATE TABLE `comments` (
  `cid` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `ownerId` int(10) unsigned NOT NULL default '0',
  `created` int(10) unsigned NOT NULL default '0',
  `author` varchar(200) NOT NULL default '',
  `mail` varchar(200) NOT NULL default '',
  `url` varchar(200) NOT NULL default '',
  `ip` varchar(64) NOT NULL default '',
  `agent` varchar(200) NOT NULL default '',
  `text` text NOT NULL default '',
  `type` varchar(16) NOT NULL default 'comment',
  `status` varchar(16) NOT NULL default 'approved',
  PRIMARY KEY  (`cid`),
  KEY (`pid`),
  KEY (`created`)
) ENGINE=MyISAM  DEFAULT CHARSET=`utf8`;

CREATE TABLE `navigations` (
  `nid` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(200) NOT NULL default '',
  `type` varchar(16) NOT NULL default 'url',
  `url` varchar(200) NOT NULL default '',
  `uri` varchar(200) NOT NULL default '',
  `page` int(10) unsigned NOT NULL default '0',
  `target` varchar(16) NOT NULL default '',
  `order` int(10) unsigned NOT NULL default '0',
  `parent` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`nid`)
) ENGINE=MyISAM  DEFAULT CHARSET=`utf8`;

CREATE TABLE `posts` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned NOT NULL default '0',
  `title` varchar(200) NOT NULL default '',
  `slug` varchar(200) NOT NULL default '',
  `created` int(10) unsigned NOT NULL default '0',
  `modified` int(10) unsigned NOT NULL default '0',
  `text` text NOT NULL default '',
  `css` text NOT NULL default '',
  `js` text NOT NULL default '',
  `type` varchar(16) NOT NULL default 'post',
  `status` varchar(16) NOT NULL default 'publish',
  `commentsNum` int(10) unsigned NOT NULL default '0',
  `allowComment` char(1) NOT NULL default '1',
  `allowPing` char(1) NOT NULL default '1',
  `allowFeed` char(1) NOT NULL default '1',
  PRIMARY KEY  (`pid`),
  UNIQUE KEY (`slug`),
  KEY (`created`)
) ENGINE=MyISAM  DEFAULT CHARSET=`utf8`;

CREATE TABLE `metas` (
  `mid` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `slug` varchar(200) NOT NULL default '',
  `type` varchar(32) NOT NULL default 'category',
  `description` varchar(200) NOT NULL default '',
  `count` int(10) unsigned default '0',
  `order` int(10) unsigned default '0',
  PRIMARY KEY  (`mid`),
  KEY (`slug`)
) ENGINE=MyISAM  DEFAULT CHARSET=`utf8`;

CREATE TABLE `settings` (
	`id` tinyint(2) unsigned NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `value` text NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=`utf8`;

CREATE TABLE `post_metas` (
  `pid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`pid`,`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=`utf8`;


CREATE TABLE `users` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `password` varchar(40) NOT NULL default '',
  `mail` varchar(200) NOT NULL default '',
  `url` varchar(200) NOT NULL default '',
  `screenName` varchar(32) NOT NULL default '',
  `created` int(10) unsigned NOT NULL default '0',
  `activated` int(10) unsigned NOT NULL default '0',
  `logged` int(10) unsigned NOT NULL default '0',
  `group` varchar(16) NOT NULL default 'visitor',
  `token` varchar(40) NOT NULL default '',
  `rememberCode` varchar(40) NOT NULL default '',
	`postsNum` int(10) unsigned NOT NULL default 0,
	`commentsNum` int(10) unsigned NOT NULL default 0,
  PRIMARY KEY  (`uid`),
  UNIQUE KEY (`name`),
  UNIQUE KEY (`mail`),
	UNIQUE KEY (`screenName`)
) ENGINE=MyISAM  DEFAULT CHARSET=`utf8`;

CREATE TABLE `sessions`(
	`session_id` varchar(40) NOT NULL default '',
	`ip_address` varchar(16) NOT NULL default '',
	`user_agent` varchar(50) NOT NULL,
	`last_activity` int(10) unsigned NOT NULL default 0,
	user_data text NOT NULL default '',
	PRIMARY KEY (`session_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=`utf8`;

INSERT INTO `settings` VALUES(1, 'blog_title', 'FieBlog');
INSERT INTO `settings` VALUES(2, 'blog_slogan', 'Leave Some Words, Get More Joy!');
INSERT INTO `settings` VALUES(3, 'blog_description', 'FieBlog源码层次清晰，易于扩展，界面简洁友好，运行流畅快速，使用FieBlog创建属于您自己的日志发布平台！');
INSERT INTO `settings` VALUES(4, 'blog_keywords', 'fieblog,mvc,php,codeigniter,open souce,开源博客');
INSERT INTO `settings` VALUES(5, 'homepage_type', 'posts');
INSERT INTO `settings` VALUES(6, 'homepage_pid', '');
INSERT INTO `settings` VALUES(7, 'current_theme', 'default');
INSERT INTO `settings` VALUES(8, 'current_theme_format', 'unix');
INSERT INTO `settings` VALUES(9, 'blog_status', 'on');
INSERT INTO `settings` VALUES(10, 'offline_reason', '稍后公布');
INSERT INTO `settings` VALUES(11, 'upload_dir', 'uploads/');
INSERT INTO `settings` VALUES(12, 'upload_max_size', '5120');
INSERT INTO `settings` VALUES(13, 'upload_exts', 'txt|pdf|doc|xls|ppt|rar|zip');
INSERT INTO `settings` VALUES(14, 'upload_img_exts', 'jpg|jpeg|bmp|png|gif');
INSERT INTO `settings` VALUES(15, 'comments_date_format', 'Y-m-d H:i');
INSERT INTO `settings` VALUES(16, 'comments_page_size', '10');
INSERT INTO `settings` VALUES(17, 'comments_list_size', '5');
INSERT INTO `settings` VALUES(18, 'comments_url_no_follow', '1');
INSERT INTO `settings` VALUES(19, 'comments_require_moderation', '0');
INSERT INTO `settings` VALUES(20, 'comments_auto_close', '0');
INSERT INTO `settings` VALUES(21, 'comments_require_mail', '1');
INSERT INTO `settings` VALUES(22, 'comments_require_url', '0');
INSERT INTO `settings` VALUES(23, 'comments_allowed_html', '');
INSERT INTO `settings` VALUES(24, 'post_date_format', 'Y-m-d H:i');
INSERT INTO `settings` VALUES(25, 'posts_page_size', '10');
INSERT INTO `settings` VALUES(26, 'posts_list_size', '5');
INSERT INTO `settings` VALUES(27, 'posts_full_text', '1');
INSERT INTO `settings` VALUES(28, 'feed_full_text', '1');
INSERT INTO `settings` VALUES(29, 'cache_enabled', '0');
INSERT INTO `settings` VALUES(30, 'cache_expire_time', '30');
INSERT INTO `settings` VALUES(31, 'dbcache_expire_time', '300');
INSERT INTO users (`uid`, `name`, `password`, `mail`, `url`, `screenName`, `created`, `activated`, `logged`, `group`, `token`, `rememberCode`, `postsNum`, `commentsNum`) VALUES (1, 'admin', '5e81b420731e3e75fa407faa08f7aae4f9f58db3', 'admin@site.com', '', 'Administrator', 1302798099, 0, 0, 'administrator', '', '', 1, 0);
INSERT INTO metas (`mid`, `name`, `slug`, `type`, `description`, `count`, `order`) VALUES (1, '随笔', 'essay', 'category', '', 1, 1);
INSERT INTO posts (`pid`, `uid`, `title`, `slug`, `created`, `modified`, `text`, `css`, `js`,`type`, `status`, `commentsNum`, `allowComment`, `allowPing`, `allowFeed`) VALUES (1, 1, '欢迎使用FieBlog', 'welcome', 1302514860, 1302756006, '如果你看到这篇文章，说明博客已经安装和配置成功了! 这是一篇测试日志，你可以进入后台删除', '', '','post', 'publish', 0, '1', '1', '1');
INSERT INTO navigations (`nid`, `title`, `type`, `url`, `uri`, `page`, `target`, `order`) VALUES (1, '主页', 'uri', '', '', 0, '', 1);
INSERT INTO navigations (`nid`, `title`, `type`, `url`, `uri`, `page`, `target`, `order`) VALUES (2, '文章', 'uri', '', 'posts', 0, '', 2);
