-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Дек 03 2013 г., 15:04
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `elementus`
--

-- --------------------------------------------------------

--
-- Структура таблицы `apps`
--

CREATE TABLE IF NOT EXISTS `apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `template_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `template_id` (`template_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `apps`
--

INSERT INTO `apps` (`id`, `name`, `key`, `domain`, `template_id`) VALUES
(1, 'Первый строй центр', 'th3Ge8nWdi2bJpH7Sw', 'elementus.loc', 23),
(2, 'Демо сайт', 'sdf42d78c2vft90tas254svswr', 'demo.elementus.loc', 82);

-- --------------------------------------------------------

--
-- Структура таблицы `elements`
--

CREATE TABLE IF NOT EXISTS `elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=139 ;

--
-- Дамп данных таблицы `elements`
--

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
(29, 1, 2),
(30, 1, 2),
(51, 2, 2),
(82, 6, 2),
(89, 15, 2),
(90, 16, 2),
(91, 16, 2),
(92, 17, 2),
(93, 17, 2),
(108, 7, 2),
(110, 7, 2),
(113, 0, 2),
(114, 0, 2),
(115, 0, 2),
(116, 0, 2),
(117, 0, 2),
(118, 0, 2),
(119, 0, 2),
(120, 0, 2),
(121, 0, 2),
(125, 18, 2),
(126, 18, 2),
(127, 18, 2),
(128, 7, 2),
(129, 18, 2),
(131, 18, 2),
(135, 7, 2),
(136, 18, 2),
(137, 1, 1),
(138, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `et_content`
--

CREATE TABLE IF NOT EXISTS `et_content` (
  `element_id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `header` varchar(255) NOT NULL,
  `content` text NOT NULL COMMENT '{"type":"html"}',
  PRIMARY KEY (`element_id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `et_content`
--

INSERT INTO `et_content` (`element_id`, `section_id`, `header`, `content`) VALUES
(16, NULL, 'All is Element', ''),
(17, 1, 'Все есть Элемент', 'Elementus - это функциональный, гибкий и простой PHP mySQL фреймворк для разработки веб приложений на любом языке программирования.'),
(51, 24, 'О компании', 'Это&nbsp;пример реализации интернет-магазина на основе <a rel="nofollow" target="_blank" href="http://elementus.org/">Elementus&nbsp;фреймворк</a>. Все материалы на сайте&nbsp;присутствуют исключительно в демострационных целях.'),
(108, 24, 'Apple iPhone 5 32Gb', 'Подробное описание товара'),
(110, 24, 'Apple iPhone 5 16Gb', 'Подробное описание товара.'),
(128, 28, 'Samsung Galaxy S4 16Gb GT-I9500', '<ul><li>смартфон на платформе Android</li><li>сенсорный экран мультитач (емкостный)</li><li>диагональ экрана 5", разрешение 1080x1920</li><li>камера 13 МП, светодиодная вспышка, автофокус</li><li>память 16 Гб, карты памяти microSD (TransFlash)</li><li>поддержка Bluetooth, NFC, Wi-Fi, 3G, GPS, ГЛОНАСС</li><li>вес 130 г, ШxВxТ 69.80x136.60x7.90 мм, акк. 2600 мАч</li></ul>'),
(135, 28, 'HTC One 32Gb', '<ul><li>смартфон на платформе Android</li><li>сенсорный экран мультитач (емкостный)</li><li>диагональ экрана 4.7", разрешение 1080x1920</li><li>камера 4 МП, вспышка, автофокус</li><li>память 32 Гб, без слота для карт памяти</li><li>поддержка Bluetooth, NFC, Wi-Fi, 3G, LTE, GPS, ГЛОНАСС</li><li>вес 143 г, ШxВxТ 68.20x137.40x9.30 мм, акк. 2300 мАч</li></ul>');

-- --------------------------------------------------------

--
-- Структура таблицы `et_content_products`
--

CREATE TABLE IF NOT EXISTS `et_content_products` (
  `element_id` int(11) NOT NULL,
  `shortdescr` text NOT NULL,
  `store` int(11) NOT NULL,
  `brand` enum('Samsung','LG','HTC','Sony','Apple') NOT NULL,
  `model` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL COMMENT '{"type":"image"}',
  PRIMARY KEY (`element_id`),
  KEY `brand` (`brand`),
  KEY `brand_2` (`brand`),
  KEY `brand_3` (`brand`),
  KEY `brand_4` (`brand`),
  KEY `brand_5` (`brand`),
  KEY `brand_6` (`brand`),
  KEY `brand_7` (`brand`),
  KEY `brand_8` (`brand`),
  KEY `brand_9` (`brand`),
  KEY `brand_10` (`brand`),
  KEY `brand_11` (`brand`),
  KEY `brand_12` (`brand`),
  KEY `brand_13` (`brand`),
  KEY `brand_14` (`brand`),
  KEY `brand_15` (`brand`),
  KEY `brand_16` (`brand`),
  KEY `brand_17` (`brand`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='{"view":{"type":"except","fields":{"some","another"}}';

--
-- Дамп данных таблицы `et_content_products`
--

INSERT INTO `et_content_products` (`element_id`, `shortdescr`, `store`, `brand`, `model`, `image`) VALUES
(108, '', 4, 'Apple', '5 32Gb', '286605ba2275a7397574.jpg'),
(110, 'смартфон на платформе iOS\r\nсенсорный экран мультитач (емкостный)\r\nдиагональ экрана 4", разрешение 640x1136\r\nкамера 8 МП, светодиодная вспышка, автофокус\r\nпамять 16 Гб, без слота для карт памяти\r\nподдержка Bluetooth, Wi-Fi, 3G, LTE, GPS, ГЛОНАСС\r\nвес 112 г, ШxВxТ 58.60x123.80x7.60 мм, акк. 1400 мАч', 2, 'Apple', '5 16Gb', '286605ba2275a7397574.jpg'),
(128, '', 4, 'Samsung', 'GT-I9500', 'ac87a8ba2af77880e8c2.jpg'),
(135, '', 2, 'HTC', 'One 32Gb', 'fdad31a54e2f7a672ca9.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `et_content_products_phones`
--

CREATE TABLE IF NOT EXISTS `et_content_products_phones` (
  `element_id` int(11) NOT NULL,
  `type` enum('смартфон','телефон') NOT NULL,
  `platform` enum('Android','iOS','Windows','другая') NOT NULL,
  `sims` int(11) NOT NULL,
  `screen_size` varchar(255) NOT NULL,
  `memory_size` int(11) NOT NULL,
  `card_slot` enum('Нет','Да') NOT NULL,
  `wi-fi` enum('Нет','Да') NOT NULL,
  `bluetooth` enum('Нет','Да') NOT NULL,
  `gps` enum('Нет','Да') NOT NULL,
  `battery` int(11) NOT NULL,
  PRIMARY KEY (`element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='{"view":{"type":"except","fields":""}}';

--
-- Дамп данных таблицы `et_content_products_phones`
--

INSERT INTO `et_content_products_phones` (`element_id`, `type`, `platform`, `sims`, `screen_size`, `memory_size`, `card_slot`, `wi-fi`, `bluetooth`, `gps`, `battery`) VALUES
(108, '', 'iOS', 1, '640x1136', 32, 'Да', 'Да', 'Да', 'Да', 1400),
(110, '', 'iOS', 1, '640x1136', 16, 'Да', 'Да', 'Да', 'Да', 1400),
(128, '', 'Android', 1, '1080x1920', 16, 'Да', 'Да', 'Да', 'Да', 2600),
(135, '', 'Android', 1, '1080x1920', 32, 'Да', 'Да', 'Да', 'Да', 2300);

-- --------------------------------------------------------

--
-- Структура таблицы `et_currencies`
--

CREATE TABLE IF NOT EXISTS `et_currencies` (
  `element_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `symbol` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  PRIMARY KEY (`element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `et_currencies`
--

INSERT INTO `et_currencies` (`element_id`, `name`, `symbol`, `code`, `rate`) VALUES
(90, 'Российский рубль', 'р.', 'RUB', '1'),
(91, 'Доллар США', '$', 'USD', '31.5');

-- --------------------------------------------------------

--
-- Структура таблицы `et_price-types`
--

CREATE TABLE IF NOT EXISTS `et_price-types` (
  `element_id` int(11) NOT NULL,
  `currency` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`element_id`),
  KEY `currency` (`currency`),
  KEY `currency_2` (`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `et_price-types`
--

INSERT INTO `et_price-types` (`element_id`, `currency`, `name`) VALUES
(92, 90, 'Розничная'),
(93, 91, 'Оптовая');

-- --------------------------------------------------------

--
-- Структура таблицы `et_prices`
--

CREATE TABLE IF NOT EXISTS `et_prices` (
  `element_id` int(11) NOT NULL,
  `price_type` int(11) DEFAULT NULL,
  `product` int(11) DEFAULT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`element_id`),
  KEY `price_type` (`price_type`),
  KEY `product` (`product`),
  KEY `price_type_2` (`price_type`),
  KEY `price_type_3` (`price_type`),
  KEY `price_type_4` (`price_type`),
  KEY `price_type_5` (`price_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `et_prices`
--

INSERT INTO `et_prices` (`element_id`, `price_type`, `product`, `value`) VALUES
(125, 93, 110, 12600),
(126, 92, 108, 16900),
(127, 93, 108, 34500),
(129, 92, 128, 22970),
(136, 92, 135, 21850);

-- --------------------------------------------------------

--
-- Структура таблицы `et_sections`
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
-- Дамп данных таблицы `et_sections`
--

INSERT INTO `et_sections` (`element_id`, `parent_id`, `name`, `path`, `template`, `link`) VALUES
(1, NULL, 'Главная', 'main', 'index', ''),
(9, NULL, 'Продукция', 'products', 'text', ''),
(10, NULL, 'Интернет-магазин', 'estore', 'text', ''),
(24, NULL, 'Главная', 'main', 'main', ''),
(26, NULL, 'Услуги', 'service', 'text', ''),
(28, NULL, 'Каталог', 'catalog', 'catalog', ''),
(29, NULL, 'Оплата', 'howtopay', 'text', ''),
(30, NULL, 'Доставка', 'delivery', 'text', ''),
(137, NULL, 'Контакты', 'contact', 'text', ''),
(138, 9, 'Кровля и гидроизоляция', 'krovlya_i_gidroizolyaciya', 'text', '');

-- --------------------------------------------------------

--
-- Структура таблицы `et_templates`
--

CREATE TABLE IF NOT EXISTS `et_templates` (
  `element_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `et_templates`
--

INSERT INTO `et_templates` (`element_id`, `name`, `path`) VALUES
(23, 'PST', 'psc'),
(82, 'E-store demo', 'demo');

-- --------------------------------------------------------

--
-- Структура таблицы `et_users`
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
-- Дамп данных таблицы `et_users`
--

INSERT INTO `et_users` (`element_id`, `name`, `surname`, `email`, `password`, `group_id`, `regdate`) VALUES
(22, 'demo', '', 'demo@demo.dem', '0d08030d71f686ccbd53d46592566d4f', 19, '2013-08-08 13:31:22'),
(25, 'demo', '', 'demo@demo.dem', '0d08030d71f686ccbd53d46592566d4f', 19, '2013-08-08 13:31:22');

-- --------------------------------------------------------

--
-- Структура таблицы `et_usersgroups`
--

CREATE TABLE IF NOT EXISTS `et_usersgroups` (
  `element_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `et_usersgroups`
--

INSERT INTO `et_usersgroups` (`element_id`, `name`) VALUES
(18, 'register'),
(19, 'admin');

-- --------------------------------------------------------

--
-- Структура таблицы `et_widgets`
--

CREATE TABLE IF NOT EXISTS `et_widgets` (
  `element_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `et_widgets`
--

INSERT INTO `et_widgets` (`element_id`, `name`, `position`, `type`) VALUES
(89, 'Товары', 1, 15);

-- --------------------------------------------------------

--
-- Структура таблицы `lang`
--

CREATE TABLE IF NOT EXISTS `lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app` int(11) DEFAULT NULL,
  `en` text NOT NULL,
  `ru` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `app` (`app`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;

--
-- Дамп данных таблицы `lang`
--

INSERT INTO `lang` (`id`, `app`, `en`, `ru`) VALUES
(1, NULL, 'sections', 'разделы'),
(2, NULL, 'element', 'элемент'),
(3, NULL, 'add', 'добавить'),
(4, NULL, 'copy', 'копировать'),
(5, NULL, 'delete', 'удалить'),
(6, NULL, 'text', 'текст'),
(7, NULL, 'products', 'товары'),
(8, NULL, 'name', 'название'),
(10, NULL, 'header', 'заголовок'),
(11, NULL, 'content', 'cодержание'),
(12, NULL, 'cancel', 'отмена'),
(13, NULL, 'added', 'добавлено'),
(14, NULL, 'close', 'закрыть'),
(15, NULL, 'descr', 'описание'),
(16, NULL, 'users', 'пользователи'),
(17, NULL, 'delete selected elements', 'удалить выбранные элементы'),
(18, NULL, 'types', 'типы'),
(19, NULL, 'section_id', 'раздел'),
(20, NULL, 'tree', 'дерево'),
(21, NULL, 'edit', 'изменить'),
(22, NULL, 'as', 'как'),
(23, NULL, 'not set', 'не задано'),
(24, NULL, 'usersgroups', 'группы пользователей'),
(25, NULL, 'subtype', 'субтип'),
(26, NULL, 'settings', 'настройки'),
(27, NULL, 'exit', 'выход'),
(28, NULL, 'type', 'тип'),
(29, NULL, 'fields', 'поля'),
(30, NULL, 'back', 'назад'),
(31, NULL, 'elements', 'элементы'),
(32, NULL, 'field', 'поле'),
(33, NULL, 'default', 'по-умолчанию'),
(34, NULL, 'advanced', 'продвинутый'),
(35, NULL, 'string', 'строка'),
(36, NULL, 'select', 'выбор'),
(37, NULL, 'integer', 'целое число'),
(38, NULL, 'file', 'файл'),
(39, NULL, 'image', 'изображение'),
(40, NULL, 'to', 'в'),
(42, 2, 'platform', 'Платформа'),
(45, 2, 'brand', 'бренд'),
(46, 2, 'store', 'Склад'),
(47, 2, 'model', 'модель'),
(48, NULL, 'error', 'ошибка'),
(49, NULL, 'selected', 'выбранные'),
(50, NULL, 'succesfuly', 'успешно'),
(51, 2, 'sims', 'Кол-во SIM'),
(52, 2, 'screen_size', 'Размер экрана'),
(53, 2, 'card_slot', 'Слот для карты памяти'),
(54, 2, 'battery', 'Емкость батареи'),
(55, NULL, 'translate', 'перевод'),
(56, 2, 'name', 'position'),
(57, 2, 'position', 'Позиция'),
(58, 2, 'name', 'название'),
(59, 2, 'currency', 'валюта'),
(60, 2, 'symbol', 'символ'),
(61, 2, 'rate', 'курс'),
(63, 2, 'value', 'значение'),
(64, 2, 'price_type', 'тип цены'),
(65, NULL, 'currencies', 'валюты'),
(66, NULL, 'prices', 'цены'),
(67, NULL, 'price-types', 'типы цен'),
(68, NULL, 'templates', 'шаблоны'),
(69, NULL, 'widgets', 'виджеты'),
(70, NULL, 'properties', 'свойства'),
(71, NULL, 'e-store', 'магазин'),
(72, NULL, 'show поля in list', 'oтображать поля в списке'),
(73, NULL, 'comma-separated ', 'разделенные запятой'),
(74, NULL, 'all', 'все'),
(75, NULL, 'defined', 'заданные'),
(76, NULL, 'only', 'только'),
(77, NULL, 'except', 'кроме'),
(78, NULL, 'advanced settings', 'Расширенные настройки'),
(79, NULL, 'To specify fields columns showing in type elements table go to "settings > advanced settings" in type menu', 'Для настройки колонок полей отображаемых в таблице элементов типа, зайдите в "настройки > дополнительные настройки" в меню типа'),
(80, NULL, 'tip', 'подсказка'),
(81, NULL, 'password', 'пароль');

-- --------------------------------------------------------

--
-- Структура таблицы `types`
--

CREATE TABLE IF NOT EXISTS `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `group` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  KEY `group` (`group`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Дамп данных таблицы `types`
--

INSERT INTO `types` (`id`, `parent`, `name`, `group`) VALUES
(1, NULL, 'sections', 3),
(2, NULL, 'content', 3),
(3, 2, 'products', 3),
(4, NULL, 'users', 2),
(5, NULL, 'usersGroups', 2),
(6, NULL, 'templates', 4),
(7, 3, 'phones', 3),
(8, 3, 'cameras', 3),
(15, NULL, 'widgets', 4),
(16, NULL, 'currencies', 1),
(17, NULL, 'price-types', 1),
(18, NULL, 'prices', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `types_settings`
--

CREATE TABLE IF NOT EXISTS `types_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `types_settings`
--

INSERT INTO `types_settings` (`id`, `type_id`, `name`, `value`) VALUES
(1, 3, 'import', '{"file":"f56d2af56e09d54f78c2.xml"}'),
(2, 7, 'view', '{"type":"except","fields":["sims","card_slot","wi-fi","bluetooth","gps"]}');

-- --------------------------------------------------------

--
-- Структура таблицы `type_groups`
--

CREATE TABLE IF NOT EXISTS `type_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `previous` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `type_groups`
--

INSERT INTO `type_groups` (`id`, `name`, `previous`) VALUES
(1, 'E-store', 3),
(2, 'Users', 1),
(3, 'Содержание', NULL),
(4, 'Системные', 2);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `apps`
--
ALTER TABLE `apps`
  ADD CONSTRAINT `apps_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `et_templates` (`element_id`);

--
-- Ограничения внешнего ключа таблицы `et_content`
--
ALTER TABLE `et_content`
  ADD CONSTRAINT `et_content_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `et_sections` (`element_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `et_price-types`
--
ALTER TABLE `et_price-types`
  ADD CONSTRAINT `et_price@002dtypes_ibfk_1` FOREIGN KEY (`currency`) REFERENCES `et_currencies` (`element_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `et_prices`
--
ALTER TABLE `et_prices`
  ADD CONSTRAINT `et_prices_ibfk_2` FOREIGN KEY (`product`) REFERENCES `et_content_products` (`element_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `et_prices_ibfk_3` FOREIGN KEY (`price_type`) REFERENCES `et_price-types` (`element_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `et_sections`
--
ALTER TABLE `et_sections`
  ADD CONSTRAINT `et_sections_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `et_sections` (`element_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `et_users`
--
ALTER TABLE `et_users`
  ADD CONSTRAINT `et_users_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `et_usersgroups` (`element_id`);

--
-- Ограничения внешнего ключа таблицы `lang`
--
ALTER TABLE `lang`
  ADD CONSTRAINT `lang_ibfk_1` FOREIGN KEY (`app`) REFERENCES `apps` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `types`
--
ALTER TABLE `types`
  ADD CONSTRAINT `types_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `types_ibfk_2` FOREIGN KEY (`group`) REFERENCES `type_groups` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
