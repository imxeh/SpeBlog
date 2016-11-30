SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `spe_config`
-- ----------------------------
DROP TABLE IF EXISTS `spe_config`;
CREATE TABLE `spe_config` (
	`key` varchar(32) NOT NULL,
	`value` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`key`),
	UNIQUE KEY `key` (`key`)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

-- ----------------------------
--  Records of `spe_config`
-- ----------------------------
BEGIN;
INSERT INTO `spe_config` VALUES ('sitename', 'SpeBlog'), ('keywords', 'SpeBlog,Blog,简约博客'), ('description', 'SpeBlog，一款优雅而简约、快速的博客程序'), ('version', '1.0'), ('whecomment', 1);
COMMIT;

-- ----------------------------
--  Table structure for `spe_user`
-- ----------------------------
DROP TABLE IF EXISTS `spe_user`;
CREATE TABLE `spe_user` (
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '用户ID',
	`username` VARCHAR(16) NOT NULL UNIQUE COMMENT '账号昵称',
	`password` VARCHAR(32) NOT NULL COMMENT '账号密码',
	`user_check` VARCHAR(64) NULL COMMENT '登录状态',
	`sign_ip` VARCHAR(15) NOT NULL COMMENT '最后登录IP',
	`createdate` INT NOT NULL COMMENT '最后登录时间'
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

-- ----------------------------
--  Records of `spe_user`
-- ----------------------------
INSERT INTO `spe_user` (`username`, `password`, `user_check`, `sign_ip`, `createdate`) VALUES ('admin', '21232f297a57a5a743894a0e4a801fc3', '', '172.168.0.1', '123456');

-- ----------------------------
--  Table structure for `spe_system`
-- ----------------------------
DROP TABLE IF EXISTS `spe_system`;
CREATE TABLE `spe_system` (
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '导航ID',
	`name` VARCHAR(16) NOT NULL UNIQUE COMMENT '名称',
	`box` TEXT NOT NULL COMMENT 'Menu'
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

-- ----------------------------
--  Records of `spe_system`
-- ----------------------------
INSERT INTO `spe_system` (`name`, `box`) VALUES (
'menu',
'<a href="index.php">首页</a>'
);

INSERT INTO `spe_system` (`name`, `box`) VALUES (
'links',
'<p class="text-center">
<a href="http://www.xlogs.cn" target="_blank">亦痕</a>
<a href="https://idc.vv1234.cn" target="_blank">零玖互联</a>
<a href="http://www.youngxj.cn" target="_blank">杨小杰博客</a>
</p>'
);

INSERT INTO `spe_system` (`name`, `box`) VALUES ('css', '');

-- ----------------------------
--  Table structure for `spe_articles`
-- ----------------------------
DROP TABLE IF EXISTS `spe_articles`;
CREATE TABLE `spe_articles` (
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '文章ID',
	`title` VARCHAR(64) NOT NULL COMMENT '标题',
	`author` VARCHAR(16) NOT NULL COMMENT '作者',
	`box` TEXT NOT NULL COMMENT '文章详情',
	`ip` VARCHAR(15) NOT NULL COMMENT '发布 IP',
	`createdate` INT NOT NULL COMMENT '发布时间'
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

-- ----------------------------
--  Records of `spe_articles`
-- ----------------------------
INSERT INTO `spe_articles` (`title`, `author`, `box`, `ip`, `createdate`) VALUES ("欢迎使用 SpeBlog", "Administrator", "这篇文章是由系统自动生成的，您可以在后台删除它。<br/>This article is automatically generated by the system, you can delete it in the background.", "192.168.0.1", 1480208371);

-- ----------------------------
--  Table structure for `spe_comment`
-- ----------------------------
DROP TABLE IF EXISTS `spe_comment`;
CREATE TABLE `spe_comment` (
	`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '评论ID',
	`article_id` INT NOT NULL COMMENT '所评文章ID',
	`reply_id` INT NOT NULL UNIQUE COMMENT '所回复评论ID',
	`name` VARCHAR(64) NOT NULL COMMENT '评论者昵称',
	`mail` VARCHAR(64) NOT NULL COMMENT '评论者邮箱',
	`url` VARCHAR(64) NOT NULL COMMENT '评论者URL',
	`box` VARCHAR(225) NOT NULL COMMENT '评论内容',
	`ip` VARCHAR(15) NOT NULL COMMENT '评论人 IP',
	`createdate` INT NOT NULL COMMENT '评论时间'
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;
