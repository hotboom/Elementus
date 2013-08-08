-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2013 at 11:51 PM
-- Server version: 5.5.25
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `elemental`
--

-- --------------------------------------------------------

--
-- Table structure for table `apps`
--

CREATE TABLE IF NOT EXISTS `apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `apps`
--

INSERT INTO `apps` (`id`, `name`, `key`) VALUES
(1, 'Тестовый сайт', 'th3Ge8nWdi2bJpH7Sw');

-- --------------------------------------------------------

--
-- Table structure for table `elements`
--

CREATE TABLE IF NOT EXISTS `elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `elements`
--

INSERT INTO `elements` (`id`, `type_id`, `app_id`) VALUES
(1, 1, 1),
(9, 1, 1),
(10, 1, 1),
(17, 2, 1),
(18, 5, 1),
(19, 5, 1),
(22, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `et_content`
--

CREATE TABLE IF NOT EXISTS `et_content` (
  `element_id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `header` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`element_id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `et_content`
--

INSERT INTO `et_content` (`element_id`, `section_id`, `header`, `content`) VALUES
(15, NULL, 'test', 'test'),
(16, NULL, 'All is Element', ''),
(17, 1, 'Все есть Элемент', 'Elementus - это функциональный, гибкий и простой PHP mySQL фреймворк для разработки веб приложений на любом языке программирования.'),
(18, NULL, 'Все есть Элемент', 'Elemental - test'),
(19, NULL, 'Все есть Элемент', 'Elemental -'),
(20, NULL, 'Все есть Элемент', 'Elemental -');

-- --------------------------------------------------------

--
-- Table structure for table `et_content_products`
--

CREATE TABLE IF NOT EXISTS `et_content_products` (
  `element_id` int(11) NOT NULL,
  `fulldescr` text NOT NULL,
  PRIMARY KEY (`element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `et_sections`
--

CREATE TABLE IF NOT EXISTS `et_sections` (
  `element_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `template` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`element_id`),
  UNIQUE KEY `element_id` (`element_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `et_sections`
--

INSERT INTO `et_sections` (`element_id`, `parent_id`, `name`, `path`, `template`, `link`) VALUES
(1, NULL, 'Главная', 'main', 'main', ''),
(9, NULL, 'Первые шаги', 'gettingstarted', 'text', ''),
(10, NULL, 'Документация', 'documentation', 'documentation', '');

-- --------------------------------------------------------

--
-- Table structure for table `et_users`
--

CREATE TABLE IF NOT EXISTS `et_users` (
  `element_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL,
  `regdate` datetime NOT NULL,
  PRIMARY KEY (`element_id`),
  KEY `group_id` (`group_id`),
  KEY `group_id_2` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `et_users`
--

INSERT INTO `et_users` (`element_id`, `name`, `surname`, `email`, `password`, `group_id`, `regdate`) VALUES
(22, 'admin', '', 'huntedbox@gmail.com', '18a417d7dfc98288', 18, '2013-08-08 13:31:22'),
(23, 'user', '', '', '', 18, '0000-00-00 00:00:00'),
(24, 'test1', '', '', '', 18, '0000-00-00 00:00:00'),
(25, 'test2', '', '', '', 18, '0000-00-00 00:00:00'),
(26, 'test3', '', '', '', 18, '0000-00-00 00:00:00'),
(27, 'test5', '', '', '', 18, '0000-00-00 00:00:00'),
(28, 'test', '', '', '', 18, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `et_usersgroups`
--

CREATE TABLE IF NOT EXISTS `et_usersgroups` (
  `element_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `et_usersgroups`
--

INSERT INTO `et_usersgroups` (`element_id`, `name`) VALUES
(18, 'register'),
(19, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `lang`
--

CREATE TABLE IF NOT EXISTS `lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `en` text NOT NULL,
  `ru` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `lang`
--

INSERT INTO `lang` (`id`, `en`, `ru`) VALUES
(1, 'sections', 'разделы'),
(2, 'element', 'элемент'),
(3, 'add', 'добавить'),
(4, 'copy', 'копировать'),
(5, 'delete', 'удалить'),
(6, 'text', 'текст'),
(7, 'products', 'товары'),
(8, 'name', 'Название'),
(24, 'Usersgroups', 'Группы пользователей'),
(10, 'header', 'заголовок'),
(11, 'content', 'Содержание'),
(12, 'cancel', 'отмена'),
(13, 'added', 'добавлено'),
(14, 'close', 'закрыть'),
(15, 'descr', 'описание'),
(16, 'users', 'пользователи'),
(17, 'delete selected elements', 'удалить выбранные элементы'),
(18, 'types', 'Типы'),
(19, 'section_id', 'раздел'),
(20, 'tree', 'дерево'),
(21, 'edit', 'изменить'),
(22, 'as', 'как'),
(23, 'not set', 'не задано');

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE IF NOT EXISTS `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `parent`, `name`) VALUES
(1, 0, 'sections'),
(2, 0, 'content'),
(3, 2, 'products'),
(4, 0, 'users'),
(5, 0, 'usersGroups');

-- --------------------------------------------------------

--
-- Table structure for table `type_groups`
--

CREATE TABLE IF NOT EXISTS `type_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `et_content`
--
ALTER TABLE `et_content`
  ADD CONSTRAINT `et_content_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `et_sections` (`element_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `et_sections`
--
ALTER TABLE `et_sections`
  ADD CONSTRAINT `et_sections_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `et_sections` (`element_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `et_users`
--
ALTER TABLE `et_users`
  ADD CONSTRAINT `et_users_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `et_usersgroups` (`element_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
