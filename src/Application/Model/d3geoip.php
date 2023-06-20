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

namespace D3\GeoIp\Application\Model;

use D3\ModCfg\Application\Model\Configuration\d3_cfg_mod;
use D3\ModCfg\Application\Model\d3str;
use D3\ModCfg\Application\Model\Exception\d3_cfg_mod_exception;
use D3\ModCfg\Application\Model\Exception\d3ShopCompatibilityAdapterException;
use D3\ModCfg\Application\Model\Log\d3log;
use Doctrine\DBAL\DBALException;
use OxidEsales\Eshop\Application\Model\Country;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Exception\DatabaseConnectionException;
use OxidEsales\Eshop\Core\Exception\DatabaseErrorException;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\Registry;

class d3geoip extends BaseModel
{
    const SKIPURL_REQUEST_PARAM = 'forceUrl';
    const SKIPURL_SESSION_PARAM = 'd3geoipForceUrl';

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
     * @param string|null $sIP optional
     *
     * @return Country
     * @throws d3ShopCompatibilityAdapterException
     * @throws d3_cfg_mod_exception
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     * @throws StandardException
     */
    public function getUserLocationCountryObject(string $sIP = null)
    {
        if (!$this->oCountry) {
            startProfile(__METHOD__);

            if (!$sIP) {
                $sIP = $this->getIP();
            }

			$sISOAlpha = $this->loadByIP(Registry::getConfig()->checkParamSpecialChars($sIP));

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
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
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
            $sIP = $_SERVER['HTTP_CF_CONNECTING_IP']
                ?? $_SERVER['HTTP_X_REAL_IP']
                ?? $_SERVER['HTTP_CLIENT_IP']
                ?? $_SERVER['HTTP_X_FORWARDED_FOR']
                ?? $_SERVER['HTTP_X_FORWARDED']
                ?? $_SERVER['HTTP_FORWARDED_FOR']
                ?? $_SERVER['HTTP_FORWARDED']
                ?? $_SERVER['REMOTE_ADDR']
                ?? 'UNKNOWN';
        }
        $sIP = str_replace(' ', '', $sIP);

        stopProfile(__METHOD__);

        return Registry::getConfig()->checkParamSpecialChars($sIP);
    }

    /**
     * get ISO alpha 2 ID by IP address
     *
     * @param string $sIP IP address
     *
     * @return string
     * @throws DatabaseConnectionException
     */
    public function loadByIP(string $sIP)
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

        $oDB = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
        $sISO = $oDB->getOne($sSelect);

        stopProfile(__METHOD__);

        return $sISO;
    }

    /**
     * get Country object by ISO alpha 2 ID
     *
     * @param string $sISOAlpha
     *
     * @return Country
     * @throws DatabaseConnectionException
     */
    public function getCountryObject(string $sISOAlpha)
    {
        startProfile(__METHOD__);

        /** @var Country $oCountry */
        $oCountry = oxNew(Country::class);
        $sSelect = "SELECT oxid FROM ".$oCountry->getViewName().
            " WHERE OXISOALPHA2 = '".$sISOAlpha."' AND OXACTIVE = '1'";

        $oDB = DatabaseProvider::getDb();
        $oCountry->load($oDB->getOne($sSelect));

        stopProfile(__METHOD__);

        return $oCountry;
    }

    /**
     * get Country object for fallback, if set
     *
     * @return Country
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     */
    public function getCountryFallBackObject()
    {
        startProfile(__METHOD__);

        $oCountry = oxNew('oxcountry');

        if ($this->_getModConfig()->getValue('blUseFallback')
            && $this->_getModConfig()->getValue('sFallbackCountryId')
        ) {
            $oCountry->load($this->_getModConfig()->getValue('sFallbackCountryId'));
        }

        stopProfile(__METHOD__);

        return $oCountry;
    }

    /**
     * check module active state and set user country specific language
     *
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     * @throws StandardException
     * @throws d3ShopCompatibilityAdapterException
     * @throws d3_cfg_mod_exception
     */
    public function setCountryLanguage()
    {
        startProfile(__METHOD__);

        $this->_getModConfig()->d3getLog()->info(__CLASS__, __FUNCTION__, __LINE__, 'start shop or url switch');
        $this->performURLSwitch();
        $this->performShopSwitch();
        $this->_getModConfig()->d3getLog()->info(__CLASS__, __FUNCTION__, __LINE__, 'end shop or url switch');

        if (!$this->_getModConfig()->isActive()
            || false == $this->_getModConfig()->getValue('blChangeLang')
        ) {
            $this->_getModConfig()->d3getLog()->info(__CLASS__, __FUNCTION__, __LINE__, 'language change option or module is disabled');
            stopProfile(__METHOD__);
            return;
        }

        $oCountry = $this->getUserLocationCountryObject();

        if (!$this->isAdmin()
            && Registry::getUtils()->isSearchEngine() === false
            && Registry::getSession()->getId()
            && Registry::getSession()->getVariable('d3isSetLang') === null
            && $oCountry->getId() && $oCountry->getFieldData('d3geoiplang') > -1
        ) {
            $language = (int) $oCountry->getFieldData('d3geoiplang');
            $this->_getModConfig()->d3getLog()->info(
                __CLASS__,
                __FUNCTION__,
                __LINE__,
                'set language',
                $this->getIP().' => '.$language
            );
            Registry::getLang()->setTplLanguage($language);
            Registry::getLang()->setBaseLanguage($language);
            Registry::getSession()->setVariable('d3isSetLang', true);
        }

        stopProfile(__METHOD__);
    }

    /**
     * check module active state and set user country specific currency
     *
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     * @throws StandardException
     * @throws d3ShopCompatibilityAdapterException
     * @throws d3_cfg_mod_exception
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
            && Registry::getUtils()->isSearchEngine() === false
            && !Registry::getSession()->getVariable('d3isSetCurr')
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
            Registry::getConfig()->setActShopCurrency((int) $oCountry->getFieldData('d3geoipcur'));
            Registry::getSession()->setVariable('d3isSetCurr', true);
        }

        stopProfile(__METHOD__);
    }

    /**
     * @param $oCurr
     *
     * @return bool
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     * @throws StandardException
     * @throws d3ShopCompatibilityAdapterException
     * @throws d3_cfg_mod_exception
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
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     * @throws StandardException
     * @throws d3ShopCompatibilityAdapterException
     * @throws d3_cfg_mod_exception
     */
    public function performShopSwitch()
    {
        if (!$this->_getModConfig()->isActive() || !$this->_getModConfig()->getValue('blChangeShop')) {
            $this->_getModConfig()->d3getLog()->info(__CLASS__, __FUNCTION__, __LINE__, 'shop change option or module is disabled');
            return;
        }

        startProfile(__METHOD__);

        $oCountry = $this->getUserLocationCountryObject();
        $iNewShop = $oCountry->getFieldData('d3geoipshop');

        $this->_getModConfig()->d3getLog()->info(__CLASS__, __FUNCTION__, __LINE__, 'check allowed shop change');

        if (Registry::getRequest()->getRequestEscapedParameter('d3redirect') != 1
            && false == $this->isAdmin()
            && Registry::getUtils()->isSearchEngine() === false
            && $oCountry->getId()
            && Registry::getConfig()->isMall()
            && $iNewShop > -1 &&
            (
                $iNewShop != Registry::getConfig()->getShopId()
                || strtolower(Registry::getConfig()->getActiveView()->getClassKey()) == 'mallstart'
            )
        ) {
            $this->_getModConfig()->d3getLog()->info(__CLASS__, __FUNCTION__, __LINE__, 'prepare shop change to '.$iNewShop);

            $oNewConf = new Config();
            $oNewConf->setShopId($iNewShop);
            $oNewConf->init();

            Registry::getConfig()->onShopChange();

            if (!Registry::getSession()->getVariable('d3isSetLang')
                && $this->_getModConfig()->getValue('blChangeLang')
                && $oCountry->getFieldData('d3geoiplang') > -1
            ) {
                $sLangId = $oCountry->getFieldData('d3geoiplang');
            } else {
                $sLangId = '';
            }

            /** @var  $oStr d3str */
            $oStr = Registry::get(d3str::class);
            $aParams = array(
                'd3redirect' => '1',
                'fnc'        => Registry::getRequest()->getRequestEscapedParameter('fnc'),
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

            $this->_getModConfig()->d3getLog()->info(__CLASS__, __FUNCTION__, __LINE__, 'change to shop url', $sUrl);

            header("Location: ".$sUrl);
            exit();
        }

        stopProfile(__METHOD__);
    }

    /**
     * check module active state and perform switching to user country specific url
     *
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     * @throws StandardException
     * @throws d3ShopCompatibilityAdapterException
     * @throws d3_cfg_mod_exception
     */
    public function performURLSwitch()
    {
        if (!$this->_getModConfig()->isActive()
            || false == $this->_getModConfig()->getValue('blChangeURL')) {
            return;
        }

        startProfile(__METHOD__);

        if (Registry::getRequest()->getRequestEscapedParameter(self::SKIPURL_REQUEST_PARAM)) {
            Registry::getSession()->setVariable(self::SKIPURL_SESSION_PARAM, true);
        }

        $oCountry = $this->getUserLocationCountryObject();

        if ($this->dontSkipUrlRedirect()
            && false == $this->isAdmin()
            && Registry::getUtils()->isSearchEngine() === false
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
     * @return bool
     */
    protected function dontSkipUrlRedirect()
    {
        return false === (
            Registry::getRequest()->getRequestEscapedParameter(self::SKIPURL_REQUEST_PARAM) ||
            Registry::getSession()->getVariable(self::SKIPURL_SESSION_PARAM)
            );
    }

	/**
	 * get all shop urls
	 *
     * @return array
     */
    public function getShopUrls()
    {
        startProfile(__METHOD__);

        $oShoplist = oxNew('oxshoplist');
        $oShoplist->getList();
        $aShopUrls = array();

        foreach ($oShoplist->arrayKeys() as $sId) {
            $aShopUrls[$sId] = Registry::getConfig()->getShopConfVar('sMallShopURL', $sId);
        }

        stopProfile(__METHOD__);

        return $aShopUrls;
    }

    /**
     * get modcfg instance
     *
     * @return d3_cfg_mod
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     */
    protected function _getModConfig()
    {
        return d3_cfg_mod::get($this->_sModId);
    }

    /**
     * get d3log instance
     *
     * @return d3log
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     */
    protected function _getLog()
    {
        if (!$this->oD3Log) {
            $this->oD3Log = $this->_getModConfig()->d3getLog();
        }

        return $this->oD3Log;
    }
}

