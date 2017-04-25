<?php

class d3_oxvatselector_geoip extends d3_oxvatselector_geoip_parent
{
    public function getArticleVat(oxArticle $oArticle)
    {
        $dVat = parent::getArticleVat($oArticle);

        if ( $aCountry2Vat = $this->getConfig()->getConfigParam( "aCountryVat" ) )
        {
            if ( isset( $aCountry2Vat[$this->d3GetGeoIpCountry()->getId()] ) ) {
                return $aCountry2Vat[$this->d3GetGeoIpCountry()->getId()];
            }
        }

        return $dVat;
    }

    public function d3GetGeoIpCountry()
    {
        $oD3GeoIp = oxNew('d3geoip');
        return $oD3GeoIp->getUserLocationCountryObject();
    }
}