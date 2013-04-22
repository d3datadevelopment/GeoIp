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
    public $sModVersion = '3.0.0.0';
    public $sModRevision = '642';
    public $sBaseConf = 'Kg5ci9jYmdnZDhjaVErQW5hUThmQ2ZyYmtudnBhTWtHRWdQdzhWbDdQbkJTSGZ5VVFPN0x3Umt0NUJCU
G1DQTI5NEhkVXFpOHNnY1VVNVdpT1dTblVZYWJQY214Q2pxNlNqRUhtTlU0bnRxN2JacGR5M01uOTUvO
VFnMDlXSm5ScHlNeDByVktnNE8yM1IyV0FhN0FpMG92alo1bDBnTGN4dUFRMUZVbWNrb010ZnJkVXBNd
GNmYmphUDVTcG82Q3J2M0VkZVAraUtxb256aS9NWCsrK2VVWmUxempPanNMOEhEWmU3WTZKNkhTMGxKY
0RRUUF2dDIrb2s3T1NPVEdQY25BN1hLcDl1UVdKNE5zU0xxMXFULzZGY3BkMkRyUXJ0NklxUmtBOG1DQ
nc9';
    public $sRequirements = '';
    public $sBaseValue = '';

    protected $_aUpdateMethods = array(
        array('check' => 'checkGeoIpTableExist',
              'do'    => 'updateGeoIpTableExist'),
        array('check' => 'checkGeoIpItems',
              'do'    => 'updateGeoIpItems'),
        array('check' => 'checkModCfgItemExist',
              'do'    => 'updateModCfgItemExist'),
        array('check' => 'checkFields',
              'do'    => 'fixFields'),
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
            'sType'       => 'CHAR(15)',
            'blNull'      => FALSE,
            'sDefault'    => FALSE,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => FALSE,
        ),
        'D3ENDIP'    => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3ENDIP',
            'sType'       => 'CHAR(15)',
            'blNull'      => FALSE,
            'sDefault'    => FALSE,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => FALSE,
        ),
        'D3STARTIPNUM'    => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3STARTIPNUM',
            'sType'       => 'INT(10) unsigned',
            'blNull'      => FALSE,
            'sDefault'    => FALSE,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => FALSE,
        ),
        'D3ENDIPNUM'    => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3ENDIPNUM',
            'sType'       => 'INT(10) unsigned',
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
                );
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
        return $blRet;
    }
}