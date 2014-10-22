-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 22, 2014 at 10:21 AM
-- Server version: 5.1.73-cll
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE IF NOT EXISTS `levels` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`id`, `name`) VALUES
(1, 'User'),
(2, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `master_form`
--

CREATE TABLE IF NOT EXISTS `master_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `master_form`
--

INSERT INTO `master_form` (`id`, `label`, `active`) VALUES
(1, 'Group', 1),
(2, 'Sales Stage', 1),
(3, 'Lead Source', 1),
(4, 'Next Action', 1),
(6, 'Client Status', 1),
(7, 'Campaigns', 1);

-- --------------------------------------------------------

--
-- Table structure for table `master_form_values`
--

CREATE TABLE IF NOT EXISTS `master_form_values` (
  `master_form_id` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `master_form_values`
--

INSERT INTO `master_form_values` (`master_form_id`, `value`) VALUES
('1', 'ths\r'),
('1', 'that\r'),
('2', 'Stage 1\r'),
('2', 'Stage 2\r'),
('3', 'Google\r'),
('3', 'Craigslist\r'),
('4', 'Call ME\r'),
('4', 'Text ME\r'),
('6', 'Pending\r'),
('6', 'Approved\r'),
('6', 'Accepted\r'),
('7', 'AdWords\r'),
('7', 'PPC\r'),
('7', 'SEO\r');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `private_dir` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `level`, `active`, `deleted`, `private_dir`) VALUES
(3, 'admin@mysite.biz', '84J54DZg0bv5O0qnTNVN9knpPALHKWmvgpVAR3oBKCU=', 2, 1, 0, ''),
(5, 'user@mysite.biz', '84J54DZg0bv5O0qnTNVN9knpPALHKWmvgpVAR3oBKCU=', 1, 1, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `user_contacts`
--

CREATE TABLE IF NOT EXISTS `user_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `cell` varchar(100) NOT NULL,
  `typeofperson` varchar(100) NOT NULL,
  `custom_fields` text NOT NULL,
  `notes` varchar(255) NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `date_deleted` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=109 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_forms`
--

CREATE TABLE IF NOT EXISTS `user_forms` (
  `user_id` int(11) NOT NULL,
  `master_form_id` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  UNIQUE KEY `user_id` (`user_id`,`master_form_id`,`value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_forms`
--

INSERT INTO `user_forms` (`user_id`, `master_form_id`, `value`) VALUES
(4, 1, 'Employees\r'),
(4, 1, 'Group1\r'),
(4, 1, 'Referalls\r'),
(4, 2, 'Stage 1\r'),
(4, 2, 'Stage 2\r'),
(4, 3, 'BING!\r'),
(4, 3, 'Google\r'),
(4, 3, 'Yahoo\r'),
(4, 4, 'Call ME\r'),
(4, 4, 'Text ME\r'),
(4, 6, 'Accepted\r'),
(4, 6, 'DECLINED\r'),
(4, 7, 'Another one\r'),
(4, 7, 'OK\r'),
(4, 7, 'SEO\r'),
(6, 1, 'that\r'),
(6, 1, 'ths\r'),
(6, 2, 'Stage 1\r'),
(6, 2, 'Stage 2\r'),
(6, 3, 'Craigslist\r'),
(6, 3, 'Google\r'),
(6, 4, 'Call ME\r'),
(6, 4, 'Text ME\r'),
(6, 6, 'Accepted\r'),
(6, 6, 'Approved\r'),
(6, 6, 'Pending\r'),
(6, 7, 'AdWords\r'),
(6, 7, 'PPC\r'),
(6, 7, 'SEO\r');

-- --------------------------------------------------------

--
-- Table structure for table `user_master_forms`
--

CREATE TABLE IF NOT EXISTS `user_master_forms` (
  `user_id` int(11) NOT NULL,
  `master_form_id` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  UNIQUE KEY `user_id` (`user_id`,`master_form_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_master_forms`
--

INSERT INTO `user_master_forms` (`user_id`, `master_form_id`, `active`) VALUES
(4, 7, 1),
(4, 6, 1),
(4, 4, 1),
(4, 3, 1),
(4, 2, 1),
(4, 1, 1),
(4, 0, 0),
(6, 0, 0),
(6, 1, 1),
(6, 2, 1),
(6, 3, 1),
(6, 4, 1),
(6, 6, 1),
(6, 7, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
