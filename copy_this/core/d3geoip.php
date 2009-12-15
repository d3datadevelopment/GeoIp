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


//            $sIP = '62.4.77.32';            // Deutschland
//            $sIP = '62.4.77.48';            // Deutschland
//            $sIP = '41.188.100.127';        // Mauretanien
//            $sIP = '4.18.40.144';           // US

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
        $sSelect = "SELECT oxid FROM ".$oCountry->getViewName()." WHERE OXISOALPHA2 = '".$sISOAlpha."' AND OXACTIVE = '1'";
        $oCountry->load(oxDb::getDb()->getOne($sSelect));

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
        $oCountry = $this->getUserLocationCountryObject();

        $this->performShopSwitch();

        if (!$this->isAdmin() && oxUtils::getInstance()->isSearchEngine() === false && $this->getSession()->getVar('d3isSetLang') === null && $oCountry->getId() && $oCountry->getFieldData('d3geoiplang') > -1)
        {
            oxLang::getInstance()->setTplLanguage((int) $oCountry->getFieldData('d3geoiplang'));
            oxLang::getInstance()->setBaseLanguage((int) $oCountry->getFieldData('d3geoiplang'));
            $this->getSession()->setVar('d3isSetLang', true);
        }

    }

    public function setCountryCurrency()
    {
        $oCountry = $this->getUserLocationCountryObject();

        if (!$this->isAdmin() && oxUtils::getInstance()->isSearchEngine() === false && !$this->getSession()->getVar('d3isSetCurr') && $oCountry->getId() && $oCountry->getFieldData('d3geoipcur') > -1)
        {
            $this->getConfig()->setActShopCurrency((int) $oCountry->getFieldData('d3geoipcur'));
            $this->getSession()->setVar('d3isSetCurr', true);
        }

    }

    public function performShopSwitch()
    {
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

                header("Location: ".$oNewConf->getShopHomeUrl($sLangId));
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
}