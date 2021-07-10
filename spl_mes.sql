-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2021 at 02:54 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spl_mes_old`
--

-- --------------------------------------------------------

--
-- Table structure for table `boiler_checklist_summery`
--

CREATE TABLE `boiler_checklist_summery` (
  `id` int(11) NOT NULL,
  `16_tph_boiler_log_sheet` tinyint(1) NOT NULL,
  `weekly_maintenance` tinyint(1) NOT NULL,
  `daily_maintenance` tinyint(1) NOT NULL,
  `daily_sample_testing` tinyint(1) NOT NULL,
  `is_draft` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `boiler_product_inputs`
--

CREATE TABLE `boiler_product_inputs` (
  `id` int(11) NOT NULL,
  `electricity` varchar(50) NOT NULL COMMENT 'Electricity/unit',
  `husk` float(10,4) NOT NULL COMMENT 'husk/ton',
  `diesel` float(10,4) NOT NULL COMMENT 'Diesel/litre',
  `labour` float(10,4) NOT NULL COMMENT 'labour/no. of people',
  `water` float(10,4) NOT NULL COMMENT 'water/ton',
  `charcoal` float(10,4) NOT NULL COMMENT 'charcoal/kg',
  `salt` float(10,4) NOT NULL COMMENT 'salt/kg',
  `is_draft` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `boiler_quality_test_reports`
--

CREATE TABLE `boiler_quality_test_reports` (
  `id` int(11) NOT NULL,
  `is_draft` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `boiler_steam_outputs`
--

CREATE TABLE `boiler_steam_outputs` (
  `id` int(11) NOT NULL,
  `refinery` float(10,4) NOT NULL,
  `oil_tank_section` float(10,4) NOT NULL,
  `steam_wastage` float(10,4) NOT NULL,
  `expeller` float(10,4) NOT NULL,
  `acid_oil_plant` float(10,4) NOT NULL,
  `total_steam_allocation` float(10,4) NOT NULL,
  `fractionation` float(10,4) NOT NULL,
  `is_draft` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` tinyint(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `name`, `slug`, `subject`, `description`, `created`, `modified`, `status`) VALUES
(2, 'Password Changed', 'change_password', 'Password Changed', '<p>Dear {{username}},</p>\r\n\r\n<p>Your password has been succesfully changed by admin so please login first&nbsp;on the Mes .</p>\r\n\r\n<p>Your login details are as follows:<br />\r\n<br />\r\nUsername: {{email}}<br />\r\nPassword: {{password}}</p>\r\n\r\n<p>Please keep this information safe for future reference.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Warm regards,<br />\r\nMes Team</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>( ***&nbsp; Please do not reply to this email ***&nbsp; )</strong></p>\r\n', '2021-06-05 09:24:51', '2021-06-05 09:24:51', 1),
(3, 'Forgot Password', 'forgot_password', 'Reset Password', '<p>Dear {{username}},</p>\r\n\r\n<p>You have requested for password change.</p>\r\n\r\n<p>Please click <a href=\"{{activation_link}}\">Here</a> to reset password<br />\r\n<br />\r\n<br />\r\nThanks,<br />\r\nMes Team</p>\r\n\r\n<p><strong>( ***&nbsp; Please do not reply to this email ***&nbsp; )</strong></p>\r\n', '2021-06-05 09:24:51', '2021-06-05 09:24:51', 1),
(1, 'User Registration', 'user_registration', 'Registration', '<p>Dear {{username}},</p>\r\n\r\n<p>Thank you for registering with us.</p>\r\n\r\n<p>Please contact to administrator for complete your registration and to activate your account.</p>\r\n\r\n<p>Your email is: {{email}}<br />\r\nYour password is: {{password}}</p>\r\n\r\n<p>Should you have any queries or if any of your details change, please contact us.</p>\r\n\r\n<p>Best regards,<br />\r\nMes Team</p>\r\n\r\n<p><strong>( ***&nbsp; Please do not reply to this email ***&nbsp; )</strong></p>\r\n', '2021-06-05 09:24:51', '2021-06-05 09:24:51', 1),
(45, 'User Add', 'user_add', 'Registration', '<p>Dear {{username}},</p>\r\n\r\n<p>You have registered on Mes</p>\r\n\r\n<p>Please click <a href=\"{{activation_link}}\">here</a> to complete your registration and to activate your account.</p>\r\n\r\n<p>Your email is: {{email}}<br />\r\nYour password is: {{password}}</p>\r\n\r\n<p>Should you have any queries or if any of your details change, please contact us.</p>\r\n\r\n<p>Best regards,<br />\r\nMes Team</p>\r\n\r\n<p><strong>( ***&nbsp; Please do not reply to this email ***&nbsp; )</strong></p>\r\n', '2021-06-05 09:24:51', '2021-06-05 09:24:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `expeller_checklist_summaries`
--

CREATE TABLE `expeller_checklist_summaries` (
  `id` int(11) NOT NULL,
  `daily_production_report` tinyint(1) NOT NULL,
  `drying_section_log_sheet` tinyint(1) NOT NULL,
  `filteration_section_checklist` tinyint(1) NOT NULL,
  `expeller_log_sheet` tinyint(1) NOT NULL,
  `pre_section_checklist` tinyint(1) NOT NULL,
  `daily_sample_testing_record` tinyint(1) NOT NULL,
  `plf_section_log_sheet` tinyint(1) NOT NULL,
  `expeller_section_checklist` tinyint(1) NOT NULL,
  `is_draft` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `expeller_productions`
--

CREATE TABLE `expeller_productions` (
  `id` int(11) NOT NULL,
  `cake_processed` float(10,4) NOT NULL,
  `power_consumed` float(10,4) NOT NULL,
  `filetr_mud_produced` float(10,4) NOT NULL,
  `commodity_processes` float(10,4) NOT NULL,
  `steam_consumed` float(10,4) NOT NULL,
  `crude_butter_recovery` float(10,4) NOT NULL,
  `oil_produced` float(10,4) NOT NULL,
  `is_draft` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `expeller_quality_test_report`
--

CREATE TABLE `expeller_quality_test_report` (
  `id` int(11) NOT NULL,
  `commodity` smallint(3) NOT NULL,
  `filtered_oil` smallint(3) NOT NULL,
  `plf_mud` smallint(3) NOT NULL,
  `ffa` smallint(3) NOT NULL,
  `filtered_oil_ffa` smallint(3) NOT NULL,
  `cake_oil_content` smallint(3) NOT NULL,
  `commodity_oil_content` smallint(3) NOT NULL,
  `impurity` smallint(3) NOT NULL,
  `is_draft` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `expeller_stock_position`
--

CREATE TABLE `expeller_stock_position` (
  `id` int(11) NOT NULL,
  `commodity_sheet_opening_value` varchar(28) NOT NULL,
  `cake_opening_value` varchar(28) NOT NULL,
  `crude_opening_value` varchar(28) NOT NULL,
  `filter_mud_opening_value` varchar(28) NOT NULL,
  `commodity_sheet_closing_value` varchar(28) NOT NULL,
  `cake_closing_value` varchar(28) NOT NULL,
  `crude_closing_value` varchar(28) NOT NULL,
  `filter_mud_closing_value` varchar(28) NOT NULL,
  `is_draft` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`id`, `user_id`, `date_from`, `date_to`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 2, '2021-06-17', '2021-06-18', 1, 1, '2021-06-15 00:00:00', '2021-06-15 00:00:00'),
(2, 2, '2021-06-30', '2021-06-30', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 2, '2021-06-29', '2021-06-30', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 3, '2021-06-16', '2021-06-17', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 3, '2021-06-18', '2021-06-20', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 4, '2021-06-16', '2021-06-17', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 4, '2021-06-30', '2021-07-07', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 4, '2021-07-04', '2021-07-13', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 4, '2021-07-05', '2021-07-19', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 9, '2021-07-10', '2021-07-19', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 2, '2021-07-16', '2021-07-28', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 2, '2021-07-28', '2021-07-31', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `oil_tank_systems`
--

CREATE TABLE `oil_tank_systems` (
  `id` int(11) NOT NULL,
  `is_draft` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `module` varchar(28) NOT NULL,
  `can_view` int(1) NOT NULL,
  `can_add` int(1) NOT NULL,
  `can_edit` int(1) NOT NULL,
  `can_delete` int(1) NOT NULL,
  `status` int(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `role_id`, `module`, `can_view`, `can_add`, `can_edit`, `can_delete`, `status`, `created_by`, `updated_by`, `created`, `modified`) VALUES
(7, 2, 'User', 1, 1, 1, 0, 0, 0, NULL, '2021-07-05 07:32:11', '2021-07-05 10:58:15'),
(8, 2, 'Role', 1, 1, 1, 0, 0, 0, NULL, '2021-07-05 07:32:11', '2021-07-06 09:37:12'),
(9, 2, 'Boiler', 1, 0, 1, 0, 0, 0, NULL, '2021-07-05 07:32:11', '2021-07-05 07:32:11'),
(10, 2, 'Expeller', 1, 1, 0, 0, 0, 0, NULL, '2021-07-05 07:32:11', '2021-07-05 07:41:06'),
(11, 2, 'Oil Tank System', 1, 1, 0, 0, 0, 0, NULL, '2021-07-05 07:32:11', '2021-07-06 09:30:51'),
(12, 2, 'Way Bridge', 1, 1, 1, 0, 0, 0, NULL, '2021-07-05 07:32:11', '2021-07-05 10:58:16'),
(19, 6, 'User', 1, 1, 0, 0, 0, 0, NULL, '2021-07-06 13:35:44', '2021-07-06 13:35:44'),
(20, 6, 'Role', 1, 1, 1, 0, 0, 0, NULL, '2021-07-06 13:35:44', '2021-07-06 13:39:22'),
(21, 6, 'Boiler', 1, 0, 0, 1, 0, 0, NULL, '2021-07-06 13:35:44', '2021-07-06 13:35:44'),
(22, 6, 'Expeller', 1, 0, 1, 0, 0, 0, NULL, '2021-07-06 13:35:44', '2021-07-06 13:35:44'),
(23, 6, 'Oil Tank System', 1, 1, 0, 1, 0, 0, NULL, '2021-07-06 13:35:45', '2021-07-06 13:39:22'),
(24, 6, 'Way Bridge', 1, 0, 1, 1, 0, 0, NULL, '2021-07-06 13:35:45', '2021-07-06 13:39:23'),
(43, 3, 'User', 1, 1, 1, 0, 0, 0, NULL, '2021-07-06 14:36:51', '2021-07-06 14:36:51'),
(44, 3, 'Role', 1, 1, 1, 0, 0, 0, NULL, '2021-07-06 14:36:51', '2021-07-06 14:36:51'),
(45, 3, 'Boiler', 1, 1, 1, 0, 0, 0, NULL, '2021-07-06 14:36:51', '2021-07-06 14:36:51'),
(46, 3, 'Expeller', 1, 1, 1, 0, 0, 0, NULL, '2021-07-06 14:36:51', '2021-07-06 14:36:51'),
(47, 3, 'Oil Tank System', 1, 1, 1, 0, 0, 0, NULL, '2021-07-06 14:36:51', '2021-07-06 14:36:51'),
(48, 3, 'Way Bridge', 1, 1, 1, 0, 0, 0, NULL, '2021-07-06 14:36:51', '2021-07-06 14:36:51');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` int(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `status`, `created_by`, `updated_by`, `created`, `modified`) VALUES
(1, 'Superadmin', 1, 1, 2, '2021-06-08 05:24:28', '2021-06-11 16:52:26'),
(2, 'Admin', 1, 1, 1, '2021-06-08 05:24:28', '2021-06-08 05:24:28'),
(3, 'User', 1, 1, 1, '2021-06-08 05:24:28', '2021-06-08 05:24:28'),
(4, 'Boiler Supervisor', 1, 1, 0, '2021-06-15 16:54:54', '2021-06-15 16:54:54'),
(5, 'Boiler Exporter', 1, 1, 0, '2021-06-15 16:54:54', '2021-06-15 16:54:54'),
(6, 'Supervisor1', 1, 1, 0, '2021-07-06 13:35:44', '2021-07-06 13:35:44'),
(7, 'role1', 1, 1, 0, '2021-07-06 14:27:48', '2021-07-06 14:27:48');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `maxlength` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `title`, `value`, `slug`, `type`, `maxlength`) VALUES
(1, 'Paging', '20', 'pageRecord', 'number', 3),
(2, 'EmailFrom', 'onishsofmes@gmail.com', 'EmailFrom', 'email', 255);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `task_type` varchar(28) NOT NULL,
  `date_at` date NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0=Pending, 1=Completed, 2=Approved, 3=Rejected',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(14) NOT NULL,
  `name` varchar(50) NOT NULL,
  `supervisor_name` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(25) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  `status` int(1) NOT NULL,
  `access_from` date DEFAULT NULL,
  `access_to` date DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `supervisor_name`, `password`, `email`, `address`, `phone`, `created_by`, `updated_by`, `token`, `status`, `access_from`, `access_to`, `avatar`, `created`, `modified`) VALUES
(1, 'sam', NULL, '$2y$10$OxnTnjlwO.0OFcRBRiHR0.G/afPQ09D7vJWv2AKVZoo//W96qkFRG', 'sam@mailinator.com', 'A-17', '9632587411', 1, 2, '1519943d92c3279a9afd', 1, NULL, NULL, NULL, '2021-06-05 11:03:56', '2021-06-11 13:07:27'),
(2, 'lonit', NULL, '$2y$10$S9c9ZQ4fpHRVskEqAVS2ZOUG1chfQ8lhqb.9SDkjqIz/9rPKUabe6', 'lonit@mailinator.com', '2', '9632587410', 1, NULL, '0c11cde8b19ae89b308d', 1, '2021-06-16', '2021-06-30', NULL, '2021-06-05 11:24:29', '2021-06-05 11:24:29'),
(3, 'sandy', NULL, '$2y$10$OxnTnjlwO.0OFcRBRiHR0.G/afPQ09D7vJWv2AKVZoo//W96qkFRG', 'sandy@mailinator.com', 'new city house', '9876543210', 2, 2, '164bc7d6bcb77a78b4e7', 1, NULL, NULL, NULL, '2021-06-11 12:00:59', '2021-06-11 16:49:15'),
(4, 'sanjay', NULL, '$2y$10$OxnTnjlwO.0OFcRBRiHR0.G/afPQ09D7vJWv2AKVZoo//W96qkFRG', 'sanjay@mailinator.com', 'A-17, new colony', '8954789652', 1, 1, 'd5216e3a68ed1bd95b18', 1, '2021-06-20', '2021-06-20', NULL, '2021-06-15 16:42:48', '2021-06-20 14:15:20'),
(6, 'sandeep jangir', NULL, '$2y$10$3Jz0TcvUNuLvf.ZC8oztlOrXfZHjDDTt2HXHtKXUxTr.wQIzlVeNa', 'sandeepsad@mailinator.com', 'A-17', '9999999991', 1, NULL, '9088bdf3a591bea60852', 1, NULL, NULL, NULL, '2021-07-04 07:15:48', '2021-07-04 07:15:48'),
(7, 'sandeep jangir', 'sanjay', '$2y$10$H67tP1jo48W1xjpp6fP7G.wuTDJHmhUuO24yaIuJf1PnOSxX0uHa6', 'sandeeps@mailinator.com', '				A-17			', '9999999993', 1, 1, '418dccb3494935e0e699', 1, '2021-07-04', '2021-07-04', NULL, '2021-07-04 07:20:30', '2021-07-04 10:43:49'),
(8, 'sandeep jangir', NULL, '$2y$10$styYnEX0laYFJTN9gA44WO4eLayR4Nt2Rgd8E7zNTAPL08/JdiPIG', 'sandeepss@mailinator.com', '				A-17			', '2999999993', 1, 1, '87996ceaae8b723097eb', 1, '2021-07-04', '2021-07-04', NULL, '2021-07-04 07:22:38', '2021-07-04 10:40:51'),
(9, 'Pooja Sharma', 'sandeep', '$2y$10$OxnTnjlwO.0OFcRBRiHR0.G/afPQ09D7vJWv2AKVZoo//W96qkFRG', 'pooja@mailinator.com', 'new colony', '523687412', 1, NULL, 'bb2b5a83efb1af9dab05', 1, '2021-07-04', '2021-07-04', NULL, '2021-07-04 11:03:52', '2021-07-04 11:03:52');

-- --------------------------------------------------------

--
-- Table structure for table `user_accessdates`
--

CREATE TABLE `user_accessdates` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `access_from` date NOT NULL,
  `access_to` date NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_accessdates`
--

INSERT INTO `user_accessdates` (`id`, `user_id`, `access_from`, `access_to`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(15, 4, '2021-06-16', '2021-06-22', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 4, '2021-07-02', '2021-07-10', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 8, '2021-07-04', '2021-07-19', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 8, '2021-07-20', '2021-07-26', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 7, '2021-07-04', '2021-07-05', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 9, '2021-07-04', '2021-07-06', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 9, '2021-07-13', '2021-07-21', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `status`, `created_by`, `updated_by`, `created`, `modified`) VALUES
(1, 1, 1, 1, 2, NULL, '2021-06-08 16:52:28', '2021-06-08 16:52:28'),
(2, 3, 2, 1, 2, 2, '2021-06-11 12:01:00', '2021-06-11 13:05:40'),
(3, 2, 2, 1, 2, 2, '2021-06-11 12:01:00', '2021-06-11 13:05:40'),
(4, 4, 3, 1, 1, 1, '2021-06-15 16:42:48', '2021-06-16 06:43:19'),
(5, 5, 2, 1, 1, NULL, '2021-07-04 06:39:20', '2021-07-04 06:39:20'),
(6, 5, 4, 1, 1, NULL, '2021-07-04 06:39:20', '2021-07-04 06:39:20'),
(7, 6, 3, 1, 1, NULL, '2021-07-04 07:15:48', '2021-07-04 07:15:48'),
(8, 6, 3, 1, 1, NULL, '2021-07-04 07:15:48', '2021-07-04 07:15:48'),
(9, 6, 4, 1, 1, NULL, '2021-07-04 07:15:48', '2021-07-04 07:15:48'),
(15, 8, 2, 1, 1, 1, '2021-07-04 10:40:51', '2021-07-04 10:40:51'),
(16, 8, 4, 1, 1, 1, '2021-07-04 10:40:51', '2021-07-04 10:40:51'),
(17, 8, 3, 1, 1, 1, '2021-07-04 10:40:51', '2021-07-04 10:40:51'),
(18, 7, 2, 1, 1, 1, '2021-07-04 10:43:49', '2021-07-04 10:43:49'),
(19, 9, 2, 1, 1, NULL, '2021-07-04 11:03:53', '2021-07-04 11:03:53'),
(20, 9, 3, 1, 1, NULL, '2021-07-04 11:03:53', '2021-07-04 11:03:53');

-- --------------------------------------------------------

--
-- Table structure for table `way_bridge_unit_ones`
--

CREATE TABLE `way_bridge_unit_ones` (
  `id` int(11) NOT NULL,
  `vendor_name` varchar(50) NOT NULL,
  `vehicle_number` varchar(64) NOT NULL,
  `time_out` datetime NOT NULL,
  `weight` float(10,4) NOT NULL,
  `commodity` varchar(64) NOT NULL,
  `time_in` datetime NOT NULL,
  `weight_out` float(10,4) NOT NULL,
  `is_draft` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boiler_checklist_summery`
--
ALTER TABLE `boiler_checklist_summery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boiler_product_inputs`
--
ALTER TABLE `boiler_product_inputs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boiler_quality_test_reports`
--
ALTER TABLE `boiler_quality_test_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boiler_steam_outputs`
--
ALTER TABLE `boiler_steam_outputs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expeller_checklist_summaries`
--
ALTER TABLE `expeller_checklist_summaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expeller_productions`
--
ALTER TABLE `expeller_productions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expeller_quality_test_report`
--
ALTER TABLE `expeller_quality_test_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expeller_stock_position`
--
ALTER TABLE `expeller_stock_position`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `oil_tank_systems`
--
ALTER TABLE `oil_tank_systems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_accessdates`
--
ALTER TABLE `user_accessdates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `way_bridge_unit_ones`
--
ALTER TABLE `way_bridge_unit_ones`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boiler_checklist_summery`
--
ALTER TABLE `boiler_checklist_summery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boiler_product_inputs`
--
ALTER TABLE `boiler_product_inputs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boiler_quality_test_reports`
--
ALTER TABLE `boiler_quality_test_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boiler_steam_outputs`
--
ALTER TABLE `boiler_steam_outputs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `expeller_checklist_summaries`
--
ALTER TABLE `expeller_checklist_summaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expeller_productions`
--
ALTER TABLE `expeller_productions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expeller_quality_test_report`
--
ALTER TABLE `expeller_quality_test_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expeller_stock_position`
--
ALTER TABLE `expeller_stock_position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `oil_tank_systems`
--
ALTER TABLE `oil_tank_systems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_accessdates`
--
ALTER TABLE `user_accessdates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `way_bridge_unit_ones`
--
ALTER TABLE `way_bridge_unit_ones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
