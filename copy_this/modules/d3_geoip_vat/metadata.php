<?php
/**
 * Module information
 */
$aModule = array(
    'id'           => 'd3_geoip_vat',
    'title'        => 'D� GeoIP Steuerselektion',
    'description'  => '',
    'thumbnail'    => 'picture.png',
    'version'      => 'indiv.',
    'author'       => 'D� Data Development',
    'extend'      => array(
        'oxvatselector' => 'd3_geoip_vat/core/d3_oxvatselector_geoip',
    )
);