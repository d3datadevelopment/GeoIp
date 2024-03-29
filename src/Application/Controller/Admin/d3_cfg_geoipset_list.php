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

use D3\ModCfg\Application\Controller\Admin\d3_cfg_mod_list;

class d3_cfg_geoipset_list extends d3_cfg_mod_list
{
    // enables language depended configuration
    protected $_blD3ShowLangSwitch = false;

    protected $_sMenuItemTitle = 'd3mxgeoip';

    protected $_sMenuSubItemTitle = 'd3mxgeoip_settings';

    /**
     * @return null
     */
    public function render()
    {
        $sRet = parent::render();

        // default page number 1
        $this->addTplParam('oxid', 1);
        $this->addTplParam("default_edit", "d3_cfg_geoipset_main");
        $this->addTplParam("updatemain", $this->_blUpdateMain);

        return $sRet;
    }
}
