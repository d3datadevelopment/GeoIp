# ==== 1.0 PE4 => 1.1 PE4 ====
ALTER TABLE `d3geoip` CHANGE `D3STARTIPNUM` `D3STARTIPNUM` INT( 10 ) UNSIGNED NOT NULL ,
CHANGE `D3ENDIPNUM` `D3ENDIPNUM` INT( 10 ) UNSIGNED NOT NULL 

# ==== 1.1 PE4 => 2.0.0 PE4 ====
INSERT INTO `d3_cfg_mod` (`OXID`, `OXSHOPID`, `OXMODID`, `OXNAME`, `OXACTIVE`, `OXSERIAL`, `OXINSTALLDATE`, `OXVERSION`, `OXSHOPVERSION`, `OXISMODULELOG`, `OXREQUIREMENTS`, `OXVALUE`, `OXVALUE_1`, `OXVALUE_2`, `OXREVISION`, `OXNEWREVISION`) VALUES
(MD5(RAND()), 'oxbaseshop', 'd3_geoip', 'GeoIP', 1, '', NOW(), '2.0.0', 'PE4', 0, '', '', '', '', 14, 14);
ALTER TABLE `oxcountry` ADD `D3GEOIPURL` VARCHAR( 255 ) NOT NULL;

# ==== 2.0.0 PE4 => 2.1.0 PE4 ====
UPDATE `d3_cfg_mod` SET `OXVERSION` = '2.1.0', `OXNEWREVISION` = '18' WHERE OXMODID = 'd3_geoip';
ALTER TABLE `oxcountry` CHANGE `D3GEOIPSHOP` `D3GEOIPSHOP` VARCHAR( 10 ) NOT NULL DEFAULT 'oxbaseshop';
UPDATE `oxcountry` SET `D3GEOIPSHOP` = 'oxbaseshop' WHERE `D3GEOIPSHOP` = '-1';
UPDATE `d3_cfg_mod` SET `OXREVISION` = `OXNEWREVISION` WHERE OXMODID = 'd3_geoip';

# ==== 2.1.0 PE4 => 2.1.1 PE4 ====
UPDATE `d3_cfg_mod` SET `OXVERSION` = '2.1.1', `OXNEWREVISION` = '24' WHERE OXMODID = 'd3_geoip';
ALTER TABLE `oxcountry` CHANGE `D3GEOIPSHOP` `D3GEOIPSHOP` VARCHAR( 10 ) NOT NULL DEFAULT 'oxbaseshop';
UPDATE `oxcountry` SET `D3GEOIPSHOP` = 'oxbaseshop' WHERE `D3GEOIPSHOP` = '-1';
UPDATE `d3_cfg_mod` SET `OXREVISION` = `OXNEWREVISION` WHERE OXMODID = 'd3_geoip';