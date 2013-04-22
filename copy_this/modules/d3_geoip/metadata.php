<?php
/**
 * Module information
 */
$aModule = array(
    'id'           => 'd3_geoip',
    'title'        => oxLang::getInstance()->translateString('D3_GEOIP_METADATA_TITLE'),
    'description'  => oxLang::getInstance()->translateString('D3_GEOIP_METADATA_DESC'),
    'thumbnail'    => 'picture.png',
    'version'      => '2.1.1',
    'author'       => oxLang::getInstance()->translateString('D3_MOD_LIB_METADATA_AUTHOR'),
    'email'        => 'support@shopmodule.com',
    'url'          => 'http://www.oxidmodule.com/',
    'extend'      => array(
        'oxcmp_cur' => 'd3_geoip/views/d3_oxcmp_cur_geoip',
        'oxcmp_lang' => 'd3_geoip/views/d3_oxcmp_lang_geoip',
    )
);