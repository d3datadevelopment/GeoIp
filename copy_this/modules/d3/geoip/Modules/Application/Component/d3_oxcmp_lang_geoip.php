<?php

namespace D3\GeoIp\Modules\Application\Component;

use D3\GeoIp\Application\Model\d3geoip;
use D3\ModCfg\Application\Model\Configuration\d3_cfg_mod;

class d3_oxcmp_lang_geoip extends d3_oxcmp_lang_geoip_parent
{
    private $_sModId = 'd3_geoip';

    /**
     * @throws \D3\ModCfg\Application\Model\Exception\d3ShopCompatibilityAdapterException
     * @throws \D3\ModCfg\Application\Model\Exception\d3_cfg_mod_exception
     * @throws \Doctrine\DBAL\DBALException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     * @throws \OxidEsales\Eshop\Core\Exception\StandardException
     */
    public function init()
    {
        if (d3_cfg_mod::get($this->_sModId)->isActive()) {
            /** @var $oLocation d3geoip */
            $oLocation = oxNew(d3geoip::class);
            $oLocation->setCountryLanguage();
        }

        parent::init();
    }
}