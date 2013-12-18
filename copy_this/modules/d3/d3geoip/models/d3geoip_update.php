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
    public $sModVersion = '3.0.0.1';
    public $sModRevision = '48';
    public $sBaseConf = '4C7a1dCMWRRcFB1TUdmeitQYmxZcmpZem5IRERUVjdlWmo5R1J3T3E0dTRJZy9NUFFpUkhWVTdZTk95Y
2d4ZStmTXpud2tteld3NlFWUEVTUDY4MDhBYVhWUlpRUnZ4RmtwS3FNM2VDeG9IL3JnMkJRWDFmVEM4U
mdCZUd1aGp4WkFPVk1NRjJyR0lLNUF1ZmEwb0J3YTUwdDhjTjRraG9DQit4NjBNRWd2KzdzZjZEQldhc
1dsSUtGK240enBoTzJWb2VpSlg1Yzh5VlhNenc4S1JHSDVoOWJRbEZvYkVCeDhvcnU2NHpZUVdodnNXc
TNjT2tVQ01nVkRxMnZoQXVTL1hrU3ZIRFBWWUdoWWlLMzRFbHhtd0lFbWN5ejA0RHZQRnhwbU5MbHhWM
E09';
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
            'blNull'      => FALSE,
            'sDefault'    => 'oxbaseshop',
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => FALSE,
        ),
        'D3GEOIPLANG' => array(
            'sTableName'  => 'oxcountry',
            'sFieldName'  => 'D3GEOIPLANG',
            'sType'       => 'TINYINT(2)',
            'blNull'      => FALSE,
            'sDefault'    => '-1',
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => FALSE,
        ),
        'D3GEOIPCUR'      => array(
            'sTableName'  => 'oxcountry',
            'sFieldName'  => 'D3GEOIPCUR',
            'sType'       => 'TINYINT(2)',
            'blNull'      => FALSE,
            'sDefault'    => '-1',
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => FALSE,
        ),
        'D3GEOIPURL'      => array(
            'sTableName'  => 'oxcountry',
            'sFieldName'  => 'D3GEOIPURL',
            'sType'       => 'VARCHAR(255)',
            'blNull'      => FALSE,
            'sDefault'    => FALSE,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => FALSE,
        ),
        'D3STARTIP'        => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3STARTIP',
            'sType'       => 'VARCHAR(39)',
            'blNull'      => FALSE,
            'sDefault'    => FALSE,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => FALSE,
        ),
        'D3ENDIP'    => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3ENDIP',
            'sType'       => 'VARCHAR(39)',
            'blNull'      => FALSE,
            'sDefault'    => FALSE,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => FALSE,
        ),
        'D3STARTIPNUM'    => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3STARTIPNUM',
            'sType'       => 'DECIMAL(38,0)',
            'blNull'      => FALSE,
            'sDefault'    => FALSE,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => FALSE,
        ),
        'D3ENDIPNUM'    => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3ENDIPNUM',
            'sType'       => 'DECIMAL(38,0)',
            'blNull'      => FALSE,
            'sDefault'    => FALSE,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => FALSE,
        ),
        'D3ISO'    => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3ISO',
            'sType'       => 'CHAR(2)',
            'blNull'      => FALSE,
            'sDefault'    => FALSE,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => FALSE,
        ),
        'D3COUNTRYNAME'    => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3COUNTRYNAME',
            'sType'       => 'VARCHAR(50)',
            'blNull'      => FALSE,
            'sDefault'    => FALSE,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => FALSE,
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
        return $this->_checkTableExist('d3geoip');
    }

    /**
     * @return bool
     */
    public function updateGeoIpTableExist()
    {
        $blRet = FALSE;
        if ($this->checkGeoIpTableExist())
        {
            $aRet  = $this->_addTable('d3geoip', $this->aFields, $this->aIndizes, 'GeoIP', 'MyISAM');
            $blRet = $aRet['blRet'];
            $this->_setActionLog('SQL', $aRet['sql'], __METHOD__);
            $this->_setUpdateBreak(TRUE);
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
        $blRet = FALSE;
        foreach ($this->_getShopList() as $oShop)
        {
            /** @var $oShop oxshop */
            $aWhere = array(
                'oxmodid'       => $this->sModKey,
                'oxnewrevision' => $this->sModRevision,
                'oxshopid'      => $oShop->getId(),
            );

            $blRet = $this->_checkTableItemNotExist('d3_cfg_mod', $aWhere);

            if ($blRet)
            {
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
        $blRet = FALSE;

        if ($this->checkModCfgItemExist())
        {
            foreach ($this->_getShopList() as $oShop)
            {
                /** @var $oShop oxshop */
                $aWhere = array(
                    'oxmodid'       => $this->sModKey,
                    'oxshopid'      => $oShop->getId(),
                    'oxnewrevision' => $this->sModRevision,
                );

                if ($this->_checkTableItemNotExist('d3_cfg_mod', $aWhere))
                {
                    // update don't use this property
                    unset($aWhere['oxnewrevision']);

                    $aInsertFields = array(
                        array (
                            'fieldname'     => 'OXID',
                            'content'       => "md5('" . $this->sModKey . " " . $oShop->getId() . " de')",
                            'force_update'  => TRUE,
                            'use_quote'     => FALSE,
                            'use_multilang' => FALSE,
                        ),
                        array (
                            'fieldname'     => 'OXSHOPID',
                            'content'       => $oShop->getId(),
                            'force_update'  => TRUE,
                            'use_quote'     => TRUE,
                            'use_multilang' => FALSE,
                        ),
                        array (
                            'fieldname'     => 'OXMODID',
                            'content'       => $this->sModKey,
                            'force_update'  => TRUE,
                            'use_quote'     => TRUE,
                            'use_multilang' => FALSE,
                        ),
                        array (
                            'fieldname'     => 'OXNAME',
                            'content'       => $this->sModName,
                            'force_update'  => TRUE,
                            'use_quote'     => TRUE,
                            'use_multilang' => FALSE,
                        ),
                        array (
                            'fieldname'     => 'OXACTIVE',
                            'content'       => "0",
                            'force_update'  => FALSE,
                            'use_quote'     => FALSE,
                            'use_multilang' => FALSE,
                        ),
                        array (
                            'fieldname'     => 'OXBASECONFIG',
                            'content'       => $this->sBaseConf,
                            'force_update'  => TRUE,
                            'use_quote'     => TRUE,
                            'use_multilang' => FALSE,
                        ),
                        array (
                            'fieldname'     => 'OXSERIAL',
                            'content'       => "",
                            'force_update'  => FALSE,
                            'use_quote'     => TRUE,
                            'use_multilang' => FALSE,
                        ),
                        array (
                            'fieldname'     => 'OXINSTALLDATE',
                            'content'       => "NOW()",
                            'force_update'  => TRUE,
                            'use_quote'     => FALSE,
                            'use_multilang' => FALSE,
                        ),
                        array (
                            'fieldname'     => 'OXVERSION',
                            'content'       => $this->sModVersion,
                            'force_update'  => TRUE,
                            'use_quote'     => TRUE,
                            'use_multilang' => FALSE,
                        ),
                        array (
                            'fieldname'     => 'OXSHOPVERSION',
                            'content'       => oxRegistry::getConfig()->getEdition(),
                            'force_update'  => TRUE,
                            'use_quote'     => TRUE,
                            'use_multilang' => FALSE,
                        ),
                        array (
                            'fieldname'     => 'OXREQUIREMENTS',
                            'content'       => $this->sRequirements,
                            'force_update'  => TRUE,
                            'use_quote'     => TRUE,
                            'use_multilang' => FALSE,
                        ),
                        array(
                            'fieldname'     => 'OXVALUE',
                            'content'       => $this->sBaseValue,
                            'force_update'  => FALSE,
                            'use_quote'     => TRUE,
                            'use_multilang' => FALSE,
                        ),
                        array(
                            'fieldname'     => 'OXNEWREVISION',
                            'content'       => $this->sModRevision,
                            'force_update'  => TRUE,
                            'use_quote'     => TRUE,
                            'use_multilang' => FALSE,
                        )
                    );
                    $aRet          = $this->_updateTableItem('d3_cfg_mod', $aInsertFields, $aWhere);
                    $blRet         = $aRet['blRet'];

                    $this->_setActionLog('SQL', $aRet['sql'], __METHOD__);
                    $this->_setUpdateBreak(FALSE);

                    if ($this->getStepByStepMode())
                    {
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
        $oShop = $this->_getShopList()->current();
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
        $oShop = $this->_getShopList()->current();
        $this->aFields['D3GEOIPSHOP']['sDefault'] = $oShop->getId();

        return $this->fixFields();
    }
}