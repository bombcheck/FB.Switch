# FB.Switch v1.0.6.1-STABLE

*Webapp zur Steuerung und Verwaltung von 433-MHz-Funksteckdosen, FritzDect 200-Steckdosen und MiLight-Lampen.*

## Features:
* Aufgebaut mit jQuery UI: Passt sich automatisch an verschiedene Browser und Mobilgeräte an
* Kann unter iOS und Android ähnlich einer nativen App direkt vom Springboard/Launcher gestartet werden
* Unterstützt das Schalten diverser Funksteckdosen (z.B. Brennenstuhl, Elro) über ein entsprechendes LAN-Gateway
* Unterstützt das Schalten und die Verbrauchs- sowie Temperaturabfrage an einer vorhandenen FritzBox angeschlossener FritzDect 200-Steckdosen
* Unterstützt das Schalten sowie die Farb- und Moduswahl von MiLight-RGBW-LED-Lampen über ein entsprechendes LAN-Gateway
* Verwaltung von Geräten und Gerätegruppen
* Definierung von Timern für Geräte und Gruppen. Für MiLight-Geräte kann im Timer definiert werden mit welchem Modus/Farbe/Helligkeit eingeschaltet werden soll
* Definierung von Aktionen für Geräte und Gruppen. Für MiLight-Geräte kann in der Aktion definiert werden mit welchem Modus/Farbe/Helligkeit eingeschaltet werden soll.
* Benutzerverwaltung: Identifikation anhand einer Client-Geräte-IP
* Benutzerverwaltung: Für jeden Benutzer können individuelle Einstellungen wie Theme, Favoriten, Auto-Refresh usw. eingestellt werden
* Audio-Feedback beim Betätigen bestimmter Buttons und dem Abschluss bestimmter Operationen

## Voraussetzungen:
* Webserver mit Apache/PHP5 (z.B. einen Raspberry Pi)
* PHP-Curl-Modul wird benötigt
* Benötigt keine Datenbank!

## Kompatible Hardware:
* 433 MHz LAN-Gateway: Brematic Home Automation Gateway GWY 433 / Conn-Air
* 433 MHz Funksteckdose: Brennenstuhl RCS 1000 N und viele andere von Elro, Intertechno, Brennenstuhl
* FritzBox-Router mit FritzDect200-Steckdosen
* MiLight RGBW-Lampen / RGBW-LED-Strip-Controller
* MiLight LAN-Gateway bis einschließlich v5

## Installation:
* Einfach in einen Ordner auf dem Webserver kopieren.
* Webserver braucht Schreibrechte auf das data-Verzeichnis sowie dessen Unterverzeichnisse und aller Dateien.
* Webserver braucht auch Schreibrechte auf das root-Verzeichnis der Installation.
* Ggf. die Pfade in der Datei manifest.json anpassen (wird für Android benötigt).

**Hinweis: Bitte bei der Benamung der Geräte, Gruppen usw. keine ":" und "|" verwenden!**

Weitere Infos, Hilfe und Support gibt es im Forum:
http://forum.power-switch.eu/viewtopic.php?f=24&t=367&p=6457
