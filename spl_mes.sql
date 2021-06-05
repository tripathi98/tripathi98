-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2021 at 03:09 PM
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
-- Database: `spl_mes`
--

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
(3, 'Forgot Password', 'forgot_password', 'Reset Password', '<p>Dear {{username}},</p>\r\n\r\n<p>You have requested for password change.</p>\r\n\r\n<p>Please click <a href=\"{{activation_link}}\">Here</a><br />\r\n<br />\r\n<br />\r\nThanks,<br />\r\nMes Team</p>\r\n\r\n<p><strong>( ***&nbsp; Please do not reply to this email ***&nbsp; )</strong></p>\r\n', '2021-06-05 09:24:51', '2021-06-05 09:24:51', 1),
(1, 'User Registration', 'user_registration', 'Registration', '<p>Dear {{username}},</p>\r\n\r\n<p>Thank you for registering with us.</p>\r\n\r\n<p>Please click <a href=\"{{activation_link}}\">here</a> to complete your registration and to activate your account.</p>\r\n\r\n<p>Your email is: {{email}}<br />\r\nYour password is: {{password}}</p>\r\n\r\n<p>Should you have any queries or if any of your details change, please contact us.</p>\r\n\r\n<p>Best regards,<br />\r\nMes Team</p>\r\n\r\n<p><strong>( ***&nbsp; Please do not reply to this email ***&nbsp; )</strong></p>\r\n', '2021-06-05 09:24:51', '2021-06-05 09:24:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(14) NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(25) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  `status` int(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `address`, `phone`, `created_by`, `updated_by`, `token`, `status`, `created`, `modified`) VALUES
(1, 'sam', '$2y$10$OxnTnjlwO.0OFcRBRiHR0.G/afPQ09D7vJWv2AKVZoo//W96qkFRG', 'sam@mailinator.com', '1', '9632587411', 19880912, NULL, '1519943d92c3279a9afd', 1, '2021-06-05 11:03:56', '2021-06-05 11:03:56'),
(2, 'lonit', '$2y$10$OxnTnjlwO.0OFcRBRiHR0.G/afPQ09D7vJWv2AKVZoo//W96qkFRG', 'lonit@mailinator.com', '2', '9632587410', 19880912, NULL, '0c11cde8b19ae89b308d', 1, '2021-06-05 11:24:29', '2021-06-05 11:24:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
