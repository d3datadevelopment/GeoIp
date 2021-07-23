<?php
/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

use D3\GeoIp\Application\Component\GeoIpComponent;
use D3\GeoIp\Application\Controller\Admin\d3_cfg_geoipset;
use D3\GeoIp\Application\Controller\Admin\d3_cfg_geoipset_licence;
use D3\GeoIp\Application\Controller\Admin\d3_cfg_geoipset_list;
use D3\GeoIp\Application\Controller\Admin\d3_cfg_geoipset_main;
use D3\GeoIp\Application\Controller\Admin\d3_country_geoip;
use D3\GeoIp\Application\Model\d3geoip;
use D3\GeoIp\Modules\Application\Component\d3_oxcmp_lang_geoip;
use D3\GeoIp\Modules\Core\d3_oxshopcontrol_geoip;
use D3\GeoIp\Setup\d3geoip_update;
use OxidEsales\Eshop\Application\Component\LanguageComponent;
use OxidEsales\Eshop\Core\ShopControl;

$sD3Logo = '<img src="https://logos.oxidmodule.com/d3logo.svg" alt="(D3)" style="height:1em;width:1em"> ';

/**
 * Module information
 */
$aModule = [
    'id'           => 'd3geoip',
    'title'        => $sD3Logo . ' GeoIP Vorauswahl',
    'description'  => [
        'de'    =>  'Begr&uuml;&szlig;en Sie Ihre Kunden in seiner Landessprache.',
        'en'    =>  '',
    ],
    'thumbnail'    => 'picture.png',
    'version'      => '4.0.3.0',
    'author'       => 'D&sup3; Data Development (Inh.: Thomas Dartsch)',
    'email'        => 'support@shopmodule.com',
    'url'          => 'http://www.oxidmodule.com/',
    'extend'      => [
        LanguageComponent::class => d3_oxcmp_lang_geoip::class,
        ShopControl::class => d3_oxshopcontrol_geoip::class
    ],
    'controllers'       => [
        'GeoIpComponent'            => GeoIpComponent::class,
        'd3_cfg_geoipset'           => d3_cfg_geoipset::class,
        'd3_cfg_geoipset_list'      => d3_cfg_geoipset_list::class,
        'd3_cfg_geoipset_main'      => d3_cfg_geoipset_main::class,
        'd3_cfg_geoipset_licence'   => d3_cfg_geoipset_licence::class,
        'd3_country_geoip'          => d3_country_geoip::class,
        'd3geoip'                   => d3geoip::class,
        'd3geoip_update'            => d3geoip_update::class,
    ],
    'templates'   => [
        'd3_cfg_geoipset_main.tpl'  => 'd3/geoip/Application/views/admin/tpl/d3_cfg_geoipset_main.tpl',
        'd3_country_geoip.tpl'      => 'd3/geoip/Application/views/admin/tpl/d3_country_geoip.tpl',
    ],
    'events'      => [
        'onActivate' => '\D3\GeoIp\Setup\Events::onActivate',
    ]
];