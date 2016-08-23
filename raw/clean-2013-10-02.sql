-- phpMyAdmin SQL Dump
-- version 3.3.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2013 at 05:05 PM
-- Server version: 5.5.25
-- PHP Version: 5.4.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dev_inw`
--

-- --------------------------------------------------------

--
-- Table structure for table `ABCategory`
--

CREATE TABLE IF NOT EXISTS `ABCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catname` varchar(150) NOT NULL,
  `catvalue` varchar(150) NOT NULL,
  `TKcat1` varchar(150) NOT NULL,
  `TKcat2` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=67 ;

-- --------------------------------------------------------

--
-- Table structure for table `ABSettings`
--

CREATE TABLE IF NOT EXISTS `ABSettings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articlelength` text NOT NULL,
  `numarticles` int(11) NOT NULL,
  `spinning` int(11) NOT NULL,
  `synonyms` int(11) NOT NULL,
  `sig_moreinfo` text NOT NULL,
  `prlink` text NOT NULL,
  `termsandconditions` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `ALNCategory`
--

CREATE TABLE IF NOT EXISTS `ALNCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catname` varchar(150) NOT NULL,
  `catvalue` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `ArticleMarketingCampaign`
--

CREATE TABLE IF NOT EXISTS `ArticleMarketingCampaign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `ABCategory` int(11) NOT NULL,
  `primarykeyword` varchar(200) NOT NULL,
  `url` varchar(300) NOT NULL,
  `datesubmitted` datetime NOT NULL,
  `tags` varchar(300) NOT NULL,
  `companyprofile` int(11) NOT NULL,
  `articletitle` text NOT NULL,
  `articletext` text NOT NULL,
  `TKSuccess` int(11) NOT NULL DEFAULT '0',
  `prid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=219 ;

-- --------------------------------------------------------

--
-- Table structure for table `canned_messages`
--

CREATE TABLE IF NOT EXISTS `canned_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `txt` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `desc` text NOT NULL,
  `leftdesc` text,
  `parent_id` int(11) NOT NULL,
  `url` varchar(50) NOT NULL DEFAULT '',
  `display` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=298 ;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `profilename` varchar(150) DEFAULT NULL,
  `contact` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address1` text NOT NULL,
  `address2` varchar(100) DEFAULT NULL,
  `zip` varchar(21) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `url` varchar(150) NOT NULL,
  `details` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `email` varchar(200) NOT NULL,
  `location` text NOT NULL,
  `countryid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=146475 ;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=489 ;

-- --------------------------------------------------------

--
-- Table structure for table `emailsettings`
--

CREATE TABLE IF NOT EXISTS `emailsettings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `replyname` varchar(100) DEFAULT NULL,
  `replyemail` varchar(100) DEFAULT NULL,
  `desc` text NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=67 ;

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE IF NOT EXISTS `features` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `detail` text NOT NULL,
  `class` varchar(20) NOT NULL,
  `priority` tinyint(2) NOT NULL,
  `form_display_order` tinyint(2) NOT NULL,
  `global` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `feeds`
--

CREATE TABLE IF NOT EXISTS `feeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `categories` varchar(255) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `item_count` int(11) NOT NULL,
  `footer` text NOT NULL,
  `allpremium` int(11) NOT NULL DEFAULT '0',
  `showlogo` int(11) NOT NULL DEFAULT '0',
  `fullcontent` int(11) NOT NULL DEFAULT '0',
  `partialcontent` int(11) NOT NULL DEFAULT '0',
  `partialcontentchars` int(11) NOT NULL DEFAULT '0',
  `numchars` int(11) NOT NULL,
  `partialcontentrandom` int(11) NOT NULL DEFAULT '0',
  `randomchars1` int(11) NOT NULL,
  `randomchars2` int(11) NOT NULL,
  `addintro` int(11) NOT NULL DEFAULT '0',
  `INewsLinkText` text NOT NULL,
  `feedtitle` varchar(350) NOT NULL,
  `showfullfeed` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Table structure for table `getnews_settings`
--

CREATE TABLE IF NOT EXISTS `getnews_settings` (
  `setting` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `helpdeskaccount`
--

CREATE TABLE IF NOT EXISTS `helpdeskaccount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inewsusername` varchar(100) NOT NULL,
  `helpdeskusername` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `IntroSettings`
--

CREATE TABLE IF NOT EXISTS `IntroSettings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting` text NOT NULL,
  `value` text NOT NULL,
  `input_type` varchar(1) NOT NULL DEFAULT 'T',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `IPFlag_FlaggedIPs`
--

CREATE TABLE IF NOT EXISTS `IPFlag_FlaggedIPs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `blocked` char(1) NOT NULL DEFAULT '0',
  `statuschangetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8358 ;

-- --------------------------------------------------------

--
-- Table structure for table `IPFlag_IPDetails`
--

CREATE TABLE IF NOT EXISTS `IPFlag_IPDetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `operation` varchar(9) NOT NULL,
  `operationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8530 ;

-- --------------------------------------------------------

--
-- Table structure for table `IPFLag_Settings`
--

CREATE TABLE IF NOT EXISTS `IPFLag_Settings` (
  `name` varchar(50) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `loginid` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  PRIMARY KEY (`loginid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `misc_settings`
--

CREATE TABLE IF NOT EXISTS `misc_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=58 ;

-- --------------------------------------------------------

--
-- Table structure for table `money_site`
--

CREATE TABLE IF NOT EXISTS `money_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `base_url` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `networksites`
--

CREATE TABLE IF NOT EXISTS `networksites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(200) NOT NULL,
  `url` varchar(255) NOT NULL,
  `logo` varchar(200) DEFAULT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `networksite_deals`
--

CREATE TABLE IF NOT EXISTS `networksite_deals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_id` int(11) NOT NULL,
  `imagebadge` varchar(250) DEFAULT NULL,
  `uniqcode` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `networksite_deal_details`
--

CREATE TABLE IF NOT EXISTS `networksite_deal_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nid` int(11) NOT NULL,
  `ndid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `news_sites`
--

CREATE TABLE IF NOT EXISTS `news_sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `ispublished` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Table structure for table `nr_bar`
--

CREATE TABLE IF NOT EXISTS `nr_bar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `display_name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `nr_bar_record`
--

CREATE TABLE IF NOT EXISTS `nr_bar_record` (
  `bar_stage_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  UNIQUE KEY `bar_stage_id` (`bar_stage_id`,`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_bar_stage`
--

CREATE TABLE IF NOT EXISTS `nr_bar_stage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bar_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `display_name` varchar(64) NOT NULL,
  `info_link` varchar(2048) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bar_id` (`bar_id`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `nr_beat`
--

CREATE TABLE IF NOT EXISTS `nr_beat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `beat_group_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `beat` (`beat_group_id`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=328 ;

-- --------------------------------------------------------

--
-- Table structure for table `nr_beat_group`
--

CREATE TABLE IF NOT EXISTS `nr_beat_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `nr_campaign`
--

CREATE TABLE IF NOT EXISTS `nr_campaign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `subject` varchar(256) NOT NULL,
  `sender_name` varchar(128) NOT NULL,
  `sender_email` varchar(254) NOT NULL,
  `date_send` datetime NOT NULL,
  `is_sent` tinyint(1) NOT NULL,
  `is_draft` tinyint(1) NOT NULL,
  `contact_count` int(11) DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  `all_contacts` tinyint(1) NOT NULL,
  `is_send_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `search` (`company_id`,`name`,`id`),
  KEY `status` (`is_sent`,`is_draft`,`date_send`,`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `nr_campaign_data`
--

CREATE TABLE IF NOT EXISTS `nr_campaign_data` (
  `campaign_id` int(11) NOT NULL,
  `content` mediumblob NOT NULL,
  `contacts` mediumblob NOT NULL,
  PRIMARY KEY (`campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_campaign_x_contact_list`
--

CREATE TABLE IF NOT EXISTS `nr_campaign_x_contact_list` (
  `campaign_id` int(11) NOT NULL,
  `contact_list_id` int(11) NOT NULL,
  PRIMARY KEY (`campaign_id`,`contact_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_cat`
--

CREATE TABLE IF NOT EXISTS `nr_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_group_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `slug` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_id` (`cat_group_id`,`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=357 ;

-- --------------------------------------------------------

--
-- Table structure for table `nr_cat_group`
--

CREATE ALGORITHM=UNDEFINED VIEW `nr_cat_group` AS select `nr_cat`.`id` AS `id`,`nr_cat`.`name` AS `name` from `nr_cat` where (`nr_cat`.`id` = `nr_cat`.`cat_group_id`);

-- --------------------------------------------------------

--
-- Table structure for table `nr_company`
--

CREATE TABLE IF NOT EXISTS `nr_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `newsroom` varchar(64) DEFAULT NULL,
  `name` varchar(128) NOT NULL,
  `newsroom_timezone` varchar(64) NOT NULL,
  `newsroom_domain` varchar(128) DEFAULT NULL,
  `company_contact_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsroom` (`newsroom`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `nr_company_contact`
--

CREATE TABLE IF NOT EXISTS `nr_company_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `title` varchar(256) DEFAULT NULL,
  `image_id` int(11) DEFAULT NULL,
  `description` mediumtext,
  `email` varchar(254) DEFAULT NULL,
  `website` varchar(256) DEFAULT NULL,
  `skype` varchar(64) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `linkedin` varchar(64) DEFAULT NULL,
  `facebook` varchar(64) DEFAULT NULL,
  `twitter` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `nr_company_profile`
--

CREATE TABLE IF NOT EXISTS `nr_company_profile` (
  `company_id` int(11) NOT NULL,
  `address_street` varchar(256) DEFAULT NULL,
  `address_apt_suite` varchar(128) DEFAULT NULL,
  `address_zip` varchar(16) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `website` varchar(256) DEFAULT NULL,
  `address_country_id` int(11) DEFAULT NULL,
  `address_state` varchar(64) DEFAULT NULL,
  `address_city` varchar(64) DEFAULT NULL,
  `email` varchar(254) DEFAULT NULL,
  `type` enum('private','public') DEFAULT NULL,
  `beat_id` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `description` text,
  `summary` varchar(400) NOT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_contact`
--

CREATE TABLE IF NOT EXISTS `nr_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `email` varchar(254) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `title` varchar(256) NOT NULL,
  `notes` text NOT NULL,
  `twitter` varchar(64) NOT NULL,
  `company_name` varchar(64) NOT NULL,
  `country_id` int(11) NOT NULL,
  `beat_1_id` int(11) NOT NULL,
  `beat_2_id` int(11) NOT NULL,
  `beat_3_id` int(11) NOT NULL,
  `is_unsubscribed` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_id` (`company_id`,`email`),
  KEY `search` (`email`,`first_name`,`last_name`,`company_name`,`beat_1_id`,`beat_2_id`,`beat_3_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=72 ;

-- --------------------------------------------------------

--
-- Table structure for table `nr_contact_list`
--

CREATE TABLE IF NOT EXISTS `nr_contact_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `company_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `last_campaign_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`,`company_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `nr_contact_list_x_contact`
--

CREATE TABLE IF NOT EXISTS `nr_contact_list_x_contact` (
  `contact_list_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  PRIMARY KEY (`contact_list_id`,`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_contact_tag`
--

CREATE TABLE IF NOT EXISTS `nr_contact_tag` (
  `contact_id` int(11) NOT NULL,
  `value` varchar(32) NOT NULL,
  PRIMARY KEY (`contact_id`,`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_content`
--

CREATE TABLE IF NOT EXISTS `nr_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `type` enum('pr','news','audio','video','image','event') NOT NULL,
  `title` varchar(256) NOT NULL,
  `slug` varchar(64) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_publish` datetime NOT NULL,
  `is_published` tinyint(1) NOT NULL,
  `is_draft` tinyint(1) NOT NULL,
  `is_report_email_sent` tinyint(1) NOT NULL DEFAULT '0',
  `cover_image_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `list_on_status` (`company_id`,`type`,`is_published`,`is_draft`),
  KEY `scheduled` (`is_published`,`is_draft`,`date_publish`),
  KEY `search` (`title`(255)),
  KEY `report_status` (`company_id`,`type`,`date_publish`,`is_published`,`is_report_email_sent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

-- --------------------------------------------------------

--
-- Table structure for table `nr_content_data`
--

CREATE TABLE IF NOT EXISTS `nr_content_data` (
  `content_id` int(11) NOT NULL,
  `content` mediumblob,
  `post_to_facebook` tinyint(1) NOT NULL,
  `post_to_twitter` tinyint(1) NOT NULL,
  `post_id_facebook` varchar(64) DEFAULT NULL,
  `post_id_twitter` varchar(64) DEFAULT NULL,
  `supporting_quote` mediumtext,
  `supporting_quote_name` varchar(256) DEFAULT NULL,
  `supporting_quote_title` varchar(256) DEFAULT NULL,
  `rel_res_pri_title` varchar(128) DEFAULT NULL,
  `rel_res_pri_link` varchar(512) DEFAULT NULL,
  `rel_res_sec_title` varchar(128) DEFAULT NULL,
  `rel_res_sec_link` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`content_id`),
  KEY `post_to_social` (`post_to_facebook`,`post_to_twitter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_content_image`
--

CREATE TABLE IF NOT EXISTS `nr_content_image` (
  `content_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  PRIMARY KEY (`content_id`,`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_content_related`
--

CREATE TABLE IF NOT EXISTS `nr_content_related` (
  `content_id` int(11) NOT NULL,
  `content_id_far` int(11) NOT NULL,
  PRIMARY KEY (`content_id`,`content_id_far`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_content_tag`
--

CREATE TABLE IF NOT EXISTS `nr_content_tag` (
  `content_id` int(11) NOT NULL,
  `value` varchar(32) NOT NULL,
  PRIMARY KEY (`content_id`,`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_country`
--

CREATE TABLE IF NOT EXISTS `nr_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=489 ;

-- --------------------------------------------------------

--
-- Table structure for table `nr_event_type`
--

CREATE TABLE IF NOT EXISTS `nr_event_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `nr_image`
--

CREATE TABLE IF NOT EXISTS `nr_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `description` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=197 ;

-- --------------------------------------------------------

--
-- Table structure for table `nr_image_variant`
--

CREATE TABLE IF NOT EXISTS `nr_image_variant` (
  `image_id` int(11) NOT NULL,
  `stored_image_id` int(11) NOT NULL,
  `name` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`image_id`,`stored_image_id`,`name`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `nr_newsroom`
--
CREATE TABLE IF NOT EXISTS `nr_newsroom` (
`company_id` int(11)
,`company_contact_id` int(11)
,`company_name` varchar(128)
,`name` varchar(64)
,`domain` varchar(128)
,`timezone` varchar(64)
,`user_id` int(11)
);
-- --------------------------------------------------------

--
-- Table structure for table `nr_newsroom_custom`
--

CREATE TABLE IF NOT EXISTS `nr_newsroom_custom` (
  `company_id` int(11) NOT NULL,
  `logo_image_id` int(11) DEFAULT NULL,
  `back_image_id` int(11) DEFAULT NULL,
  `back_image_repeat` enum('repeat','no-repeat') NOT NULL,
  `link_color` char(7) DEFAULT NULL,
  `link_hover_color` char(7) DEFAULT NULL,
  `back_color` char(7) DEFAULT NULL,
  `text_color` char(7) DEFAULT NULL,
  `logo_back_color` varchar(11) NOT NULL,
  `header_color` char(7) NOT NULL,
  `headline` varchar(256) DEFAULT NULL,
  `ganal` varchar(16) DEFAULT NULL,
  `rel_res_pri_title` varchar(128) DEFAULT NULL,
  `rel_res_pri_link` varchar(512) DEFAULT NULL,
  `rel_res_sec_title` varchar(128) DEFAULT NULL,
  `rel_res_sec_link` varchar(512) DEFAULT NULL,
  `rel_res_ter_title` varchar(128) DEFAULT NULL,
  `rel_res_ter_link` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_pb_audio`
--

CREATE TABLE IF NOT EXISTS `nr_pb_audio` (
  `content_id` int(11) NOT NULL,
  `stored_file_id` int(11) NOT NULL,
  `summary` varchar(512) NOT NULL,
  `source` varchar(256) DEFAULT NULL,
  `license` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`content_id`),
  KEY `license` (`license`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_pb_event`
--

CREATE TABLE IF NOT EXISTS `nr_pb_event` (
  `content_id` int(11) NOT NULL,
  `event_type_id` int(11) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_finish` datetime DEFAULT NULL,
  `is_all_day` tinyint(1) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_code` varchar(128) DEFAULT NULL,
  `address` varchar(128) DEFAULT NULL,
  `summary` varchar(400) NOT NULL,
  PRIMARY KEY (`content_id`),
  KEY `event` (`event_type_id`,`date_start`,`date_finish`),
  KEY `price` (`price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_pb_image`
--

CREATE TABLE IF NOT EXISTS `nr_pb_image` (
  `content_id` int(11) NOT NULL,
  `summary` varchar(512) NOT NULL,
  `image_id` int(11) NOT NULL,
  `source` varchar(256) DEFAULT NULL,
  `license` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`content_id`),
  KEY `license` (`license`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_pb_news`
--

CREATE TABLE IF NOT EXISTS `nr_pb_news` (
  `content_id` int(11) NOT NULL,
  `summary` varchar(512) NOT NULL,
  `cat_1_id` int(11) DEFAULT NULL,
  `cat_2_id` int(11) DEFAULT NULL,
  `cat_3_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`content_id`),
  KEY `cats` (`cat_1_id`,`cat_2_id`,`cat_3_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_pb_pr`
--

CREATE TABLE IF NOT EXISTS `nr_pb_pr` (
  `content_id` int(11) NOT NULL,
  `summary` varchar(512) NOT NULL,
  `is_premium` tinyint(1) NOT NULL,
  `is_under_review` tinyint(1) NOT NULL,
  `cat_1_id` int(11) DEFAULT NULL,
  `cat_2_id` int(11) DEFAULT NULL,
  `cat_3_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`content_id`),
  KEY `premium` (`is_premium`,`is_under_review`),
  KEY `cats` (`cat_1_id`,`cat_2_id`,`cat_3_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_pb_video`
--

CREATE TABLE IF NOT EXISTS `nr_pb_video` (
  `content_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `external_provider` enum('youtube') NOT NULL,
  `external_video_id` varchar(64) NOT NULL,
  `external_author` varchar(256) DEFAULT NULL,
  `external_duration` int(11) DEFAULT NULL,
  `summary` varchar(512) NOT NULL,
  `source` varchar(256) DEFAULT NULL,
  `license` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`content_id`),
  KEY `external_provider` (`external_provider`,`external_video_id`),
  KEY `license` (`license`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_report_setting`
--

CREATE TABLE IF NOT EXISTS `nr_report_setting` (
  `company_id` int(11) NOT NULL,
  `overall_email` varchar(1024) DEFAULT NULL,
  `overall_when` enum('weekly','monthly') DEFAULT NULL,
  `pr_email` varchar(1024) DEFAULT NULL,
  `pr_when` enum('7','30') DEFAULT NULL,
  PRIMARY KEY (`company_id`),
  KEY `overall_when` (`overall_when`),
  KEY `pr_when` (`pr_when`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_social_auth_facebook`
--

CREATE TABLE IF NOT EXISTS `nr_social_auth_facebook` (
  `company_id` int(11) NOT NULL,
  `access_token` varchar(256) NOT NULL,
  `page` varchar(64) DEFAULT NULL,
  `date_renewed` datetime NOT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_social_auth_twitter`
--

CREATE TABLE IF NOT EXISTS `nr_social_auth_twitter` (
  `company_id` int(11) NOT NULL,
  `oauth_token` varchar(256) NOT NULL,
  `oauth_token_secret` varchar(256) NOT NULL,
  `username` varchar(64) NOT NULL,
  `date_renewed` datetime NOT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nr_stored_file`
--

CREATE TABLE IF NOT EXISTS `nr_stored_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filename` (`filename`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

-- --------------------------------------------------------

--
-- Table structure for table `nr_stored_image`
--

CREATE TABLE IF NOT EXISTS `nr_stored_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(64) NOT NULL,
  `width` smallint(6) NOT NULL,
  `height` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filename` (`filename`),
  KEY `width` (`width`,`height`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=491 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `nr_user`
--
CREATE TABLE IF NOT EXISTS `nr_user` (
`id` int(11)
,`first_name` varchar(50)
,`last_name` varchar(50)
,`password` varchar(50)
,`email` varchar(100)
,`is_active` smallint(1)
,`is_admin` tinyint(1)
,`credits` int(1)
,`is_verified` int(1)
);
-- --------------------------------------------------------

--
-- Table structure for table `order_numbers`
--

CREATE TABLE IF NOT EXISTS `order_numbers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deal_id` tinyint(3) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `price` double NOT NULL,
  `mktime` int(14) unsigned NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12529 ;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE IF NOT EXISTS `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `details` text NOT NULL,
  `page_heading` text NOT NULL,
  `page_descr` text NOT NULL,
  `image` varchar(50) NOT NULL,
  `enabled` smallint(1) NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `package_deals`
--

CREATE TABLE IF NOT EXISTS `package_deals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `descr` text NOT NULL,
  `price` double NOT NULL,
  `myaccount_text` text NOT NULL,
  `total_profiles` tinyint(3) NOT NULL DEFAULT '1',
  `total_websites` tinyint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `package_deals_v1`
--

CREATE TABLE IF NOT EXISTS `package_deals_v1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `descr` text NOT NULL,
  `price` double NOT NULL,
  `myaccount_text` text NOT NULL,
  `total_profiles` tinyint(3) NOT NULL DEFAULT '1',
  `total_websites` tinyint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `package_deal_details`
--

CREATE TABLE IF NOT EXISTS `package_deal_details` (
  `deal_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `deal_type` enum('MONTHLY','WEEKLY','DAILY') NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`deal_id`,`package_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `package_features`
--

CREATE TABLE IF NOT EXISTS `package_features` (
  `packageid` int(11) NOT NULL,
  `featureid` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`packageid`,`featureid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(50) NOT NULL,
  `page_title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `main_template` varchar(50) NOT NULL,
  `header` varchar(50) NOT NULL,
  `footer` varchar(50) NOT NULL,
  `leftbar` varchar(50) NOT NULL,
  `rightbar` varchar(50) NOT NULL,
  `metakeywords` text NOT NULL,
  `metadescription` text NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_name` (`page_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=76 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages_v1`
--

CREATE TABLE IF NOT EXISTS `pages_v1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(50) NOT NULL,
  `page_title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `main_template` varchar(50) NOT NULL,
  `header` varchar(50) NOT NULL,
  `footer` varchar(50) NOT NULL,
  `leftbar` varchar(50) NOT NULL,
  `rightbar` varchar(50) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_name` (`page_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- Table structure for table `pdf_views`
--

CREATE TABLE IF NOT EXISTS `pdf_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prsid` int(11) NOT NULL,
  `timevisited` datetime NOT NULL,
  `IP` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10095 ;

-- --------------------------------------------------------

--
-- Table structure for table `prs`
--

CREATE TABLE IF NOT EXISTS `prs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `parent_cat` int(11) NOT NULL,
  `sub_cat` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `summary` varchar(250) CHARACTER SET utf8 NOT NULL,
  `detail` text CHARACTER SET utf8 NOT NULL,
  `packageid` int(11) NOT NULL DEFAULT '1',
  `is_upgrade` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_package_id` int(11) NOT NULL DEFAULT '0',
  `url_title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `status` smallint(1) NOT NULL,
  `payment_status` smallint(1) NOT NULL,
  `views` int(11) NOT NULL,
  `approve_time` datetime NOT NULL,
  `releasedate` date DEFAULT NULL,
  `publishingtime` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT 'time of publishing ',
  `partner_id` varchar(40) CHARACTER SET utf8 NOT NULL,
  `c_contact` varchar(100) CHARACTER SET utf8 NOT NULL,
  `c_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `c_address1` varchar(100) CHARACTER SET utf8 NOT NULL,
  `c_address2` varchar(100) CHARACTER SET utf8 NOT NULL,
  `c_zip` varchar(21) CHARACTER SET utf8 NOT NULL,
  `c_phone` varchar(20) CHARACTER SET utf8 NOT NULL,
  `c_url` varchar(150) CHARACTER SET utf8 NOT NULL,
  `c_details` text CHARACTER SET utf8 NOT NULL,
  `c_save` int(11) NOT NULL DEFAULT '0',
  `location` varchar(250) CHARACTER SET utf8 NOT NULL,
  `countryid` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `publishedon` tinyint(2) DEFAULT NULL,
  `updated` datetime NOT NULL,
  `iframe` tinyint(1) NOT NULL DEFAULT '1',
  `videoplace` tinyint(1) NOT NULL DEFAULT '1',
  `videourl` text CHARACTER SET utf8,
  `videoOrigURL` text CHARACTER SET utf8 NOT NULL,
  `incharge` int(11) NOT NULL,
  `no_payment_reminder` tinyint(1) NOT NULL,
  `canned_msg_id` int(11) NOT NULL,
  `done_editing` tinyint(4) NOT NULL,
  `canned_msg_list` varchar(250) CHARACTER SET utf8 NOT NULL,
  `template` varchar(255) CHARACTER SET utf8 NOT NULL,
  `header_template` varchar(50) CHARACTER SET utf8 NOT NULL,
  `footer_template` varchar(50) CHARACTER SET utf8 NOT NULL,
  `exclude_in_feeds` tinyint(1) NOT NULL,
  `adsense` tinyint(1) NOT NULL,
  `exclude_on_mainpage` tinyint(1) NOT NULL,
  `from_rss` tinyint(1) NOT NULL,
  `exclude_in_sitemap` tinyint(1) NOT NULL,
  `top_done` tinyint(1) unsigned NOT NULL,
  `pdf_generated` int(11) NOT NULL DEFAULT '0',
  `report_screenshot` text CHARACTER SET utf8 NOT NULL,
  `guid` text CHARACTER SET utf8 NOT NULL,
  `time_pdf_generated` datetime NOT NULL,
  `pdf_reminders` int(11) NOT NULL DEFAULT '0',
  `newssiteshortlink` text CHARACTER SET utf8 NOT NULL,
  `bi_link_submitted` int(11) NOT NULL DEFAULT '0',
  `bi_pdf_submitted` int(11) NOT NULL DEFAULT '0',
  `date_submitted_bi` date NOT NULL,
  `date_resubmitted_bi` varchar(20) CHARACTER SET utf8 NOT NULL,
  `prs_pdf_generated` int(11) NOT NULL DEFAULT '0',
  `docstoc_id` varchar(50) CHARACTER SET utf8 NOT NULL,
  `slideshare_id` varchar(50) CHARACTER SET utf8 NOT NULL,
  `slideshare_url` varchar(250) CHARACTER SET utf8 NOT NULL,
  `scribd_id` varchar(50) CHARACTER SET utf8 NOT NULL,
  `issuu_doc_id` varchar(50) CHARACTER SET utf8 NOT NULL,
  `divshare_submit` varchar(2) CHARACTER SET utf8 NOT NULL,
  `divshare_url` varchar(250) CHARACTER SET utf8 NOT NULL,
  `edocr_url` varchar(150) CHARACTER SET utf8 NOT NULL,
  `bi_docstoc_submitted` int(11) NOT NULL DEFAULT '0',
  `bi_slideshare_submitted` int(11) NOT NULL DEFAULT '0',
  `bi_scribd_submitted` int(11) NOT NULL DEFAULT '0',
  `bi_issuu_submitted` int(11) NOT NULL DEFAULT '0',
  `bi_divshare_submitted` int(11) NOT NULL DEFAULT '0',
  `regenerate_cache` int(11) NOT NULL DEFAULT '1',
  `bluga_problem` int(11) NOT NULL DEFAULT '0',
  `reseller_mailsent` int(11) NOT NULL DEFAULT '0',
  `reseller_notes` varchar(300) CHARACTER SET utf8 NOT NULL,
  `throughAPI` int(11) NOT NULL DEFAULT '0',
  `IntroAssigned` int(11) NOT NULL DEFAULT '0' COMMENT '0 means not assigned, a positive value is the ID of the writer to which intro is assigned, -1 means the intro has been submitted',
  `IntroReviewed` int(11) NOT NULL DEFAULT '0' COMMENT '0 means the Intro is not reviewed. 1 means intro is reviewd, 2 means intro is not required to be reviewed meaning that review setting is off',
  `writerID` int(11) NOT NULL DEFAULT '0',
  `recustomer_email` varchar(250) NOT NULL,
  `fifemail` text NOT NULL,
  `socialgoogle` text NOT NULL,
  `socialfacebook` text NOT NULL,
  `socialfblike` text NOT NULL,
  `socialfbrec` text NOT NULL,
  `socialrss` text NOT NULL,
  `socialtwitter` text NOT NULL,
  `socialpinterest` text NOT NULL,
  `socialdelicious` text NOT NULL,
  `socialtumblr` text NOT NULL,
  `socialyoutube` text NOT NULL,
  `sociallinkedin` text NOT NULL,
  `percentdup` int(11) NOT NULL DEFAULT '100',
  `nofollow` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_prs_status` (`status`),
  KEY `ind_prs_comp` (`parent_cat`,`status`,`packageid`),
  KEY `sub_cat` (`sub_cat`),
  KEY `approve_time` (`approve_time`),
  KEY `exclude_in_feeds` (`exclude_in_feeds`),
  KEY `exclude_on_mainpage` (`exclude_on_mainpage`),
  KEY `exclude_in_sitemap` (`exclude_in_sitemap`),
  KEY `userid` (`userid`),
  KEY `payment_status` (`payment_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=241036 ;

-- --------------------------------------------------------

--
-- Table structure for table `prs_quick`
--

CREATE TABLE IF NOT EXISTS `prs_quick` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `parent_cat` int(11) NOT NULL,
  `sub_cat` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `summary` varchar(250) CHARACTER SET utf8 NOT NULL,
  `detail` text CHARACTER SET utf8 NOT NULL,
  `packageid` int(11) NOT NULL DEFAULT '1',
  `is_upgrade` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_package_id` int(11) NOT NULL DEFAULT '0',
  `url_title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `status` smallint(1) NOT NULL COMMENT '0=not submitted by admin, 1=submitted by admin, 2=rejected by user',
  `payment_status` smallint(1) NOT NULL,
  `views` int(11) NOT NULL,
  `approve_time` datetime NOT NULL,
  `releasedate` date DEFAULT NULL,
  `publishingtime` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT 'time of publishing ',
  `partner_id` varchar(40) CHARACTER SET utf8 NOT NULL,
  `c_contact` varchar(100) CHARACTER SET utf8 NOT NULL,
  `c_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `c_address1` varchar(100) CHARACTER SET utf8 NOT NULL,
  `c_address2` varchar(100) CHARACTER SET utf8 NOT NULL,
  `c_zip` varchar(21) CHARACTER SET utf8 NOT NULL,
  `c_phone` varchar(20) CHARACTER SET utf8 NOT NULL,
  `c_url` varchar(150) CHARACTER SET utf8 NOT NULL,
  `c_details` text CHARACTER SET utf8 NOT NULL,
  `location` varchar(250) CHARACTER SET utf8 NOT NULL,
  `countryid` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `publishedon` tinyint(2) DEFAULT NULL,
  `updated` datetime NOT NULL,
  `iframe` tinyint(1) NOT NULL DEFAULT '1',
  `videoplace` tinyint(1) NOT NULL DEFAULT '1',
  `videourl` text CHARACTER SET utf8,
  `videoOrigURL` text CHARACTER SET utf8 NOT NULL,
  `tags` text NOT NULL,
  `keyword1` text NOT NULL,
  `link1` text NOT NULL,
  `keyword2` text NOT NULL,
  `link2` text NOT NULL,
  `logo` text NOT NULL,
  `image1` text NOT NULL,
  `image2` text NOT NULL,
  `image3` text NOT NULL,
  `image4` text NOT NULL,
  `image5` text NOT NULL,
  `file1` text NOT NULL,
  `file2` text NOT NULL,
  `uploadid` int(11) NOT NULL,
  `orderid` varchar(100) NOT NULL,
  `useremail` varchar(250) NOT NULL,
  `istitleinc` int(11) NOT NULL DEFAULT '0',
  `iscompinfoinc` int(11) NOT NULL DEFAULT '0',
  `islocinc` int(11) NOT NULL DEFAULT '0',
  `islogoinc` int(11) NOT NULL DEFAULT '0',
  `isaddimages` int(11) NOT NULL DEFAULT '0',
  `isaddfilesinc` int(11) NOT NULL DEFAULT '0',
  `isaddlinksinc` int(11) NOT NULL DEFAULT '0',
  `isvideoinc` int(11) NOT NULL DEFAULT '0',
  `previewbeforesubmit` int(11) NOT NULL DEFAULT '0',
  `adminsubmitted` datetime NOT NULL,
  `RejectionNotes` text NOT NULL,
  `prID` int(11) NOT NULL DEFAULT '0',
  `customerEditStatus` int(11) NOT NULL DEFAULT '0',
  `customerEditDate` datetime NOT NULL,
  `customerEditTitle` int(11) NOT NULL DEFAULT '0',
  `customerEditLoc` int(11) NOT NULL DEFAULT '0',
  `customerEditCDetails` int(11) NOT NULL DEFAULT '0',
  `socialgoogle` text NOT NULL,
  `socialfacebook` text NOT NULL,
  `socialfblike` text NOT NULL,
  `socialfbrec` text NOT NULL,
  `socialrss` text NOT NULL,
  `socialtwitter` text NOT NULL,
  `socialpinterest` text NOT NULL,
  `socialdelicious` text NOT NULL,
  `socialtumblr` text NOT NULL,
  `socialyoutube` text NOT NULL,
  `sociallinkedin` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17140 ;

-- --------------------------------------------------------

--
-- Table structure for table `prs_quick_history`
--

CREATE TABLE IF NOT EXISTS `prs_quick_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qprid` int(11) NOT NULL,
  `rejectedby` varchar(30) NOT NULL,
  `rejectionreason` text NOT NULL,
  `title` text NOT NULL,
  `summary` text NOT NULL,
  `detail` text NOT NULL,
  `aboutcompany` text NOT NULL,
  `datewritten` datetime NOT NULL,
  `rejectiondate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `PRWritingSettings`
--

CREATE TABLE IF NOT EXISTS `PRWritingSettings` (
  `id` int(11) NOT NULL DEFAULT '0',
  `setting` text NOT NULL,
  `value` text NOT NULL,
  `input_type` varchar(1) NOT NULL DEFAULT 'T'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `PRWriting_History`
--

CREATE TABLE IF NOT EXISTS `PRWriting_History` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `rejectedby` varchar(30) NOT NULL,
  `rejectionreason` text NOT NULL,
  `title` text NOT NULL,
  `summary` text NOT NULL,
  `detail` text NOT NULL,
  `aboutcompany` text NOT NULL,
  `datewritten` datetime NOT NULL,
  `rejectiondate` datetime NOT NULL,
  `EditorRejectionComments` text NOT NULL,
  `EditorRejectionCommentsDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1336 ;

-- --------------------------------------------------------

--
-- Table structure for table `pr_features`
--

CREATE TABLE IF NOT EXISTS `pr_features` (
  `prid` int(11) NOT NULL,
  `featureid` int(11) NOT NULL,
  `index` smallint(6) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`prid`,`featureid`,`index`),
  KEY `featureid` (`featureid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pr_intro`
--

CREATE TABLE IF NOT EXISTS `pr_intro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prid` int(11) NOT NULL,
  `introtext` text NOT NULL,
  `dateadded` datetime NOT NULL,
  `used` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=102 ;

-- --------------------------------------------------------

--
-- Table structure for table `pr_networksites`
--

CREATE TABLE IF NOT EXISTS `pr_networksites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nid` int(11) NOT NULL,
  `prid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pr_tags`
--

CREATE TABLE IF NOT EXISTS `pr_tags` (
  `pr_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pr_id`,`tag_id`),
  KEY `ind_pr_tags_comp` (`tag_id`,`pr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `RandomFeeds`
--

CREATE TABLE IF NOT EXISTS `RandomFeeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schemeid` int(11) NOT NULL,
  `additionallink` int(11) NOT NULL,
  `companysitelinktext` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `raw_payments`
--

CREATE TABLE IF NOT EXISTS `raw_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(5) NOT NULL DEFAULT '2CO',
  `datetime` datetime NOT NULL,
  `orderid` varchar(100) NOT NULL,
  `prid` int(11) NOT NULL,
  `deal_order_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` varchar(40) NOT NULL,
  `raw_data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10116 ;

-- --------------------------------------------------------

--
-- Table structure for table `ResellerPRFormSettings`
--

CREATE TABLE IF NOT EXISTS `ResellerPRFormSettings` (
  `name` varchar(50) NOT NULL,
  `value` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ResellerPROrders`
--

CREATE TABLE IF NOT EXISTS `ResellerPROrders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transactioncode` varchar(50) NOT NULL,
  `yourname` varchar(50) NOT NULL,
  `forum_username` varchar(100) NOT NULL,
  `companyid` int(11) NOT NULL DEFAULT '0',
  `location` varchar(100) NOT NULL,
  `country` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contactname` varchar(100) NOT NULL,
  `companyname` varchar(255) NOT NULL,
  `companyaddress` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `url` varchar(255) NOT NULL,
  `companydetails` text NOT NULL,
  `category` int(11) NOT NULL,
  `sub_category` int(11) DEFAULT NULL,
  `angle` varchar(30) NOT NULL COMMENT 'angle field can have four values: problem, discount, website, announcement',
  `productdetails` text NOT NULL,
  `discountoffer` text NOT NULL,
  `siteorproductdetails` text NOT NULL,
  `companyannouncement` text NOT NULL,
  `otherangle` text NOT NULL,
  `primarykeywords` text NOT NULL,
  `secondaykeyword1` text NOT NULL,
  `secondaykeyword2` text NOT NULL,
  `secondaykeyword3` text NOT NULL,
  `tags` text NOT NULL,
  `logo` varchar(100) NOT NULL,
  `addimage1` varchar(100) NOT NULL,
  `addimage2` varchar(100) NOT NULL,
  `addimage3` varchar(100) NOT NULL,
  `addimage4` text NOT NULL,
  `addimage5` text NOT NULL,
  `file1` text NOT NULL,
  `file2` text NOT NULL,
  `links` text NOT NULL,
  `addlinks` text NOT NULL,
  `video` text NOT NULL,
  `videoplace` text NOT NULL,
  `videourl` text NOT NULL,
  `videoOrigURL` text NOT NULL,
  `comments` text NOT NULL,
  `dateadded` datetime NOT NULL,
  `dateamended` datetime NOT NULL,
  `assigned` int(11) NOT NULL DEFAULT '0' COMMENT 'assigned=1 means currently assigned to a writer, assigned=2 means the writer has submitted the PR',
  `dateassigned` datetime NOT NULL,
  `writerid` int(11) NOT NULL,
  `title` text NOT NULL,
  `summary` text NOT NULL,
  `detail` text NOT NULL,
  `link_url_1` varchar(180) DEFAULT NULL,
  `link_url_2` varchar(180) DEFAULT NULL,
  `link_url_3` varchar(180) DEFAULT NULL,
  `link_text_1` varchar(50) DEFAULT NULL,
  `link_text_2` varchar(50) DEFAULT NULL,
  `link_text_3` varchar(50) DEFAULT NULL,
  `additional_link_url_1` varchar(180) DEFAULT NULL,
  `additional_link_url_2` varchar(180) DEFAULT NULL,
  `additional_link_url_3` varchar(180) DEFAULT NULL,
  `additional_link_text_1` varchar(50) DEFAULT NULL,
  `additional_link_text_2` varchar(50) DEFAULT NULL,
  `additional_link_text_3` varchar(50) DEFAULT NULL,
  `aboutcompany` text NOT NULL,
  `dateWriterSubmitted` datetime NOT NULL,
  `reviewed` int(11) NOT NULL DEFAULT '0' COMMENT 'reviewed=0 means the PR is not reviewed yet. reviewed=1 means, the PR is reviewed and submitted, reviewed=2 means there was no need to review the PR before being queued',
  `dateReviewed` datetime NOT NULL,
  `rejected` int(11) NOT NULL DEFAULT '0',
  `dateRejected` datetime NOT NULL,
  `datePRadded` datetime NOT NULL,
  `prID` int(11) NOT NULL,
  `lastreviewedby` varchar(30) NOT NULL COMMENT 'this field can have values: customer or reseller',
  `RejectionNotes` text NOT NULL,
  `EditorRejectionComments` text NOT NULL,
  `EditorRejectionCommentsDate` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0=Unassigned 1=Assigned to a Writer 2=Written and sent to reseller 3=reseller reject (as editor) and sent to writer 4=reseller reject and sent to admin (as editor) 5=reseller sends to customer 6=customer reject-sent to reseller as editor 7=customer reject',
  `laststatusdate` datetime NOT NULL,
  `WriterCommentstoAdmin` text NOT NULL,
  `WriterCommentstoAdminDate` datetime NOT NULL,
  `adminEditingtoCustomer` int(11) NOT NULL DEFAULT '0' COMMENT '0 is the default status, 1 means sent to the customer, 2 means the customer replied and the editor action is pending. 4 means the writer has sent to the editor',
  `AdmintoCustomerNotes` text NOT NULL,
  `AdmintoCustomerDate` datetime NOT NULL,
  `customerEditedDate` datetime NOT NULL,
  `CustomerEditCommentstoWriter` text NOT NULL,
  `AdmintoWriterDate` datetime NOT NULL,
  `PRMarketer` int(11) NOT NULL DEFAULT '0',
  `dateEditorEdited` datetime NOT NULL,
  `socialgoogle` text NOT NULL,
  `socialfacebook` text NOT NULL,
  `socialfblike` text NOT NULL,
  `socialfbrec` text NOT NULL,
  `socialrss` text NOT NULL,
  `socialtwitter` text NOT NULL,
  `socialpinterest` text NOT NULL,
  `socialdelicious` text NOT NULL,
  `socialtumblr` text NOT NULL,
  `socialyoutube` text NOT NULL,
  `sociallinkedin` text NOT NULL,
  `islogoinc` int(11) NOT NULL DEFAULT '0',
  `isaddimages` int(11) NOT NULL DEFAULT '0',
  `isaddfilesinc` int(11) NOT NULL DEFAULT '0',
  `isaddlinksinc` int(11) NOT NULL DEFAULT '0',
  `isvideoinc` int(11) NOT NULL DEFAULT '0',
  `issocialinc` int(11) NOT NULL DEFAULT '0',
  `orderPRWriting` int(11) NOT NULL DEFAULT '0',
  `orderPRDistribution` int(11) NOT NULL DEFAULT '0',
  `orderRush` int(11) NOT NULL DEFAULT '0',
  `paymentID` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1871 ;

-- --------------------------------------------------------

--
-- Table structure for table `ResellerSiteCodeNumbers`
--

CREATE TABLE IF NOT EXISTS `ResellerSiteCodeNumbers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` text NOT NULL,
  `custname` varchar(50) NOT NULL,
  `custemail` varchar(200) NOT NULL,
  `item_name` varchar(200) NOT NULL,
  `ipn_track_id` varchar(100) NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `dateordered` datetime NOT NULL,
  `used` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14682 ;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE IF NOT EXISTS `review` (
  `clients_id` int(15) NOT NULL AUTO_INCREMENT,
  `client_id` varchar(150) NOT NULL DEFAULT '',
  `rating` tinyint(2) NOT NULL DEFAULT '0',
  `proj_date` date NOT NULL DEFAULT '0000-00-00',
  `proj_desc` text NOT NULL,
  `photoname` text NOT NULL,
  `companyname` text NOT NULL,
  `feedback` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `emailid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`clients_id`),
  FULLTEXT KEY `feedback` (`feedback`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE IF NOT EXISTS `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `script_url` text NOT NULL,
  `date` varchar(4) NOT NULL,
  `rateing` varchar(4) NOT NULL,
  `photo` varchar(4) NOT NULL,
  `dateformat` varchar(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `input_type` enum('F','T') NOT NULL DEFAULT 'T',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `socialmedia`
--

CREATE TABLE IF NOT EXISTS `socialmedia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  `name` varchar(400) NOT NULL,
  `logo` varchar(300) NOT NULL,
  `signuplink` text NOT NULL,
  `signuptext` varchar(500) NOT NULL,
  `textboxtext` varchar(500) NOT NULL,
  `comments` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `submission`
--

CREATE TABLE IF NOT EXISTS `submission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prid` int(11) NOT NULL,
  `ace_campaignname` text NOT NULL,
  `ace_moneysiteurl1` text NOT NULL,
  `ace_moneysiteurl2` text NOT NULL,
  `ace_moneysiteurl3` text NOT NULL,
  `ace_sbtitle` text NOT NULL,
  `ace_companyinfo` text NOT NULL,
  `ace_companyurl` text NOT NULL,
  `ace_companyname` text NOT NULL,
  `ace_companycontact` text NOT NULL,
  `ace_catkeywords` text NOT NULL,
  `ace_key1` text NOT NULL,
  `ace_key2` text NOT NULL,
  `ace_key3` text NOT NULL,
  `ace_prcontent` text NOT NULL,
  `ace_prtitle` text NOT NULL,
  `ace_datetime_submitted` datetime NOT NULL,
  `ace_datetime_resubmitted` datetime NOT NULL,
  `ace_emailed` int(11) NOT NULL DEFAULT '0',
  `ace_email_datetime` datetime NOT NULL,
  `ace_URL` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2825 ;

-- --------------------------------------------------------

--
-- Table structure for table `submission_setting`
--

CREATE TABLE IF NOT EXISTS `submission_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting` text NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(40) CHARACTER SET utf8 NOT NULL,
  `url_tag` varchar(40) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `url_tag` (`url_tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=456955 ;

-- --------------------------------------------------------

--
-- Table structure for table `TKCategory`
--

CREATE TABLE IF NOT EXISTS `TKCategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catname` varchar(150) NOT NULL,
  `catvalue` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Table structure for table `top_log`
--

CREATE TABLE IF NOT EXISTS `top_log` (
  `prid` int(10) unsigned NOT NULL,
  `top_url` varchar(200) NOT NULL,
  `top_user` varchar(50) NOT NULL,
  `top_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`prid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userfeeds`
--

CREATE TABLE IF NOT EXISTS `userfeeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feedid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `deal_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_package_deal_id` int(10) unsigned NOT NULL DEFAULT '0',
  `verify_code` varchar(50) NOT NULL,
  `active` smallint(1) NOT NULL,
  `created` datetime NOT NULL,
  `activated` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text,
  `months` tinyint(4) NOT NULL DEFAULT '1',
  `unsub_pdfreminder` int(11) NOT NULL DEFAULT '0',
  `pending_reminder` int(11) NOT NULL DEFAULT '0',
  `reseller` int(11) NOT NULL DEFAULT '0',
  `introsubscription` int(11) NOT NULL DEFAULT '0',
  `prreview` int(11) NOT NULL DEFAULT '0' COMMENT 'this is for resellers with writing service. If this field has a zero value, it means that the PR written by the writers are directly queued, if set to 1 editor is i-news admin, if set to 2 reseller is the editor',
  `resellerinterventionifadmineditor` int(11) NOT NULL DEFAULT '0' COMMENT 'if admin is the editor, there are two options:  a written PR goes to the reseller if this field =1, if this field is zero then goes directly to admin',
  `ip_address` varchar(20) DEFAULT NULL,
  `SendEmailNotificationToReseller` int(11) NOT NULL DEFAULT '1',
  `SendEmailNotificationToCustomer` int(11) NOT NULL DEFAULT '1',
  `ResellerPaypalID` varchar(100) NOT NULL,
  `PDFEmailToCustomer` int(11) NOT NULL DEFAULT '0',
  `socialgoogle` text NOT NULL,
  `socialfacebook` text NOT NULL,
  `socialfblike` text NOT NULL,
  `socialfbrec` text NOT NULL,
  `socialrss` text NOT NULL,
  `socialtwitter` text NOT NULL,
  `socialpinterest` text NOT NULL,
  `socialdelicious` text NOT NULL,
  `socialtumblr` text NOT NULL,
  `socialyoutube` text NOT NULL,
  `sociallinkedin` text NOT NULL,
  `nofollowLinks` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=238823 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_package_deals`
--

CREATE TABLE IF NOT EXISTS `user_package_deals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL,
  `price` double NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` tinyint(1) NOT NULL,
  `rollover` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7788 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_package_deal_details`
--

CREATE TABLE IF NOT EXISTS `user_package_deal_details` (
  `user_package_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `type_qty` int(11) NOT NULL,
  `type_duration` enum('MONTHLY','DAILY','WEEKLY') NOT NULL,
  `used` int(11) NOT NULL,
  PRIMARY KEY (`user_package_id`,`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `website_details`
--

CREATE TABLE IF NOT EXISTS `website_details` (
  `company_id` int(11) NOT NULL,
  `keyword` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  KEY `company_id` (`company_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `WriterToAdminEditingHistory`
--

CREATE TABLE IF NOT EXISTS `WriterToAdminEditingHistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `comments` text NOT NULL,
  `commentsdate` datetime NOT NULL,
  `commenttype` varchar(40) NOT NULL COMMENT 'this field can have three values: writertoadmin,admintocutsomer,admintowriter,resellertocustomer',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=485 ;

-- --------------------------------------------------------

--
-- Table structure for table `xmlfeeds`
--

CREATE TABLE IF NOT EXISTS `xmlfeeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `url` varchar(255) NOT NULL,
  `template` varchar(255) NOT NULL,
  `header_template` varchar(50) NOT NULL,
  `footer_template` varchar(50) NOT NULL,
  `include_in_feeds` tinyint(1) NOT NULL,
  `include_in_sitemap` tinyint(1) NOT NULL,
  `auto_accept` tinyint(1) NOT NULL,
  `catid` varchar(10) NOT NULL,
  `adsense` tinyint(1) NOT NULL,
  `items_per_run` int(11) NOT NULL,
  `strip_links` tinyint(1) NOT NULL,
  `replace_words` text NOT NULL,
  `show_on_mainpage` tinyint(4) NOT NULL,
  `last_run` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `xmlfeeds_items`
--

CREATE TABLE IF NOT EXISTS `xmlfeeds_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `done` tinyint(1) unsigned NOT NULL,
  `mktime` int(10) unsigned NOT NULL,
  `item_title` varchar(255) NOT NULL,
  `item_url` varchar(255) NOT NULL,
  `item_mktime` int(10) unsigned NOT NULL,
  `item_descr` text NOT NULL,
  `feedid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_url` (`item_url`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Structure for view `nr_newsroom`
--
DROP TABLE IF EXISTS `nr_newsroom`;

CREATE ALGORITHM=UNDEFINED VIEW `nr_newsroom` AS select `nr_company`.`id` AS `company_id`,`nr_company`.`company_contact_id` AS `company_contact_id`,`nr_company`.`name` AS `company_name`,`nr_company`.`newsroom` AS `name`,`nr_company`.`newsroom_domain` AS `domain`,`nr_company`.`newsroom_timezone` AS `timezone`,`nr_company`.`user_id` AS `user_id` from `nr_company` where (`nr_company`.`newsroom` is not null);

-- --------------------------------------------------------

--
-- Structure for view `nr_user`
--
DROP TABLE IF EXISTS `nr_user`;

CREATE ALGORITHM=UNDEFINED VIEW `nr_user` AS select `users`.`id` AS `id`,`users`.`fname` AS `first_name`,`users`.`lname` AS `last_name`,`users`.`password` AS `password`,`users`.`email` AS `email`,`users`.`active` AS `is_active`,`users`.`admin` AS `is_admin`,0 AS `credits`,if((`users`.`verify_code` = ''),1,0) AS `is_verified` from `users`;






--
-- Dumping data for table `nr_company`
--

INSERT INTO `nr_company` (`id`, `user_id`, `newsroom`, `name`, `newsroom_timezone`, `newsroom_domain`, `company_contact_id`) VALUES
(11, 238823, 'inw-00705c23-red', 'Red Company', 'Europe/London', NULL, NULL);

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `password`, `email`, `deal_id`, `user_package_deal_id`, `verify_code`, `active`, `created`, `activated`, `updated`, `admin`, `notes`, `months`, `unsub_pdfreminder`, `pending_reminder`, `reseller`, `introsubscription`, `prreview`, `resellerinterventionifadmineditor`, `ip_address`, `SendEmailNotificationToReseller`, `SendEmailNotificationToCustomer`, `ResellerPaypalID`, `PDFEmailToCustomer`, `socialgoogle`, `socialfacebook`, `socialfblike`, `socialfbrec`, `socialrss`, `socialtwitter`, `socialpinterest`, `socialdelicious`, `socialtumblr`, `socialyoutube`, `sociallinkedin`, `nofollowLinks`) VALUES
(238823, 'Jonathan', 'Pike', 'lemon123', 'dev-inewswire@staite.net', 0, 0, '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, NULL, 1, 0, 0, 0, 0, 0, 0, NULL, 1, 1, '', 0, '', '', '', '', '', '', '', '', '', '', '', 0);



-- phpMyAdmin SQL Dump
-- version 3.3.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2013 at 05:18 PM
-- Server version: 5.5.25
-- PHP Version: 5.4.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `dev_inw`
--

--
-- Dumping data for table `nr_bar`
--

INSERT INTO `nr_bar` (`id`, `name`, `display_name`) VALUES
(1, 'dashboard', 'Dashboard Progress Bar');

--
-- Dumping data for table `nr_bar_stage`
--

INSERT INTO `nr_bar_stage` (`id`, `bar_id`, `name`, `display_name`, `info_link`) VALUES
(4, 1, 'company-details', 'Company Details', 'manage/newsroom/company'),
(5, 1, 'customize', 'Design / Customization', 'manage/newsroom/customize'),
(6, 1, 'pr-submission', 'First PR Submission', 'manage/publish/pr/edit');

--
-- Dumping data for table `nr_beat`
--

INSERT INTO `nr_beat` (`id`, `beat_group_id`, `name`) VALUES
(1, 1, 'A&E News'),
(2, 1, 'Books'),
(3, 1, 'Celebrities'),
(4, 1, 'Funding'),
(5, 1, 'Humor'),
(6, 1, 'Internet and Streaming Media'),
(7, 1, 'Mixed Media'),
(8, 1, 'Movies'),
(9, 1, 'Multi-Media'),
(10, 1, 'Music'),
(11, 1, 'Performing Arts'),
(12, 1, 'Photography'),
(13, 1, 'Radio'),
(14, 1, 'Television'),
(15, 1, 'Theater'),
(16, 1, 'Video Games'),
(17, 2, 'Advertising'),
(18, 2, 'Business Development'),
(19, 2, 'Business News'),
(20, 2, 'Business Process Management'),
(21, 2, 'Business Technology'),
(22, 2, 'Business to Business'),
(23, 2, 'Business to Consumer'),
(24, 2, 'Career Planning'),
(25, 2, 'Compliance and Regulations'),
(26, 2, 'Corporate Communications'),
(27, 2, 'Corporate Officers'),
(28, 2, 'Customer Relationship Management'),
(29, 2, 'e-Commerce'),
(30, 2, 'Economy'),
(31, 2, 'Emerging Businesses'),
(32, 2, 'Enterprise Resource Planning'),
(33, 2, 'Entrepreneurship'),
(34, 2, 'Finance'),
(35, 2, 'Intellectual Property'),
(36, 2, 'Marketing'),
(37, 2, 'Non Profit'),
(38, 2, 'Project and Program Management'),
(39, 2, 'Public Sector'),
(40, 2, 'Regional Business News'),
(41, 2, 'Research and Development'),
(42, 2, 'Risk Management'),
(43, 2, 'Sales and Re-sale'),
(44, 2, 'Security'),
(45, 2, 'Small Business'),
(46, 2, 'Supply Chain Management'),
(47, 2, 'Transportation and Logistics'),
(48, 3, 'Educational News'),
(49, 3, 'Educational Technology'),
(50, 3, 'K through 12'),
(51, 3, 'Non-Profit Schools and Institutions'),
(52, 3, 'School Sports'),
(53, 3, 'Sciences'),
(54, 4, 'Asset Management'),
(55, 4, 'Commodities'),
(56, 4, 'Credit Unions'),
(57, 4, 'Economy'),
(58, 4, 'Financial News'),
(59, 4, 'Healthcare'),
(60, 4, 'Insurance'),
(61, 4, 'Investments'),
(62, 4, 'Life Insurance'),
(63, 4, 'Mergers and Acquisitions'),
(64, 4, 'Mutual Funds'),
(65, 4, 'Real Estate'),
(66, 4, 'Regulations and Compliance'),
(67, 4, 'Risk Management'),
(68, 4, 'Stocks'),
(69, 5, 'County Government'),
(70, 5, 'Defense and Homeland Security'),
(71, 5, 'Economy'),
(72, 5, 'Elections'),
(73, 5, 'Emergency and Disaster Relief'),
(74, 5, 'Energy'),
(75, 5, 'Environmental Protection'),
(76, 5, 'Government News'),
(77, 5, 'Politicians'),
(78, 5, 'Politics'),
(79, 5, 'Public Works'),
(80, 5, 'Rights and Privileges'),
(81, 5, 'State or Provincial Government'),
(82, 5, 'Taxation and Tariffs'),
(83, 5, 'Town and City Government'),
(84, 5, 'Trade'),
(85, 6, 'Automation'),
(86, 6, 'Construction'),
(87, 6, 'Cooperatives'),
(88, 6, 'Design and Architecture'),
(89, 6, 'Electrical'),
(90, 6, 'Gardens and Landscape'),
(91, 6, 'Home Improvement and Renovations'),
(92, 6, 'Home Theater and Audio/Visual Equipment'),
(93, 6, 'Houses'),
(94, 6, 'Housing and Community Planning and Development'),
(95, 6, 'Interiors and Decoration'),
(96, 6, 'Mortgages and Loans'),
(97, 6, 'Phone, Cable, and Internet Services'),
(98, 6, 'Pools, Jacuzzis, and Hot Tubs'),
(99, 6, 'Safety'),
(100, 6, 'Security'),
(101, 6, 'Utilities'),
(102, 6, 'Water, Sewage, and Septic Systems'),
(103, 7, 'Geography'),
(104, 7, 'History'),
(105, 7, 'Writing'),
(106, 8, 'Advertising and Public Relations'),
(107, 8, 'Agriculture and Horticulture'),
(108, 8, 'Alternative Energy'),
(109, 8, 'Architecture and Design'),
(110, 8, 'Arts and Entertainment'),
(111, 8, 'Automotive'),
(112, 8, 'Aviation'),
(113, 8, 'Banking, Finance, Insurance'),
(114, 8, 'Beauty and Personal Care'),
(115, 8, 'Broadcasting'),
(116, 8, 'Business to Business'),
(117, 8, 'Chemicals'),
(118, 8, 'Civil Engineering'),
(119, 8, 'Computers and Software'),
(120, 8, 'Construction'),
(121, 8, 'Consulting and Professional Services'),
(122, 8, 'Consumer Goods'),
(123, 8, 'Electronics'),
(124, 8, 'Energy'),
(125, 8, 'Engineering'),
(126, 8, 'Entertainment'),
(127, 8, 'Environmental and Waste Management'),
(128, 8, 'Facilities Management'),
(129, 8, 'Fashion and Apparel'),
(130, 8, 'Food and Beverage Manufacturing'),
(131, 8, 'Food and Beverage Services'),
(132, 8, 'Freight and Logistics'),
(133, 8, 'Green Industries'),
(134, 8, 'Healthcare'),
(135, 8, 'Heavy Machinery'),
(136, 8, 'High Technology'),
(137, 8, 'Hospitality'),
(138, 8, 'Industry News'),
(139, 8, 'Internet and e-Commerce'),
(140, 8, 'Legal Services'),
(141, 8, 'Maintenance and Repair'),
(142, 8, 'Manufacturing'),
(143, 8, 'Metals'),
(144, 8, 'Military and Defense'),
(145, 8, 'Mining'),
(146, 8, 'Office Equipment'),
(147, 8, 'Pharmaceuticals and Biotech'),
(148, 8, 'Professional Audiovisual Industry'),
(149, 8, 'Publishing and Printing'),
(150, 8, 'Real Estate'),
(151, 8, 'Retail'),
(152, 8, 'Sciences'),
(153, 8, 'Security Services and Solutions'),
(154, 8, 'Space Technology'),
(155, 8, 'Sports, Fitness, and Recreation'),
(156, 8, 'Telecommunications'),
(157, 8, 'Trade and Commerce'),
(158, 8, 'Transportation'),
(159, 8, 'Travel'),
(160, 8, 'Wholesale'),
(161, 9, 'Appliances'),
(162, 9, 'Business Intelligence'),
(163, 9, 'Business Process Management (BPM)'),
(164, 9, 'Client/Server Computing'),
(165, 9, 'Cloud computing'),
(166, 9, 'Computer Engineering'),
(167, 9, 'Computer Hardware'),
(168, 9, 'Computer Peripherals'),
(169, 9, 'Computer Software'),
(170, 9, 'Consumer Electronics'),
(171, 9, 'Customer Relationship Management (CRM)'),
(172, 9, 'Data Storage and Warehousing'),
(173, 9, 'Database Management Systems'),
(174, 9, 'Desktop Publishing'),
(175, 9, 'Digital Media Recorders and Players'),
(176, 9, 'Displays and Monitors'),
(177, 9, 'E-Commerce and e-Business'),
(178, 9, 'E-mail and Groupware'),
(179, 9, 'Embedded Computing'),
(180, 9, 'Enterprise Content Management (ECM)'),
(181, 9, 'Enterprise Resource Planning (ERP)'),
(182, 9, 'Global Position Systems (GPS) and Services'),
(183, 9, 'Graphic Rendering and Animation'),
(184, 9, 'Grid Computing'),
(185, 9, 'Information Systems'),
(186, 9, 'Instant Messaging and Chatting'),
(187, 9, 'Internet Computing'),
(188, 9, 'IT Management'),
(189, 9, 'IT Security'),
(190, 9, 'Microprocessors'),
(191, 9, 'Mobile and Smart Phones'),
(192, 9, 'Multi- and Parallel Processing'),
(193, 9, 'Multi-Media'),
(194, 9, 'Network Administration'),
(195, 9, 'Networking'),
(196, 9, 'Operating Systems'),
(197, 9, 'Optical Character Recognition (OCR) and Imaging'),
(198, 9, 'Personal Computers'),
(199, 9, 'Personal Digital Assistants (PDA)'),
(200, 9, 'Portable Media Players'),
(201, 9, 'Portals'),
(202, 9, 'Printing'),
(203, 9, 'Programming'),
(204, 9, 'Retail Technology'),
(205, 9, 'Saas (Software as Service)'),
(206, 9, 'Scientific Computing'),
(207, 9, 'Search Engines'),
(208, 9, 'Servers and Mainframes'),
(209, 9, 'Social Media'),
(210, 9, 'Speech Recognition'),
(211, 9, 'Standards and Protocols'),
(212, 9, 'Super Computing'),
(213, 9, 'Supply Chain Management (SCM)'),
(214, 9, 'Support and Maintenance'),
(215, 9, 'System and Data Integration'),
(216, 9, 'Tablet Computing'),
(217, 9, 'Telecommunications'),
(218, 9, 'Training and Education'),
(219, 9, 'VARs, OEMs, and Integrator'),
(220, 9, 'Video Games'),
(221, 9, 'Virtualization'),
(222, 9, 'Voice Over IP and Telephony'),
(223, 9, 'Web 2.0'),
(224, 9, 'Web Browsers'),
(225, 9, 'Web Services'),
(226, 9, 'Wireless Networking'),
(227, 10, 'Civil Rights'),
(228, 10, 'Commerce'),
(229, 10, 'Corporate Law'),
(230, 10, 'Intellectual Property Law'),
(231, 10, 'Law Firms'),
(232, 10, 'Law News'),
(233, 10, 'Organized Crime'),
(234, 10, 'Terrorism'),
(235, 10, 'White Collar Crime'),
(236, 11, 'Active and Healthy Living'),
(237, 11, 'Beauty and Personal Care'),
(238, 11, 'Children''s Issues'),
(239, 11, 'Consumerism'),
(240, 11, 'Cooking and Entertaining'),
(241, 11, 'Decorating'),
(242, 11, 'Digital Living'),
(243, 11, 'Divorce'),
(244, 11, 'Environmentalism'),
(245, 11, 'Ethnic and Multi-Cultural'),
(246, 11, 'Exercise and Physical Fitness'),
(247, 11, 'Fashion'),
(248, 11, 'Games and Play'),
(249, 11, 'General Interest'),
(250, 11, 'Healthcare'),
(251, 11, 'Hispanic'),
(252, 11, 'Hobbies and Crafts'),
(253, 11, 'Men''s Issues'),
(254, 11, 'Parenting'),
(255, 11, 'Personal and Family Finances'),
(256, 11, 'Pets'),
(257, 11, 'Recipes'),
(258, 11, 'Recreation and Sports'),
(259, 11, 'Regional Living'),
(260, 11, 'Religion'),
(261, 11, 'Retirement'),
(262, 11, 'Seniors'),
(263, 11, 'Urban Living'),
(264, 11, 'Vacations and Travel'),
(265, 11, 'Weddings'),
(266, 11, 'Women''s Issues'),
(267, 11, 'Work'),
(268, 12, 'Alternative Medicine'),
(269, 12, 'Cancer/Oncology'),
(270, 12, 'Health Insurance'),
(271, 12, 'Healthcare and Medical Informatics'),
(272, 12, 'Healthcare and Medical News'),
(273, 12, 'Healthcare Technology'),
(274, 12, 'Neurology'),
(275, 12, 'Physicians'),
(276, 13, 'Community News'),
(277, 13, 'County News'),
(278, 13, 'International News'),
(279, 13, 'Local News'),
(280, 13, 'Metro News'),
(281, 13, 'National News'),
(282, 13, 'State News'),
(283, 14, 'Aeronautics and Aerospace'),
(284, 14, 'Agriculture and Horticulture'),
(285, 14, 'Astronomy and Astrophysics'),
(286, 14, 'Climatology'),
(287, 14, 'Computer'),
(288, 14, 'Economics'),
(289, 14, 'Electrical and Electronics'),
(290, 14, 'Environmental'),
(291, 14, 'Geology'),
(292, 14, 'Mathematics'),
(293, 14, 'Mechanics'),
(294, 14, 'Meteorology'),
(295, 14, 'Military Technology'),
(296, 14, 'Optics'),
(297, 14, 'Physics'),
(298, 14, 'Research'),
(299, 15, 'Collegiate Sports'),
(300, 15, 'Equipment'),
(301, 15, 'Football'),
(302, 15, 'High School Sports'),
(303, 15, 'Hunting and Fishing'),
(304, 15, 'Ice Hockey'),
(305, 15, 'Olympic Sports'),
(306, 15, 'Online Gaming'),
(307, 15, 'Professional Sports'),
(308, 15, 'Shooting'),
(309, 15, 'Soccer'),
(310, 15, 'Sports News'),
(311, 15, 'Sports-related Business'),
(312, 15, 'Track and Field'),
(313, 15, 'Xtreme Sports'),
(314, 16, 'Air Travel'),
(315, 16, 'Business Travel'),
(316, 16, 'Casinos'),
(317, 16, 'Conventions, Trade Shows, Meetings, and Events'),
(318, 16, 'Global Positioning Systems (GPS)'),
(319, 16, 'Hiking and Backpacking'),
(320, 16, 'Maps and Atlases'),
(321, 16, 'Rail Roads'),
(322, 16, 'Restaurants, Bars, and Catering'),
(323, 16, 'Shopping'),
(324, 16, 'Tourism'),
(325, 16, 'Traffic'),
(326, 16, 'Travel Guides'),
(327, 16, 'Travel, Transportation, and Hospitality News');

--
-- Dumping data for table `nr_beat_group`
--

INSERT INTO `nr_beat_group` (`id`, `name`) VALUES
(1, 'Arts and Entertainments'),
(2, 'Business'),
(3, 'Education'),
(4, 'Financial and Insurance Services'),
(5, 'Government'),
(6, 'Home'),
(7, 'Humanities'),
(8, 'Industries'),
(9, 'Information Technology'),
(10, 'Law'),
(11, 'Lifestyles and Society'),
(12, 'Medicine and Healthcare'),
(13, 'News'),
(14, 'Sciences'),
(15, 'Sports'),
(16, 'Travel and Transportation');

--
-- Dumping data for table `nr_cat`
--

INSERT INTO `nr_cat` (`id`, `cat_group_id`, `name`, `slug`) VALUES
(2, 2, 'Architecture', 'architecture'),
(3, 3, 'Art & Entertainment', 'art-entertainment'),
(4, 3, 'Books', 'books'),
(5, 3, 'Celebrities', 'celebrities'),
(6, 3, 'Country Music', 'country-music'),
(7, 3, 'Dance', 'dance'),
(8, 3, 'Magazines', 'magazines'),
(9, 3, 'Movies', 'movies'),
(10, 3, 'Museums', 'museums'),
(11, 3, 'Music', 'music'),
(12, 3, 'Music Downloads', 'music-downloads'),
(13, 3, 'News & Talk Shows', 'news-talk-shows'),
(14, 3, 'Performing Arts', 'performing-arts'),
(15, 3, 'Photography', 'photography'),
(16, 3, 'Television', 'television'),
(17, 3, 'Web sites / Internet', 'web-sites-internet'),
(18, 18, 'Automotive', 'automotive'),
(19, 18, 'Aftermarket', 'aftermarket'),
(20, 18, 'Classic Autos', 'classic-autos'),
(21, 18, 'Consumer Publications', 'consumer-publications'),
(22, 18, 'Motorcycle & Bike', 'motorcycle-bike'),
(23, 18, 'Racing', 'racing'),
(24, 18, 'Recreational Vehicle', 'recreational-vehicle'),
(25, 18, 'Repair & Service', 'repair-service'),
(26, 18, 'Trade Publications', 'trade-publications'),
(27, 27, 'Blogging & Social Media', 'blogging-social-media'),
(28, 28, 'Business', 'business'),
(29, 28, 'Advertising / Marketing', 'advertising-marketing'),
(30, 28, 'Books', 'books'),
(31, 28, 'Consumer Research', 'consumer-research'),
(32, 28, 'Corporations', 'corporations'),
(33, 28, 'Direct Marketing', 'direct-marketing'),
(34, 28, 'Entrepreneurs', 'entrepreneurs'),
(35, 28, 'Finance', 'finance'),
(36, 28, 'Franchise', 'franchise'),
(37, 28, 'Human Resources', 'human-resources'),
(38, 28, 'Insurance', 'insurance'),
(39, 28, 'Investment', 'investment'),
(40, 28, 'Management', 'management'),
(41, 28, 'Markets', 'markets'),
(42, 28, 'Network Marketing', 'network-marketing'),
(43, 28, 'Online Marketing / SEO', 'online-marketing-seo'),
(44, 28, 'Public Relations', 'public-relations'),
(45, 28, 'Publications', 'publications'),
(46, 28, 'Real Estate', 'real-estate'),
(47, 28, 'Retail', 'retail'),
(48, 28, 'Stocks', 'stocks'),
(49, 28, 'Supermarkets', 'supermarkets'),
(50, 28, 'Travel', 'travel'),
(51, 28, 'Women in Business', 'women-in-business'),
(52, 28, 'e-Commerce', 'e-commerce'),
(53, 53, 'Chemical', 'chemical'),
(54, 54, 'Coaching / Mentoring', 'coaching-mentoring'),
(55, 55, 'Computer', 'computer'),
(56, 55, 'Databases', 'databases'),
(57, 55, 'Games & Entertainment', 'games-entertainment'),
(58, 55, 'Instruction', 'instruction'),
(59, 55, 'Macintosh', 'macintosh'),
(60, 55, 'Microsoft Windows PC', 'microsoft-windows-pc'),
(61, 55, 'Operating Systems', 'operating-systems'),
(62, 55, 'Programming', 'programming'),
(63, 55, 'Security', 'security'),
(64, 55, 'Software', 'software'),
(65, 55, 'Utilities', 'utilities'),
(66, 66, 'Consumer', 'consumer'),
(67, 66, 'Gifts and Collectibles', 'gifts-and-collectibles'),
(68, 66, 'Hobbies', 'hobbies'),
(69, 66, 'Web sites / Internet', 'web-sites-internet'),
(70, 70, 'Design', 'design'),
(71, 70, 'Graphic Design', 'graphic-design'),
(72, 70, 'Industrial', 'industrial'),
(73, 70, 'Web', 'web'),
(74, 74, 'Economy', 'economy'),
(75, 75, 'Education', 'education'),
(76, 75, 'College / University', 'college-university'),
(77, 75, 'Home Schooling', 'home-schooling'),
(78, 75, 'K-12', 'k-12'),
(79, 75, 'Post Graduate', 'post-graduate'),
(80, 75, 'Technical', 'technical'),
(81, 81, 'Employment', 'employment'),
(82, 82, 'Engineering', 'engineering'),
(83, 83, 'Environment', 'environment'),
(84, 83, 'Alternative Energy', 'alternative-energy'),
(85, 83, 'Animal Rights', 'animal-rights'),
(86, 83, 'Energy & Oil', 'energy-oil'),
(87, 83, 'Environmental Regulation', 'environmental-regulation'),
(88, 83, 'Global Warming', 'global-warming'),
(89, 83, 'Natural Resources', 'natural-resources'),
(90, 90, 'Events / Trade Shows', 'events-trade-shows'),
(91, 91, 'Fraud / Identity Theft', 'fraud-identity-theft'),
(93, 93, 'Government', 'government'),
(94, 93, 'Education', 'education'),
(95, 93, 'Elections', 'elections'),
(96, 93, 'Federal Budget', 'federal-budget'),
(97, 93, 'Foreign Conflict', 'foreign-conflict'),
(98, 93, 'Foreign Policy', 'foreign-policy'),
(99, 93, 'Judicial', 'judicial'),
(100, 93, 'Law Enforcement', 'law-enforcement'),
(101, 93, 'Legislative', 'legislative'),
(102, 93, 'Local', 'local'),
(103, 93, 'National', 'national'),
(104, 93, 'Public Services', 'public-services'),
(105, 93, 'Security', 'security'),
(106, 93, 'State', 'state'),
(107, 93, 'Transportation', 'transportation'),
(108, 108, 'Home and Family', 'home-and-family'),
(109, 108, 'Banking / Personal Finance', 'banking-personal-finance'),
(110, 108, 'Bereavement / Loss', 'bereavement-loss'),
(111, 108, 'Home Furnishings / Interiors', 'home-furnishings-interiors'),
(112, 108, 'Landscaping & Gardening', 'landscaping-gardening'),
(113, 113, 'Marriage / Relationships', 'marriage-relationships'),
(114, 108, 'Money', 'money'),
(115, 108, 'Parenting', 'parenting'),
(116, 108, 'Pets', 'pets'),
(117, 108, 'Taxes', 'taxes'),
(118, 108, 'Wedding / Bridal', 'wedding-bridal'),
(119, 119, 'Industry', 'industry'),
(120, 119, 'Agriculture', 'agriculture'),
(121, 119, 'Apparel / Textiles', 'apparel-textiles'),
(122, 119, 'Broadcast', 'broadcast'),
(123, 119, 'Construction / Building', 'construction-building'),
(124, 119, 'Electrical', 'electrical'),
(125, 119, 'Food', 'food'),
(126, 119, 'Food Safety', 'food-safety'),
(127, 119, 'Funeral', 'funeral'),
(128, 119, 'Healthcare', 'healthcare'),
(129, 119, 'Leisure / Hospitality', 'leisure-hospitality'),
(130, 119, 'Logistics / Shipping', 'logistics-shipping'),
(131, 119, 'Manufacturing / Production', 'manufacturing-production'),
(132, 119, 'Mining / Metals', 'mining-metals'),
(133, 119, 'Oil / Energy', 'oil-energy'),
(134, 119, 'Paper / Forest Products', 'paper-forest-products'),
(135, 119, 'Plumbing Heating & AC', 'plumbing-heating-ac'),
(136, 119, 'Print Media', 'print-media'),
(137, 119, 'Printing', 'printing'),
(138, 119, 'Publishing', 'publishing'),
(139, 119, 'Radio', 'radio'),
(140, 119, 'Restaurants', 'restaurants'),
(141, 119, 'Tobacco', 'tobacco'),
(142, 119, 'Toy', 'toy'),
(143, 143, 'Insurance', 'insurance'),
(144, 144, 'Legal / Law', 'legal-law'),
(145, 145, 'Lifestyle', 'lifestyle'),
(146, 145, 'Beauty', 'beauty'),
(147, 145, 'Dating / Singles', 'dating-singles'),
(148, 145, 'Diet / Weight Loss', 'diet-weight-loss'),
(149, 145, 'Fashion', 'fashion'),
(150, 145, 'Food / Beverage', 'food-beverage'),
(151, 145, 'Health & Fitness', 'health-fitness'),
(152, 145, 'Hotel / Resorts', 'hotel-resorts'),
(153, 145, 'Pastimes', 'pastimes'),
(154, 145, 'Restaurants', 'restaurants'),
(155, 145, 'Retirement', 'retirement'),
(156, 145, 'Travel & Tourism', 'travel-tourism'),
(157, 157, 'Machinery', 'machinery'),
(158, 158, 'Maritime', 'maritime'),
(159, 159, 'Medical', 'medical'),
(160, 159, 'Addiction', 'addiction'),
(161, 159, 'Allergies', 'allergies'),
(162, 159, 'Alternative Medicine', 'alternative-medicine'),
(163, 159, 'Asthma', 'asthma'),
(164, 159, 'Cancer', 'cancer'),
(165, 159, 'Cardiology', 'cardiology'),
(166, 159, 'Chiropractic', 'chiropractic'),
(167, 159, 'Dental', 'dental'),
(168, 159, 'Dermatology', 'dermatology'),
(169, 159, 'Diabetes', 'diabetes'),
(170, 159, 'Emergency', 'emergency'),
(171, 159, 'Family Medicine', 'family-medicine'),
(172, 159, 'General', 'general'),
(173, 159, 'Geriatrics', 'geriatrics'),
(174, 159, 'Hospitals', 'hospitals'),
(175, 159, 'Infectious Diseases', 'infectious-diseases'),
(176, 159, 'Internal Medicine', 'internal-medicine'),
(177, 159, 'Managed Care / HMO', 'managed-care-hmo'),
(178, 159, 'Medical Products', 'medical-products'),
(179, 159, 'Mental Health', 'mental-health'),
(180, 159, 'Neurology', 'neurology'),
(181, 159, 'Nursing', 'nursing'),
(182, 159, 'Nutrition', 'nutrition'),
(183, 159, 'OB / GYN', 'ob-gyn'),
(184, 159, 'Pediatrics', 'pediatrics'),
(185, 159, 'Pharmaceuticals', 'pharmaceuticals'),
(186, 159, 'Physical Therapy', 'physical-therapy'),
(187, 159, 'Plastic Surgery', 'plastic-surgery'),
(188, 159, 'Psychology', 'psychology'),
(189, 159, 'Radiology / Imaging', 'radiology-imaging'),
(190, 159, 'Research', 'research'),
(191, 159, 'Sports Medicine', 'sports-medicine'),
(192, 159, 'Surgery', 'surgery'),
(193, 159, 'Vision', 'vision'),
(194, 194, 'Military', 'military'),
(195, 195, 'Miscellaneous', 'miscellaneous'),
(196, 196, 'Nanotechnology', 'nanotechnology'),
(197, 197, 'Non-profit', 'non-profit'),
(198, 198, 'Occupational Safety', 'occupational-safety'),
(199, 199, 'Opinion / Editorial', 'opinion-editorial'),
(200, 200, 'Other', 'other'),
(201, 200, 'Announce', 'announce'),
(202, 200, 'Tools and Services', 'tools-and-services'),
(203, 203, 'Politics', 'politics'),
(204, 204, 'Public Utilities', 'public-utilities'),
(205, 205, 'RSS & Content Syndication', 'rss-content-syndication'),
(206, 206, 'Religion', 'religion'),
(207, 206, 'Christian', 'christian'),
(208, 206, 'Islam', 'islam'),
(209, 206, 'Jewish', 'jewish'),
(210, 206, 'Other', 'other'),
(211, 211, 'Science and Research', 'science-and-research'),
(212, 212, 'Self-Help / Personal Growth', 'self-help-personal-growth'),
(213, 213, 'Small Business', 'small-business'),
(214, 214, 'Society', 'society'),
(215, 214, 'Affirmative Action', 'affirmative-action'),
(216, 214, 'African American Interests', 'african-american-interests'),
(217, 214, 'Asian Interests', 'asian-interests'),
(218, 214, 'Children''s Issues', 'children-s-issues'),
(219, 214, 'Civil Rights', 'civil-rights'),
(220, 214, 'Crime', 'crime'),
(221, 214, 'Disabled Issues / Disabilities', 'disabled-issues-disabilities'),
(222, 214, 'Gay / Lesbian', 'gay-lesbian'),
(223, 214, 'Hispanic', 'hispanic'),
(224, 214, 'Human Rights', 'human-rights'),
(225, 214, 'Men''s Interests', 'men-s-interests'),
(226, 214, 'Native American', 'native-american'),
(227, 214, 'Senior Citizens', 'senior-citizens'),
(228, 214, 'Social Services', 'social-services'),
(229, 214, 'Teen Issues/Interests', 'teen-issues-interests'),
(230, 214, 'Women''s Interest', 'women-s-interest'),
(231, 231, 'Sports', 'sports'),
(232, 231, 'Baseball', 'baseball'),
(233, 231, 'Basketball', 'basketball'),
(234, 231, 'Bicycling', 'bicycling'),
(235, 231, 'Boating / Maritime', 'boating-maritime'),
(236, 231, 'Bowling', 'bowling'),
(237, 231, 'Boxing', 'boxing'),
(238, 231, 'Fishing', 'fishing'),
(239, 231, 'Football', 'football'),
(240, 231, 'Golf', 'golf'),
(241, 231, 'Hockey', 'hockey'),
(242, 231, 'Hunting', 'hunting'),
(243, 231, 'Martial Arts', 'martial-arts'),
(244, 231, 'Outdoors', 'outdoors'),
(245, 231, 'Rugby', 'rugby'),
(246, 231, 'Soccer', 'soccer'),
(247, 231, 'Water', 'water'),
(248, 231, 'Winter/Snow', 'winter-snow'),
(249, 249, 'Technology', 'technology'),
(250, 249, 'Biotechnology', 'biotechnology'),
(251, 249, 'Computer', 'computer'),
(252, 249, 'Electronics', 'electronics'),
(253, 249, 'Enterprise Software', 'enterprise-software'),
(254, 249, 'Games', 'games'),
(255, 249, 'Graphics/Printing/CAD', 'graphics-printing-cad'),
(256, 249, 'Hardware / Peripherals', 'hardware-peripherals'),
(257, 249, 'Industrial', 'industrial'),
(258, 249, 'Information', 'information'),
(259, 249, 'Internet', 'internet'),
(260, 249, 'Multimedia', 'multimedia'),
(261, 249, 'Nanotechnology', 'nanotechnology'),
(262, 249, 'Networking', 'networking'),
(263, 249, 'Public Sector/Government', 'public-sector-government'),
(264, 249, 'Robotics', 'robotics'),
(265, 249, 'Semiconductor', 'semiconductor'),
(266, 249, 'Software', 'software'),
(267, 249, 'Telecommunications', 'telecommunications'),
(268, 249, 'Webmasters', 'webmasters'),
(269, 269, 'Telecom', 'telecom'),
(270, 270, 'Trade', 'trade'),
(271, 271, 'Transportation', 'transportation'),
(272, 272, 'Volunteer', 'volunteer'),
(273, 273, 'Weather', 'weather'),
(274, 144, 'Law Technology', 'law_technology'),
(275, 28, 'Technology', 'technology'),
(276, 249, 'Business', 'business'),
(277, 159, 'Hearing', 'hearing'),
(278, 278, 'Archive', 'archive'),
(279, 279, 'Health & Fitness', 'health-fitness'),
(280, 28, 'Legal/Law', 'legal-law'),
(281, 144, 'Business', 'business'),
(282, 282, 'Water', 'water'),
(283, 283, 'Internet Marketing', 'internet-marketing'),
(284, 284, 'Shopping', 'shopping'),
(285, 285, 'Travel', 'travel'),
(286, 286, 'ExtFeeds', 'extfeeds'),
(291, 291, 'Apparel', 'apparel'),
(292, 292, 'Appliance & Tool', 'appliance-tool'),
(293, 293, 'Daily Deals', 'daily-deals'),
(294, 294, 'Search Engine Marketing', 'search-engine-marketing'),
(295, 294, 'SEO', 'seo'),
(296, 296, 'Pets', 'pets');

--
-- Dumping data for table `nr_country`
--

INSERT INTO `nr_country` (`id`, `name`) VALUES
(248, 'Australia'),
(249, 'Afghanistan'),
(250, 'Albania'),
(251, 'Algeria'),
(253, 'American Samoa'),
(254, 'Andorra'),
(255, 'Angola'),
(256, 'Anguilla'),
(257, 'Antarctica'),
(258, 'Antigua & Barbuda'),
(259, 'Argentina'),
(260, 'Armenia'),
(261, 'Aruba'),
(262, 'Australia'),
(263, 'Austria'),
(264, 'Azerbaijan'),
(265, 'Bahamas'),
(266, 'Bahrain'),
(267, 'Bangladesh'),
(268, 'Barbados'),
(269, 'Belarus'),
(270, 'Belgium'),
(271, 'Belize'),
(272, 'Benin'),
(273, 'Bermuda'),
(274, 'Bhutan'),
(275, 'Bolivia'),
(276, 'Bosnia & Herzegovina'),
(277, 'Botswana'),
(278, 'Bouvet Island'),
(279, 'Brazil'),
(280, 'British Indian Ocean Territory'),
(281, 'Brunei Darussalam'),
(282, 'Bulgaria'),
(283, 'Burkina Faso'),
(284, 'Burundi'),
(285, 'Cambodia'),
(286, 'Cameroon'),
(287, 'Canada'),
(288, 'Cape Verde'),
(289, 'Cayman Islands'),
(290, 'Central African Republic'),
(291, 'Chad'),
(292, 'Chile'),
(293, 'China'),
(294, 'Christmas Island'),
(295, 'Cocos (keeling) Islands'),
(296, 'Colombia'),
(297, 'Comoros'),
(298, 'Congo'),
(299, 'Congo, The Democratic Republic'),
(300, 'Cook Islands'),
(301, 'Costa Rica'),
(303, 'Croatia'),
(304, 'Cuba'),
(305, 'Cyprus'),
(306, 'Czech Republic'),
(307, 'Denmark'),
(308, 'Djibouti'),
(309, 'Dominica'),
(310, 'Dominican Republic'),
(311, 'East Timor'),
(312, 'Ecuador'),
(313, 'Egypt'),
(314, 'El Salvador'),
(315, 'Equatorial Guinea'),
(316, 'Eritrea'),
(317, 'Estonia'),
(318, 'Ethiopia'),
(319, 'Falkland Islands (malvinas)'),
(320, 'Faroe Islands'),
(321, 'Fiji'),
(322, 'Finland'),
(323, 'France'),
(324, 'French Guiana'),
(325, 'French Polynesia'),
(326, 'French Southern Territories'),
(327, 'Gabon'),
(328, 'Gambia'),
(329, 'Georgia'),
(330, 'Germany'),
(331, 'Ghana'),
(332, 'Gibraltar'),
(333, 'Greece'),
(334, 'Greenland'),
(335, 'Grenada'),
(336, 'Guadeloupe'),
(337, 'Guam'),
(338, 'Guatemala'),
(339, 'Guinea'),
(340, 'Guinea-bissau'),
(341, 'Guyana'),
(342, 'Haiti'),
(343, 'Heard Island & Mcdonald Islands'),
(344, 'Holy See (Vatican City State)'),
(345, 'Honduras'),
(346, 'Hong Kong'),
(347, 'Hungary'),
(348, 'Iceland'),
(349, 'India'),
(350, 'Indonesia'),
(351, 'Iran, Islamic Republic Of'),
(352, 'Iraq'),
(353, 'Ireland'),
(354, 'Israel'),
(355, 'Italy'),
(356, 'Jamaica'),
(357, 'Japan'),
(358, 'Jordan'),
(359, 'Kazakstan'),
(360, 'Kenya'),
(361, 'Kiribati'),
(363, 'Korea, Republic Of'),
(364, 'Kuwait'),
(365, 'Kyrgyzstan'),
(367, 'Latvia'),
(368, 'Lebanon'),
(369, 'Lesotho'),
(370, 'Liberia'),
(371, 'Libyan Arab Jamahiriya'),
(372, 'Liechtenstein'),
(373, 'Lithuania'),
(374, 'Luxembourg'),
(375, 'Macau'),
(376, 'Macedonia, The Former Yugoslav'),
(377, 'Madagascar'),
(378, 'Malawi'),
(379, 'Malaysia'),
(380, 'Maldives'),
(381, 'Mali'),
(382, 'Malta'),
(383, 'Marshall Islands'),
(384, 'Martinique'),
(385, 'Mauritania'),
(386, 'Mauritius'),
(387, 'Mayotte'),
(388, 'Mexico'),
(389, 'Micronesia, Federated States Of'),
(390, 'Moldova, Republic Of'),
(391, 'Monaco'),
(392, 'Mongolia'),
(393, 'Montserrat'),
(394, 'Morocco'),
(395, 'Mozambique'),
(396, 'Myanmar'),
(397, 'Namibia'),
(398, 'Nauru'),
(399, 'Nepal'),
(400, 'Netherlands'),
(401, 'Netherlands Antilles'),
(402, 'New Caledonia'),
(403, 'New Zealand'),
(404, 'Nicaragua'),
(405, 'Niger'),
(406, 'Nigeria'),
(407, 'Niue'),
(408, 'Norfolk Island'),
(409, 'Northern Mariana Islands'),
(410, 'Norway'),
(411, 'Oman'),
(412, 'Pakistan'),
(413, 'Palau'),
(414, 'Palestinian Territory, Occupied'),
(415, 'Panama'),
(416, 'Papua New Guinea'),
(417, 'Paraguay'),
(418, 'Peru'),
(419, 'Philippines'),
(420, 'Pitcairn'),
(421, 'Poland'),
(422, 'Portugal'),
(423, 'Puerto Rico'),
(424, 'Qatar'),
(425, 'Reunion'),
(426, 'Romania'),
(427, 'Russian Federation'),
(428, 'Rwanda'),
(429, 'Saint Helena'),
(430, 'Saint Kitts & Nevis'),
(431, 'Saint Lucia'),
(432, 'Saint Pierre & Miquelon'),
(433, 'Saint Vincent & The Grenadines'),
(434, 'Samoa'),
(435, 'San Marino'),
(436, 'Sao Tome & Principe'),
(437, 'Saudi Arabia'),
(438, 'Senegal'),
(439, 'Seychelles'),
(440, 'Sierra Leone'),
(441, 'Singapore'),
(442, 'Slovakia'),
(443, 'Slovenia'),
(444, 'Solomon Islands'),
(445, 'Somalia'),
(446, 'South Africa'),
(447, 'South Georgia & The South Sandwich Islan'),
(448, 'Spain'),
(449, 'Sri Lanka'),
(450, 'Sudan'),
(451, 'Suriname'),
(452, 'Svalbard & Jan Mayen'),
(453, 'Swaziland'),
(454, 'Sweden'),
(455, 'Switzerland'),
(456, 'Syrian Arab Republic'),
(457, 'Taiwan'),
(458, 'Tajikistan'),
(459, 'Tanzania, United Republic Of'),
(460, 'Thailand'),
(461, 'Togo'),
(462, 'Tokelau'),
(463, 'Tonga'),
(464, 'Trinidad & Tobago'),
(465, 'Tunisia'),
(466, 'Turkey'),
(467, 'Turkmenistan'),
(468, 'Turks & Caicos Islands'),
(469, 'Tuvalu'),
(470, 'Uganda'),
(471, 'Ukraine'),
(472, 'United Arab Emirates'),
(473, 'United Kingdom'),
(474, 'United States'),
(475, 'United States Minor Outlying Islands'),
(476, 'Uruguay'),
(477, 'Uzbekistan'),
(478, 'Vanuatu'),
(479, 'Venezuela'),
(480, 'Viet Nam'),
(481, 'Virgin Islands, British'),
(482, 'Virgin Islands, U.S.'),
(483, 'Wallis & Futuna'),
(484, 'Western Sahara'),
(485, 'Yemen'),
(486, 'Yugoslavia'),
(487, 'Zambia'),
(488, 'Zimbabwe');

--
-- Dumping data for table `nr_event_type`
--

INSERT INTO `nr_event_type` (`id`, `name`) VALUES
(1, 'Meeting'),
(2, 'Lecture'),
(3, 'Conference'),
(4, 'Seminar'),
(5, 'Charity'),
(6, 'Fundraiser'),
(7, 'Launch/Premiere'),
(8, 'Display'),
(9, 'Webinar'),
(10, 'Show'),
(11, 'Symposium'),
(12, 'Exhibition'),
(13, 'Competition'),
(14, 'Other');
