SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `template_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `template_id` (`template_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `apps` (`id`, `name`, `key`, `domain`, `template_id`) VALUES
(1, 'Тестовый сайт', 'th3Ge8nWdi2bJpH7Sw', 'elementus.loc', 23),
(2, 'Демо сайт', 'sdf42d78c2vft90tas254svswr', 'demo.elementus.loc', 82);

CREATE TABLE IF NOT EXISTS `elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=89 ;

INSERT INTO `elements` (`id`, `type_id`, `app_id`) VALUES
(1, 1, 1),
(9, 1, 1),
(10, 1, 1),
(17, 2, 1),
(18, 5, 1),
(19, 5, 1),
(22, 4, 1),
(23, 6, 1),
(24, 1, 2),
(25, 4, 2),
(26, 1, 1),
(28, 1, 2),
(51, 2, 2),
(86, 3, 2),
(87, 3, 2),
(30, 1, 2),
(29, 1, 2),
(85, 3, 2),
(82, 6, 2),
(88, 3, 2);

CREATE TABLE IF NOT EXISTS `et_content` (
  `element_id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `header` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`element_id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `et_content` (`element_id`, `section_id`, `header`, `content`) VALUES
(15, NULL, 'test', 'test'),
(16, NULL, 'All is Element', ''),
(17, 1, 'Все есть Элемент', 'Elementus - это функциональный, гибкий и простой PHP mySQL фреймворк для разработки веб приложений на любом языке программирования.'),
(18, NULL, 'Все есть Элемент', 'Elemental - test'),
(19, NULL, 'Все есть Элемент', 'Elemental -'),
(20, NULL, 'Все есть Элемент', 'Elemental -'),
(31, NULL, '', ''),
(32, NULL, '', ''),
(33, NULL, '', ''),
(46, NULL, 'первый нах!', ''),
(47, NULL, 'второй пошел', ''),
(48, NULL, '', ''),
(49, NULL, 'test', ''),
(50, NULL, 'Всем заголовкам заголовок', 'test2'),
(51, 24, 'О магазине', 'Это&nbsp;пример реализации интернет-магазина на основе <a rel="nofollow" target="_blank" href="http://elementus.org/">Elementus&nbsp;фреймворк</a>. Все материалы на сайте&nbsp;<span>присутствуют исключительно в демострационных целях.</span>'),
(52, NULL, 'test3', ''),
(53, NULL, 'test4', ''),
(54, 28, 'test6', ''),
(55, NULL, 'test6', ''),
(56, NULL, 'test6', ''),
(57, NULL, 'tset769', ''),
(58, NULL, '4y6drhg', ''),
(59, NULL, 'tset', 'tset'),
(60, NULL, '', 'tests'),
(61, NULL, 'tsetsets', ''),
(62, NULL, 'sefgi', ''),
(63, NULL, 'wlitfhrsdio', ''),
(64, NULL, 'etstse', ''),
(65, NULL, 'test35r', 'sklfjsklj'),
(66, NULL, 'test', 'test'),
(67, NULL, 'test', 'test'),
(68, NULL, 'test39785', 'test'),
(69, NULL, 'test39785', 'test'),
(70, NULL, 'test39785', 'test'),
(71, NULL, 'test39785', 'test'),
(72, NULL, 'etset', 'setset'),
(73, NULL, 'etset', 'setset'),
(74, NULL, 'etset', 'setset'),
(75, NULL, 'etsfsetsfs', 'setset'),
(76, NULL, 'sklfjskl', 'sklfsl'),
(77, NULL, 'test', ''),
(78, NULL, '', ''),
(79, NULL, '', ''),
(80, NULL, '', ''),
(81, NULL, '', ''),
(83, 24, '', ''),
(84, 24, '', ''),
(85, 24, 'test', ''),
(86, 24, '', ''),
(87, 24, '', ''),
(88, 24, '', '');

CREATE TABLE IF NOT EXISTS `et_content_phones` (
  `element_id` int(11) NOT NULL,
  `fulldescr` text NOT NULL,
  `screen` varchar(255) NOT NULL,
  `store` int(11) NOT NULL,
  PRIMARY KEY (`element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `et_content_products` (
  `element_id` int(11) NOT NULL,
  `fulldescr` text NOT NULL COMMENT '{"type":"html"}',
  `store` int(11) NOT NULL,
  `brand` enum('Apple','HTC','LG') NOT NULL,
  `model` int(11) NOT NULL,
  `image` varchar(255) NOT NULL COMMENT '{"type":"image"}',
  PRIMARY KEY (`element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

INSERT INTO `et_sections` (`element_id`, `parent_id`, `name`, `path`, `template`, `link`) VALUES
(1, NULL, 'Главная', 'main', 'main', ''),
(9, NULL, 'Первые шаги', 'gettingstarted', 'text', ''),
(10, NULL, 'Документация', 'documentation', 'documentation', ''),
(24, NULL, 'Главная', 'main', 'main', ''),
(26, NULL, 'Демо', '', '', 'http://demo.elementus.loc'),
(27, NULL, 'О компании', 'about', 'text', ''),
(28, NULL, 'Каталог', 'catalog', 'catalog', ''),
(29, NULL, 'Оплата', 'howtopay', 'text', ''),
(30, NULL, 'Доставка', 'delivery', 'text', ''),
(34, NULL, 'test', '', '', ''),
(45, NULL, 'test7', '', '', '');

CREATE TABLE IF NOT EXISTS `et_templates` (
  `element_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `et_templates` (`element_id`, `name`, `path`) VALUES
(23, 'Elementus', 'elementus'),
(82, 'E-store demo', 'demo');

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

INSERT INTO `et_users` (`element_id`, `name`, `surname`, `email`, `password`, `group_id`, `regdate`) VALUES
(22, 'demo', '', 'demo@demo.dem', '0d08030d71f686ccbd53d46592566d4f', 19, '2013-08-08 13:31:22'),
(25, 'demo', '', 'demo@demo.dem', '0d08030d71f686ccbd53d46592566d4f', 19, '2013-08-08 13:31:22');

CREATE TABLE IF NOT EXISTS `et_usersgroups` (
  `element_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `et_usersgroups` (`element_id`, `name`) VALUES
(18, 'register'),
(19, 'admin');

CREATE TABLE IF NOT EXISTS `lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `en` text NOT NULL,
  `ru` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

INSERT INTO `lang` (`id`, `en`, `ru`) VALUES
(1, 'sections', 'разделы'),
(2, 'element', 'элемент'),
(3, 'add', 'добавить'),
(4, 'copy', 'копировать'),
(5, 'delete', 'удалить'),
(6, 'text', 'текст'),
(7, 'products', 'товары'),
(8, 'name', 'название'),
(24, 'Usersgroups', 'группы пользователей'),
(10, 'header', 'заголовок'),
(11, 'content', 'cодержание'),
(12, 'cancel', 'отмена'),
(13, 'added', 'добавлено'),
(14, 'close', 'закрыть'),
(15, 'descr', 'описание'),
(16, 'users', 'пользователи'),
(17, 'delete selected elements', 'удалить выбранные элементы'),
(18, 'types', 'типы'),
(19, 'section_id', 'раздел'),
(20, 'tree', 'дерево'),
(21, 'edit', 'изменить'),
(22, 'as', 'как'),
(23, 'not set', 'не задано'),
(25, 'subtype', 'субтип'),
(26, 'settings', 'настройки'),
(27, 'exit', 'выход'),
(28, 'type', 'тип'),
(29, 'fields', 'поля'),
(30, 'back', 'назад'),
(31, 'elements', 'элементы');

CREATE TABLE IF NOT EXISTS `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

INSERT INTO `types` (`id`, `parent`, `name`) VALUES
(1, NULL, 'sections'),
(2, NULL, 'content'),
(3, 2, 'products'),
(4, NULL, 'users'),
(5, NULL, 'usersGroups'),
(6, NULL, 'templates'),
(7, 3, 'phones'),
(8, 3, 'cameras');

CREATE TABLE IF NOT EXISTS `type_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


ALTER TABLE `apps`
  ADD CONSTRAINT `apps_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `et_templates` (`element_id`);

ALTER TABLE `et_content`
  ADD CONSTRAINT `et_content_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `et_sections` (`element_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `et_sections`
  ADD CONSTRAINT `et_sections_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `et_sections` (`element_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `et_users`
  ADD CONSTRAINT `et_users_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `et_usersgroups` (`element_id`);

ALTER TABLE `types`
  ADD CONSTRAINT `types_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `types` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
