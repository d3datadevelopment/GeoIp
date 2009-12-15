<?

class d3_oxcmp_cur_geoip extends d3_oxcmp_cur_geoip_parent
{

    public function init()
    {

        $oLocation = &oxNew('d3geoip');
        //$oLocation->setUserCountry();
        $oLocation->setCountryCurrency();

        return parent::init();
    }

}