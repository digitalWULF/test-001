CREATE DATABASE `mediatech_test` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

CREATE TABLE `short_urls` (
  `long_url` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `short_url` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` int(11) NOT NULL,
  PRIMARY KEY (`long_url`),
  KEY `short_url_idx` (`short_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;