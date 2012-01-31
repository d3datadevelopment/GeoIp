-- 
-- Tabellenstruktur für Tabelle `d3geoip`
-- 

CREATE TABLE `d3geoip` (
  `D3STARTIP` char(15) NOT NULL,
  `D3ENDIP` char(15) NOT NULL,
  `D3STARTIPNUM` int(10) unsigned NOT NULL,
  `D3ENDIPNUM` int(10) unsigned NOT NULL,
  `D3ISO` char(2) NOT NULL,
  `D3COUNTRYNAME` varchar(50) NOT NULL
) ENGINE=MyISAM COMMENT='GeoIP';
