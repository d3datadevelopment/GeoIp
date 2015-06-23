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
class d3_oxcmp_cur_geoip extends d3_oxcmp_cur_geoip_parent
{
    private $_sModId = 'd3_geoip';

    /**
     * @return null
     */
    public function init()
    {
        if (d3_cfg_mod::get($this->_sModId)->isActive()) {
            if (d3_cfg_mod::get($this->_sModId)->hasDebugMode()) {
                /** @var $oGeoIp d3geoip */
                $oGeoIp = oxNew('d3geoip');
                echo $oGeoIp->getIP();
            }

            /** @var $oLocation d3geoip */
            $oLocation = oxNew('d3geoip');
            $oLocation->setCountryCurrency();
        }

        return parent::init();
    }
}
