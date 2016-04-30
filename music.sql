-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 30, 2016 at 09:11 PM
-- Server version: 5.6.25
-- PHP Version: 5.5.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;


--
-- Database: `music`
--

-- --------------------------------------------------------

--
-- Table structure for table `media_album`
--

CREATE TABLE `media_album` (
  `album_id` int(10) NOT NULL,
  `album_name` varchar(255) NOT NULL DEFAULT '',
  `album_name_ascii` varchar(255) NOT NULL DEFAULT '',
  `album_singer` varchar(50) NOT NULL DEFAULT '',
  `album_img` varchar(255) NOT NULL DEFAULT '',
  `album_info` text NOT NULL,
  `album_viewed` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `media_album`
--

INSERT INTO `media_album` (`album_id`, `album_name`, `album_name_ascii`, `album_singer`, `album_img`, `album_info`, `album_viewed`) VALUES
(1, 'HEllo', 'hello', '1', 'templates/default/images/m20.jpg', '', 0),
(2, 'The world', 'the world', '2', 'templates/default/images/m21.jpg', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `media_cat`
--

CREATE TABLE `media_cat` (
  `cat_id` int(3) NOT NULL,
  `m_title_ascii` varchar(120) NOT NULL DEFAULT '',
  `cat_name` varchar(120) NOT NULL DEFAULT '',
  `cat_type` char(3) NOT NULL DEFAULT '',
  `cat_order` int(3) NOT NULL DEFAULT '0',
  `sub_id` int(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `media_cat`
--

INSERT INTO `media_cat` (`cat_id`, `m_title_ascii`, `cat_name`, `cat_type`, `cat_order`, `sub_id`) VALUES
(8, '', 'Electronic', '', 0, 11),
(2, '', 'Acoustic', '', 0, 11),
(4, '', 'Pop', '', 0, 11),
(6, '', 'Country', '', 0, 11),
(7, '', 'Rap', '', 0, 11),
(9, '', 'Ambient', '', 0, 2),
(10, '', 'Hip Hop', '', 0, 11),
(11, '', 'U.S.', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `media_comment`
--

CREATE TABLE `media_comment` (
  `comment_id` int(5) NOT NULL,
  `comment_media_id` int(5) NOT NULL DEFAULT '0',
  `comment_poster` varchar(5) NOT NULL DEFAULT '',
  `comment_content` text NOT NULL,
  `comment_time` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `media_comment`
--

INSERT INTO `media_comment` (`comment_id`, `comment_media_id`, `comment_poster`, `comment_content`, `comment_time`) VALUES
(8, 18, 'thanh', 'good song', '2016-05-01 01:55:57'),
(7, 22, 'ghgfh', 'fhfghfgh', '2016-05-01 01:28:19'),
(6, 22, 'rrtre', 'tertertert', '2016-04-30 20:27:55'),
(5, 112, 'ghfdh', 'dgfhdfghdfgh', '2016-04-30 20:28:02');

-- --------------------------------------------------------

--
-- Table structure for table `media_config`
--

CREATE TABLE `media_config` (
  `config_name` varchar(50) NOT NULL DEFAULT '',
  `config_value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `media_config`
--

INSERT INTO `media_config` (`config_name`, `config_value`) VALUES
('default_tpl', 'default'),
('total_visit', '6577'),
('announcement', ''),
('must_login_to_play', '0'),
('web_title', 'XtreMediaf'),
('web_url', 'http://localhost/music'),
('must_login_to_download', '0'),
('server_folder', ''),
('server_url', ''),
('current_month', '4'),
('web_email', 'thanhsnguyen@hotmail.com'),
('download_salt', '16-06-89'),
('media_per_page', '30'),
('mod_permission', '0'),
('intro_song', ''),
('intro_song_is_local', '0');

-- --------------------------------------------------------

--
-- Table structure for table `media_counter`
--

CREATE TABLE `media_counter` (
  `ip` varchar(15) NOT NULL DEFAULT '',
  `sid` varchar(32) NOT NULL DEFAULT '',
  `time` varchar(12) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `media_counter`
--

INSERT INTO `media_counter` (`ip`, `sid`, `time`) VALUES
('::1', 'b212240a6cb14d116ee65059030310fe', '1461787706');

-- --------------------------------------------------------

--
-- Table structure for table `media_data`
--

CREATE TABLE `media_data` (
  `m_id` int(10) NOT NULL,
  `m_title` varchar(120) NOT NULL DEFAULT '',
  `m_title_ascii` varchar(120) NOT NULL DEFAULT '',
  `m_singer` int(5) NOT NULL DEFAULT '0',
  `m_album` int(5) NOT NULL DEFAULT '0',
  `m_cat` varchar(120) NOT NULL DEFAULT '',
  `m_url` varchar(250) NOT NULL DEFAULT '',
  `m_poster` varchar(50) NOT NULL DEFAULT '',
  `m_is_local` tinyint(1) NOT NULL DEFAULT '0',
  `m_lyric` text,
  `m_type` int(1) NOT NULL DEFAULT '0',
  `m_width` int(3) DEFAULT NULL,
  `m_height` int(3) DEFAULT NULL,
  `m_viewed` int(10) NOT NULL DEFAULT '0',
  `m_viewed_month` int(10) NOT NULL DEFAULT '0',
  `m_downloaded` int(5) NOT NULL DEFAULT '0',
  `m_downloaded_month` int(10) NOT NULL DEFAULT '0',
  `m_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `m_is_broken` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `media_data`
--

INSERT INTO `media_data` (`m_id`, `m_title`, `m_title_ascii`, `m_singer`, `m_album`, `m_cat`, `m_url`, `m_poster`, `m_is_local`, `m_lyric`, `m_type`, `m_width`, `m_height`, `m_viewed`, `m_viewed_month`, `m_downloaded`, `m_downloaded_month`, `m_date`, `m_is_broken`) VALUES
(1, 'Holding Back', 'holding back', 3, 0, '6', 'http://www.scottandrew.com/mp3/demos/holding_back_demo_011504.mp3', 'templates/default/images/m0.jpg', 0, '', 1, 0, 0, 51, 8, 1, 1, '2016-03-05 06:00:00', 0),
(2, 'Dont You Remember ', 'dont you remember ', 1, 0, '6', 'uploads/DontYouRemember-Adele.mp3', 'templates/default/images/m1.jpg', 0, '', 1, 0, 0, 17, 17, 0, 0, '2016-03-05 06:00:00', 0),
(4, 'Hiding My Heart ', 'hiding my heart ', 1, 0, '6', 'uploads/HidingMyHeart-Adele.mp3', 'templates/default/images/m2.jpg', 0, '', 1, 0, 0, 1, 1, 0, 0, '2016-03-07 06:00:00', 0),
(11, 'Good For You ', 'good for you ', 1, 1, '8', 'uploads/GoodForYou-SelenaGomezFtAAPRocky.mp3', 'templates/default/images/m13.jpg', 0, '', 1, NULL, NULL, 0, 0, 0, 0, '2016-04-29 05:00:00', 0),
(3, 'Melt My Heart To Stone', 'melt my heart to stone', 1, 0, '6', 'uploads/MeltMyHeartToStone.mp3', 'templates/default/images/m4.jpg', 0, '', 1, NULL, NULL, 1, 1, 0, 0, '0000-00-00 00:00:00', 0),
(5, 'Rolling in The Deep', 'rolling in the deep', 1, 1, '4', 'uploads/RollingInTheDeepMainVersion-Adele.mp3', 'templates/default/images/m3.jpg', 0, '', 1, 0, 0, 2, 2, 0, 0, '2016-03-07 06:00:00', 0),
(6, 'Best For Last', 'best for last', 1, 1, '6', 'uploads/BestForLast.mp3', 'templates/default/images/m5.jpg', 0, '', 1, 0, 0, 0, 0, 0, 0, '2016-03-07 06:00:00', 0),
(7, 'Hello', 'hello', 1, 1, '4', 'uploads/Hello_Adele.mp3', 'templates/default/images/m6.jpg', 0, '', 1, 0, 0, 3, 3, 1, 1, '2016-03-07 06:00:00', 0),
(8, 'Thin Ice', 'thin ice', 3, 2, '4', 'http://flatfull.com/wp/musik/wp-content/uploads/2015/07/Miaow-10-Thin-ice.mp3', 'templates/default/images/m7.jpg', 0, '', 1, 0, 0, 5, 5, 1, 1, '2016-03-07 06:00:00', 0),
(9, 'You Will Never See Me Again', 'you will never see me again', 1, 1, '4', 'uploads/You-Never-See-Me-Again-Adele.mp3', 'templates/default/images/m9.jpg', 0, '', 1, 0, 0, 0, 0, 0, 0, '2016-04-29 05:00:00', 0),
(10, 'When We Were Young', 'when we were young', 1, 1, '4', 'uploads/WhenWeWereYoung-Adele.mp3', 'templates/default/images/m12.jpg', 0, '', 1, NULL, NULL, 0, 0, 0, 0, '2016-04-29 05:00:00', 0),
(12, 'I Want You To Know ', 'i want you to know ', 2, 2, '10', 'uploads/IWantYouToKnow-ZeddFtSelenaGomez.mp3', 'templates/default/images/m14.jpg', 0, '', 1, NULL, NULL, 1, 1, 2, 2, '2016-04-29 05:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `media_gift`
--

CREATE TABLE `media_gift` (
  `gift_id` varchar(20) NOT NULL DEFAULT '',
  `gift_media_id` int(5) NOT NULL DEFAULT '0',
  `gift_sender_id` int(5) NOT NULL DEFAULT '0',
  `gift_sender_name` varchar(100) NOT NULL DEFAULT '',
  `gift_sender_email` varchar(100) NOT NULL DEFAULT '',
  `gift_recip_name` varchar(100) NOT NULL DEFAULT '',
  `gift_recip_email` varchar(100) NOT NULL DEFAULT '',
  `gift_message` text NOT NULL,
  `gift_time` varchar(12) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `media_manage`
--

CREATE TABLE `media_manage` (
  `manage_type` varchar(25) NOT NULL DEFAULT '',
  `manage_user` varchar(5) NOT NULL DEFAULT '',
  `manage_media` varchar(5) NOT NULL DEFAULT '',
  `manage_timeout` varchar(12) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `media_online`
--

CREATE TABLE `media_online` (
  `timestamp` varchar(15) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `sid` varchar(32) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `media_playlist`
--

CREATE TABLE `media_playlist` (
  `playlist_id` int(200) NOT NULL,
  `playlist_user_id` int(50) NOT NULL,
  `playlist_title` varchar(50) NOT NULL,
  `playlist_thumb` varchar(100) NOT NULL,
  `playlist_contents` varchar(255) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `media_playlist`
--

INSERT INTO `media_playlist` (`playlist_id`, `playlist_user_id`, `playlist_title`, `playlist_thumb`, `playlist_contents`) VALUES
(1, 2, 'Hello', 'http://flatfull.com/wp/musik/wp-content/uploads/2015/07/m0-150x150.jpg', '14'),
(2, 2, 'hellohelo', 'templates/default/images/m1.jpg', '14:110'),
(3, 2, 'mania', 'templates/default/images/m7.jpg', '110'),
(10, 2, 'hello', 'templates/default/images/m4.jpg', '12:16:110:13');

-- --------------------------------------------------------

--
-- Table structure for table `media_singer`
--

CREATE TABLE `media_singer` (
  `singer_id` int(10) NOT NULL,
  `singer_name` varchar(255) NOT NULL DEFAULT '',
  `singer_name_ascii` varchar(255) NOT NULL DEFAULT '',
  `singer_img` varchar(255) NOT NULL DEFAULT '',
  `singer_info` text NOT NULL,
  `singer_type` char(1) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `media_singer`
--

INSERT INTO `media_singer` (`singer_id`, `singer_name`, `singer_name_ascii`, `singer_img`, `singer_info`, `singer_type`) VALUES
(1, 'Adele', 'adele', 'http://flatfull.com/wp/musik/wp-content/uploads/2015/07/m20-150x150.jpg', 'Adele Laurie Blue Adkins[5] MBE (/É™ËˆdÉ›l/; born 5 May 1988) is an English singer and songwriter. Graduating from the BRIT School for Performing Arts and Technology in 2006, Adele was given a recording contract by XL Recordings after a friend posted her demo on Myspace the same year. In 2007, she received the Brit Awards &quot;Critics\' Choice&quot; award and won the BBC Sound of 2008 poll. Her debut album, 19, was released in 2008 to commercial and critical success. It is certified seven times platinum in the UK, and double platinum in the US. An appearance she made on Saturday Night Live in late 2008 boosted her career in the US. At the 51st Annual Grammy Awards in 2009, Adele received the awards for Best New Artist and Best Female Pop Vocal Performance.\r\n\r\nAdele released her second studio album, 21, in early 2011. The album was well received critically and surpassed the success of her debut,[6] earning the singer numerous awards in 2012, including a record-tying six Grammy Awards, including Album of the Year; two Brit Awards, including British Album of the Year, and three American Music Awards. The album has been certified 16 times platinum in the UK, and is the fourth best-selling album in the UK of all time.[7] In the US it has held the top position longer than any album since 1985, and is certified Diamond.[8][9] The album has sold 31 million copies worldwide.[10]', '1'),
(2, 'Selena Gomez', 'selena gomez', 'templates/default/images/m9.jpg', 'Selena Marie Gomez (/sÉ™ËˆliËnÉ™ mÉ™ËˆriË ËˆÉ¡oÊŠmÉ›z/ sÉ™-lee-nÉ™ mÉ™-ree goh-mez;[3] Spanish pronunciation: [seËˆlena ËˆÉ£omes];[4] born July 22, 1992) is an American singer and actress. She was first featured on the children\'s series Barney &amp; Friends in the early 2000s. In 2007, Gomez came to prominence after being cast in the Disney Channel series Wizards of Waverly Place, in which she starred as the lead character, Alex Russo, until its conclusion in 2012. She formed the band Selena Gomez &amp; the Scene after signing a recording contract with Hollywood Records in 2008; they released the studio albums Kiss &amp; Tell (2009), A Year Without Rain (2010), and When the Sun Goes Down (2011) before beginning a hiatus in 2012.\r\n\r\nGomez entered the film industry with star billings in feature films including Ramona and Beezus (2010), Monte Carlo (2011), and Hotel Transylvania (2012). She embraced an increasingly mature public image with her star billing in the film Spring Breakers (2013) and her 2013 debut solo studio album, Stars Dance. The latter project debuted at number one on the US Billboard 200 and included the Billboard Hot 100 top-ten single &quot;Come &amp; Get It.&quot; Gomez was released from her recording contract with Hollywood Records following the completion of her compilation album For You (2014). She subsequently signed with Interscope Records to continue work on her sophomore studio album, Revival (2015). It debuted at number one in the US and featured the top-ten singles &quot;Good for You&quot;, &quot;Same Old Love&quot; and &quot;Hands to Myself.&quot;', '1'),
(3, 'Travis Scott', 'travis scott', 'http://flatfull.com/wp/musik/wp-content/uploads/2015/07/m20-150x150.jpg', '', '1');

-- --------------------------------------------------------

--
-- Table structure for table `media_tpl`
--

CREATE TABLE `media_tpl` (
  `tpl_id` int(3) NOT NULL,
  `tpl_sname` varchar(20) NOT NULL DEFAULT '',
  `tpl_fname` varchar(255) NOT NULL DEFAULT '',
  `tpl_order` int(3) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `media_tpl`
--

INSERT INTO `media_tpl` (`tpl_id`, `tpl_sname`, `tpl_fname`, `tpl_order`) VALUES
(1, 'funnycolors', 'FunnyColors', 1);

-- --------------------------------------------------------

--
-- Table structure for table `media_user`
--

CREATE TABLE `media_user` (
  `user_id` int(5) NOT NULL,
  `user_name` varchar(50) NOT NULL DEFAULT '',
  `user_password` varchar(50) NOT NULL DEFAULT '',
  `user_new_password` varchar(15) NOT NULL DEFAULT '',
  `user_email` varchar(100) DEFAULT NULL,
  `user_sex` char(1) NOT NULL DEFAULT '0',
  `user_hide_info` varchar(32) NOT NULL DEFAULT '',
  `user_regdate` varchar(12) NOT NULL DEFAULT '',
  `user_level` tinyint(1) NOT NULL DEFAULT '1',
  `user_playlist_id` varchar(20) NOT NULL DEFAULT '',
  `user_online` tinyint(1) NOT NULL DEFAULT '0',
  `user_ip` varchar(15) NOT NULL DEFAULT '',
  `user_identifier` varchar(32) DEFAULT NULL,
  `user_timeout` varchar(12) DEFAULT NULL,
  `user_lastvisit` varchar(12) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `media_user`
--

INSERT INTO `media_user` (`user_id`, `user_name`, `user_password`, `user_new_password`, `user_email`, `user_sex`, `user_hide_info`, `user_regdate`, `user_level`, `user_playlist_id`, `user_online`, `user_ip`, `user_identifier`, `user_timeout`, `user_lastvisit`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'fWQU59WMsWZVuok', 'redphoenix89@yahoo.com', '1', '1', '2006-12-20', 3, 'nI8RG0eJt3pilGZ8HFjy', 0, '', '', '', '1457235868'),
(2, 'thanh', '4b9bb70e705d1b2979ecd9446b1ddbae', '', 'thanhsnguyen@hotmail.com', '1', '', '2016-03-05 1', 3, 'wj7QHTXfb1lFd7ywQjjv', 1, '::1', '02be7e8e88d3c8a2bc8b8a8625622347', '1462057606', '1462045643'),
(3, 'pisi', '0e98a608a5fbe2922cc74d400f077a16', '', 'hello@hrlo.com', '1', '', '2016-04-29 2', 1, '3y5KUTbGwf0h1Y242jIB', 0, '', '', '', '1461987640');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `media_album`
--
ALTER TABLE `media_album`
  ADD PRIMARY KEY (`album_id`);

--
-- Indexes for table `media_cat`
--
ALTER TABLE `media_cat`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `media_comment`
--
ALTER TABLE `media_comment`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `media_config`
--
ALTER TABLE `media_config`
  ADD PRIMARY KEY (`config_name`);

--
-- Indexes for table `media_data`
--
ALTER TABLE `media_data`
  ADD PRIMARY KEY (`m_id`),
  ADD KEY `m_title` (`m_title`);

--
-- Indexes for table `media_gift`
--
ALTER TABLE `media_gift`
  ADD PRIMARY KEY (`gift_id`);

--
-- Indexes for table `media_online`
--
ALTER TABLE `media_online`
  ADD KEY `timestamp` (`timestamp`),
  ADD KEY `ip` (`ip`);

--
-- Indexes for table `media_playlist`
--
ALTER TABLE `media_playlist`
  ADD PRIMARY KEY (`playlist_id`);

--
-- Indexes for table `media_singer`
--
ALTER TABLE `media_singer`
  ADD PRIMARY KEY (`singer_id`);

--
-- Indexes for table `media_tpl`
--
ALTER TABLE `media_tpl`
  ADD PRIMARY KEY (`tpl_id`);

--
-- Indexes for table `media_user`
--
ALTER TABLE `media_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `media_album`
--
ALTER TABLE `media_album`
  MODIFY `album_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `media_cat`
--
ALTER TABLE `media_cat`
  MODIFY `cat_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `media_comment`
--
ALTER TABLE `media_comment`
  MODIFY `comment_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `media_data`
--
ALTER TABLE `media_data`
  MODIFY `m_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `media_playlist`
--
ALTER TABLE `media_playlist`
  MODIFY `playlist_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `media_singer`
--
ALTER TABLE `media_singer`
  MODIFY `singer_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `media_tpl`
--
ALTER TABLE `media_tpl`
  MODIFY `tpl_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `media_user`
--
ALTER TABLE `media_user`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
