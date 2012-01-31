<?php

// @copyright © D³ Data Development
//
// This Software is the property of Data Development and is protected
// by copyright law - it is NOT Freeware.
//
// Any unauthorized use of this software without a valid license key
// is a violation of the license agreement and will be prosecuted by
// civil and criminal law.
//
// http://www.shopmodule.com

// AUTOR Daniel Seifert <ds@shopmodule.com>

class d3_cfg_geoipset_main extends d3_cfg_mod_main
{
    protected $_sModId = 'd3_geoip';

    protected $_sThisTemplate = "d3_cfg_geoipset_main.tpl";
    
    public function getIpCountry($sIP)
    {
        $oD3GeoIP = oxNew('d3geoip');
        $oCountry = $oD3GeoIP->getUserLocationCountryObject($sIP);

        if ($oCountry->getId())
            $sTitle = $oCountry->getFieldData('oxtitle');
        else
            $sTitle = oxLang::getInstance()->translateString('D3_GEOIP_SET_IP_CHECKIP_NOTSET');

        return $sTitle;
    }
}