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
class d3_cfg_geoipset_main extends d3_cfg_mod_main
{
    protected $_sModId = 'd3_geoip';
    protected $_sThisTemplate = "d3_cfg_geoipset_main.tpl";
    protected $_blHasDebugSwitch = true;
    protected $_sDebugHelpTextIdent = 'D3_GEOIP_SET_DEBUG_DESC';

    /**
     * @param $sIP
     * @return string
     */
    public function getIpCountry($sIP)
    {
        startProfile(__METHOD__);

        /** @var $oD3GeoIP d3geoip */
        $oD3GeoIP = oxNew('d3geoip');
        $oCountry = $oD3GeoIP->getUserLocationCountryObject($sIP);

        if ($oCountry->getId()) {
            $sTitle = $oCountry->getFieldData('oxtitle');
        } else {
            $sTitle = oxRegistry::getLang()->translateString('D3_GEOIP_SET_IP_CHECKIP_NOTSET');
        }

        stopProfile(__METHOD__);

        return $sTitle;
    }

    /**
     * @return oxcountrylist
     */
    public function getCountryList()
    {
        startProfile(__METHOD__);

        /** @var $oCountryList oxcountrylist */
        $oCountryList = oxNew('oxcountrylist');
        $oListObject = $oCountryList->getBaseObject();
        $sFieldList = $oListObject->getSelectFields();
        $sQ = "select $sFieldList from " . $oListObject->getViewName();
        $oCountryList->selectString($sQ);

        stopProfile(__METHOD__);

        return $oCountryList;
    }

    /**
     * @return oxcountrylist
     */
    public function getIPCountryList()
    {
        startProfile(__METHOD__);

        /** @var $oGeoIp  d3geoip */
        $oGeoIp = oxNew('d3geoip');
        /** @var $oCountryList oxcountrylist */
        $oCountryList = oxNew('oxcountrylist');
        $oListObject = $oCountryList->getBaseObject();
        $sFieldList = $oListObject->getSelectFields();
        $sQ = "select (SELECT d3startip FROM ".$oGeoIp->getViewName().
            " WHERE D3ISO = " .$oListObject->getViewName(). ".
            oxisoalpha2 LIMIT 1) as IP,  $sFieldList from " . $oListObject->getViewName();

        $oCountryList->selectString($sQ);

        stopProfile(__METHOD__);

        return $oCountryList;
    }
}
