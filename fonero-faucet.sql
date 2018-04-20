-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 24 2017 г., 12:47
-- Версия сервера: 5.7.20-0ubuntu0.16.04.1
-- Версия PHP: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `fonero`
--

-- --------------------------------------------------------

--
-- Структура таблицы `web_faucets`
--

DROP TABLE IF EXISTS `web_faucets`;
CREATE TABLE `web_faucets` (
  `faucet_id` int(11) NOT NULL,
  `faucet_unixtime` varchar(255) NOT NULL DEFAULT '-',
  `faucet_date` date NOT NULL,
  `faucet_time` time NOT NULL,
  `faucet_hash` varchar(255) NOT NULL,
  `faucet_addr` varchar(255) NOT NULL,
  `faucet_wallet` varchar(255) NOT NULL,
  `faucet_amount` varchar(10) NOT NULL,
  `faucet_txid` text NOT NULL,
  `faucet_jackpot` int(11) NOT NULL DEFAULT '0',
  `faucet_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `web_faucets`
--
ALTER TABLE `web_faucets`
  ADD PRIMARY KEY (`faucet_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `web_faucets`
--
ALTER TABLE `web_faucets`
  MODIFY `faucet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
