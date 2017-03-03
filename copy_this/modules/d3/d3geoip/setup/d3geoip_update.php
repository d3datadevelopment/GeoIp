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
 * @copyright © D³ Data Development, Thomas Dartsch
 * @author    D³ Data Development - Daniel Seifert <ds@shopmodule.com>
 * @link      http://www.oxidmodule.com
 */

class d3geoip_update extends d3install_updatebase
{
    public $sModKey = 'd3_geoip';
    public $sModName = 'GeoIP';
    public $sModVersion = '3.0.2.1';
    public $sModRevision = '75';
    public $sBaseConf = 'sw2M1p3STcxMXh0eDhyR1A3YUJDMDVDakZUM2FnakVDOWdwRm9SQzFiMzhqUEtNTTFXK2ZmSlhqd1k2a
DJiSi9QSjRTQUhwOXgrMzVOOVRKRWM1c1FUci9OMUtJR2xxQ0JUTzd3V3FaSEFneGRRL3FOOU1sS09ib
FpDSjBrTUJDQTh6R2pScnZpOXphaUtlZUovQ1UzNlN5di9LeXBIYmlTQzRTRlA0czRBRmVibzZOY1R1V
lNZVUE1R3hTa2drOGx0a09HN3JhMkx1OVJvcnN5OHUvbVhqWUVURVhGanBva29GOUY3amNTcXhsTVdRM
W8vRWJtYW9IY0NqdDBlSEtwOFRJMGQ1OElxeG13bDVySGFub1ovYkkrWEtvSm1HY3RMVXlPd0pJU2dmW
Vk9';
    public $sRequirements = '';
    public $sBaseValue = '';

    protected $_aUpdateMethods = array(
        array('check' => 'checkGeoIpTableExist',
              'do'    => 'updateGeoIpTableExist'),
        array('check' => 'checkGeoIpItems',
              'do'    => 'updateGeoIpItems'),
        array('check' => 'checkModCfgItemExist',
              'do'    => 'updateModCfgItemExist'),
        array('check' => 'checkGeoIpFields',
              'do'    => 'fixGeoIpFields'),
        array('check' => 'checkIndizes',
              'do'    => 'fixIndizes'),
        array('check' => 'hasUnregisteredFiles',
              'do'    => 'showUnregisteredFiles'),
        array('check' => 'checkRegisteredComponent',
              'do'    => 'unregisterComponent'),
        array('check' => 'checkModCfgSameRevision',
              'do'    => 'updateModCfgSameRevision'),
    );

    // Standardwerte für checkMultiLangTables() und fixRegisterMultiLangTables()
    public $aMultiLangTables = array();

    public $aFields = array(
        'D3GEOIPSHOP'        => array(
            'sTableName'  => 'oxcountry',
            'sFieldName'  => 'D3GEOIPSHOP',
            'sType'       => 'VARCHAR(10)',
            'blNull'      => false,
            'sDefault'    => 'oxbaseshop',
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => false,
        ),
        'D3GEOIPLANG' => array(
            'sTableName'  => 'oxcountry',
            'sFieldName'  => 'D3GEOIPLANG',
            'sType'       => 'TINYINT(2)',
            'blNull'      => false,
            'sDefault'    => '-1',
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => false,
        ),
        'D3GEOIPCUR'      => array(
            'sTableName'  => 'oxcountry',
            'sFieldName'  => 'D3GEOIPCUR',
            'sType'       => 'TINYINT(2)',
            'blNull'      => false,
            'sDefault'    => '-1',
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => false,
        ),
        'D3GEOIPURL'      => array(
            'sTableName'  => 'oxcountry',
            'sFieldName'  => 'D3GEOIPURL',
            'sType'       => 'VARCHAR(255)',
            'blNull'      => false,
            'sDefault'    => false,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => false,
        ),
        'D3STARTIP'        => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3STARTIP',
            'sType'       => 'VARCHAR(39)',
            'blNull'      => false,
            'sDefault'    => false,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => false,
        ),
        'D3ENDIP'    => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3ENDIP',
            'sType'       => 'VARCHAR(39)',
            'blNull'      => false,
            'sDefault'    => false,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => false,
        ),
        'D3STARTIPNUM'    => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3STARTIPNUM',
            'sType'       => 'DECIMAL(38,0)',
            'blNull'      => false,
            'sDefault'    => false,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => false,
        ),
        'D3ENDIPNUM'    => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3ENDIPNUM',
            'sType'       => 'DECIMAL(38,0)',
            'blNull'      => false,
            'sDefault'    => false,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => false,
        ),
        'D3ISO'    => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3ISO',
            'sType'       => 'CHAR(2)',
            'blNull'      => false,
            'sDefault'    => false,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => false,
        ),
        'D3COUNTRYNAME'    => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3COUNTRYNAME',
            'sType'       => 'VARCHAR(50)',
            'blNull'      => false,
            'sDefault'    => false,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => false,
        ),
    );

    public $aIndizes = array(
        'IPNUM' => array(
            'sTableName' => 'd3geoip',
            'sType'      => 'INDEX',
            'sName'      => 'IPNUM',
            'aFields'    => array(
                'D3STARTIPNUM' => 'D3STARTIPNUM',
                'D3ENDIPNUM' => 'D3ENDIPNUM',
            ),
        ),
        'D3ISO' => array(
            'sTableName' => 'd3geoip',
            'sType'      => 'INDEX',
            'sName'      => 'D3ISO',
            'aFields'    => array(
                'D3ISO' => 'D3ISO',
            ),
        ),
    );

    /**
     * @return bool
     */
    public function checkGeoIpTableExist()
    {
        return $this->_checkTableNotExist('d3geoip');
    }

    /**
     * @return bool
     */
    public function updateGeoIpTableExist()
    {
        $blRet = false;
        if ($this->checkGeoIpTableExist()) {
            $blRet  = $this->_addTable2('d3geoip', $this->aFields, $this->aIndizes, 'GeoIP', 'MyISAM');
        }

        return $blRet;
    }

    /**
     * @return bool
     */
    public function checkGeoIpItems()
    {
        /** @var $oShop oxshop */
        $aWhere = array(
            'D3ISO' => 'DE',
        );

        $blRet = $this->_checkTableItemNotExist('d3geoip', $aWhere);

        return $blRet;
    }

    /**
     * @return bool
     */
    public function updateGeoIpItems()
    {
        return $this->_confirmMessage('D3_GEOIP_UPDATE_ITEMINSTALL');
    }

    /**
     * @return bool
     */
    public function checkModCfgItemExist()
    {
        $blRet = false;
        foreach ($this->getShopList() as $oShop) {
            /** @var $oShop oxshop */
            $aWhere = array(
                'oxmodid'       => $this->sModKey,
                'oxnewrevision' => $this->sModRevision,
                'oxshopid'      => $oShop->getId(),
            );

            $blRet = $this->_checkTableItemNotExist('d3_cfg_mod', $aWhere);

            if ($blRet) {
                return $blRet;
            }
        }

        return $blRet;
    }

    /**
     * @return bool
     */
    public function updateModCfgItemExist()
    {
        $blRet = false;

        if ($this->checkModCfgItemExist()) {
            foreach ($this->getShopList() as $oShop) {
                /** @var $oShop oxshop */
                $aWhere = array(
                    'oxmodid'       => $this->sModKey,
                    'oxshopid'      => $oShop->getId(),
                    'oxnewrevision' => $this->sModRevision,
                );

                if ($this->_checkTableItemNotExist('d3_cfg_mod', $aWhere)) {
                    // update don't use this property
                    unset($aWhere['oxnewrevision']);

                    $aInsertFields = array(
                        array (
                            'fieldname'     => 'OXID',
                            'content'       => "md5('" . $this->sModKey . " " . $oShop->getId() . " de')",
                            'force_update'  => true,
                            'use_quote'     => false,
                            'use_multilang' => false,
                        ),
                        array (
                            'fieldname'     => 'OXSHOPID',
                            'content'       => $oShop->getId(),
                            'force_update'  => true,
                            'use_quote'     => true,
                            'use_multilang' => false,
                        ),
                        array (
                            'fieldname'     => 'OXMODID',
                            'content'       => $this->sModKey,
                            'force_update'  => true,
                            'use_quote'     => true,
                            'use_multilang' => false,
                        ),
                        array (
                            'fieldname'     => 'OXNAME',
                            'content'       => $this->sModName,
                            'force_update'  => true,
                            'use_quote'     => true,
                            'use_multilang' => false,
                        ),
                        array (
                            'fieldname'     => 'OXACTIVE',
                            'content'       => "0",
                            'force_update'  => false,
                            'use_quote'     => false,
                            'use_multilang' => false,
                        ),
                        array (
                            'fieldname'     => 'OXBASECONFIG',
                            'content'       => $this->sBaseConf,
                            'force_update'  => true,
                            'use_quote'     => true,
                            'use_multilang' => false,
                        ),
                        array (
                            'fieldname'     => 'OXSERIAL',
                            'content'       => "",
                            'force_update'  => false,
                            'use_quote'     => true,
                            'use_multilang' => false,
                        ),
                        array (
                            'fieldname'     => 'OXINSTALLDATE',
                            'content'       => "NOW()",
                            'force_update'  => true,
                            'use_quote'     => false,
                            'use_multilang' => false,
                        ),
                        array (
                            'fieldname'     => 'OXVERSION',
                            'content'       => $this->sModVersion,
                            'force_update'  => true,
                            'use_quote'     => true,
                            'use_multilang' => false,
                        ),
                        array (
                            'fieldname'     => 'OXSHOPVERSION',
                            'content'       => oxRegistry::getConfig()->getEdition(),
                            'force_update'  => true,
                            'use_quote'     => true,
                            'use_multilang' => false,
                        ),
                        array (
                            'fieldname'     => 'OXREQUIREMENTS',
                            'content'       => $this->sRequirements,
                            'force_update'  => true,
                            'use_quote'     => true,
                            'use_multilang' => false,
                        ),
                        array(
                            'fieldname'     => 'OXVALUE',
                            'content'       => $this->sBaseValue,
                            'force_update'  => false,
                            'use_quote'     => true,
                            'use_multilang' => false,
                        ),
                        array(
                            'fieldname'     => 'OXNEWREVISION',
                            'content'       => $this->sModRevision,
                            'force_update'  => true,
                            'use_quote'     => true,
                            'use_multilang' => false,
                        )
                    );
                    $blRet          = $this->_updateTableItem2('d3_cfg_mod', $aInsertFields, $aWhere);

                    if ($this->getStepByStepMode()) {
                        break;
                    }
                }
            }
        }
        return $blRet;
    }

    /**
     * change default value for shop id in EE
     * @return bool
     */
    public function checkGeoIpFields()
    {
        /** @var $oShop oxshop */
        $oShop = $this->getShopList()->current();
        $this->aFields['D3GEOIPSHOP']['sDefault'] = $oShop->getId();

        return $this->checkFields();
    }

    /**
     * change default value for shop id in EE
     * @return bool
     */
    public function fixGeoIpFields()
    {
        /** @var $oShop oxshop */
        $oShop = $this->getShopList()->current();
        $this->aFields['D3GEOIPSHOP']['sDefault'] = $oShop->getId();

        return $this->fixFields();
    }

    /**
     * @return bool
     */
    public function hasUnregisteredFiles()
    {
        return $this->_hasUnregisteredFiles('d3geoip', array('d3FileRegister'));
    }

    /**
     * @return bool
     */
    public function showUnregisteredFiles()
    {
        return $this->_showUnregisteredFiles('d3geoip', array('d3FileRegister'));
    }

    /**
     * @return bool
     */
    public function checkRegisteredComponent()
    {
        /** @var $oShop oxshop */
        foreach ($this->getShopListByActiveModule('d3geoip') as $oShop) {
            $aUserComponents = $this->_d3GetUserComponentsFromDb($oShop);

            if (is_array($aUserComponents)
                && in_array('d3cmp_geoip', array_keys($aUserComponents))
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function unregisterComponent()
    {
        $blRet = true;
        $sVarName = 'aUserComponentNames';

        /** @var $oShop oxshop */
        foreach ($this->getShopList() as $oShop) {
            $aUserComponents = $this->_d3GetUserComponentsFromDb($oShop);

            if (is_array($aUserComponents)
                && in_array('d3cmp_geoip', array_keys($aUserComponents))
            ) {
                unset($aUserComponents['d3cmp_geoip']);
                if (false == count($aUserComponents)) {
                    $aUserComponents = null;
                }
                $this->fixOxconfigVariable($sVarName, $oShop->getId(), '', $aUserComponents, 'arr');
            }
        }

        return $blRet;
    }

    /**
     * @param oxShop $oShop
     * @return array|null
     */
    protected function _d3GetUserComponentsFromDb(oxShop $oShop)
    {
        $sVarName = 'aUserComponentNames';
        $sModuleId = '';
        $oDb = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);
        $sSelect = "SELECT oxvartype as type, ".oxRegistry::getConfig()->getDecodeValueQuery().
            " as value FROM `oxconfig` WHERE oxshopid = ".$oDb->quote($oShop->getId()).
            " AND oxvarname = ".$oDb->quote($sVarName).
            " AND oxmodule = ".$oDb->quote($sModuleId);

        $aResult = $oDb->getAll($sSelect);
        $aUserComponents = is_array($aResult) && count($aResult)
            ? oxRegistry::getConfig()->decodeValue($aResult[0]['type'], $aResult[0]['value'])
            : null;

        return $aUserComponents;
    }
}
