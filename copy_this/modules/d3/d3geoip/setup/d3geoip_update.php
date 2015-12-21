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
 * @copyright � D� Data Development, Thomas Dartsch
 * @author    D� Data Development - Daniel Seifert <ds@shopmodule.com>
 * @link      http://www.oxidmodule.com
 */

class d3geoip_update extends d3install_updatebase
{
    public $sModKey = 'd3_geoip';
    public $sModName = 'GeoIP';
    public $sModVersion = '3.0.1.0';
    public $sModRevision = '67';
    public $sBaseConf = '5FibjlIQlRvbWMzY29mVi85RXFxbkc5bFI3R24rNkd5Y0lEcXJFOGhtaGExRVcyaEF6a281cVhRUXFMU
0d6dnNDbCtLRVdObFh3bnVEdUNRTFJrVlE5VGtsRkF3Tyt4TU1Pd290WDliOTQ2SUE5Skk0eTcxTGdlT
XZna0dhS2ZOekJUSC94ZUd5YmxXZzRXcG5QSWcvZnFJa1l0N1MrdWRZaFU1VG5nUGFwNEF1WTh6azNja
Gs3SmtzQ05SVjROSGdSZ2N0S3Q1TTV1RGlCSU5RZnh1dGVNNVd6bTBzMU1FdHFiNytJQldBRjRTNmx3U
VVlRkhrRzYxYUtpaWNBOUUrLzZ2YjN5SDM1cllVMTIrYUFPRnRYcFpibHVBQytEQVFnNFV0RWpFZXAwd
FE9';
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
              'do'    => 'registerComponent'),
        array('check' => 'checkModCfgSameRevision',
              'do'    => 'updateModCfgSameRevision'),
    );

    // Standardwerte f�r checkMultiLangTables() und fixRegisterMultiLangTables()
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
        $sVarName = 'aUserComponentNames';
        $sModuleId = '';
        /** @var $oShop oxshop */
        foreach ($this->getShopListByActiveModule('d3geoip') as $oShop) {
            /** @var array $aUserComponents */
            $aUserComponents = oxRegistry::getConfig()->getShopConfVar($sVarName, $oShop->getId(), $sModuleId);

            if (false == $aUserComponents
                || false == $aUserComponents['d3cmp_geoip']
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function registerComponent()
    {
        $blRet = true;
        $sVarName = 'aUserComponentNames';
        $sModuleId = '';

        /** @var $oShop oxshop */
        foreach ($this->getShopList() as $oShop) {
            $aUserComponents = oxRegistry::getConfig()->getShopConfVar($sVarName, $oShop->getId(), $sModuleId);
            if (false == $aUserComponents) {
                $aUserComponents = array();
            }

            if (false == $aUserComponents['d3cmp_geoip']) {
                $blDontUseCache = 1;
                $aUserComponents['d3cmp_geoip'] = $blDontUseCache;
                $this->fixOxconfigVariable($sVarName, $oShop->getId(), '', $aUserComponents, 'arr');
            }
        }

        return $blRet;
    }
}
