<?php

class d3GeoIP extends oxI18n
{
    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'd3geoip';

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

    public function getUserLocationCountryObject()
    {

        if (!$this->oCountry)
        {
            $sIP = $_SERVER['REMOTE_ADDR'];

            /*
            $sIP = '62.4.77.32';
            $sIP = '62.4.77.48';
            $sIP = '41.188.100.127';
            */

            $iIPNum = $this->_getNumIp($sIP);
            $sISOAlpha = $this->LoadByIPNum($iIPNum);
            $this->oCountry = $this->getCountryObject($sISOAlpha);
        }

        return $this->oCountry;
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
        $oCountry = &oxNew('oxcountry');
        $sSelect = "SELECT oxid FROM ".$oCountry->getViewName()." WHERE OXISOALPHA2 = '".$sISOAlpha."'";
        $oCountry->load(oxDb::getDb()->getOne($sSelect));

        return $oCountry;
    }

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

    public function setCountryLanguage()
    {
        $oCountry = $this->getUserLocationCountryObject();
        $aCountryLangs = $this->getConfig()->getConfigParam('aCountryLangs');

        if (!$this->getSession()->getVar('d3isSetLang') && $aCountryLangs && $oCountry->getFieldData('oxisoalpha2') && isset($aCountryLangs[$oCountry->getFieldData('oxisoalpha2')]))
        {
            $iNewLanguage = $aCountryLangs[$oCountry->getFieldData('oxisoalpha2')];
            oxLang::getInstance()->setTplLanguage((int) $iNewLanguage);
            oxLang::getInstance()->setBaseLanguage((int) $iNewLanguage);
            $this->getSession()->setVar('d3isSetLang', true);
        }

    }

    public function setCountryCurrency()
    {
        $oCountry = $this->getUserLocationCountryObject();
        $aCountryCurrs = $this->getConfig()->getConfigParam('aCountryCurrs');

        if (!$this->getSession()->getVar('d3isSetCurr') && $aCountryCurrs && $oCountry->getFieldData('oxisoalpha2') && isset($aCountryCurrs[$oCountry->getFieldData('oxisoalpha2')]))
        {
            $iNewCurrency = $aCountryCurrs[$oCountry->getFieldData('oxisoalpha2')];
            $this->getConfig()->setActShopCurrency((int) $iNewCurrency );
            $this->getSession()->setVar('d3isSetCurr', true);
        }

    }
}