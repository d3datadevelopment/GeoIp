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

namespace D3\GeoIp\Modules\Core;

use OxidEsales\Eshop\Core\Registry;

class d3_oxshopcontrol_geoip extends d3_oxshopcontrol_geoip_parent
{
    /**
     * @param null $sClass
     * @param null $sFunction
     * @param null $aParams
     * @param null $aViewsChain
     */
    public function start ($sClass = null, $sFunction = null, $aParams = null, $aViewsChain = null)
    {
        $this->_d3AddGeoIpComponent();

        parent::start($sClass, $sFunction, $aParams, $aViewsChain);
    }
    
    /**
     * check, if developer mode has to be enabled
     */
    protected function _d3AddGeoIpComponent()
    {
        startProfile(__METHOD__);

        $aUserComponentNames = Registry::getConfig()->getConfigParam('aUserComponentNames');
        $sGeoIpCmpName = 'GeoIpComponent';
        $sGeoIpCmpName = \D3\GeoIp\Application\Component\GeoIpComponent::class;
        $blDontUseCache = 1;

        if (false == is_array($aUserComponentNames)) {
            $aUserComponentNames = array();
        }

        if (false == in_array($sGeoIpCmpName, array_keys($aUserComponentNames))) {
            $aUserComponentNames[$sGeoIpCmpName] = $blDontUseCache;
            Registry::getConfig()->setConfigParam('aUserComponentNames', $aUserComponentNames);
        }

        stopProfile(__METHOD__);
    }
}
