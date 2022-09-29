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

namespace D3\GeoIp\Application\Component;

use D3\GeoIp\Application\Model\d3geoip;
use D3\ModCfg\Application\Model\Configuration\d3_cfg_mod;
use D3\ModCfg\Application\Model\Exception\d3_cfg_mod_exception;
use D3\ModCfg\Application\Model\Exception\d3ShopCompatibilityAdapterException;
use Doctrine\DBAL\DBALException;
use OxidEsales\Eshop\Application\Component\CurrencyComponent;
use OxidEsales\Eshop\Core\Controller\BaseController;
use OxidEsales\Eshop\Core\Exception\DatabaseConnectionException;
use OxidEsales\Eshop\Core\Exception\DatabaseErrorException;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\Eshop\Core\Registry;

class GeoIpComponent extends BaseController
{
    /**
     * Marking object as component
     *
     * @var bool
     */
    protected $_blIsComponent = true;

    private $_sModId = 'd3_geoip';

    /**
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     * @throws StandardException
     * @throws d3ShopCompatibilityAdapterException
     * @throws d3_cfg_mod_exception
     */
    public function init()
    {
        if (d3_cfg_mod::get($this->_sModId)->isActive()) {
            if (d3_cfg_mod::get($this->_sModId)->hasDebugMode()) {
                /** @var $oGeoIp d3geoip */
                $oGeoIp = oxNew(d3geoip::class);
                echo $oGeoIp->getIP();
            }

            /** @var $oLocation d3geoip */
            $oLocation = oxNew(d3geoip::class);
            $oLocation->setCountryCurrency();
            // moved to oxcmp_lang extension because here it's too late
            // $oLocation->setCountryLanguage();

            if (!isset($oBasket)) {
                $oBasket = Registry::getSession()->getBasket();
            }

            // call component again, if curr is registered before we changed it
            // reason: own component can added after default components only
            if ($oLocation->hasNotSetCurrency($oBasket->getBasketCurrency())) {
                $oActView = Registry::getConfig()->getActiveView();
                $aComponents = $oActView->getComponents();

                /** @var CurrencyComponent $oCurCmp */
                $oCurCmp = $aComponents['oxcmp_cur'];
                $oCurCmp->init();
            }
            // language isn't registered, we don't need an additional check
        }

        parent::init();
    }
}
