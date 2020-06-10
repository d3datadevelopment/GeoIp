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
 * @author    D3 Data Development - Daniel Seifert <support@shopmodule.com>
 * @link      http://www.oxidmodule.com
 */

namespace D3\GeoIp\Application\Controller\Admin;

use D3\GeoIp\Application\Model\d3geoip;
use D3\ModCfg\Application\Controller\Admin\d3_cfg_mod_main;
use D3\ModCfg\Application\Model\Exception\d3_cfg_mod_exception;
use D3\ModCfg\Application\Model\Exception\d3ShopCompatibilityAdapterException;
use Doctrine\DBAL\DBALException;
use OxidEsales\Eshop\Application\Model\CountryList;
use OxidEsales\Eshop\Core\Exception\DatabaseConnectionException;
use OxidEsales\Eshop\Core\Exception\DatabaseErrorException;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\Eshop\Core\Registry;

class d3_cfg_geoipset_main extends d3_cfg_mod_main
{
    protected $_sModId = 'd3_geoip';
    protected $_sThisTemplate = "d3_cfg_geoipset_main.tpl";
    protected $_blHasDebugSwitch = true;
    protected $_sDebugHelpTextIdent = 'D3_GEOIP_SET_DEBUG_DESC';
    protected $_sMenuItemTitle = 'd3mxgeoip';
    protected $_sMenuSubItemTitle = 'd3mxgeoip_settings';
    public $oCountryList;

    /**
     * @param $sIP
     * @return string
     * @throws d3ShopCompatibilityAdapterException
     * @throws d3_cfg_mod_exception
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     * @throws StandardException
     */
    public function getIpCountry($sIP)
    {
        startProfile(__METHOD__);

        /** @var $oD3GeoIP d3geoip */
        $oD3GeoIP = oxNew(d3geoip::class);
        $oCountry = $oD3GeoIP->getUserLocationCountryObject($sIP);

        if ($oCountry->getId()) {
            $sTitle = $oCountry->getFieldData('oxtitle');
        } else {
            $sTitle = Registry::getLang()->translateString('D3_GEOIP_SET_IP_CHECKIP_NOTSET');
        }

        stopProfile(__METHOD__);

        return $sTitle;
    }

    /**
     * @return CountryList
     */
    public function getIPCountryList()
    {
        if ($this->oCountryList) {
            return $this->oCountryList;
        }

        startProfile(__METHOD__);
        /** @var $oGeoIp d3geoip */
        $oGeoIp = oxNew(d3geoip::class);
        /** @var $oCountryList CountryList */
        $this->oCountryList = oxNew(CountryList::class);
        $oListObject = $this->oCountryList->getBaseObject();
        $sFieldList = $oListObject->getSelectFields();
        $sQ = "select (SELECT d3startip FROM ".$oGeoIp->getViewName().
            " WHERE D3ISO = " .$oListObject->getViewName(). ".
            oxisoalpha2 LIMIT 1) as IP,  $sFieldList from " . $oListObject->getViewName();
        $sQ.= " ORDER  BY oxactive DESC, oxtitle";

        $this->oCountryList->selectString($sQ);

        stopProfile(__METHOD__);

        return $this->oCountryList;
    }
}
