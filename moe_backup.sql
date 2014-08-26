-- phpMyAdmin SQL Dump
-- version 4.0.7
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1:3307
-- Erstellungszeit: 21. Jul 2014 um 15:18
-- Server Version: 5.5.38-0
-- PHP-Version: 5.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `usrdb_ce1cxtbw`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `moe_backup`
--

CREATE TABLE IF NOT EXISTS `moe_backup` (
  `OXID` char(32) NOT NULL,
  `OCBTITLE` varchar(255) NOT NULL,
  `OCBTITLE_1` varchar(255) NOT NULL,
  `MOEFILEPREFIX` varchar(255) NOT NULL,
  `OCBDATETIME` datetime NOT NULL,
  `MOEBACKUPDIR` varchar(400) NOT NULL,
  `MOELOGGINGDIR` varchar(400) NOT NULL,
  `MOEINCLUDE` varchar(400) NOT NULL,
  `MOEEXCLUDE` varchar(400) NOT NULL,
  `MOEDESCRIPTION` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `moe_backup`
 ADD PRIMARY KEY (`OXID`);


--
-- Daten für Tabelle `moe_backup`
--

INSERT INTO `moe_backup` (`OXID`, `OCBTITLE`, `OCBTITLE_1`, `MOEFILEPREFIX`, `OCBDATETIME`, `MOEBACKUPDIR`, `MOELOGGINGDIR`, `MOEINCLUDE`, `MOEEXCLUDE`, `MOEDESCRIPTION`) VALUES('b029da7d9163b9d97f569dfc1393f17f', 'Module Sichern', '', 'Modules', '0000-00-00 00:00:00', 'data/backup/modules/', 'data/backup/modules/', 'modules/', 'modules/moe/moe_backup/.git', 'gggvvvv');
INSERT INTO `moe_backup` (`OXID`, `OCBTITLE`, `OCBTITLE_1`, `MOEFILEPREFIX`, `OCBDATETIME`, `MOEBACKUPDIR`, `MOELOGGINGDIR`, `MOEINCLUDE`, `MOEEXCLUDE`, `MOEDESCRIPTION`) VALUES('2d975b6493b0719c9d6a145393f8e89a', 'Komplettsicherung', '', 'Backup_all', '0000-00-00 00:00:00', 'data/backup/shop/', 'data/backup/shop/', '', 'tmp/*;data/backup/*;data/import/pictures/*;statistik;dumper;cgi-bin;FTP;phpMyAdmin*;export/TRUMAN;out/pictures/*;move;Move', '');
INSERT INTO `moe_backup` (`OXID`, `OCBTITLE`, `OCBTITLE_1`, `MOEFILEPREFIX`, `OCBDATETIME`, `MOEBACKUPDIR`, `MOELOGGINGDIR`, `MOEINCLUDE`, `MOEEXCLUDE`, `MOEDESCRIPTION`) VALUES('0e45279d2ec42b72462434fccaf60f2b', 'Modul moe_backup sichern', '', 'moe_backup', '0000-00-00 00:00:00', 'data/backup/modules/', 'data/backup/modules/', 'modules/moe/moe_backup/', 'modules/moe/moe_backup/.git', '');
INSERT INTO `moe_backup` (`OXID`, `OCBTITLE`, `OCBTITLE_1`, `MOEFILEPREFIX`, `OCBDATETIME`, `MOEBACKUPDIR`, `MOELOGGINGDIR`, `MOEINCLUDE`, `MOEEXCLUDE`, `MOEDESCRIPTION`) VALUES('4df525a6d0102bbfe9a52d9cffd0ef15', 'Modul azimport sichern', '', 'azimport', '0000-00-00 00:00:00', 'data/backup/modules/', 'data/backup/modules/', 'modules/anzido/azimport/', '', '');
