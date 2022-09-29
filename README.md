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

## Changelog

Siehe [CHANGELOG](CHANGELOG.md) für weitere Informationen.

## Beitragen

Wenn Sie eine Verbesserungsvorschlag haben, legen Sie einen Fork des Repositories an und erstellen Sie einen Pull Request. Alternativ können Sie einfach ein Issue erstellen. Fügen Sie das Projekt zu Ihren Favoriten hinzu. Vielen Dank.

- Erstellen Sie einen Fork des Projekts
- Erstellen Sie einen Feature Branch (git checkout -b feature/AmazingFeature)
- Fügen Sie Ihre Änderungen hinzu (git commit -m 'Add some AmazingFeature')
- Übertragen Sie den Branch (git push origin feature/AmazingFeature)
- Öffnen Sie einen Pull Request

## Lizenz
(Stand: 06.05.2021)

Vertrieben unter der GPLv3 Lizenz.

```
Copyright (c) D3 Data Development (Inh. Thomas Dartsch)

Diese Software wird unter der GNU GENERAL PUBLIC LICENSE Version 3 vertrieben.
```

Die vollständigen Copyright- und Lizenzinformationen entnehmen Sie bitte der [LICENSE](LICENSE.md)-Datei, die mit diesem Quellcode verteilt wurde.

## Support

- D3 Data Development (Inh. Thomas Dartsch)
- Home: [www.d3data.de](https://www.d3data.de)
- E-Mail: support@shopmodule.com
