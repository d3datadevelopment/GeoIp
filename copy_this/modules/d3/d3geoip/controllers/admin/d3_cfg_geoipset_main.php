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

    public function getCountryList()
    {
        $oCountryList = oxNew('oxcountrylist');
        if ($oCountryList->getBaseObject()->isMultilang())
        {
            $oCountryList->getBaseObject()->setLanguage(oxLang::getInstance()->getTplLanguage());
        }
        $oListObject = $oCountryList->getBaseObject();
        $sFieldList = $oListObject->getSelectFields();
        $sQ = "select $sFieldList from " . $oListObject->getViewName();
        $oCountryList->selectString($sQ);

        return $oCountryList;
    }

    public function getIPCountryList()
    {
        $oGeoIp = oxNew('d3geoip');
        $oCountryList = oxNew('oxcountrylist');
        if ($oCountryList->getBaseObject()->isMultilang())
        {
            $oCountryList->getBaseObject()->setLanguage(oxLang::getInstance()->getTplLanguage());
        }
        $oListObject = $oCountryList->getBaseObject();
        $sFieldList = $oListObject->getSelectFields();
        $sQ = "select (SELECT d3startip FROM ".$oGeoIp->getViewName()." WHERE D3ISO = " .$oListObject->getViewName(). ".oxisoalpha2 LIMIT 1) as IP,  $sFieldList from " . $oListObject->getViewName();

        $oCountryList->selectString($sQ);

        return $oCountryList;
    }
}