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

class d3GeoIP extends oxbase
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
            startProfile(__METHOD__);

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

            stopProfile(__METHOD__);
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
        startProfile(__METHOD__);

        if ($this->_getModConfig()->getValue('blUseTestIp') && $this->_getModConfig()->getValue('sTestIp'))
        {
            $sIP = $this->_getModConfig()->getValue('sTestIp');
        }
        elseif ($this->_getModConfig()->getValue('blUseTestCountry') && $this->_getModConfig()->getValue('sTestCountryIp'))
        {
            $sIP = $this->_getModConfig()->getValue('sTestCountryIp');
        }
        else
        {
            // ToDo: use $_SERVER['X-Forwared-For'] && Client-IP in case of proxy
            $sIP = $_SERVER['REMOTE_ADDR'];
        }

        stopProfile(__METHOD__);

        return $sIP;
    }

    /**
     * get IP number by IP address
     * @param $sIP
     * @return bool
     * @throws Exception|int
     */
    protected function _getNumIp($sIP)
    {
        // make sure it is an ip
        if (filter_var($sIP, FILTER_VALIDATE_IP) === FALSE)
            return FALSE;

        startProfile(__METHOD__);

        $parts = unpack('N*', inet_pton($sIP));

        if (strpos($sIP, '.') !== FALSE)
        {
            $parts = array(1=>0, 2=>0, 3=>0, 4=>$parts[1]);
        }

        foreach ($parts as &$part)
        {
            if ($part < 0)
                $part += 4294967296;
        }

        if (function_exists('bcadd'))
        {
            $dIP = $parts[4];
            $dIP = bcadd($dIP, bcmul($parts[3], '4294967296'));
            $dIP = bcadd($dIP, bcmul($parts[2], '18446744073709551616'));
            $dIP = bcadd($dIP, bcmul($parts[1], '79228162514264337593543950336'));
        }
        else
        {
            throw new Exception('extension BCMath is required');
        }

        $aIP = explode('.', $dIP);

        stopProfile(__METHOD__);

        return $aIP[0];
    }

    /**
     * get ISO alpha 2 ID by IP number
     *
     * @param int $iIPNum IP number
     * @return string
     */
    public function LoadByIPNum($iIPNum)
    {
        startProfile(__METHOD__);

        $sSelect = "SELECT d3iso FROM ".$this->_sClassName." WHERE d3startipnum <= '$iIPNum' AND d3endipnum >= '$iIPNum'";
        $sISO = oxDb::getDb()->getOne($sSelect);

        stopProfile(__METHOD__);

        return $sISO;
    }

    /**
     * get oxcountry object by ISO alpha 2 ID
     *
     * @param string $sISOAlpha
     * @return oxcountry
     */
    public function getCountryObject($sISOAlpha)
    {
        startProfile(__METHOD__);

        $oCountry = oxNew('oxcountry');
        $sSelect = "SELECT oxid FROM ".$oCountry->getViewName()." WHERE OXISOALPHA2 = '".$sISOAlpha."' AND OXACTIVE = '1'";

        $oCountry->load(oxDb::getDb()->getOne($sSelect));

        stopProfile(__METHOD__);

        return $oCountry;
    }

    /**
     * get oxcountry object for fallback, if set
     *
     * @return oxcountry
     */
    public function getCountryFallBackObject()
    {
        startProfile(__METHOD__);

        $oCountry = oxNew('oxcountry');

        if ($this->_getModConfig()->getValue('blUseFallback') && $this->_getModConfig()->getValue('sFallbackCountryId'))
        {
            $oCountry->Load($this->_getModConfig()->getValue('sFallbackCountryId'));
        }

        stopProfile(__METHOD__);

        return $oCountry;
    }

    /**
     * check module active state and set user country specific language
     *
     */
    public function setCountryLanguage()
    {
        startProfile(__METHOD__);

        $this->performURLSwitch();
        $this->performShopSwitch();

        if (!$this->_getModConfig()->isActive() || !$this->_getModConfig()->getValue('blChangeLang'))
        {
            stopProfile(__METHOD__);
            return;
        }

        $oCountry = $this->getUserLocationCountryObject();

        if (!$this->isAdmin() && oxRegistry::getUtils()->isSearchEngine() === false && oxRegistry::getSession()->getVariable('d3isSetLang') === null && $oCountry->getId() && $oCountry->getFieldData('d3geoiplang') > -1)
        {
            $this->_getLog()->log('info', __CLASS__, __FUNCTION__, __LINE__, 'set language', $this->getIP().' => '.$oCountry->getFieldData('d3geoiplang'));
            oxRegistry::getLang()->setTplLanguage((int) $oCountry->getFieldData('d3geoiplang'));
            oxRegistry::getLang()->setBaseLanguage((int) $oCountry->getFieldData('d3geoiplang'));
            oxRegistry::getSession()->setVariable('d3isSetLang', true);
        }

        stopProfile(__METHOD__);
    }

    /**
     * check module active state and set user country specific currency
     *
     */
    public function setCountryCurrency()
    {
        if (!$this->_getModConfig()->isActive() || !$this->_getModConfig()->getValue('blChangeCurr'))
            return;

        startProfile(__METHOD__);

        $oCountry = $this->getUserLocationCountryObject();

        if (!$this->isAdmin() && oxRegistry::getUtils()->isSearchEngine() === false && !oxRegistry::getSession()->getVariable('d3isSetCurr') && $oCountry->getId() && $oCountry->getFieldData('d3geoipcur') > -1)
        {
            $this->_getLog()->log('info', __CLASS__, __FUNCTION__, __LINE__, 'set currency', $this->getIP().' => '.$oCountry->getFieldData('d3geoipcur'));
            oxRegistry::getConfig()->setActShopCurrency((int) $oCountry->getFieldData('d3geoipcur'));
            oxRegistry::getSession()->setVariable('d3isSetCurr', true);
        }

        stopProfile(__METHOD__);
    }

    /**
     * check module active state and perform switching to user country specific shop (EE only)
     *
     */
    public function performShopSwitch()
    {
        if (!$this->_getModConfig()->isActive() || !$this->_getModConfig()->getValue('blChangeShop'))
            return;

        startProfile(__METHOD__);

        $oCountry = $this->getUserLocationCountryObject();
        $iNewShop = $oCountry->getFieldData('d3geoipshop');

        if (oxRegistry::getConfig()->getRequestParameter('d3redirect') != 1 &&
            !$this->isAdmin() &&
            oxRegistry::getUtils()->isSearchEngine() === false &&
            $oCountry->getId() &&
            $this->getConfig()->isMall() &&
            $iNewShop > -1 &&
            (
                $iNewShop != $this->getConfig()->getShopId() ||
                strtolower($this->getConfig()->getActiveView()->getClassName()) == 'mallstart'
            )
        )
        {
            $oNewConf = new oxConfig();
            $oNewConf->setShopId($iNewShop);
            $oNewConf->init();

            $this->getConfig()->onShopChange();

            if (!oxRegistry::getSession()->getVariable('d3isSetLang') &&
                $this->_getModConfig()->getValue('blChangeLang') &&
                $oCountry->getFieldData('d3geoiplang') > -1)
            {
                $sLangId = $oCountry->getFieldData('d3geoiplang');
            }
            else
            {
                $sLangId = '';
            }

            /** @var  $oStr d3str */
            $oStr = oxRegistry::get('d3str');
            $aParams = array(
                'd3redirect' => '1',
                'fnc'        => oxRegistry::getConfig()->getRequestParameter('fnc'),
                'shp'        => $iNewShop
            );
            $sUrl = str_replace('&amp;', '&', $oStr->generateParameterUrl($oNewConf->getShopHomeUrl($sLangId), $aParams));

            $this->_getLog()->log('info', __CLASS__, __FUNCTION__, __LINE__, 'change shop', $this->getIP().' => '.$sUrl);

            header("Location: ".$sUrl);
            exit();
        }

        stopProfile(__METHOD__);
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

        startProfile(__METHOD__);

        $oCountry = $this->getUserLocationCountryObject();

        if (!$this->isAdmin() &&
            oxRegistry::getUtils()->isSearchEngine() === false &&
            $oCountry->getId() &&
            $oCountry->getFieldData('d3geoipurl') &&
            strlen(trim($oCountry->getFieldData('d3geoipurl'))) > 0
        )
        {
            $sNewUrl = $oCountry->getFieldData('d3geoipurl');

            $this->_getLog()->log('info', __CLASS__, __FUNCTION__, __LINE__, 'change url', $this->getIP().' => '.$oCountry->getFieldData('d3geoipurl'));

            header("Location: ".$sNewUrl);
            exit();
        }

        stopProfile(__METHOD__);
    }

    /**
     * get all shop urls
     *
     * @return array
     */
    public function getShopUrls()
    {
        startProfile(__METHOD__);

        $oShoplist = oxNew( 'oxshoplist' );
        $oShoplist->getList();
        $aShopUrls = array();
        foreach ( $oShoplist as $sId => $oShop )
        {
            $aShopUrls[$sId] = $this->getConfig()->getShopConfVar( 'sMallShopURL', $sId );
        }

        stopProfile(__METHOD__);

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