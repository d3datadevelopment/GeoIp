<?php
/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id'           => 'd3geoip',
    'title'        => (class_exists('d3utils') ? d3utils::getInstance()->getD3Logo() : 'D&sup3;') . ' GeoIP Vorauswahl',
    'description'  => array(
        'de'    =>  'Begr&uuml;&szlig;en Sie Ihre Kunden in seiner Landessprache.',
        'en'    =>  '',
    ),
    'thumbnail'    => 'picture.png',
    'version'      => '3.0.0.2',
    'author'       => 'D&sup3; Data Development (Inh.: Thomas Dartsch)',
    'email'        => 'support@shopmodule.com',
    'url'          => 'http://www.oxidmodule.com/',
    'extend'      => array(
        'oxcmp_cur'  => 'd3/d3geoip/modules/components/d3_oxcmp_cur_geoip',
        'oxcmp_lang' => 'd3/d3geoip/modules/components/d3_oxcmp_lang_geoip',
    ),
    'files'       => array(
        'd3_cfg_geoipset'   => 'd3/d3geoip/controllers/admin/d3_cfg_geoipset.php',
        'd3_cfg_geoipset_list'   => 'd3/d3geoip/controllers/admin/d3_cfg_geoipset_list.php',
        'd3_cfg_geoipset_main'   => 'd3/d3geoip/controllers/admin/d3_cfg_geoipset_main.php',
        'd3_cfg_geoipset_licence'   => 'd3/d3geoip/controllers/admin/d3_cfg_geoipset_licence.php',
        'd3_country_geoip'   => 'd3/d3geoip/controllers/admin/d3_country_geoip.php',
        'd3geoip'   => 'd3/d3geoip/models/d3geoip.php',
        'd3geoip_update'   => 'd3/d3geoip/models/d3geoip_update.php',
    ),
    'templates'   => array(
        'd3_cfg_geoipset_main.tpl' => 'd3/d3geoip/views/admin/tpl/d3_cfg_geoipset_main.tpl',
        'd3_country_geoip.tpl' => 'd3/d3geoip/views/admin/tpl/d3_country_geoip.tpl',
    ),
    'events'      => array(
        'onActivate' => 'd3install::checkUpdateStart',
    ),
    'blocks'      => array(
    ),
);