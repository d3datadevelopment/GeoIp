-- phpMyAdmin SQL Dump
-- version 2.8.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 30. Oktober 2009 um 11:02
-- Server Version: 5.0.51
-- PHP-Version: 5.2.5
-- 
-- 
-- --------------------------------------------------------
-- 
-- Tabellenstruktur für Tabelle `d3geoip`
-- 

CREATE TABLE `d3geoip` (
  `D3STARTIP` char(15) collate latin1_general_ci NOT NULL,
  `D3ENDIP` char(15) collate latin1_general_ci NOT NULL,
  `D3STARTIPNUM` int(10) NOT NULL,
  `D3ENDIPNUM` int(10) NOT NULL,
  `D3ISO` char(2) collate latin1_general_ci NOT NULL,
  `D3COUNTRYNAME` varchar(50) collate latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='GeoIP';
