<?php

class d3_country_geoip extends oxAdminView
{
    protected $_sDefSort = 'sort';

    protected $_sThisTemplate = 'd3_country_geoip.tpl';

    private $_oSet;

    private $_sModId = 'd3_geoip';

    public function render()
    {
        $this->_oSet = d3_cfg_mod::get($this->_sModId);

        $myConfig = $this->getConfig();

            if ( !$myConfig->getConfigParam( 'blAllowSharedEdit' ) )
                $this->_aViewData['readonly'] = true;

        $ret = parent::render();

        $soxId = oxConfig::getParameter( "oxid");
        // check if we right now saved a new entry
        $sSavedID = oxConfig::getParameter( "saved_oxid");
        if ( ($soxId == "-1" || !isset( $soxId)) && isset( $sSavedID) ) {
            $soxId = $sSavedID;
            oxSession::deleteVar( "saved_oxid");
            $this->_aViewData["oxid"] =  $soxId;
            // for reloading upper frame
            $this->_aViewData["updatelist"] =  "1";
        }

        if ( $soxId != "-1" && isset( $soxId)) {
            // load object
            $oCountry = oxNew( "oxcountry" );
            $oCountry->loadInLang( $this->_iEditLang, $soxId );

            if ($oCountry->isForeignCountry()) {
                $this->_aViewData["blForeignCountry"] = true;
            } else {
                $this->_aViewData["blForeignCountry"] = false;
            }

            $oOtherLang = $oCountry->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oCountry->loadInLang( key($oOtherLang), $soxId );
            }
            $this->_aViewData["edit"] = $this->oCountry =  $oCountry;

            // remove already created languages
            $aLang = array_diff (oxLang::getInstance()->getLanguageNames(), $oOtherLang );
            if ( count( $aLang))
                $this->_aViewData["posslang"] = $aLang;

            foreach ( $oOtherLang as $id => $language) {
                $oLang= new oxStdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] = clone $oLang;
            }
        } else {
            $this->_aViewData["blForeignCountry"] = true;
        }

        $this->oShopList = &oxNew('oxshoplist');
        $oShop = &oxNew('oxshop');
        $sSelect = "SELECT * FROM ".$oShop->getViewName()." WHERE ".$oShop->getSqlActiveSnippet();

        $this->oShopList->selectString($sSelect);

        $this->getLangList();

        return $ret;
    }

    public function getModCfgValue($sIdent)
    {
        $this->_oSet = d3_cfg_mod::get($this->_sModId);
        return $this->_oSet->getValue($sIdent);
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
        $myConfig  = $this->getConfig();

        //allow malladmin only to perform this action
        if ( !$myConfig->getConfigParam( 'blAllowSharedEdit' ) )
            return;

        $soxId   = oxConfig::getParameter( "oxid");
        $aParams = oxConfig::getParameter( "editval" );

        $oCountry = oxNew( "oxcountry" );

        if ( $soxId != "-1") {
            $oCountry->loadInLang( $this->_iEditLang, $soxId );
        } else {
            $aParams['oxcountry__oxid']        = null;
        }

        //$aParams = $oCountry->ConvertNameArray2Idx( $aParams);
        $oCountry->setLanguage(0);
        $oCountry->assign( $aParams );
        $oCountry->setLanguage($this->_iEditLang);
        $oCountry = oxUtilsFile::getInstance()->processFiles( $oCountry );

        $oCountry->save();
        $this->_aViewData["updatelist"] = "1";

        // set oxid if inserted
        if ( $soxId == "-1")
            oxSession::setVar( "saved_oxid", $oCountry->oxcountry__oxid->value);
    }

    public function getShopList()
    {
        return $this->oShopList;
    }

    public function getCurList()
    {
        $aCurrencies = array();

        if ($this->getModCfgValue('blChangeShop') && $this->oCountry->getFieldData('d3geoipshop'))
        {
            $sShopId = $this->oCountry->getFieldData('d3geoipshop');
        }
        else
        {
            $sShopId = $this->getConfig()->getActiveView()->getViewConfig()->getActiveShopId();
        }

        $sQ = "select DECODE( oxvarvalue, '".$this->getConfig()->getConfigParam( 'sConfigKey' )."') as oxvarvalue from oxconfig where oxshopid = '".$sShopId."' AND oxvarname = 'aCurrencies'";

        $sCurs = oxDb::getDb(2)->getOne($sQ);
        foreach (unserialize($sCurs) as $sKey => $sValue)
        {
            $aFields = explode('@', $sValue);
            $aCurrencies[$sKey]->id = $sKey;
            $aCurrencies[$sKey]->name  = $aFields[0];
            $aCurrencies[$sKey]->sign = $aFields[4];
        }

        return $aCurrencies;
    }

    public function getLangList()
    {
        if ($this->getModCfgValue('blChangeShop') && $this->oCountry->getFieldData('d3geoipshop'))
        {
            $sShopId = $this->oCountry->getFieldData('d3geoipshop');
        }
        else
        {
            $sShopId = $this->getConfig()->getActiveView()->getViewConfig()->getActiveShopId();
        }

        $aLanguages = array();
        $aLangParams = $this->getConfig()->getShopConfVar('aLanguageParams', $sShopId);
        $aConfLanguages = $this->getConfig()->getShopConfVar('aLanguages', $sShopId);

        if ( is_array( $aConfLanguages ) ) {
            $i = 0;
            reset( $aConfLanguages );
            while ( list( $key, $val ) = each( $aConfLanguages ) ) {

                if ( $blOnlyActive && is_array($aLangParams) ) {
                    //skipping non active languages
                    if ( !$aLangParams[$key]['active'] ) {
                        $i++;
                    	continue;
                    }
                }

                if ( $val) {
                    $oLang = new oxStdClass();
                    if ( isset($aLangParams[$key]['baseId']) ) {
                        $oLang->id  = $aLangParams[$key]['baseId'];
                    } else {
                        $oLang->id  = $i;
                    }
                    $oLang->oxid    = $key;
                    $oLang->abbr    = $key;
                    $oLang->name    = $val;

                    if ( is_array($aLangParams) ) {
                        $oLang->active  = $aLangParams[$key]['active'];
                        $oLang->sort   = $aLangParams[$key]['sort'];
                    }

                    if ( isset( $iLanguage ) && $oLang->id == $iLanguage ) {
                        $oLang->selected = 1;
                    } else {
                        $oLang->selected = 0;
                    }
                    if ($oLang->active)
                        $aLanguages[$oLang->id] = $oLang;
                }
                ++$i;
            }
        }

        if ( $blSort && is_array($aLangParams) ) {
            uasort( $aLanguages, array($this, '_sortLanguagesCallback') );
        }

        return $aLanguages;
    }

    protected function _sortLanguagesCallback( $oLang1, $oLang2 )
    {
        $sSortParam = $this->_sDefSort;
        $sVal1 = is_string($oLang1->$sSortParam) ? strtolower( $oLang1->$sSortParam ) : $oLang1->$sSortParam;
        $sVal2 = is_string($oLang2->$sSortParam) ? strtolower( $oLang2->$sSortParam ) : $oLang2->$sSortParam;

        if ( $this->_sDefSortOrder == 'asc' ) {
            return ($sVal1 < $sVal2) ? -1 : 1;
        } else {
            return ($sVal1 > $sVal2) ? -1 : 1;
        }
    }

}