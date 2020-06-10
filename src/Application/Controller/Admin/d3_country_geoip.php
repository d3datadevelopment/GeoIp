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

namespace D3\GeoIp\Application\Controller\Admin;

use D3\ModCfg\Application\Model\Configuration\d3_cfg_mod;
use Doctrine\DBAL\DBALException;
use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Application\Model\Country;
use OxidEsales\Eshop\Application\Model\Shop;
use OxidEsales\Eshop\Application\Model\ShopList;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Exception\DatabaseConnectionException;
use OxidEsales\Eshop\Core\Exception\DatabaseErrorException;
use OxidEsales\Eshop\Core\Registry;
use stdClass;

class d3_country_geoip extends AdminDetailsController
{
    protected $_sDefSort = 'sort';
    protected $_sDefSortOrder = 'asc';
    protected $_sThisTemplate = 'd3_country_geoip.tpl';
    private $_sModId = 'd3_geoip';
    /** @var  Country */
    public $oCountry;
    /** @var  ShopList */
    public $oShopList;

    /**
     * @return string
     */
    public function render()
    {
        if (false == Registry::getConfig()->getConfigParam('blAllowSharedEdit')) {
            $this->addTplParam('readonly', true);
        }

        $ret = parent::render();

        $soxId = Registry::getRequest()->getRequestEscapedParameter("oxid");
        // check if we right now saved a new entry
        $sSavedID = Registry::getRequest()->getRequestEscapedParameter("saved_oxid");
        if (($soxId == "-1" || !isset($soxId)) && isset($sSavedID)) {
            $soxId = $sSavedID;
            Registry::getSession()->deleteVariable("saved_oxid");
            $this->addTplParam("oxid", $soxId);
            // for reloading upper frame
            $this->addTplParam("updatelist", "1");
        }

        if ($soxId != "-1" && isset($soxId)) {
            // load object
            /** @var $oCountry Country */
            $oCountry = oxNew(Country::class);
            $oCountry->loadInLang($this->_iEditLang, $soxId);
            
            if ($oCountry->isForeignCountry()) {
                $this->addTplParam("blForeignCountry", true);
            } else {
                $this->addTplParam("blForeignCountry", false);
            }

            $oOtherLang = $oCountry->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                $oCountry->loadInLang(key($oOtherLang), $soxId);
            }

            $this->oCountry = $oCountry;
            $this->addTplParam("edit", $oCountry);

            // remove already created languages
            $aLang = array_diff(Registry::getLang()->getLanguageNames(), $oOtherLang);

            if (count($aLang)) {
                $this->addTplParam("posslang", $aLang);
            }

            foreach ($oOtherLang as $id => $language) {
                $oLang= new stdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] = clone $oLang;
            }
        } else {
            $this->addTplParam("blForeignCountry", true);
        }

        $this->oShopList = oxNew(ShopList::class);
        /** @var $oShop Shop */
        $oShop = oxNew(Shop::class);
        $sSelect = "SELECT * FROM ".$oShop->getViewName()." WHERE ".$oShop->getSqlActiveSnippet();
        $this->oShopList->selectString($sSelect);
        $this->getLangList();

        return $ret;
    }

    /**
     * @param $sIdent
     * @return mixed
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     */
    public function getModCfgValue($sIdent)
    {
        return d3_cfg_mod::get($this->_sModId)->getValue($sIdent);
    }

    public function saveshop()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST['editval']['oxcountry__d3geoiplang'] = '-1';
            $_POST['editval']['oxcountry__d3geoipcur'] = '-1';
        }

        $this->save();
    }

    public function save()
    {
        //allow malladmin only to perform this action
        if (false == Registry::getConfig()->getConfigParam('blAllowSharedEdit')) {
            return;
        }

        $soxId   = Registry::getRequest()->getRequestEscapedParameter("oxid");
        $aParams = Registry::getRequest()->getRequestEscapedParameter("editval");

        /** @var $oCountry Country */
        $oCountry = oxNew(Country::class);

        if ($soxId != "-1") {
            $oCountry->loadInLang($this->_iEditLang, $soxId);
        } else {
            $aParams['oxcountry__oxid']        = null;
        }

        $oCountry->setLanguage(0);
        $oCountry->assign($aParams);
        $oCountry->setLanguage($this->_iEditLang);
        $oCountry = Registry::get('oxUtilsFile')->processFiles($oCountry);

        $oCountry->save();
        $this->addTplParam("updatelist", "1");

        // set oxid if inserted
        if ($soxId == "-1") {
            Registry::getSession()->setVariable("saved_oxid", $oCountry->getId());
        }
    }

    /**
     * @return ShopList
     */
    public function getShopList()
    {
        return $this->oShopList;
    }

    /**
     * @return array
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     */
    public function getCurList()
    {
        if ($this->getModCfgValue('blChangeShop')
            && $this->oCountry->getFieldData('d3geoipshop')
            && $this->oCountry->getFieldData('d3geoipshop') > 0     // -1 is user choice
        ) {
            $sShopId = $this->oCountry->getFieldData('d3geoipshop');
        } else {
            $sShopId = Registry::getConfig()->getActiveView()->getViewConfig()->getActiveShopId();
        }

        $sQ = "select DECODE( oxvarvalue, '".Registry::getConfig()->getConfigParam('sConfigKey').
            "') as oxvarvalue from oxconfig where oxshopid = '".$sShopId."' AND oxvarname = 'aCurrencies'";

        $oDB = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
        $sCurs = $oDB->getOne($sQ);

        return $this->d3ExtractCurList($sCurs);
    }

    /**
     * @param $sCurrencies
     *
     * @return array
     */
    public function d3ExtractCurList($sCurrencies)
    {
        $aCurrencies = array();

        if ($sCurrencies) {
            foreach (unserialize($sCurrencies) as $sKey => $sValue) {
                $aFields = explode('@', $sValue);
                $cur = new stdClass();
                $cur->id     = trim($sKey);
                $cur->name   = trim($aFields[0]);
                $cur->sign   = trim($aFields[4]);
                $aCurrencies[$sKey] = $cur;
            }
        }

        return $aCurrencies;
    }

    /**
     * ToDo: has to be refactored
     * @return array
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     */
    public function getLangList()
    {
        if ($this->getModCfgValue('blChangeShop')
            && $this->oCountry->getFieldData('d3geoipshop')
            && $this->oCountry->getFieldData('d3geoipshop') > 0     // -1 is user choice
        ) {
            $sShopId = $this->oCountry->getFieldData('d3geoipshop');
        } else {
            $sShopId = Registry::getConfig()->getActiveView()->getViewConfig()->getActiveShopId();
        }

        $aLanguages = array();
        $aLangParams = Registry::getConfig()->getShopConfVar('aLanguageParams', $sShopId);
        $aConfLanguages = Registry::getConfig()->getShopConfVar('aLanguages', $sShopId);

        if (is_array($aConfLanguages)) {
            $i = 0;
            reset($aConfLanguages);
            while ((list($key, $val) = each($aConfLanguages))) {
                if (is_array($aLangParams)) {
                    //skipping non active languages
                    if (false == $aLangParams[$key]['active']) {
                        $i++;
                        continue;
                    }
                }

                if ($val) {
                    $oLang = new stdClass();
                    if (isset($aLangParams[$key]['baseId'])) {
                        $oLang->id  = $aLangParams[$key]['baseId'];
                    } else {
                        $oLang->id  = $i;
                    }
                    $oLang->oxid    = $key;
                    $oLang->abbr    = $key;
                    $oLang->name    = $val;

                    if (is_array($aLangParams)) {
                        $oLang->active  = $aLangParams[$key]['active'];
                        $oLang->sort   = $aLangParams[$key]['sort'];
                    }

                    if ($oLang->active) {
                        $aLanguages[$oLang->id] = $oLang;
                    }
                }
                ++$i;
            }
        }

        if (is_array($aLangParams)) {
            uasort($aLanguages, array($this, '_sortLanguagesCallback'));
        }

        return $aLanguages;
    }

    /**
     * @param $oLang1
     * @param $oLang2
     * @return int
     */
    protected function _sortLanguagesCallback($oLang1, $oLang2)
    {
        $sSortParam = $this->_sDefSort;
        $sVal1 = is_string($oLang1->$sSortParam) ? strtolower($oLang1->$sSortParam) : $oLang1->$sSortParam;
        $sVal2 = is_string($oLang2->$sSortParam) ? strtolower($oLang2->$sSortParam) : $oLang2->$sSortParam;

        if ($this->_sDefSortOrder == 'asc') {
            return ($sVal1 < $sVal2) ? -1 : 1;
        } else {
            return ($sVal1 > $sVal2) ? -1 : 1;
        }
    }
}
