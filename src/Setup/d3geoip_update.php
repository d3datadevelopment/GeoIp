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
 * @author    D3 Data Development - Daniel Seifert <support@shopmodule.com>
 * @link      http://www.oxidmodule.com
 */

namespace D3\GeoIp\Setup;

use D3\ModCfg\Application\Model\Install\d3install_updatebase;
use D3\ModCfg\Application\Model\Installwizzard\d3installdbfield;
use D3\ModCfg\Application\Model\Installwizzard\d3installdbtable;
use Doctrine\DBAL\DBALException;
use OxidEsales\Eshop\Application\Model\Shop;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Exception\ConnectionException;
use OxidEsales\Eshop\Core\Exception\DatabaseConnectionException;
use OxidEsales\Eshop\Core\Exception\DatabaseErrorException as DatabaseErrorException;
use OxidEsales\Eshop\Core\Registry;

class d3geoip_update extends d3install_updatebase
{
    public $sModKey = 'd3_geoip';
    public $sModName = 'GeoIP';
    public $sModVersion = '4.0.2.0';
    public $sModRevision = '4020';
    public $sBaseConf = '--------------------------------------------------------------------------------
Idvv2==N0IxdktTWFExSTZnejNxbzR0dzQ4Y3lnYzArVWZISEZKNWRoaXBwMTRxNjFlQmpHSFZObk8za
2QrUGRuT3U1T3Nua090WThJak1jVFFxdGc1K0QxMFl6WDB3VUhzakVFM3hPYXc4RjBIWTVtZVR4YlBRe
TdZOWZpNWdpajR1NWFzdjc3aVN6YkZra1A2NHQ5ZWVXS0lsWG5tVU1xcG9GaWRpOU5MVDRjZDZUNXVFd
lVzUXlZYnlheHl6Qy9sNWFRdWdDQUFXM21NRUdnR0V1amkvVmpwaGxWZ1FIaU51M1pUaTdzMk9IeFNjb
kpZVEp5ZWxzUGlJUWZFbkdpbVBWZHN4ZStNcW1QWWwrV21ESTFpQlA1TWRCVG9BOEFqSHRMcXBsZHdjM
1loSHpoN1BZSjk2MUR0a0hIWlJYWUlCbnY=
--------------------------------------------------------------------------------';
    public $sRequirements = '';
    public $sBaseValue = '';

    public $sMinModCfgVersion = '5.3.6.0';
    
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

    // default values for checkMultiLangTables() and fixRegisterMultiLangTables()
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
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     */
    public function hasDeleteGeoIpTableFields()
    {
    	/** @var d3installdbfield $oInstallDbField */
    	$oInstallDbField = oxNew(d3installdbfield::class, $this);
    	return $oInstallDbField->checkDeleteFields();
    }

    /**
     * @return bool
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     */
    public function deleteGeoIpTableFields()
    {
        $blRet = false;
        if ($this->hasDeleteGeoIpTableFields()) {
	        /** @var d3installdbfield $oInstallDbField */
	        $oInstallDbField = oxNew(d3installdbfield::class, $this);
            $blRet  = $oInstallDbField->fixDeleteFields(__METHOD__);
        }

        return $blRet;
    }

    /**
     * @return bool true, if table has wrong engine
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     */
	public function checkGeoIpTableEngine()
	{
		/** @var d3installdbtable $oDbTable */
		$oDbTable = oxNew(d3installdbtable::class, $this);
		$aData = $oDbTable->getTableData('d3geoip');

		if (isset($aData) && count($aData) && isset($aData['ENGINE']) && $aData['ENGINE'] == 'InnoDB') {
			return false;
		}

		return true;
	}

    /**
     * @return bool
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     */
	public function updateGeoIpTableEngine()
	{
		/** @var d3installdbtable $oDbTable */
		$oDbTable = oxNew(d3installdbtable::class, $this);
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
     * @throws DBALException
     * @throws DatabaseConnectionException
     */
    public function checkModCfgItemExist()
    {
        $blRet = false;
        foreach ($this->getShopList() as $oShop) {
            /** @var $oShop Shop */
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
                /** @var $oShop Shop */
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
                            'content'       => Registry::getConfig()->getEdition(),
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
        /** @var $oShop Shop */
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
        /** @var $oShop Shop */
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
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     * @throws ConnectionException
     */
    public function checkRegisteredComponent()
    {
        /** @var $oShop Shop */
        foreach ($this->getShopListByActiveModule('d3geoip') as $oShop) {
            $aUserComponents = $this->_d3GetUserComponentsFromDb($oShop);

            if (is_array($aUserComponents)
                && in_array('GeoIpComponent', array_keys($aUserComponents))
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     * @throws DBALException
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     */
    public function unregisterComponent()
    {
        $blRet = true;
        $sVarName = 'aUserComponentNames';

        /** @var $oShop Shop */
        foreach ($this->getShopList() as $oShop) {
            $aUserComponents = $this->_d3GetUserComponentsFromDb($oShop);

            if (is_array($aUserComponents)
                && in_array('GeoIpComponent', array_keys($aUserComponents))
            ) {
                unset($aUserComponents['GeoIpComponent']);
                if (false == count($aUserComponents)) {
                    $aUserComponents = null;
                }
                $this->fixOxconfigVariable($sVarName, $oShop->getId(), '', $aUserComponents, 'arr');
            }
        }

        return $blRet;
    }

    /**
     * @param Shop $oShop
     *
     * @return array|null
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     */
    protected function _d3GetUserComponentsFromDb(Shop $oShop)
    {
        $sVarName = 'aUserComponentNames';
        $sModuleId = '';
        $oDb = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
        $sSelect = "SELECT oxvartype as type, ".Registry::getConfig()->getDecodeValueQuery().
            " as value FROM `oxconfig` WHERE oxshopid = ".$oDb->quote($oShop->getId()).
            " AND oxvarname = ".$oDb->quote($sVarName).
            " AND oxmodule = ".$oDb->quote($sModuleId);

        $aResult = $oDb->getAll($sSelect);
        $aUserComponents = is_array($aResult) && count($aResult)
            ? Registry::getConfig()->decodeValue($aResult[0]['type'], $aResult[0]['value'])
            : null;

        return $aUserComponents;
    }
}
