INSERT INTO `d3_cfg_mod` (`OXID`, `OXSHOPID`, `OXMODID`, `OXNAME`, `OXACTIVE`, `OXSERIAL`, `OXINSTALLDATE`, `OXVERSION`, `OXSHOPVERSION`, `OXISMODULELOG`, `OXREQUIREMENTS`, `OXVALUE`, `OXVALUE_1`, `OXVALUE_2`, `OXNEWREVISION`) VALUES
(MD5(RAND()), 1, 'd3_geoip', 'GeoIP', 1, '', NOW(), '2.1.2', 'EE4', 0, '', '', '', '', 26);
INSERT INTO `d3_cfg_mod` (`OXID`, `OXSHOPID`, `OXMODID`, `OXNAME`, `OXACTIVE`, `OXSERIAL`, `OXINSTALLDATE`, `OXVERSION`, `OXSHOPVERSION`, `OXISMODULELOG`, `OXREQUIREMENTS`, `OXVALUE`, `OXVALUE_1`, `OXVALUE_2`, `OXNEWREVISION`) VALUES
(MD5(RAND()), 2, 'd3_geoip', 'GeoIP', 1, '', NOW(), '2.1.2', 'EE4', 0, '', '', '', '', 26);
INSERT INTO `d3_cfg_mod` (`OXID`, `OXSHOPID`, `OXMODID`, `OXNAME`, `OXACTIVE`, `OXSERIAL`, `OXINSTALLDATE`, `OXVERSION`, `OXSHOPVERSION`, `OXISMODULELOG`, `OXREQUIREMENTS`, `OXVALUE`, `OXVALUE_1`, `OXVALUE_2`, `OXNEWREVISION`) VALUES
(MD5(RAND()), 3, 'd3_geoip', 'GeoIP', 1, '', NOW(), '2.1.2', 'EE4', 0, '', '', '', '', 26);

ALTER TABLE `oxcountry` ADD `D3GEOIPSHOP` VARCHAR( 10 ) NOT NULL DEFAULT '-1',
ADD `D3GEOIPLANG` TINYINT( 2 ) NOT NULL DEFAULT '-1',
ADD `D3GEOIPCUR` TINYINT( 2 ) NOT NULL DEFAULT '-1',
ADD `D3GEOIPURL` VARCHAR( 255 ) NOT NULL; 

UPDATE `d3_cfg_mod` SET `OXREVISION` = `OXNEWREVISION` WHERE `OXMODID` = 'd3_geoip';