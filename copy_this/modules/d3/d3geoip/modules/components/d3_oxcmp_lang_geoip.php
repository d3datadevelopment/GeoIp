<?php

class d3_oxcmp_lang_geoip extends d3_oxcmp_lang_geoip_parent
{
    private $_sModId = 'd3_geoip';

    public function init()
    {
        if (d3_cfg_mod::get($this->_sModId)->isActive()) {
            /** @var $oLocation d3geoip */
            $oLocation = oxNew('d3geoip');
            $oLocation->setCountryLanguage();
        }

        parent::init();
    }
}