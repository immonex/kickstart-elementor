# Schnellstart

Sind alle [Systemanforderungen](systemanforderungen) erfüllt, kann das Plugin in wenigen Minuten betriebsfertig eingerichtet werden.

Eine typische Immobilienmakler-Website enthält (mindestens) eine Übersichtsseite, die neben einer Liste der aktuellen Immobilienangebote üblicherweise auch Such- bzw. Filtermöglichkeiten sowie eine Karte mit Standortmarkern bietet. Jedes Angebot ist wiederum mit einer Objekt-Detailseite verlinkt.

Je nachdem, ob nur das [Elementor-Core-Plugin](https://de.wordpress.org/plugins/elementor/) oder auch die [Pro-Erweiterung](https://be.elementor.com/visit/?bta=229006&nci=5657) genutzt werden, gibt es für die Erstellung der beiden hierfür benötigten Vorlagen (Übersicht und Detailseite) nach der [Installation](installation) und Aktivierung mehrere Möglichkeiten, die nachfolgend beschrieben werden.

?> Der erste [Import von Immobilienangeboten via OpenImmo-Schnittstelle](https://docs.immonex.de/kickstart/#/schnellstart/import) sollte bereits **vor** dem Anlegen der Templates erfolgen, um hierbei eine [Vorschau](/elementor-immobilien-widgets/einleitung?id=vorschau) anhand realer Daten zu ermöglichen. (Bei der Umsetzung der **Detailansichten** erlaubt Elementor Pro die Auswahl einer bestimmten Vorschau-Immobilie, bei der Core-Variante werden hierfür entsprechende Beispielinhalte verwendet.)

## Elementor Core

### Seiten als Vorlagen

Wird nur das Elementor-Basisplugin (Core) eingesetzt, können die Vorlagen in Form von regulären Seiten umgesetzt werden.

1. ***Seiten → Seite hinzufügen***
2. ***Mit Elementor bearbeiten*** wählen
3. ***Seiteneinstellungen*** (*Seite/Zahnrad-Icon* links oben)
   - Titel: *Immobilienangebote*
   - **optional**: Titel ausblenden oder – je nach Theme – alternatives Seitenlayout auswählen (z. B. *Elementor Gesamte Breite*)
4. Folgende [Widgets](/elementor-immobilien-widgets/uebersicht) aus der Kategorie *Immobilienliste* (links) in die Seite ziehen:
   - *Übersichtskarte*
   - *Suchformular*
   - *Filter/Sortierung*
   - *Liste (Grid)*
   - *Seitennavigation*
5. ***Veröffentlichen*** und zurück zu WordPress wechseln
6. Weitere Seite "Immobiliendetails" mit diesen Widgets der gleichnamigen Kategorie anlegen:
   - *Standard-Header*
   - *Galerie*
   - *Beschreibung*
   - *Zimmer & Flächen*
   - *Zustand & Erschließung*
   - *Ausstattungsliste*
   - *Energieausweis*
   - *Preise*
   - *Standortkarte*
   - *Downloads & Links*
   - *Kontaktperson/-formular* ([Kickstart Team Add-on](https://de.wordpress.org/plugins/immonex-kickstart-team/) erforderlich)
   - Footer
7. ***immonex → Einstellungen***
   - ***Listen → [Allgemein] Immobilien-Übersicht***: *Immobilienangebote* auswählen + ***Änderungen speichern***
   - ***Detailansicht → [Allgemein] Immobilien-Detailseite***: *Immobiliendetails* auswählen + ***Änderungen speichern***

### Elementor-Blöcke für Gutenberg

Auch bei der zweiten Variante werden zwei *Vorlageseiten* angelegt. Die Widgets werden hier allerdings nicht direkt eingefügt, sondern zunächst in *Elementor-Templates* gebündelt und anschließend als *Gutenberg-Blöcke* in die Seiten eingebunden.

Das ist etwas aufwendiger, dafür können die Template-Inhalte flexibler aufgeteilt und – je nach Projektvorgabe – auch in mehreren Seiten verwendet werden.

Voraussetzung hierfür ist die Installation des (kostenlosen) Add-on-Plugins [Elementor Blocks for Gutenberg](https://de.wordpress.org/plugins/block-builder/).

1. ***Elementor → Editor → Templates → Neues Template hinzufügen***
   - Template-Typ: *Container*
   - Template-Name: *Immobilienangebote*
2. Folgende [Widgets](/elementor-immobilien-widgets/uebersicht) aus der Kategorie *Immobilienliste* (links) in den Inhaltsbereich ziehen:
   - *Übersichtskarte*
   - *Suchformular*
   - *Filter/Sortierung*
   - *Liste (Grid)*
   - *Seitennavigation*
3. ***Veröffentlichen*** und zurück zu WordPress wechseln
4. Weiteres **Elementor-Template** "Immobiliendetails" mit diesen Widgets der gleichnamigen Kategorie anlegen:
   - *Standard-Header*
   - *Galerie*
   - *Beschreibung*
   - *Zimmer & Flächen*
   - *Zustand & Erschließung*
   - *Ausstattungsliste*
   - *Energieausweis*
   - *Preise*
   - *Standortkarte*
   - *Downloads & Links*
   - *Kontaktperson/-formular* ([Kickstart Team Add-on](https://de.wordpress.org/plugins/immonex-kickstart-team/) erforderlich)
   - Footer
5. ***Seiten → Seite hinzufügen***
6. Gewünschten Seitentitel für die **Angebotsübersicht** eingeben
7. **optional**: alternatives Seitentemplate auswählen (z. B. *Elementor Gesamte Seite*)
8. Block ***Elementar-Bibliothek*** (*Elementor Library*) einfügen
   - Template: *Immobilienangebote* auswählen
9. ***Veröffentlichen***
10. ***Seiten → Seite hinzufügen***
11. Seitentitel **Immobiliendetails** eingeben (wird im Frontend normalerweise **nicht** angezeigt)
12. **optional**: alternatives Seitentemplate auswählen (z. B. *Elementor Gesamte Breite*)
13. Block ***Elementar-Bibliothek*** (*Elementor Library*) einfügen
    - Template: *Immobiliendetails* auswählen
14. ***Veröffentlichen***
15. ***immonex → Einstellungen***
    - ***Listen → [Allgemein] Immobilien-Übersicht***: *Immobilienangebote* auswählen + ***Änderungen speichern***
    - ***Detailansicht → [Allgemein] Immobilien-Detailseite***: *Immobiliendetails* auswählen + ***Änderungen speichern***

**That's it!** 😃

Wurden bereits [Immobilienangebote via OpenImmo-Schnittstelle importiert](https://docs.immonex.de/kickstart/#/schnellstart/import), werden diese nun in der neu angelegten [Standard-Übersichtsseite](https://docs.immonex.de/kickstart/#/beitragsarten-taxonomien?id=immobilien-beitr%c3%a4ge) (mitunter auch als *Archivseite* bezeichnet, meist unter `domain.tld/immobilien/`) angezeigt.

## Elementor Pro

Mit der Pro-Variante von Elementor sollten die o. g. Vorlagen als reguläre *Archiv- und Single-Post-Templates* der benutzerdefinierten Beitragsart (*Custom Post Type* oder kurz *CPT*) für Immobilien angelegt werden:

1. ***Elementor → Editor → Templates / Gespeicherte Templates → Neues Template hinzufügen***
   - Template-Typ: *Archiv*
   - Template-Name: *Immobilienangebote*
2. Vorlagenbibliothek schließen und Widgets analog zu Punkt 4. des vorherigen Abschnitts hinzufügen **oder** eine [individuelle Immobilienliste auf Basis eines Loop Grids](/elementor-pro/immobilien-loop-grid) erstellen
3. ***Bedingung hinzufügen*** (Pfeil neben *Veröffentlichen* rechts oben + ***Bedingungen anzeigen***)
   - Einschließen: *Immobilien Archiv*
   - ***Speichern & Schließen***, anschließend zurück zu WordPress
4. Weiteres Template erstellen:
   - Template-Typ: *Einzelner Beitrag*
   - Template-Name: *Immobiliendetails*
5. ***Bedingung hinzufügen***
   - Einschließen: *Immobilien*
   - ***Speichern & Schließen*** und zurück zu WordPress
6. ***immonex → Einstellungen***
   - ***Listen → [Allgemein] Immobilien-Übersicht***: *keine (Theme-Template verwenden)* auswählen + ***Änderungen speichern***
   - ***Detailansicht → [Allgemein] Immobilien-Detailseite***: *keine (Theme-Template verwenden)* + ***Änderungen speichern***

Die [Standard-Listenansicht](https://docs.immonex.de/kickstart/#/beitragsarten-taxonomien?id=immobilien-beitr%c3%a4ge) der Immobilienangebote kann nun über die *Archiv-URL des Immobilien-CPT*, normalerweise `domain.tld/immobilien/`, aufgerufen werden.

## Hier geht's weiter &#8811; <!-- {docsify-ignore} -->

- [Immobilien-Widgets für Elementor](/elementor-immobilien-widgets/einleitung)