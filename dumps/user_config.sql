-- phpMyAdmin SQL Dump
-- version 3.5.6
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Мар 26 2013 г., 23:48
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
-- Структура таблицы `user_config`
--

DROP TABLE IF EXISTS `user_config`;
CREATE TABLE IF NOT EXISTS `user_config` (
  `id_config` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `language` enum('ru','en','cz') NOT NULL DEFAULT 'en',
  `confirmation_number` int(10) unsigned NOT NULL,
  `photo` varchar(64) NOT NULL,
  `weight` int(10) unsigned NOT NULL,
  `arythmy_step` smallint(5) unsigned NOT NULL DEFAULT '3',
  PRIMARY KEY (`id_config`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Дамп данных таблицы `user_config`
--

INSERT INTO `user_config` (`id_config`, `id_user`, `language`, `confirmation_number`, `photo`, `weight`, `arythmy_step`) VALUES
(1, 173, 'cz', 346747, '', 20, 0),
(5, 177, 'ru', 190246, '5179088b2fdd56cf8538f6e63fa9d609.jpg', 75, 10),
(6, 180, 'cz', 562053, 'b95c24a0cf7d9c37ce83c910c219994c.jpg', 0, 0),
(7, 182, 'cz', 309965, '', 0, 0),
(21, 194, 'ru', 999999, '', 0, 0),
(22, 195, 'ru', 999999, 'f58eebac332081096c191849df895856.jpg', 0, 0),
(23, 196, 'en', 999999, '', 0, 0),
(24, 197, 'cz', 999999, '', 0, 0),
(25, 198, 'cz', 999999, 'f9ac39c6babf0d36cbd5b33cf821d60e.jpg', 0, 10),
(26, 199, '', 996729, '', 0, 0),
(28, 201, 'en', 999999, '', 0, 0),
(29, 202, 'en', 999999, '', 0, 0),
(30, 204, 'cz', 999999, '', 0, 0),
(31, 205, 'cz', 999999, '', 0, 0),
(32, 206, 'en', 999999, '', 0, 0),
(33, 207, 'en', 999999, '', 0, 0);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `user_config`
--
ALTER TABLE `user_config`
  ADD CONSTRAINT `user_config_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
