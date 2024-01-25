-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-01-25 15:39:47
-- サーバのバージョン： 10.4.27-MariaDB
-- PHP のバージョン: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `php`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `moushi`
--

CREATE TABLE `moushi` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` varchar(100) NOT NULL,
  `tel` int(20) NOT NULL,
  `grade` varchar(20) NOT NULL,
  `gakkou` varchar(100) NOT NULL,
  `kana` varchar(100) NOT NULL,
  `katei` varchar(20) NOT NULL,
  `joinn` date NOT NULL,
  `postalCode` int(255) NOT NULL,
  `prefecture` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `moushi`
--

INSERT INTO `moushi` (`id`, `fullname`, `email`, `message`, `tel`, `grade`, `gakkou`, `kana`, `katei`, `joinn`, `postalCode`, `prefecture`, `city`, `address1`, `address2`) VALUES
(0, 'トリシマリント', '221013@sapporo-mirai.ac.jp', 'aa', 2147483647, '1', 'sss', 'bokudesu', 'zenniti', '2024-01-24', 40022, '北海道', '札幌市厚別区', '厚別南', 'ひばりが丘パークマンション105');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
