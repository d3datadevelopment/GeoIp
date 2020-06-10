# GeoIP Vorauswahl für OXID eShop

## Installation / Update

Führen Sie den folgenden Befehl in Ihrer Shopinstallation aus:

```
composer require d3/geoip:^4.0 --no-dev
```

Aktivieren Sie das Modul im Adminbereich Ihres Shops.

## Einrichtung

Im Adminbereich unter (D3) Module -> GeoIP finden Sie die Grundeinstellungen, mit denen Sie das Verhalten des Moduls vorgeben könen.

Unter "Stammdaten -> Länder -> GeoIP Vorauswahl" können Sie für jedes Land einstellen, welche Auswahl dafür getroffen werden soll.

## Wartung

Die Basis des Moduls ist die GeoIP-Datenbank. Diese sollte regelmäßig aktualisiert werden. Infos zur verwendbaren Datenbank finden Sie im Dokument `3rd_party_license.md` in diesem Paket.

## Deinstallation

Führen Sie den folgenden Befehl in Ihrer Shopinstallation aus:

```
composer remove d3/geoip --no-dev
```

## Support

- D3 Data Development (Inh. Thomas Dartsch)
- Home: [www.d3data.de](https://www.d3data.de)
- E-Mail: support@shopmodule.com
