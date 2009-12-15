<?

class d3_oxcmp_lang_geoip extends d3_oxcmp_lang_geoip_parent
{
    public function init()
    {
        $oLocation = &oxNew('d3geoip');
        //$oLocation->setUserCountry();
        $oLocation->setCountryLanguage();

        return parent::init();
    }
}