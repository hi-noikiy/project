-- phpMyAdmin SQL Dump
-- version 2.11.2.1
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 建立日期: Feb 04, 2009, 09:17 AM
-- 伺服器版本: 5.0.45
-- PHP 版本: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 資料庫: `test2`
--

-- --------------------------------------------------------

--
-- 資料表格式： `_sys_admin`
--

CREATE TABLE `_sys_admin` (
  `id` int(11) NOT NULL auto_increment,
  `login_name` varchar(255) NOT NULL,
  `login_pass` varchar(255) NOT NULL,
  `gpid` int(11) NOT NULL,
  `real_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `gpid` (`gpid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;

--
-- 列出以下資料庫的數據： `_sys_admin`
--

INSERT INTO `_sys_admin` (`id`, `login_name`, `login_pass`, `gpid`, `real_name`) VALUES
(99, 'admin', 'fa246d0262c3925617b0c72bb20eeb1d', 1, '管理员'),
(1, 'ozchamp', '72870614884a05be92e3c79d8969a3eb', 1, '');

-- --------------------------------------------------------

--
-- 資料表格式： `_sys_dic`
--

CREATE TABLE `_sys_dic` (
  `id` int(11) NOT NULL auto_increment,
  `dickey` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sys_tag` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `dickey` (`dickey`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 列出以下資料庫的數據： `_sys_dic`
--

INSERT INTO `_sys_dic` (`id`, `dickey`, `name`, `sys_tag`) VALUES
(1, 'tag', '有效性', 1);

-- --------------------------------------------------------

--
-- 資料表格式： `_sys_dic_item`
--

CREATE TABLE `_sys_dic_item` (
  `id` int(11) NOT NULL auto_increment,
  `dicid` int(11) NOT NULL,
  `dicval` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sys_tag` tinyint(2) default '0',
  PRIMARY KEY  (`id`),
  KEY `dicid` (`dicid`),
  KEY `dicval` (`dicval`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 列出以下資料庫的數據： `_sys_dic_item`
--

INSERT INTO `_sys_dic_item` (`id`, `dicid`, `dicval`, `name`, `sys_tag`) VALUES
(1, 1, 1, '有效', 1),
(2, 1, 2, '無效', 1);

-- --------------------------------------------------------

--
-- 資料表格式： `_sys_group`
--

CREATE TABLE `_sys_group` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;

--
-- 列出以下資料庫的數據： `_sys_group`
--

INSERT INTO `_sys_group` (`id`, `name`) VALUES
(1, '超级管理员');

-- --------------------------------------------------------

--
-- 資料表格式： `_sys_group_perm`
--

CREATE TABLE `_sys_group_perm` (
  `id` int(11) NOT NULL auto_increment,
  `admin_id` int(11) default NULL,
  `group_id` int(11) default NULL,
  `perm_id` int(11) NOT NULL,
  `s_tag` tinyint(4) default '0',
  `a_tag` tinyint(4) default '0',
  `e_tag` tinyint(4) default '0',
  `d_tag` tinyint(4) default '0',
  PRIMARY KEY  (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `group_id` (`group_id`),
  KEY `perm_id` (`perm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- 列出以下資料庫的數據： `_sys_group_perm`
--

INSERT INTO `_sys_group_perm` (`id`, `admin_id`, `group_id`, `perm_id`, `s_tag`, `a_tag`, `e_tag`, `d_tag`) VALUES
(12, NULL, 1, 1000, 1, 1, 1, 1),
(11, NULL, 1, 3, 1, 1, 1, 1),
(10, NULL, 1, 2, 1, 1, 1, 1),
(9, NULL, 1, 1, 1, 1, 1, 1),
(8, NULL, 1, 5, 1, 1, 1, 1),
(7, NULL, 1, 4, 1, 1, 1, 1),
(13, NULL, 1, 1001, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- 資料表格式： `_sys_permission`
--

CREATE TABLE `_sys_permission` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `tab` varchar(255) NOT NULL,
  `tag` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `tab` (`tab`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1002 ;

--
-- 列出以下資料庫的數據： `_sys_permission`
--

INSERT INTO `_sys_permission` (`id`, `title`, `tab`, `tag`) VALUES
(4, '字典管理', '_sys_dic', 1),
(5, '字典项管理', '_sys_dic_item', 1),
(1, '群组管理', '_sys_group', 1),
(2, '用户管理', '_sys_admin', 1),
(3, '权限管理', '_sys_group_perm', 1),
(1000, '網站新聞管理', '_web_news', 1),
(1001, '營業項目管理', '_web_product', 1);

-- --------------------------------------------------------

--
-- 資料表格式： `_web_news`
--

CREATE TABLE `_web_news` (
  `id` int(11) NOT NULL auto_increment,
  `hit` int(11) default '0',
  `tab` varchar(255) default NULL,
  `tabid` int(11) default NULL,
  `ntype` int(11) default '0',
  `imgurl` varchar(255) default NULL,
  `title` varchar(255) NOT NULL,
  `titlepy` varchar(255) default NULL,
  `titlesub` varchar(255) default NULL,
  `content` text NOT NULL,
  `formsite` varchar(255) default NULL,
  `newsdt` datetime NOT NULL,
  `showtag` tinyint(4) NOT NULL,
  `systag` tinyint(4) default '0',
  `descno` int(11) default '100',
  PRIMARY KEY  (`id`),
  KEY `tab` (`tab`,`tabid`),
  KEY `systag` (`systag`),
  KEY `titlepy` (`titlepy`),
  KEY `hit` (`hit`),
  KEY `ntype` (`ntype`),
  KEY `showtag` (`showtag`),
  KEY `descno` (`descno`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=102 ;

--
-- 列出以下資料庫的數據： `_web_news`
--

INSERT INTO `_web_news` (`id`, `hit`, `tab`, `tabid`, `ntype`, `imgurl`, `title`, `titlepy`, `titlesub`, `content`, `formsite`, `newsdt`, `showtag`, `systag`, `descno`) VALUES
(1, 0, NULL, NULL, 0, NULL, '', NULL, NULL, '<p>　　<span class="page_title2">舞台特效－表演藝術的精靈工程</span> <img height="0" alt="" width="0" src="/upload/image/info_testpic4.gif" /><br />\r\n　　當麥可傑克遜在舞台上高唱成名曲&ldquo;Beat it&rdquo;，並隨著強烈的節奏熱舞&hellip;..忽然，麥可的手，指向舞台前方，兩道火焰精準的噴出，接著五道噴霧直衝舞台， 現場觀眾激昂的情緒，被這樣的視覺震憾，帶到了高潮&hellip;..此時麥可整個人轉向後方，兩手高舉， 舞台後方頓時從上而下，冒出一整排金黃色的煙火瀑布，另所有的觀眾目不暇給，<img height="180" alt="" width="240" align="right" style="margin: 10px" src="/images/about_pic.jpg" />眼睛不知道該盯著麥可看，還是亮麗的煙火瀑布。等到麥可轉向觀眾深深一鞠躬，瀟灑的作出Ending。全體觀眾除了聲嘶力竭的歡呼，便是忘情的鼓掌，直到啞了喉嚨，拍紅了手都不自知&hellip;..因為這麼精 彩絕倫的表演，帶給觀眾視覺上震憾的效果，的確使人感動。當然，麥可傑克遜的魅力絕對是這場表演的主角，但是，在這麼直接靠近歌迷，觀眾的舞台表演裡，要做到盡善盡美，視覺效果上優異的設計與演出，絕對是不可或缺 的主因，而這也是舞台特效工程主要的任務與事業。 <br />\r\n　　特效工程的範疇，當然不止這些，甚至其他電影裏，下雪的場景，你當然不會 天真的認為，導演會等到真的下雨或是下雪才來拍戲。大量的使用噴霧，低溫火燄，乾冰，以營造神仙傳說的氣氛。綜藝節目，介紹主持人出場，攝影棚內上方爆開火花&hellip;..這些都是所謂的&ldquo;特效&rdquo;，它總是在所有的表演場合，扮演著 畫龍點睛的角色，不僅適切的營造現場氣氛，更能製造出視覺上或聽覺上特殊的效果，讓所有的表演更生動迷人，說它是表演藝術的精靈工程，一點也不為過。 <br />\r\n<br />\r\n　　<span class="page_title2">百尺竿頭，更進一步&quot;星際&quot;創立的宗旨</span> <br />\r\n　　是的，百尺竿頭，更進一步，正是星際舞台特效工程有限公司創建的精 神與宗旨。早在15年前，&ldquo;星際&rdquo;創辦人便已親身投入特效工程的事業， 在辛勤不懈的奮鬥下，承接無數電影，電視，舞台，秀場，PUB，乃至工地秀，野台秀的案子，在所有需要表演特殊效果的場 合，總要兢兢業業的完成，即使這般努力，即使在台灣特效公司排名已數一數二<img height="180" alt="" width="240" align="left" style="margin: 10px" src="/images/about_pic2.jpg" />，&ldquo;星際&rdquo;創辦人仍不滿足，因為，台灣的舞台特效工程業，特別是在Fireworks(煙火)，受限於資訊的缺乏，在將近20年的發展過程中， 可說進步緩慢，不僅如此，就連設備器材也相當老舊，眼看著台灣已躋身經濟大國，國際級表演場合，一場接著一場登陸台灣，舞台特效卻一點也跟不上這樣的時代洪流&hellip;&hellip; <br />\r\n　　於是，&ldquo;星際&rdquo;(ASTRAL)誕生了，就像電影&ldquo;星際大戰&rdquo;的內容一樣，它代 表的是最先進的知識，最前衛的科技，我們以&ldquo;星際&rdquo;自許，革新一般特效公司的做法&hellip;..，為了&lsquo;掌握資訊，我們除了直接與美國，日本舞台特效科技大國維持維繫外，我們更進一步執行技術交流，以期獲得最先進Know-How，追求舞台上最完美特殊效果的演出，另一方面，在初期的工程技術上已完成設備器材之研發與製作，正所謂&ldquo;工欲善其事，必先利其器&rdquo;，我們堅信唯有一流的設備，才有分毫不差的演出。 <br />\r\n<br />\r\n　　<span class="page_title2">專業／創新／服務 〝星際〞的自信與決心</span> <br />\r\n　　自信來自於相對的決心，因為義無反顧的決意將舞台特效朝專業化發展， 所以&ldquo;星際&rdquo;毫不考慮的與美國Luna Tech Inc.簽訂合作及技術交流合約，這家美國公司，她所生產的Fireworks 煙火化學品，所註冊Pyropak專屬商 標，早已名聞國際，並且容獲巨星Michael Jackson舞台表演，所唯一指使用的商標產品，Pyropak相關的特效化學用品超過400種，遺憾的是，台灣長期以來資訊封閉，10年來反覆使用的就是十餘種，但是，從今天起 &ldquo;星際&rdquo;將<img height="180" alt="" width="240" align="right" style="margin: 10px" src="/images/about_pic3.jpg" />試著與世界同步引進先進產品，將起跑線上的動力，交付給至美國專業受訓的工作伙伴，舞台特效在這批人的眼中，可再也不是聊勝於無的雕蟲小技，&ldquo;是舞台表演決勝的精靈工程&hellip;.&rdquo;他們會這麼告訴您。<br />\r\n　　決心因著對專業素養的自信而成型，執著於為台灣的特效表演事業開創新的格局，我們知道，這將是一場長時間耐力的對決，也只有持續不斷的精進，才經得起檢驗，&ldquo;Demand checks everything&rdquo;客戶市場的需求，往往就是最嚴謹的檢驗，因此，在專業之外，滿足客戶的需求便成為&ldquo;星際&rdquo;最重要的課題。於是，我們的行銷部除了負責產品引進，國外技術交流之安排，對國內客戶表演場次， 也將提出最中肯的建議及最有效率特效設計。在工程施作方面，我們有最敬業最整齊的工程人員，不僅提供最先進新穎的設備，更具備分毫不差及安全的施作。此外，&ldquo;星際&rdquo;更設置研發部門，滿足各種不同型態的表演需求。所以，當別人的噴筒只能將彩帶送向8m高度，而&ldquo;星際&rdquo;的彩帶卻能在第六層樓高飄揚，真正發揮Swing所產生的效果，實在不足為奇，因為，我們真正付出了努力與心血。 <br />\r\n<br />\r\n<span class="page_title2">〝星際〞的公司組織部門</span> <br />\r\n<span class="page_title2">行銷部: </span><br />\r\n國外產品代理開發，安排技術交流，人員受訓事宜。<br />\r\n國內市場規劃，客戶開發，特效表演設計與建議。 <br />\r\n<br />\r\n<span class="page_title2">工程部: </span><br />\r\n特效設備製作。<br />\r\n現場設備及管線架線施作。<br />\r\n現場特效表演，控制與操作。<br />\r\n<br />\r\n<span class="page_title2">研發部: </span><br />\r\n設備研發及改良。<br />\r\nFireworks煙火化學品，操作研究及施作設計。<br />\r\n<br />\r\n<span class="page_title2">氣球部: </span><br />\r\n專業氣球施工佈置。</p>', NULL, '0000-00-00 00:00:00', 0, 1, 100),
(100, 0, NULL, NULL, 0, NULL, 'fffffff', NULL, NULL, '', NULL, '0000-00-00 00:00:00', 0, 0, 100),
(101, 0, NULL, NULL, 0, NULL, 'ffffffff', NULL, NULL, '', NULL, '0000-00-00 00:00:00', 0, 0, 100);

-- --------------------------------------------------------

--
-- 資料表格式： `_web_product`
--

CREATE TABLE `_web_product` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `time` varchar(20) default NULL,
  `size` varchar(100) default NULL,
  `case` varchar(100) default NULL,
  `number` int(11) default NULL,
  `img` varchar(255) default NULL,
  `youtobe` varchar(255) default NULL,
  `showtag` int(11) NOT NULL default '1',
  `small_img` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 列出以下資料庫的數據： `_web_product`
--

INSERT INTO `_web_product` (`id`, `name`, `time`, `size`, `case`, `number`, `img`, `youtobe`, `showtag`, `small_img`) VALUES
(2, 'ccccccccccccccccccc', 'ccccc', 'cccccccccccc', NULL, 0, 'upload/2009/02/20090204081520.jpg', NULL, 1, 'upload/2009/02/20090204081520_small.jpg');
