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

namespace D3\GeoIp\Modules\Application\Component {

    use OxidEsales\Eshop\Application\Component\LanguageComponent;

    class d3_oxcmp_lang_geoip_parent extends LanguageComponent {}
}

namespace D3\GeoIp\Modules\Core {

    use OxidEsales\Eshop\Core\ShopControl;

    class d3_oxshopcontrol_geoip_parent extends ShopControl {}
}
