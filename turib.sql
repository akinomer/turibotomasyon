-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 31 Mar 2022, 09:25:25
-- Sunucu sürümü: 5.7.36
-- PHP Sürümü: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `turib`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `emirler`
--

DROP TABLE IF EXISTS `emirler`;
CREATE TABLE IF NOT EXISTS `emirler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `smscode` varchar(222) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Tablo döküm verisi `emirler`
--

INSERT INTO `emirler` (`id`, `user_id`, `status`, `smscode`) VALUES
(2, 7, 0, NULL),
(3, 7, 0, NULL),
(4, 7, 0, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kriterler`
--

DROP TABLE IF EXISTS `kriterler`;
CREATE TABLE IF NOT EXISTS `kriterler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `elus` varchar(222) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fiyat` varchar(222) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mevcut_fiyat` varchar(222) COLLATE utf8_unicode_ci NOT NULL,
  `unvan` varchar(222) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lokasyon` varchar(222) COLLATE utf8_unicode_ci DEFAULT NULL,
  `miktar` varchar(222) COLLATE utf8_unicode_ci DEFAULT NULL,
  `urunadi` varchar(222) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banka` varchar(222) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `onay` int(11) NOT NULL DEFAULT '0',
  `bulunanfiyat` varchar(222) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bulunanmiktar` varchar(222) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kalanmiktar` varchar(222) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rnd` varchar(222) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Tablo döküm verisi `kriterler`
--

INSERT INTO `kriterler` (`id`, `user_id`, `elus`, `fiyat`, `mevcut_fiyat`, `unvan`, `lokasyon`, `miktar`, `urunadi`, `banka`, `status`, `onay`, `bulunanfiyat`, `bulunanmiktar`, `kalanmiktar`, `rnd`) VALUES
(22, 11, 'TRXXFUI02114', '5.05', '5.05', 'Hikmet Şeflek Tarım', 'MERKEZHKMT', '82000', 'Mısır 1.Sınıf', '', 1, 0, NULL, NULL, NULL, '342'),
(32, 7, 'TRXXENB12116', '5.2', '5.2', 'Hacıömeroğlu Diyarbakır', 'MRZHACIOMEROGLU', '12110', 'Buğday Ekmeklik Kırmızı Sert 3.Sınıf', '', 1, 0, NULL, NULL, NULL, '840');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `satis_emirler`
--

DROP TABLE IF EXISTS `satis_emirler`;
CREATE TABLE IF NOT EXISTS `satis_emirler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emirler` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `satis_emirler`
--

INSERT INTO `satis_emirler` (`id`, `emirler`) VALUES
(1, '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `turib_auth`
--

DROP TABLE IF EXISTS `turib_auth`;
CREATE TABLE IF NOT EXISTS `turib_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `turibusername` varchar(222) COLLATE utf8_unicode_ci NOT NULL,
  `turibparola` varchar(222) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `smscode` varchar(222) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cookies` longtext COLLATE utf8_unicode_ci,
  `banka_hesaplar` varchar(222) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hata_mesaj` longtext COLLATE utf8_unicode_ci,
  `ad_soyad` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sicil_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vergino` varchar(222) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Tablo döküm verisi `turib_auth`
--

INSERT INTO `turib_auth` (`id`, `turibusername`, `turibparola`, `user_id`, `status`, `smscode`, `cookies`, `banka_hesaplar`, `hata_mesaj`, `ad_soyad`, `sicil_no`, `vergino`) VALUES
(9, '37252934296', '4YOIAB', 11, 1, '177387', '[{\"domain\": \"platform.turib.com.tr\", \"expiry\": 1648714436, \"httpOnly\": true, \"name\": \".ASPXAUTH\", \"path\": \"/\", \"sameSite\": \"Lax\", \"secure\": false, \"value\": \"B384BE71901251A89AADF656694C2903CBBA85BC19D01F32E97B75A537DB63DA1E414180C64D492C6E7828819CA5B7F1AAE3787CE2B23CE8F020FFEFC79AC7B97F33E37C60297F0DEA2E65BE8606202E6A388909110F8AEDC3F0BC5FF2E26498274A20B968B5297FBFC42B63977F92DD\"}, {\"domain\": \"platform.turib.com.tr\", \"httpOnly\": true, \"name\": \"ASP.NET_SessionId\", \"path\": \"/\", \"sameSite\": \"Lax\", \"secure\": false, \"value\": \"mqyhl1o0zonwjskaor50kd2l\"}, {\"domain\": \"platform.turib.com.tr\", \"httpOnly\": true, \"name\": \"__RequestVerificationToken\", \"path\": \"/\", \"secure\": false, \"value\": \"dviN4fDdhblhpLmuoUoP_qojGB6VGwZ249xyspRE-uLaAepZkQ5NRFQ6H-56Ujdq4EAPDrrbMgD_qj85E3_5koor-jG6eUs50Y0B4JwAtms1\"}]', '[\"KTK-97155072\"]', NULL, 'İSMAİL AYDIN', 'MKK Sicil No: 27928183', NULL),
(12, '18332037366', 'VY95W0', 7, 1, '', '[{\"domain\": \"platform.turib.com.tr\", \"expiry\": 1648714637, \"httpOnly\": true, \"name\": \".ASPXAUTH\", \"path\": \"/\", \"sameSite\": \"Lax\", \"secure\": false, \"value\": \"A66F891364C47DE8490CF80A838A58678212FD70BB22FBC680714FB4BA55D9F5A7D5957E37F303E1BE3590EF652400E9D2439E134077278901DDD17620B02D1763554611076E0247041CCACB969D3A8E86D4E5A7D8E3ECDD2E91A53877C616CCC27377B5130ADA9E1198D137098D596C\"}, {\"domain\": \"platform.turib.com.tr\", \"httpOnly\": true, \"name\": \"ASP.NET_SessionId\", \"path\": \"/\", \"sameSite\": \"Lax\", \"secure\": false, \"value\": \"hegzuk0ebgultukf05hoox5k\"}, {\"domain\": \"platform.turib.com.tr\", \"httpOnly\": true, \"name\": \"__RequestVerificationToken\", \"path\": \"/\", \"secure\": false, \"value\": \"6hQAsgXNHArmhZMqwAodfqIBjNfEI_Hh10raSTD2sSh7aiwTVJD1T_8yJUYf3ZAWmHV2al7CKv6iGvTYE4kyOYhaaGD90EwdCFrc9QUwdIg1\"}]', '[\"DNZ-13374742\"]', NULL, 'YUSUF GÜNDOĞDU', 'MKK Sicil No: 34009986', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(222) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(222) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `signal_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rnd` varchar(222) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `type`, `signal_id`, `rnd`) VALUES
(1, 'ozsuakin', '123456', 1, NULL, NULL),
(7, 'yusuf', '123456', 0, 'adbeaf4a-a50b-11ec-9b0c-7a60e06487ac', '690'),
(11, 'aydin', '123456', 0, NULL, '342'),
(12, 'deneme', '123456', 2, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
