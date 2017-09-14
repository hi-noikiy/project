-- phpMyAdmin SQL Dump
-- version 2.11.2.1
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 建立日期: Feb 14, 2009, 09:58 AM
-- 伺服器版本: 5.0.45
-- PHP 版本: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 資料庫: `test2`
--

-- --------------------------------------------------------

--
-- 資料表格式： `_sys_section`
--

CREATE TABLE `_sys_section` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `table_name` varchar(100) default NULL,
  `field_name` varchar(100) default NULL,
  `field_value` varchar(100) default NULL,
  `parent_id` int(11) NOT NULL default '0',
  `link` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `hide_sub` tinyint(4) NOT NULL default '0',
  `Slist` int(11) NOT NULL default '1',
  `Sadd` int(11) NOT NULL default '1',
  `Sedit` int(11) NOT NULL default '1',
  `Sdelete` int(11) NOT NULL default '1',
  `control` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- 列出以下資料庫的數據： `_sys_section`
--

INSERT INTO `_sys_section` (`id`, `name`, `table_name`, `field_name`, `field_value`, `parent_id`, `link`, `sort`, 

`hide_sub`, `Slist`, `Sadd`, `Sedit`, `Sdelete`, `control`) VALUES
(1, '網站管理 ', '', '', '', 8, '', 0, 1, 0, 0, 0, 0, 1),
(2, '關於我們', 'news', 'id', '1', 1, 'index.php?type=web&do=info&cn=news&id=1', 0, 0, 0, 0, 1, 0, 1),
(3, '最新消息列表', NULL, NULL, NULL, 1, 'index.php?type=web&do=list&cn=news', 0, 0, 1, 1, 1, 1, 1),
(4, '作業表單', '', '', '', 0, '', 0, 1, 0, 0, 0, 0, 1),
(5, '類別管理', NULL, NULL, NULL, 4, '', 0, 0, 1, 1, 1, 1, 1),
(6, '作業管理', NULL, NULL, NULL, 4, '', 0, 0, 1, 1, 1, 1, 1),
(7, '供應商管理', NULL, NULL, NULL, 4, '', 0, 0, 1, 1, 1, 1, 1),
(8, '會員管理', '', '', '', 0, '', 0, 1, 0, 0, 0, 0, 1),
(9, '會員列表', NULL, NULL, NULL, 8, '', 0, 0, 1, 1, 1, 1, 1),
(10, '會員身份類別', NULL, NULL, NULL, 8, '', 0, 0, 1, 1, 1, 1, 1),
(11, '系統管理', '', '', '', 0, '', 0, 0, 0, 0, 0, 0, 1),
(12, '商品管理', NULL, NULL, NULL, 11, '', 0, 0, 1, 1, 1, 1, 1),
(13, '公告者管理', NULL, NULL, NULL, 11, '', 0, 0, 1, 1, 1, 1, 1),
(14, '投資類別 ', NULL, NULL, NULL, 11, '', 0, 0, 1, 1, 1, 1, 1),
(15, '投資區域', NULL, NULL, NULL, 11, '', 0, 0, 1, 1, 1, 1, 1),
(16, '字典管理', NULL, NULL, NULL, 11, '', 0, 0, 1, 1, 1, 1, 1),
(17, '安全退出', '', '', '', 0, 'login.php?out=yes', 0, 0, 0, 0, 0, 0, 1),
(18, '管理員管理', '', '', '', 11, '', 0, 0, 0, 0, 0, 0, 1),
(19, '群组管理', '', '', '', 18, '', 0, 0, 1, 1, 1, 1, 1),
(20, '帳號管理', '', '', '', 18, '', 0, 0, 1, 1, 1, 1, 1),
(21, '權限管理', '', '', '', 18, '', 0, 0, 1, 1, 1, 1, 1);
