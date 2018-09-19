<?php
/**
 * This Software is the property of Data Development and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * http://www.shopmodule.com
 *
 * @copyright (C) D3 Data Development (Inh. Thomas Dartsch)
 * @author    D3 Data Development - Daniel Seifert <ds@shopmodule.com>
 * @link      http://www.oxidmodule.com
 */

$sLangName  = 'Deutsch';
$iLangNr    = 0;
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = array(
    'charset'                                       => 'ISO-8859-15',
    'd3mxgeoip'                                     => "<i class='fa fa-globe'></i> GeoIP",
    'd3mxgeoip_settings'                            => 'Einstellungen',
    'd3tbclgeoip_settings_main'                     => 'Grundeinstellungen',
    'tbclcountry_geoip'                             => 'GeoIP-Kundenumleitungen',

    'D3_GEOIP_TRANSL'                               => 'GeoIP-Kundenumleitungen',

    'D3_GEOIP_SET_DEBUG_DESC'                       => 'Mit aktiviertem Debug-Modus wird im Frontend die aktuelle '.
        'IP-Adresse des Shopbesuchers gezeigt. Beachten Sie, dass Umleitungen, die auf Weiterleitungen basieren, dann '.
        'nicht ausgeführt werden können.',
    'D3_GEOIP_SET_OPTIONS'                          => 'Optionen',
    'D3_GEOIP_SET_OPTIONS_CHANGESHOP'               => 'Mandant wechseln, wenn eingestellt (nur EE)',
    'D3_GEOIP_SET_OPTIONS_CHANGECURR'               => 'Währung wechseln, wenn eingestellt',
    'D3_GEOIP_SET_OPTIONS_CHANGELANG'               => 'Sprache wechseln, wenn eingestellt',
    'D3_GEOIP_SET_OPTIONS_CHANGEURL'                => 'URL wechseln, wenn eingestellt',
    'D3_GEOIP_SET_OPTIONS_NOCOUNTRY'                => 'Shop verwendet die Einstellung dieses Landes, wenn IP nicht '.
        'zuzuordnen ist',
    'D3_GEOIP_SET_IP'                               => 'IP-Einstellungen',
    'D3_GEOIP_SET_IP_TESTIP'                        => 'statt Kunden-IP immer diese IP-Adresse verwenden',
    'D3_GEOIP_SET_IP_TESTCOUNTRY'                   => 'statt Kunden-IP immer eine IP-Adresse dieses Landes verwenden',
    'D3_GEOIP_SET_IP_TESTCOUNTRY_INACTIVE'          => '(inaktiv)',
    'D3_GEOIP_SET_IP_CHECKIP'                       => 'diese IP-Adresse prüfen',
    'D3_GEOIP_SET_IP_CHECKIP_NOTSET'                => 'IP nicht zugewiesen oder Land nicht aktiv',
    'D3_GENERAL_GEOIP_SAVE'                         => 'Speichern',

    'D3_GEOIP_SELSHOP'                              => 'zuständiger Shop',
    'D3_GEOIP_SELLANG'                              => 'vorgewählte Sprache',
    'D3_GEOIP_SELCUR'                               => 'vorgewählte Währung',
    'D3_GEOIP_CUSTSELSHOP'                          => ' - vom Kunden gewählter Shop - ',
    'D3_GEOIP_CUSTSELLANG'                          => ' - vom Kunden gewählte Sprache - ',
    'D3_GEOIP_CUSTSELCUR'                           => ' - vom Kunden gewählte Währung - ',
    'D3_GEOIP_OR'                                   => 'oder',
    'D3_GEOIP_DISABLED'                             => '(deaktiviert)',
    'D3_GEOIP_SELURL'                               => 'zu wechselnde URL',
    'D3_GEOIP_SELURL_DESC'                          => 'Sobald im URL-Feld eine Eingabe vorhanden ist, wird bei '.
        'passenden Kunden versucht, zur hinterlegten URL zu wechseln. Möchten Sie den Mandanten, die Währung und / '.
        'oder die Sprache einstellen, entfernen Sie die URL.<br><br>Aktivieren / deaktivieren Sie den URL-Wechsel '.
        'zusätzlich in den Modul-Grundeinstellungen.',

    'D3_GEOIP_METADATA_TITLE'                       => 'D³ GeoIP Vorauswahl',
    'D3_GEOIP_METADATA_DESC'                        => 'Begrüßen Sie Ihre Kunden in seiner Landessprache.',

    'D3_GEOIP_UPDATE_ITEMINSTALL'                   => 'Die umfangreiche IP-Liste kann leider nicht über die '.
        'automatische Installation eingebunden werden. Bitte installieren Sie diese manuell. '.PHP_EOL.PHP_EOL.
        'Laden Sie bitte die aktuellen CSV-Daten des Anbieters Maxmind. In "setup+doku/importCSVtoMySQL" des Modulpaketes finden Sie weitere Informationen und die erforderlichen Datenbank-Befehle, um die nötigen Daten zu generieren.'.PHP_EOL.PHP_EOL.
        'Alternativ finden Sie die erforderlichen Daten im Installationspaket des Moduls unter "setup+doku/geoip-data_*.sql" oder als handlichere SQL-Dateien unter "setup+doku/geoip_data_parts_*/". '.PHP_EOL.PHP_EOL.
        'Sie benötigen entweder die Komplettdateien oder die Sammlung kleinerer Daten. Führen Sie die SQL-Datei(en) bitte in Ihrer Datenbank aus.',

    'D3_GEOIP_UPDATE_ITEMUPDATE'                    => 'Durch die Datenbankaktualisieren ist es notwendig geworden, die IP-Liste zu aktualisieren. Die umfangreiche IP-Liste kann leider nicht über die '.
        'automatische Installation eingebunden werden. Bitte installieren Sie diese manuell. '.PHP_EOL.PHP_EOL.
        'Laden Sie bitte die aktuellen CSV-Daten des Anbieters Maxmind. In "setup+doku/importCSVtoMySQL" des Modulpaketes finden Sie weitere Informationen und die erforderlichen Datenbank-Befehle, um die nötigen Daten zu generieren.'.PHP_EOL.PHP_EOL.
        'Alternativ finden Sie die erforderlichen Daten im Installationspaket des Moduls unter "setup+doku/geoip-data_*.sql" oder als handlichere SQL-Dateien unter "setup+doku/geoip_data_parts_*/". '.PHP_EOL.PHP_EOL.
        'Sie benötigen entweder die Komplettdateien oder die Sammlung kleinerer Daten. Führen Sie die SQL-Datei(en) bitte in Ihrer Datenbank aus.',
);
