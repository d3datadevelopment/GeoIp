<?php
/**
 * Metadata version
 */
$sMetadataVersion = '2.0';

use D3\GeoIp\Application\Component\GeoIpComponent;
use D3\GeoIp\Application\Controller\Admin\d3_cfg_geoipset;
use D3\GeoIp\Application\Controller\Admin\d3_cfg_geoipset_licence;
use D3\GeoIp\Application\Controller\Admin\d3_cfg_geoipset_list;
use D3\GeoIp\Application\Controller\Admin\d3_cfg_geoipset_main;
use D3\GeoIp\Application\Controller\Admin\d3_country_geoip;
use D3\GeoIp\Application\Model\d3geoip;
use D3\GeoIp\Modules\Application\Component\d3_oxcmp_lang_geoip;
use D3\GeoIp\Modules\Core\d3_oxshopcontrol_geoip;
use D3\GeoIp\Setup as ModuleSetup;
use D3\GeoIp\Setup\d3geoip_update;
use OxidEsales\Eshop\Application\Component\LanguageComponent;
use OxidEsales\Eshop\Core\ShopControl;

$sD3Logo = (class_exists(d3\modcfg\Application\Model\d3utils::class) ? d3\modcfg\Application\Model\d3utils::getInstance()->getD3Logo() : 'D&sup3;');

/**
 * Module information
 */
$aModule = array(
    'id'           => 'd3geoip',
    'title'        => $sD3Logo . ' GeoIP Vorauswahl',
    'description'  => array(
        'de'    =>  'Begr&uuml;&szlig;en Sie Ihre Kunden in seiner Landessprache.',
        'en'    =>  '',
    ),
    'thumbnail'    => 'picture.png',
    'version'      => '4.0.0.0',
    'author'       => 'D&sup3; Data Development (Inh.: Thomas Dartsch)',
    'email'        => 'support@shopmodule.com',
    'url'          => 'http://www.oxidmodule.com/',
    'extend'      => array(
        LanguageComponent::class => d3_oxcmp_lang_geoip::class,
        ShopControl::class => d3_oxshopcontrol_geoip::class
    ),
    'controllers'       => array(
        'GeoIpComponent'            => GeoIpComponent::class,
        'd3_cfg_geoipset'           => d3_cfg_geoipset::class,
        'd3_cfg_geoipset_list'      => d3_cfg_geoipset_list::class,
        'd3_cfg_geoipset_main'      => d3_cfg_geoipset_main::class,
        'd3_cfg_geoipset_licence'   => d3_cfg_geoipset_licence::class,
        'd3_country_geoip'          => d3_country_geoip::class,
        'd3geoip'                   => d3geoip::class,
        'd3geoip_update'            => d3geoip_update::class,
    ),
    'templates'   => array(
        'd3_cfg_geoipset_main.tpl'  => 'd3/geoip/Application/views/admin/tpl/d3_cfg_geoipset_main.tpl',
        'd3_country_geoip.tpl'      => 'd3/geoip/Application/views/admin/tpl/d3_country_geoip.tpl',
    ),
    'events'      => array(
        'onActivate' => '\D3\GeoIp\Setup\Events::onActivate',
    ),
    'blocks'      => array(
    ),
    'd3SetupClasses' => array(
        d3geoip_update::class,
    ),
    'd3FileRegister'    => array(
        'd3/geoip/IntelliSenseHelper.php',
        'd3/geoip/metadata.php',
        'd3/geoip/Application/views/admin/de/d3_geoip_lang.php',
        'd3/geoip/Setup/Events.php'
    ),
);