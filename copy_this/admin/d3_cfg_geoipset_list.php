<?php

// @copyright © D³ Data Development
//
// This Software is the property of Data Development and is protected
// by copyright law - it is NOT Freeware.
//
// Any unauthorized use of this software without a valid license key
// is a violation of the license agreement and will be prosecuted by
// civil and criminal law.
//
// http://www.shopmodule.com

// AUTOR Daniel Seifert <ds@shopmodule.com>

class d3_cfg_geoipset_list extends d3_cfg_mod_list
{
    // enables language depended configuration
    protected $_blD3ShowLangSwitch = false;

    protected $_sMenuItemTitle = 'd3mxgeoip';

    protected $_sMenuSubItemTitle = 'd3mxgeoip_settings';

    public function render()
    {
        $sRet = parent::render();

        // default page number 1
        $this->_aViewData['oxid'] = 1;
        $this->_aViewData["default_edit"] =  "d3_cfg_geoipset_main";
        $this->_aViewData["updatemain"]   =  $this->_blUpdateMain;

        return $sRet;
    }
}