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

class d3GeoIP extends oxI18n
{
    protected $_sClassName = 'd3geoip';
    private $_sModId = 'd3_geoip';
    public $oCountry;
    public $oD3Log;

    /**
     * Class constructor, initiates parent constructor (parent::oxI18n()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('d3geoip');
    }

    /**
     * get oxcountry object by given IP address (optional)
     *
     * @param string $sIP optional
     * @return oxcountry
     */
    public function getUserLocationCountryObject($sIP = null)
    {
        if (!$this->oCountry)
        {
            if (!$sIP)
            {
                $sIP = $this->getIP();
            }

            $iIPNum = $this->_getNumIp($sIP);
            $sISOAlpha = $this->LoadByIPNum($iIPNum);

            if (!$sISOAlpha)
            {
                $this->_getLog()->log('error', __CLASS__, __FUNCTION__, __LINE__, 'get ISO by IP failed', $sIP);
                $this->oCountry = $this->getCountryFallBackObject();
            }
            else
            {
                $this->_getLog()->log('info', __CLASS__, __FUNCTION__, __LINE__, 'get ISO by IP', $sIP." => ".$sISOAlpha);
                $this->oCountry = $this->getCountryObject($sISOAlpha);
            }
        }

        return $this->oCountry;
    }

    /**
     * get IP address from client or set test IP address
     *
     * @return string
     */
    public function getIP()
    {
        if ($this->_getModConfig()->getValue('blUseTestIp') && $this->_getModConfig()->getValue('sTestIp'))
        {
            return $this->_getModConfig()->getValue('sTestIp');
        }
        elseif ($this->_getModConfig()->getValue('blUseTestCountry') && $this->_getModConfig()->getValue('sTestCountryIp'))
        {
            return $this->_getModConfig()->getValue('sTestCountryIp');
        }
        else
        {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * get IP number by IP address
     *
     * @param string $sIP IP address
     * @return int IP number
     */
    protected function _getNumIp($sIP)
    {
        $aIP = explode('.',$sIP);
        $iIP = ($aIP[0] * 16777216) + ($aIP[1] * 65536) + ($aIP[2] * 256) + ($aIP[3] * 1);
        return $iIP;
    }

    /**
     * get ISO alpha 2 ID by IP number
     *
     * @param int $iIPNum IP number
     * @return string
     */
    public function LoadByIPNum($iIPNum)
    {
        $sSelect = "SELECT d3iso FROM ".$this->_sClassName." WHERE d3startipnum <= '$iIPNum' AND d3endipnum >= '$iIPNum'";
        return oxDb::getDb()->getOne($sSelect);
    }

    /**
     * get oxcountry object by ISO alpha 2 ID
     *
     * @param string $sISOAlpha
     * @return oxcountry
     */
    public function getCountryObject($sISOAlpha)
    {
        $oCountry = oxNew('oxcountry');
        $sSelect = "SELECT oxid FROM ".$oCountry->getViewName()." WHERE OXISOALPHA2 = '".$sISOAlpha."' AND OXACTIVE = '1'";

        $oCountry->load(oxDb::getDb()->getOne($sSelect));

        return $oCountry;
    }

    /**
     * get oxcountry object for fallback, if set
     *
     * @return object
     */
    public function getCountryFallBackObject()
    {
        $oCountry = oxNew('oxcountry');

        if ($this->_getModConfig()->getValue('blUseFallback') && $this->_getModConfig()->getValue('sFallbackCountryId'))
        {
            $oCountry->Load($this->_getModConfig()->getValue('sFallbackCountryId'));
        }

        return $oCountry;
    }

    /**
     * check module active state and set user country specific language
     *
     */
    public function setCountryLanguage()
    {
        $this->performURLSwitch();
        $this->performShopSwitch();

        if (!$this->_getModConfig()->isActive() || !$this->_getModConfig()->getValue('blChangeLang'))
            return;

        $oCountry = $this->getUserLocationCountryObject();

        if (!$this->isAdmin() && oxRegistry::getUtils()->isSearchEngine() === false && oxRegistry::getSession()->getVariable('d3isSetLang') === null && $oCountry->getId() && $oCountry->getFieldData('d3geoiplang') > -1)
        {
            $this->_getLog()->log('info', __CLASS__, __FUNCTION__, __LINE__, 'set language', $this->getIP().' => '.$oCountry->getFieldData('d3geoiplang'));
            oxRegistry::getLang()->setTplLanguage((int) $oCountry->getFieldData('d3geoiplang'));
            oxRegistry::getLang()->setBaseLanguage((int) $oCountry->getFieldData('d3geoiplang'));
            oxRegistry::getSession()->setVariable('d3isSetLang', true);
        }
    }

    /**
     * check module active state and set user country specific currency
     *
     */
    public function setCountryCurrency()
    {
        if (!$this->_getModConfig()->isActive() || !$this->_getModConfig()->getValue('blChangeCurr'))
            return;

        $oCountry = $this->getUserLocationCountryObject();

        if (!$this->isAdmin() && oxRegistry::getUtils()->isSearchEngine() === false && !oxRegistry::getSession()->getVariable('d3isSetCurr') && $oCountry->getId() && $oCountry->getFieldData('d3geoipcur') > -1)
        {
            $this->_getLog()->log('info', __CLASS__, __FUNCTION__, __LINE__, 'set currency', $this->getIP().' => '.$oCountry->getFieldData('d3geoipcur'));
            oxRegistry::getConfig()->setActShopCurrency((int) $oCountry->getFieldData('d3geoipcur'));
            oxRegistry::getSession()->setVariable('d3isSetCurr', true);
        }
    }

    /**
     * check module active state and perform switching to user country specific shop (EE only)
     *
     */
    public function performShopSwitch()
    {
        if (!$this->_getModConfig()->isActive() || !$this->_getModConfig()->getValue('blChangeShop'))
            return;

        $oCountry = $this->getUserLocationCountryObject();

        $iNewShop = $oCountry->getFieldData('d3geoipshop');

        if (!$this->isAdmin() && oxRegistry::getUtils()->isSearchEngine() === false && $oCountry->getId() && $this->getConfig()->isMall() && $iNewShop > -1 && $iNewShop != $this->getConfig()->getShopId())
        {
            $oNewConf = new oxConfig();
            $oNewConf->setShopId($iNewShop);
            $oNewConf->init();

            $this->getConfig()->onShopChange();

            if (!oxRegistry::getSession()->getVariable('d3isSetLang') && $oCountry->getFieldData('d3geoiplang') > -1)
            {
                $sLangId = $oCountry->getFieldData('d3geoiplang');
            }
            else
            {
                $sLangId = '';
            }

            $this->_getLog()->log('info', __CLASS__, __FUNCTION__, __LINE__, 'change shop', $this->getIP().' => '.$oNewConf->getShopHomeUrl($sLangId));

            header("Location: ".$oNewConf->getShopHomeUrl($sLangId));
            exit();
        }
    }

    /**
     * check module active state and perform switching to user country specific url
     *
     */
    public function performURLSwitch()
    {
        if (!$this->_getModConfig()->isActive() || !$this->_getModConfig()->getValue('blChangeURL'))
        {
            return;
        }

        $oCountry = $this->getUserLocationCountryObject();

        if (!$this->isAdmin() && oxRegistry::getUtils()->isSearchEngine() === false && $oCountry->getId() && $oCountry->getFieldData('d3geoipurl') && strlen(trim($oCountry->getFieldData('d3geoipurl'))) > 0)
        {
            $sNewUrl = $oCountry->getFieldData('d3geoipurl');

            $this->_getLog()->log('info', __CLASS__, __FUNCTION__, __LINE__, 'change url', $this->getIP().' => '.$oCountry->getFieldData('d3geoipurl'));

            header("Location: ".$sNewUrl);
            exit();
        }
    }

    /**
     * get all shop urls
     *
     * @return array
     */
    public function getShopUrls()
    {
        $oShoplist = oxNew( 'oxshoplist' );
        $oShoplist->getList();
        $aShopUrls = array();
        foreach ( $oShoplist as $sId => $oShop )
        {
            $aShopUrls[$sId] = $this->getConfig()->getShopConfVar( 'sMallShopURL', $sId );
        }

        return $aShopUrls;
    }

    /**
     * get modcfg instance
     *
     * @return d3_cfg_mod
     */
    protected function _getModConfig()
    {
        return d3_cfg_mod::get($this->_sModId);
    }

    /**
     * get d3log instance
     *
     * @return d3log
     */
    protected function _getLog()
    {
        if (!$this->oD3Log)
        {
            $this->oD3Log = $this->_getModConfig()->getLog();
        }

        return $this->oD3Log;
    }
}