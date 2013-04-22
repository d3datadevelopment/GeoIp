<?php

class d3_oxcmp_cur_geoip extends d3_oxcmp_cur_geoip_parent
{
    private $_sModId = 'd3_geoip';

    public function init()
    {
        if (d3_cfg_mod::get($this->_sModId)->getValue('blDebugmodeGlobal'))
        {
            $oGeoIp = oxNew('d3geoip');
            echo $oGeoIp->getIP();
        }

        $oLocation = oxNew('d3geoip');
        //$oLocation->setUserCountry();
        $oLocation->setCountryCurrency();

        return parent::init();
    }

}