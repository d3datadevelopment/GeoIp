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
    public $sModVersion = '3.1.0.0';
    public $sModRevision = '3100';
    public $sBaseConf = '9qGv2==ZmhGbXhxSWUvZ25tSjFMV3J5aTExOTdhdWg2QkdrdHJBT09CajlXUzRDOGdMZ0YraEp1N0xPc
WkyOGhMZDBKMmwyUldaOXVrNDNjOWtMRW9BZEJ3VjY5NWQyMmZ3VG9qdHhRSVF4NXYvUGZqUmpxZm1Td
i9HbkxNZDRxVzNRRlZrRForU0RMSHVNR25hR1ROMkt0ZUM0SXpnKzBYUHJnc0Y1dDJYaDNIMEsrWE9uR
1FkbmJ4VUlMSGd1UEYxT0pPdGJhazQ4b1l6WDBBRElkM1dTWFM4VFg4S0didCs1aG1lNG9QYTh4YmVZe
XhDL3N0aXFncEhHWDJqNFFyQ0VDTFlkWUFVR20zU2RuZ05NUnREZythR2x1Z2VQOTYvbXNHZVVHMW9zN
UUrZ3pOeW9TUmo3MDg4dGx0RkRUK0wzb2k=';
    public $sRequirements = '';
    public $sBaseValue = '';

    public $sMinModCfgVersion = '4.4.1.0';
    
    protected $_aUpdateMethods = array(
        array('check' => 'checkGeoIpTableExist',
              'do'    => 'updateGeoIpTableExist'),
        array('check' => 'checkModCfgItemExist',
              'do'    => 'updateModCfgItemExist'),
        array('check' => 'hasDeleteGeoIpTableFields',
              'do'    => 'deleteGeoIpTableFields'),
        array('check' => 'checkGeoIpFields',
              'do'    => 'fixGeoIpFields'),
        array('check' => 'checkIndizes',
              'do'    => 'fixIndizes'),
        array('check' => 'checkGeoIpTableEngine',
              'do'    => 'updateGeoIpTableEngine'),
        array('check' => 'checkGeoIpItems',
              'do'    => 'updateGeoIpItems'),
        array('check' => 'hasUnregisteredFiles',
              'do'    => 'showUnregisteredFiles'),
        array('check' => 'checkRegisteredComponent',
              'do'    => 'unregisterComponent'),
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
        'D3IP'        => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3IP',
            'sType'       => 'VARCHAR(45)',
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
            'sFieldName'  => 'D3STARTIPBIN',
            'sType'       => 'VARBINARY(16)',
            'blNull'      => false,
            'sDefault'    => false,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => false,
        ),
        'D3ENDIPNUM'    => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3ENDIPBIN',
            'sType'       => 'VARBINARY(16)',
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
            'blNull'      => true,
            'sDefault'    => false,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => false,
        ),
        'D3COUNTRYNAME'    => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3COUNTRYNAME',
            'sType'       => 'VARCHAR(50)',
            'blNull'      => true,
            'sDefault'    => false,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => false,
        ),
        'D3CONTINENTCODE'    => array(
            'sTableName'  => 'd3geoip',
            'sFieldName'  => 'D3CONTINENTCODE',
            'sType'       => 'CHAR(2)',
            'blNull'      => true,
            'sDefault'    => false,
            'sComment'    => '',
            'sExtra'      => '',
            'blMultilang' => false,
        ),
    );

    public $aIndizes = array(
        'PRIMARY' => array(
            'sTableName' => 'd3geoip',
            'sType'      => 'PRIMARY',
            'sName'      => 'PRIMARY',
            'aFields'    => array(
                'D3IP'   => 'D3IP',
            ),
        ),
        'IPBIN' => array(
            'sTableName' => 'd3geoip',
            'sType'      => 'INDEX',
            'sName'      => 'IPBIN',
            'aFields'    => array(
                'D3STARTIPNUM' => 'D3STARTIPBIN',
                'D3ENDIPNUM' => 'D3ENDIPBIN',
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

    public $aDeleteFields = array(
    	'D3STARTIPNUM' => array(
    		'sTableName'    => 'd3geoip',
    		'sFieldName'    => 'D3STARTIPNUM',
    		'blMultilang'   => false,
	    ),
    	'D3ENDIPNUM' => array(
    		'sTableName'    => 'd3geoip',
    		'sFieldName'    => 'D3ENDIPNUM',
    		'blMultilang'   => false,
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
            $blRet  = $this->_addTable2('d3geoip', $this->aFields, $this->aIndizes, 'GeoIP', 'InnoDB');
        }

        return $blRet;
    }

	/**
	 * @return bool
	 * @throws oxSystemComponentException
	 */
    public function hasDeleteGeoIpTableFields()
    {
    	/** @var d3installdbfield $oInstallDbField */
    	$oInstallDbField = oxNew('d3installdbfield', $this);
    	return $oInstallDbField->checkDeleteFields();
    }

	/**
	 * @return bool
	 * @throws oxSystemComponentException
	 */
    public function deleteGeoIpTableFields()
    {
        $blRet = false;
        if ($this->hasDeleteGeoIpTableFields()) {
	        /** @var d3installdbfield $oInstallDbField */
	        $oInstallDbField = oxNew('d3installdbfield', $this);
            $blRet  = $oInstallDbField->fixDeleteFields(__METHOD__);
        }

        return $blRet;
    }

	/**
	 * @return bool true, if table has wrong engine
	 * @throws oxSystemComponentException
	 */
	public function checkGeoIpTableEngine()
	{
		/** @var d3installdbtable $oDbTable */
		$oDbTable = oxNew('d3installdbtable', $this);
		$aData = $oDbTable->getTableData('d3geoip');

		if (isset($aData) && count($aData) && isset($aData['ENGINE']) && $aData['ENGINE'] == 'InnoDB') {
			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 * @throws oxSystemComponentException
	 */
	public function updateGeoIpTableEngine()
	{
		/** @var d3installdbtable $oDbTable */
		$oDbTable = oxNew('d3installdbtable', $this);
		$blRet = $oDbTable->changeTableEngine('d3geoip', 'InnoDB');
		return $blRet;
	}

    /**
     * @return bool
     */
    public function checkGeoIpItems()
    {
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
	 * @throws oxConnectionException
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
	 * @throws oxConnectionException
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
	 *
	 * @return array|null
	 * @throws oxConnectionException
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