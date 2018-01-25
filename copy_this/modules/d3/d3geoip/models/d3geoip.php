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

class d3GeoIP extends oxBase
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
	 *
	 * @return oxcountry
	 * @throws d3_cfg_mod_exception
	 * @throws oxConnectionException
	 * @throws oxSystemComponentException
	 */
    public function getUserLocationCountryObject($sIP = null)
    {
        if (!$this->oCountry) {
            startProfile(__METHOD__);

            if (!$sIP) {
                $sIP = $this->getIP();
            }

			$sISOAlpha = $this->loadByIP(oxRegistry::getConfig()->checkParamSpecialChars($sIP));

            if (!$sISOAlpha) {
                $this->_getLog()->log(
                    d3log::ERROR,
                    __CLASS__,
                    __FUNCTION__,
                    __LINE__,
                    'get ISO by IP failed',
                    $sIP
                );
                $this->oCountry = $this->getCountryFallBackObject();
            } else {
                $this->_getLog()->log(
                    d3log::INFO,
                    __CLASS__,
                    __FUNCTION__,
                    __LINE__,
                    'get ISO by IP',
                    $sIP." => ".$sISOAlpha
                );
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
	 * @throws d3_cfg_mod_exception
	 */
    public function getIP()
    {
        startProfile(__METHOD__);

        if ($this->_getModConfig()->getValue('blUseTestIp')
            && $this->_getModConfig()->getValue('sTestIp')
        ) {
            $sIP = $this->_getModConfig()->getValue('sTestIp');
        } elseif ($this->_getModConfig()->getValue('blUseTestCountry')
            && $this->_getModConfig()->getValue('sTestCountryIp')
        ) {
            $sIP = $this->_getModConfig()->getValue('sTestCountryIp');
        } else {
            if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
                $sIP = $_SERVER['HTTP_CF_CONNECTING_IP'];
            } else if (isset($_SERVER['HTTP_X_REAL_IP'])) {
                $sIP = $_SERVER['HTTP_X_REAL_IP'];
            } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $sIP = $_SERVER['HTTP_CLIENT_IP'];
            } else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $sIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else if(isset($_SERVER['HTTP_X_FORWARDED'])) {
                $sIP = $_SERVER['HTTP_X_FORWARDED'];
            } else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
                $sIP = $_SERVER['HTTP_FORWARDED_FOR'];
            } else if(isset($_SERVER['HTTP_FORWARDED'])) {
                $sIP = $_SERVER['HTTP_FORWARDED'];
            } else if(isset($_SERVER['REMOTE_ADDR'])) {
                $sIP = $_SERVER['REMOTE_ADDR'];
            } else {
                $sIP = 'UNKNOWN';
            }
        }
        $sIP = str_replace(' ', '', $sIP);

        stopProfile(__METHOD__);

        return oxRegistry::getConfig()->checkParamSpecialChars($sIP);
    }

	/**
	 * get ISO alpha 2 ID by IP address
	 *
	 * @param int $sIP IP address
	 *
	 * @return string
	 * @throws oxConnectionException
	 */
    public function loadByIP($sIP)
    {
        startProfile(__METHOD__);

	    $sSelect = "
			SELECT 
				d3iso 
			FROM 
				".$this->_sClassName." 
			WHERE 
				LPAD(
					BINARY(
						if(
							IS_IPV4('" . $sIP . "'),
							INET_ATON('" . $sIP . "'),
							INET6_ATON('" . $sIP . "')
						)
					), 
					16, 
					0
				) BETWEEN D3STARTIPBIN AND D3ENDIPBIN";

        $sISO = oxDb::getDb()->getOne($sSelect);

        stopProfile(__METHOD__);

        return $sISO;
    }

	/**
	 * get oxcountry object by ISO alpha 2 ID
	 *
	 * @param string $sISOAlpha
	 *
	 * @return oxcountry
	 * @throws oxConnectionException
	 * @throws oxSystemComponentException
	 */
    public function getCountryObject($sISOAlpha)
    {
        startProfile(__METHOD__);

        $oCountry = oxNew('oxcountry');
        $sSelect = "SELECT oxid FROM ".$oCountry->getViewName().
            " WHERE OXISOALPHA2 = '".$sISOAlpha."' AND OXACTIVE = '1'";

        $oCountry->load(oxDb::getDb()->getOne($sSelect));

        stopProfile(__METHOD__);

        return $oCountry;
    }

	/**
	 * get oxcountry object for fallback, if set
	 *
	 * @return oxcountry
	 * @throws d3_cfg_mod_exception
	 * @throws oxSystemComponentException
	 */
    public function getCountryFallBackObject()
    {
        startProfile(__METHOD__);

        $oCountry = oxNew('oxcountry');

        if ($this->_getModConfig()->getValue('blUseFallback')
            && $this->_getModConfig()->getValue('sFallbackCountryId')
        ) {
            $oCountry->Load($this->_getModConfig()->getValue('sFallbackCountryId'));
        }

        stopProfile(__METHOD__);

        return $oCountry;
    }

	/**
	 * check module active state and set user country specific language
	 *
	 * @throws d3_cfg_mod_exception
	 * @throws oxConnectionException
	 * @throws oxSystemComponentException
	 */
    public function setCountryLanguage()
    {
        startProfile(__METHOD__);

        $this->performURLSwitch();
        $this->performShopSwitch();

        if (!$this->_getModConfig()->isActive()
            || false == $this->_getModConfig()->getValue('blChangeLang')) {
            stopProfile(__METHOD__);
            return;
        }

        $oCountry = $this->getUserLocationCountryObject();

        if (!$this->isAdmin()
            && oxRegistry::getUtils()->isSearchEngine() === false
            && oxRegistry::getSession()->getId()
            && oxRegistry::getSession()->getVariable('d3isSetLang') === null
            && $oCountry->getId() && $oCountry->getFieldData('d3geoiplang') > -1
        ) {
            $this->_getLog()->log(
                d3log::INFO,
                __CLASS__,
                __FUNCTION__,
                __LINE__,
                'set language',
                $this->getIP().' => '.$oCountry->getFieldData('d3geoiplang')
            );
            oxRegistry::getLang()->setTplLanguage((int) $oCountry->getFieldData('d3geoiplang'));
            oxRegistry::getLang()->setBaseLanguage((int) $oCountry->getFieldData('d3geoiplang'));
            oxRegistry::getSession()->setVariable('d3isSetLang', true);
        }

        stopProfile(__METHOD__);
    }

	/**
	 * check module active state and set user country specific currency
	 *
	 * @throws d3_cfg_mod_exception
	 * @throws oxConnectionException
	 * @throws oxSystemComponentException
	 */
    public function setCountryCurrency()
    {
        if (!$this->_getModConfig()->isActive()
            || false == $this->_getModConfig()->getValue('blChangeCurr')
        ) {
            return;
        }

        startProfile(__METHOD__);

        $oCountry = $this->getUserLocationCountryObject();

        if (!$this->isAdmin()
            && oxRegistry::getUtils()->isSearchEngine() === false
            && !oxRegistry::getSession()->getVariable('d3isSetCurr')
            && $oCountry->getId()
            && $oCountry->getFieldData('d3geoipcur') > -1
        ) {
            $this->_getLog()->log(
                d3log::INFO,
                __CLASS__,
                __FUNCTION__,
                __LINE__,
                'set currency',
                $this->getIP().' => '.$oCountry->getFieldData('d3geoipcur')
            );
            oxRegistry::getConfig()->setActShopCurrency((int) $oCountry->getFieldData('d3geoipcur'));
            oxRegistry::getSession()->setVariable('d3isSetCurr', true);
        }

        stopProfile(__METHOD__);
    }

	/**
	 * @param $oCurr
	 *
	 * @return bool
	 * @throws d3_cfg_mod_exception
	 * @throws oxConnectionException
	 * @throws oxSystemComponentException
	 */
    public function hasNotSetCurrency($oCurr)
    {
        $oCountry = $this->getUserLocationCountryObject();
        if ($oCountry->getFieldData('d3geoipcur') > 0
            && $oCurr->id != $oCountry->getFieldData('d3geoipcur')
        ) {
            return true;
        }

        return false;
    }

	/**
	 * check module active state and perform switching to user country specific shop (EE only)
	 *
	 * @throws d3_cfg_mod_exception
	 * @throws oxConnectionException
	 * @throws oxSystemComponentException
	 */
    public function performShopSwitch()
    {
        if (!$this->_getModConfig()->isActive() || !$this->_getModConfig()->getValue('blChangeShop')) {
            return;
        }

        startProfile(__METHOD__);

        $oCountry = $this->getUserLocationCountryObject();
        $iNewShop = $oCountry->getFieldData('d3geoipshop');

        if (oxRegistry::getConfig()->getRequestParameter('d3redirect') != 1
            && false == $this->isAdmin()
            && oxRegistry::getUtils()->isSearchEngine() === false
            && $oCountry->getId()
            && $this->getConfig()->isMall()
            && $iNewShop > -1 &&
            (
                $iNewShop != $this->getConfig()->getShopId()
                || strtolower($this->getConfig()->getActiveView()->getClassName()) == 'mallstart'
            )
        ) {
            $oNewConf = new oxConfig();
            $oNewConf->setShopId($iNewShop);
            $oNewConf->init();

            $this->getConfig()->onShopChange();

            if (!oxRegistry::getSession()->getVariable('d3isSetLang')
                && $this->_getModConfig()->getValue('blChangeLang')
                && $oCountry->getFieldData('d3geoiplang') > -1
            ) {
                $sLangId = $oCountry->getFieldData('d3geoiplang');
            } else {
                $sLangId = '';
            }

            /** @var  $oStr d3str */
            $oStr = oxRegistry::get('d3str');
            $aParams = array(
                'd3redirect' => '1',
                'fnc'        => oxRegistry::getConfig()->getRequestParameter('fnc'),
                'shp'        => $iNewShop
            );
            $sUrl = str_replace(
                '&amp;',
                '&',
                $oStr->generateParameterUrl($oNewConf->getShopHomeUrl($sLangId), $aParams)
            );

            $this->_getLog()->log(
                d3log::INFO,
                __CLASS__,
                __FUNCTION__,
                __LINE__,
                'change shop',
                $this->getIP().' => '.$sUrl
            );

            header("Location: ".$sUrl);
            exit();
        }

        stopProfile(__METHOD__);
    }

	/**
	 * check module active state and perform switching to user country specific url
	 *
	 * @throws d3_cfg_mod_exception
	 * @throws oxConnectionException
	 * @throws oxSystemComponentException
	 */
    public function performURLSwitch()
    {
        if (!$this->_getModConfig()->isActive()
            || false == $this->_getModConfig()->getValue('blChangeURL')) {
            return;
        }

        startProfile(__METHOD__);

        $oCountry = $this->getUserLocationCountryObject();

        if (false == $this->isAdmin()
            && oxRegistry::getUtils()->isSearchEngine() === false
            && $oCountry->getId()
            && $oCountry->getFieldData('d3geoipurl')
            && strlen(trim($oCountry->getFieldData('d3geoipurl'))) > 0
        ) {
            $sNewUrl = $oCountry->getFieldData('d3geoipurl');

            $this->_getLog()->log(
                d3log::INFO,
                __CLASS__,
                __FUNCTION__,
                __LINE__,
                'change url',
                $this->getIP().' => '.$oCountry->getFieldData('d3geoipurl')
            );

            header("Location: ".$sNewUrl);
            exit();
        }

        stopProfile(__METHOD__);
    }

	/**
	 * get all shop urls
	 *
	 * @return array
	 * @throws oxSystemComponentException
	 */
    public function getShopUrls()
    {
        startProfile(__METHOD__);

        $oShoplist = oxNew('oxshoplist');
        $oShoplist->getList();
        $aShopUrls = array();

        foreach ($oShoplist->arrayKeys() as $sId) {
            $aShopUrls[$sId] = $this->getConfig()->getShopConfVar('sMallShopURL', $sId);
        }

        stopProfile(__METHOD__);

        return $aShopUrls;
    }

	/**
	 * get modcfg instance
	 *
	 * @return d3_cfg_mod
	 * @throws d3_cfg_mod_exception
	 */
    protected function _getModConfig()
    {
        return d3_cfg_mod::get($this->_sModId);
    }

	/**
	 * get d3log instance
	 *
	 * @return d3log
	 * @throws d3_cfg_mod_exception
	 */
    protected function _getLog()
    {
        if (!$this->oD3Log) {
            $this->oD3Log = $this->_getModConfig()->d3getLog();
        }

        return $this->oD3Log;
    }
}
