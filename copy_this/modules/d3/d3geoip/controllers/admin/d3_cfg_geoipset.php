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

class d3_cfg_geoipset extends d3_cfg_mod_
{

    public function render()
    {
        $this->_aViewData['sListClass'] = 'd3_cfg_geoipset_list';

        $this->_aViewData['sMainClass'] = 'd3_cfg_geoipset_main';

        $this->_hasListItems = false;

        return parent::render();
    }
}