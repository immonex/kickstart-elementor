# Elementor-Immobilien-Widgets

Als *Widgets* werden Frontend-Elemente bezeichnet, die im Elementor-Editor per Drag'n'Drop in Seiten/Beiträge oder Templates eingefügt und anschließend konfiguriert werden können.

![Immobilien-Widgets im Elementor-Editor](../assets/scst-elementor-immobilien-widgets.png ':no-zoom')

## Arten und Kategorien

<div class="two-column-layout"><div>

![Elementor-Immobilien-Widget-Kategorien](../assets/scst-elementor-immobilien-widget-kategorien.png ':no-zoom')

</div><div>

Die meisten [Elementor-Immobilien-Widgets](uebersicht) sind nach dem jeweiligen Einsatzbereich in den beiden Hauptkategorien für Listen- und Übersichtsseiten (→ ***Immobilienlisten und -suche***) sowie Objekt-Detailansicht-Templates (→ ***Immobiliendetails***) gruppiert.

Der Umfang dieser Kategorien hängt davon ab, ob nebem dem [Kickstart-Basisplugin](https://de.wordpress.org/plugins/immonex-kickstart/) noch weitere [unterstützte Komponenten](/grundlagen/systemanforderungen?id=optionale-komponenten) verwendet werden.

Für aktive Zusatzplugins und Kickstart-Add-ons werden weitere Kategorien ergänzt:

- ***Kontakte & Agenturen*** ([Team Add-on](https://de.wordpress.org/plugins/immonex-kickstart-team/)), wobei einige der hier enthaltenen Widgets parallel auch in den Hauptkategorien verfügbar sein können.

Beim **Rendering der Ausgabe** wird zwischen zwei Widget-Arten unterschieden:

### Reguläre Widgets

Bei *regulären* Widgets werden die Inhalte – sowohl für das Frontend als auch für die Vorschau im Elementor-Editor – vollständig innerhalb des Elementor-Add-ons auf Basis der im Plugin-Ordner ([Skin](/anpassung-erweiterung/skins?id=elementor-immobilien-widgets)) enthaltenen Templates generiert.

### Native Widgets

*Native* Widgets sind am 🄽 im Titel erkennbar und ermöglichen – als nutzerfreundlichere Alternative zu den entsprechenden Kickstart-Shortcodes – die einfache Einbindung, Konfiguration und Gestaltung von Frontend-Elementen, deren Inhalte von **anderen immonex-Plugins** (→ *Parent-Plugins*) gerendert werden.

#### Parent-Plugin

Jedes native Widget greift intern auf die Schnittstellen oder Shortcodes eines bestimmten *Parent-Plugins* zurück – in den meisten Fällen die des [Kickstart-Basisplugins](https://de.wordpress.org/plugins/immonex-kickstart/) oder eines anderes Add-ons.

#### Skin-Ordner

Native Widgets und meist etwas umfangreicher, unterscheiden sich vom Handling her aber nur marginal von ihren regulären Pendants. Nur bei elementaren Anpassungen auf **Template-Code-Ebene** muss der abweichende [Skin-Ordner](/anpassung-erweiterung/skins?id=elementor-immobilien-widgets) des **Parent-Plugins** berücksichtigt werden.

</div></div>

## Konfiguration

<div class="two-column-layout"><div>

![Widget-Optionen im Elementor-Editor](../assets/scst-widget-optionen-elementor-editor.png ':no-zoom')

</div><div>

Jedes [Elementor-Immobilien-Widget](uebersicht) verfügt über eine Reihe von Optionen, mit denen die hierüber eingebundenen Inhalte und deren Optik individuell angepasst werden können.

Wie bei Elementor üblich, sind die Optionen in drei Tabs unterteilt: Unter ***Inhalt*** werden Art und Umfang der einzubindenden Informationen festgelegt, der Tab ***Stil*** enthält die zugehörigen Formatierungs- und Gestaltungsoptionen.

Umfasst die Widget-Ausgabe mehrere Elemente unterschiedlicher Art, ist auch der ***Stil***-Tab in entsprechende Abschnitte unterteilt, die die jeweiligen Optionen für die individuelle Anpassung der Optik enthalten.

Der Tab ***Erweitert*** enthält allgemeine Einstellungen wie Rahmen/Abstände, CSS-Klassen/Regeln, Animationen und benutzerdefinierte Attribute, die sich auf das Container-Element beziehen.

</div></div>

## Vorschau

<div class="two-column-layout"><div>

![Auswahl des Immobilien-Archivs für die Vorschau im Elementor-Editor](../assets/scst-elementor-pro-vorschau-auswahl-2.webp ':no-zoom')

![Auswahl einer Vorschau-Immobilie im Elementor-Editor](../assets/scst-elementor-pro-vorschau-auswahl.webp ':no-zoom')

</div><div>

Bei der Erstellung von Immobilienseiten und -vorlagen ist es vorteilhaft, für Vorschauzwecke bereits auf reale Objektdaten zurückgreifen zu können. Daher sollte bereits vorab ein [Import von Immobilienangeboten via OpenImmo-Schnittstelle](https://docs.immonex.de/kickstart/#/schnellstart/import) erfolgen, sofern noch keine Angebote vorhanden sind.

Elementor Pro bietet hierbei die Möglichkeit, sowohl für [Listenansichten auf Loop-Basis](/elementor-pro/immobilien-loop-grid) (*Archive*) als auch für Einzelvorlagen (*Single Templates*) explizit die Immobilien-Beitragsart bzw. eine bestimmte Immobilie auszuwählen.

Ist das Elementor-Core-Plugin **ohne** die Pro-Erweiterung im Einsatz, werden bei den *nativen* Listen-Widgets im Editor auch hier die Echtdaten eingebunden. Bei Templates für Immobilien-Detailansichten werden aber Beispielinhalte verwendet, die nicht an die tatsächlichen Immobiliendaten gebunden sind.

</div></div>

## Hier geht's weiter &#8811; <!-- {docsify-ignore} -->

- [Widget-Übersicht](uebersicht)