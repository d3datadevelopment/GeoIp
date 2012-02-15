<?php

// @copyright © D³ Data Development
//
// This Software is the property of Data Development and is protected
// by copyright law - it is NOT Freeware.
//
// Any unauthorized use of this software without a valid license key
// is a violation of the license agreement and will be prosecuted by
// civil and criminal law.
//
// http://www.shopmodule.com

// AUTOR Daniel Seifert <ds@shopmodule.com>

class d3GeoIP extends oxI18n
{
    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'd3geoip';
    
    private $_sModId = 'd3_geoip';

    /**
     * Class constructor, initiates parent constructor (parent::oxI18n()).
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('d3geoip');
    }

    public function getUserLocationCountryObject($sIP = false)
    {
        if (!$this->oCountry)
        {
            if (!$sIP)
                $sIP = $this->getIP();
                
            $iIPNum = $this->_getNumIp($sIP);
            $sISOAlpha = $this->LoadByIPNum($iIPNum);

            if (!$sISOAlpha)
            {
                $this->_getLog()->setLog('error', __CLASS__, __FUNCTION__, __LINE__, 'get ISO by IP failed', $sIP);
                $this->oCountry = $this->getCountryFallBackObject();
            }
            else
            {
                $this->_getLog()->setLog('info', __CLASS__, __FUNCTION__, __LINE__, 'get ISO by IP', $sIP." => ".$sISOAlpha);
                $this->oCountry = $this->getCountryObject($sISOAlpha);  
            }
        }

        return $this->oCountry;
    }
    
    public function getIP()
    {
        if ($this->_getConfig()->getValue('blUseTestIp') && $this->_getConfig()->getValue('sTestIp'))
            return $this->_getConfig()->getValue('sTestIp');
        else
            return $_SERVER['REMOTE_ADDR'];
    }

    protected function _getNumIp($sIP)
    {
        $aIP = explode('.',$sIP);
        $iIP = ($aIP[0] * 16777216) + ($aIP[1] * 65536) + ($aIP[2] * 256) + ($aIP[3] * 1);
        return $iIP;
    }

    public function LoadByIPNum($iIPNum)
    {
        $sSelect = "SELECT d3iso FROM ".$this->_sClassName." WHERE d3startipnum <= '$iIPNum' AND d3endipnum >= '$iIPNum'";
        return oxDb::getDb()->getOne($sSelect);
    }

    public function getCountryObject($sISOAlpha)
    {
        $oCountry = oxNew('oxcountry');
        $sSelect = "SELECT oxid FROM ".$oCountry->getViewName()." WHERE OXISOALPHA2 = '".$sISOAlpha."' AND OXACTIVE = '1'";
        $oCountry->load(oxDb::getDb()->getOne($sSelect));

        return $oCountry;
    }
    
    public function getCountryFallBackObject()
    {
        $oCountry = oxNew('oxcountry');
        
        if ($this->_getConfig()->getValue('blUseFallback') && $this->_getConfig()->getValue('sFallbackCountryId'))
        {
            $oCountry->Load($this->_getConfig()->getValue('sFallbackCountryId'));
        }

        return $oCountry;
    }

/*
    public function setUserCountry()
    {
        if (!$this->getUser()) {
            $oCountry = $this->getUserLocationCountryObject();
            $oUser = &oxNew('oxuser');
            $oUser->oxuser__oxcountryid = oxNew('oxfield');
            $oUser->oxuser__oxcountryid->setValue($oCountry->getId());
            $this->setUser($oUser);
        }

        return;
    }
*/

    public function setCountryLanguage()
    {
        $this->performURLSwitch();
        $this->performShopSwitch();

        if (!$this->_getConfig()->getFieldData('oxactive') || !$this->_getConfig()->getValue('blChangeLang'))
            return;

        $oCountry = $this->getUserLocationCountryObject();
            
        if (!$this->isAdmin() && oxUtils::getInstance()->isSearchEngine() === false && $this->getSession()->getVar('d3isSetLang') === null && $oCountry->getId() && $oCountry->getFieldData('d3geoiplang') > -1)
        {
            $this->_getLog()->setLog('info', __CLASS__, __FUNCTION__, __LINE__, 'set language', $this->getIP().' => '.$oCountry->getFieldData('d3geoiplang'));
            oxLang::getInstance()->setTplLanguage((int) $oCountry->getFieldData('d3geoiplang'));
            oxLang::getInstance()->setBaseLanguage((int) $oCountry->getFieldData('d3geoiplang'));
            $this->getSession()->setVar('d3isSetLang', true);
        }
    }

    public function setCountryCurrency()
    {
        if (!$this->_getConfig()->getFieldData('oxactive') || !$this->_getConfig()->getValue('blChangeCurr'))
            return;

        $oCountry = $this->getUserLocationCountryObject();

        if (!$this->isAdmin() && oxUtils::getInstance()->isSearchEngine() === false && !$this->getSession()->getVar('d3isSetCurr') && $oCountry->getId() && $oCountry->getFieldData('d3geoipcur') > -1)
        {
            $this->_getLog()->setLog('info', __CLASS__, __FUNCTION__, __LINE__, 'set currency', $this->getIP().' => '.$oCountry->getFieldData('d3geoipcur'));
            $this->getConfig()->setActShopCurrency((int) $oCountry->getFieldData('d3geoipcur'));
            $this->getSession()->setVar('d3isSetCurr', true);
        }
    }

    public function performShopSwitch()
    {
        if (!$this->_getConfig()->getFieldData('oxactive') || !$this->_getConfig()->getValue('blChangeShop'))
            return;
            
        $oCountry = $this->getUserLocationCountryObject();

        $iNewShop = $oCountry->getFieldData('d3geoipshop');

        $aShopLinks = $this->getShopUrls();

        if (!$this->isAdmin() && oxUtils::getInstance()->isSearchEngine() === false && $oCountry->getId() && $this->getConfig()->isMall() && $iNewShop > -1 && $iNewShop != $this->getConfig()->getShopId())
        {
            $oNewConf = new oxConfig();
            $oNewConf->setShopId($iNewShop);
            $oNewConf->init();

            $this->getConfig()->onShopChange();

            if (!$this->getSession()->getVar('d3isSetLang') && $oCountry->getFieldData('d3geoiplang') > -1)
                $sLangId = $oCountry->getFieldData('d3geoiplang');
            else
                $sLangId = '';

            $this->_getLog()->setLog('info', __CLASS__, __FUNCTION__, __LINE__, 'change shop', $this->getIP().' => '.$oNewConf->getShopHomeUrl($sLangId));
            
            header("Location: ".$oNewConf->getShopHomeUrl($sLangId));
            exit();
        }
    }
    
    public function performURLSwitch()
    {
        if (!$this->_getConfig()->getFieldData('oxactive') || !$this->_getConfig()->getValue('blChangeURL'))
            return;
            
        $oCountry = $this->getUserLocationCountryObject();
        
        if (!$this->isAdmin() && oxUtils::getInstance()->isSearchEngine() === false && $oCountry->getId() && $oCountry->getFieldData('d3geoipurl') && strlen(trim($oCountry->getFieldData('d3geoipurl'))) > 0)
        {
            $sNewUrl = $oCountry->getFieldData('d3geoipurl');

            $this->_getLog()->setLog('info', __CLASS__, __FUNCTION__, __LINE__, 'change url', $this->getIP().' => '.$oCountry->getFieldData('d3geoipurl'));
            
            header("Location: ".$sNewUrl);
            exit();
        }
    }

    public function getShopUrls()
    {
        $oShoplist = oxNew( 'oxshoplist' );
        $oShoplist->getList();
        $aShopUrls = array();
        foreach ( $oShoplist as $sId => $oShop ) {
            $aShopUrls[$sId] = $this->getConfig()->getShopConfVar( 'sMallShopURL', $sId );
        }

        return $aShopUrls;
    }
    
    protected function _getConfig()
    {
        return d3_cfg_mod::get($this->_sModId);
    }
    
    protected function _getLog()
    {
        if (!$this->oD3Log)
            $this->oD3Log = $this->_getConfig()->getLogInstance();
            
        return $this->oD3Log;
    }
}