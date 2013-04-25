-- phpMyAdmin SQL Dump
-- version 3.5.6
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Апр 25 2013 г., 21:22
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `fpd`
--

-- --------------------------------------------------------

--
-- Структура таблицы `advertisement`
--

CREATE TABLE IF NOT EXISTS `advertisement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `left_panel_show` tinyint(1) NOT NULL,
  `right_panel_show` tinyint(1) NOT NULL,
  `left_panel_text` varchar(4096) NOT NULL,
  `right_panel_text` varchar(4096) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `advertisement`
--

INSERT INTO `advertisement` (`id`, `left_panel_show`, `right_panel_show`, `left_panel_text`, `right_panel_text`) VALUES
(1, 1, 1, '<img src="http://s2.jrnl.ie/media/2011/08/297394_370330064948_507044948_1484824_6623199_n-390x285.jpg">\r\n<br>\r\n', '<img src="http://static6.businessinsider.com/image/4dece84bccd1d51404280000-300/funny-ad.jpg">');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
