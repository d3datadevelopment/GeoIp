/* download files from https://dev.maxmind.com/geoip/geoip2/geolite2/ */

/* create a tmp table for imported IPv4 data */

CREATE TABLE `IPv4` (
	`network` VARCHAR(50) NOT NULL,
	`geoname_id` VARCHAR(7) NOT NULL,
	`registered_country_geoname_id` VARCHAR(7) NOT NULL,
	`represented_country_geoname_id` VARCHAR(7) NOT NULL,
	`is_anonymous_proxy` TINYINT(1) NOT NULL,
	`is_satellite_provider` TINYINT(1) NOT NULL,
	PRIMARY KEY (`network`),
	INDEX `key2` (`geoname_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/* import all IPv4 address from CSV file */

LOAD DATA LOW_PRIORITY LOCAL INFILE 'GeoLite2-Country-Blocks-IPv4.csv' INTO TABLE `IPv4` CHARACTER SET latin1 FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 LINES (`network`, `geoname_id`, `registered_country_geoname_id`, `represented_country_geoname_id`, `is_anonymous_proxy`, `is_satellite_provider`);

/* create a tmp table for imported IPv6 data */

CREATE TABLE `IPv6` (
	`network` VARCHAR(50) NOT NULL,
	`geoname_id` VARCHAR(7) NULL DEFAULT NULL,
	`registered_country_geoname_id` VARCHAR(7) NOT NULL,
	`represented_country_geoname_id` VARCHAR(7) NOT NULL,
	`is_anonymous_proxy` TINYINT(1) NOT NULL,
	`is_satellite_provider` TINYINT(1) NOT NULL,
	PRIMARY KEY (`network`),
	INDEX `key2` (`geoname_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/* import all IPv4 address from CSV file */

LOAD DATA LOW_PRIORITY LOCAL INFILE 'GeoLite2-Country-Blocks-IPv6.csv' INTO TABLE `IPv4` FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 LINES (`network`, `geoname_id`, `registered_country_geoname_id`, `represented_country_geoname_id`, `is_anonymous_proxy`, `is_satellite_provider`);

/* create a tmp table for imported county data */

CREATE TABLE `Countries` (
	`geoname_id` CHAR(7) NOT NULL,
	`locale_code` CHAR(2) NOT NULL,
	`continent_code` CHAR(2) NOT NULL,
	`continent_name` VARCHAR(25) NOT NULL,
	`country_iso_code` CHAR(2) NOT NULL,
	`country_name` VARCHAR(50) NOT NULL
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/* import all countries from CSV file */

LOAD DATA LOW_PRIORITY LOCAL INFILE 'GeoLite2-Country-Locations-en.csv' INTO TABLE `GeoIP`.`Countries` CHARACTER SET utf8 FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 LINES (`geoname_id`, `locale_code`, `continent_code`, `continent_name`, `country_iso_code`, `country_name`);

/* copy all IPv4 data + calculated start and end IP to d3geoip table */

INSERT INTO d3geoip (D3IP, D3STARTIP, D3ENDIP, D3STARTIPBIN, D3ENDIPBIN, D3ISO, D3COUNTRYNAME, D3CONTINENTCODE)
    SELECT
      network as D3IP,
      INET_NTOA(INET_ATON( SUBSTRING_INDEX(network, '/', 1)) 
       & 0xffffffff ^ ((0x1 << ( 32 - SUBSTRING_INDEX(network, '/', -1))  ) -1 )) D3STARTIP,
      INET_NTOA(INET_ATON( SUBSTRING_INDEX(network, '/', 1)) 
       | ((0x100000000 >> SUBSTRING_INDEX(network, '/', -1) ) -1 )) D3ENDIP,
       0 as D3STARTIPBIN,
       0 as D3ENDIPBIN,
       country_iso_code as D3ISO,
       country_name as D3COUNTRYNAME,
       continent_code as D3CONTINENTCODE
    FROM (
        SELECT 
            IPv4.network, 
            Countries.country_iso_code, 
            Countries.country_name, 
            Countries.continent_code 
        FROM 
            IPv4
        LEFT JOIN 
            Countries 
            ON 
                IPv4.geoname_id = Countries.geoname_id
    ) as src
    
/* copy all IPv6 data to d3geoip table */

INSERT INTO d3geoip (D3IP, D3STARTIP, D3ENDIP, D3STARTIPBIN, D3ENDIPBIN, D3ISO, D3COUNTRYNAME, D3CONTINENTCODE)
    SELECT
      network as D3IP,
       0 as D3STARTIP,
       0 as D3ENDIP,
       0 as D3STARTIPBIN,
       0 as D3ENDIPBIN,
       country_iso_code as D3ISO,
       country_name as D3COUNTRYNAME,
       continent_code as D3CONTINENTCODE
    FROM (
        SELECT 
            IPv6.network, 
            Countries.country_iso_code, 
            Countries.country_name, 
            Countries.continent_code  
        FROM 
            IPv6
        LEFT JOIN 
            Countries 
            ON 
                IPv6.geoname_id = Countries.geoname_id
    ) as src
    
/* create a getFirstIp from IPv6 CIDR method */
    
DELIMITER //
CREATE FUNCTION getFirstIp (`Ip` VARCHAR(46), `Mask` INT(2) UNSIGNED) RETURNS varchar(39)
BEGIN
    DECLARE First VARCHAR (42) DEFAULT '';

    SET First = INET6_NTOA(UNHEX(RPAD(SUBSTR(HEX(INET6_ATON(Ip)), 1, Mask / 4), 32, 0)));

    RETURN First;
END
//

/* create a getLastIp from IPv6 CIDR method */

DELIMITER //
CREATE FUNCTION getLastIp (`Ip` VARCHAR(46), `Mask` INT(2) UNSIGNED) RETURNS varchar(39)
BEGIN
    DECLARE IpNumber VARBINARY(16);
    DECLARE Last VARCHAR(39) DEFAULT '';
    DECLARE FlexBits, Counter, Deci, NewByte INT UNSIGNED;
    DECLARE HexIp VARCHAR(32);

    SET IpNumber = INET6_ATON(Ip);
    SET HexIp    = HEX(IpNumber);
    SET FlexBits = 128 - Mask;
    SET Counter  = 32;

    WHILE (FlexBits > 0) DO
        SET Deci    = CONV(SUBSTR(HexIp, Counter, 1), 16, 10);
        SET NewByte = Deci | (POW(2, LEAST(4, FlexBits)) - 1);
        SET Last    = CONCAT(CONV(NewByte, 10, 16), Last);

        IF FlexBits >= 4 THEN SET FlexBits = FlexBits - 4;
        ELSE SET FlexBits = 0;
        END IF;

        SET Counter  = Counter - 1;
    END WHILE;

    SET Last = CONCAT(SUBSTR(HexIp, 1, Counter), Last);

    RETURN INET6_NTOA(UNHEX(Last));
END
//

/* calculate startIp for IPv6 */

UPDATE d3geoip 
SET 
    D3STARTIP = getFirstIp(
        SUBSTRING_INDEX(D3IP, '/', 1),
        SUBSTRING_INDEX(D3IP, '/', -1)
    ) 
WHERE 
    D3IP LIKE "%:%" 
    AND D3STARTIP = 0;
    
/* calculate endIp for IPv6 */
    
UPDATE d3geoip 
SET 
    D3ENDIP = getLastIp(
        SUBSTRING_INDEX(D3IP, '/', 1),
        SUBSTRING_INDEX(D3IP, '/', -1)
    ) 
WHERE 
    D3IP LIKE "%:%" 
    AND D3ENDIP = 0;

/* calculate fixed length binary startIp (v4 + v6) */

UPDATE 
    d3geoip 
SET 
    D3STARTIPBIN = LPAD(
        IF (
            IS_IPV4(D3STARTIP), 
            INET_ATON(D3STARTIP), 
            INET6_ATON(D3STARTIP)
        ),
        16,
        0
    )
WHERE D3STARTIPBIN = 0;

/* calculate fixed length binary endIp (v4 + v6) */

UPDATE 
    d3geoip 
SET 
    D3ENDIPBIN = LPAD(
        IF (
            IS_IPV4(D3ENDIP), 
            INET_ATON(D3ENDIP), 
            INET6_ATON(D3ENDIP)
        ),
        16,
        0
    )
WHERE D3ENDIPBIN = 0;

/* remove created functions and tmp tables*/

DROP function getFirstIp;
DROP function getLastIp;
DROP TABLE `IPv4`;
DROP TABLE `IPv6`;
DROP TABLE `Countries`;
