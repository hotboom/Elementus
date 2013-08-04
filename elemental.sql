-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2013 at 02:00 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `elements`
--

INSERT INTO `elements` (`id`, `type_id`, `app_id`) VALUES
(1, 1, 1),
(2, 1, 1),
(3, 1, 1),
(4, 1, 1),
(5, 1, 1),
(6, 1, 1),
(7, 1, 1),
(8, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `elements_type_products`
--

CREATE TABLE IF NOT EXISTS `elements_type_products` (
  `element_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `descr` text NOT NULL,
  PRIMARY KEY (`element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `elements_type_products_bikes`
--

CREATE TABLE IF NOT EXISTS `elements_type_products_bikes` (
  `element_id` int(11) NOT NULL,
  `frame_size` int(11) NOT NULL,
  `brand` int(11) NOT NULL,
  `store_moskow` int(11) NOT NULL,
  `store_perm` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `elements_type_sections`
--

CREATE TABLE IF NOT EXISTS `elements_type_sections` (
  `element_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`element_id`),
  UNIQUE KEY `element_id` (`element_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `elements_type_sections`
--

INSERT INTO `elements_type_sections` (`element_id`, `parent_id`, `name`) VALUES
(1, NULL, 'Главная'),
(2, NULL, 'Каталог'),
(3, NULL, 'О компании'),
(4, NULL, 'Контакты'),
(5, NULL, 'Услуги'),
(6, NULL, 'Еще раздельчик епта!'),
(7, NULL, 'Ееще разочек а!'),
(8, NULL, 'еуые2');

-- --------------------------------------------------------

--
-- Table structure for table `elements_type_text`
--

CREATE TABLE IF NOT EXISTS `elements_type_text` (
  `element_id` int(11) NOT NULL,
  `header` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`element_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `element_types`
--

CREATE TABLE IF NOT EXISTS `element_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `element_types`
--

INSERT INTO `element_types` (`id`, `parent`, `name`) VALUES
(1, 0, 'sections'),
(2, 0, 'text'),
(3, 0, 'products'),
(4, 0, 'users');

-- --------------------------------------------------------

--
-- Table structure for table `lang`
--

CREATE TABLE IF NOT EXISTS `lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `en` text NOT NULL,
  `ru` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

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
(9, 'root', 'корень'),
(10, 'header', 'заголовок'),
(11, 'content', 'Содержание'),
(12, 'cancel', 'отмена'),
(13, 'added', 'добавлено'),
(14, 'close', 'закрыть'),
(15, 'descr', 'описание'),
(16, 'users', 'пользователи'),
(17, 'delete selected elements', 'удалить выбранные элементы');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `elements_type_sections`
--
ALTER TABLE `elements_type_sections`
  ADD CONSTRAINT `elements_type_sections_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `elements_type_sections` (`element_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
