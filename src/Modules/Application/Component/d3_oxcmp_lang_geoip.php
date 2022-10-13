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

namespace D3\GeoIp\Modules\Application\Component;

use D3\GeoIp\Application\Model\d3geoip;
use D3\ModCfg\Application\Model\Configuration\d3_cfg_mod;
use D3\ModCfg\Application\Model\Exception\d3_cfg_mod_exception;
use D3\ModCfg\Application\Model\Exception\d3ShopCompatibilityAdapterException;
use Doctrine\DBAL\DBALException;
use OxidEsales\Eshop\Core\Exception\DatabaseConnectionException;
use OxidEsales\Eshop\Core\Exception\DatabaseErrorException;
use OxidEsales\Eshop\Core\Exception\StandardException;

class d3_oxcmp_lang_geoip extends d3_oxcmp_lang_geoip_parent
{
    private $_sModId = 'd3_geoip';

    /**
     * @throws d3ShopCompatibilityAdapterException
     * @throws d3_cfg_mod_exception
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     * @throws StandardException
     */
    public function init()
    {
        if (d3_cfg_mod::get($this->_sModId)->isActive()) {
            /** @var $oLocation d3geoip */
            $oLocation = oxNew(d3geoip::class);
            d3_cfg_mod::get($this->_sModId)->d3getLog()->info(__CLASS__, __FUNCTION__, __LINE__, 'start perform language switch');
            $oLocation->setCountryLanguage();
            d3_cfg_mod::get($this->_sModId)->d3getLog()->info(__CLASS__, __FUNCTION__, __LINE__, 'end perform language switch');
        }

        parent::init();
    }
}