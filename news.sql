CREATE TABLE `epii_articles_articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '文章标题',
  `desc` varchar(200) NOT NULL DEFAULT '' COMMENT '文章简介',
  `image` varchar(200) NOT NULL DEFAULT '' COMMENT '文章图片',
  `content` text COMMENT '内容',
  `classify_id` varchar(200) NOT NULL DEFAULT '' COMMENT '分类id以,号隔开',
  `tags_id` varchar(200) NOT NULL DEFAULT '' COMMENT '标签id以,号隔开',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态0未开启,1开启',
  `sort` smallint(6) unsigned DEFAULT '255' COMMENT '排序',
  `addtime` int(11) NOT NULL COMMENT '创建时间',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  `tags_name` varchar(200) DEFAULT '' COMMENT '标签名称，以，号隔开',
  `classify_name` varchar(200) DEFAULT '' COMMENT '分类名称',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='文章表';



CREATE TABLE `epii_articles_classify` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '节点名称',
  `url` varchar(255) DEFAULT NULL COMMENT 'url',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态0未开启,1开启',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `sort` smallint(6) unsigned DEFAULT '255' COMMENT '排序',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级节点ID',
  `icon` varchar(50) NOT NULL COMMENT '图标',
  `badge` varchar(20) DEFAULT NULL COMMENT '小标',
  `is_open` varchar(10) DEFAULT NULL COMMENT '是否默认打开',
  `open_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1新窗口打开 0 addtab',
  `badge_class` varchar(50) NOT NULL DEFAULT '',
  `trees` varchar(100) DEFAULT '' COMMENT '分类树',
  `level` tinyint(1) DEFAULT '0' COMMENT '分类等级0无1一级2二级3三级',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `pid_2` (`pid`),
  KEY `open_type` (`open_type`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='文章分类表';



CREATE TABLE `epii_articles_tags` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '标签名称',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态0未开启,1开启',
  `sort` smallint(6) unsigned DEFAULT '255' COMMENT '排序',
  `addtime` int(11) NOT NULL COMMENT '创建时间',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  `articles_count` int(11) DEFAULT '0' COMMENT '文章各数',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='文章标签表';
