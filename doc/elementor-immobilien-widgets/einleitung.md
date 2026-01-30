# Elementor-Immobilien-Widgets

Als *Widgets* werden Frontend-Elemente bezeichnet, die im Elementor-Editor per Drag'n'Drop in Seiten/Beiträge oder Templates eingefügt und anschließend konfiguriert werden können.

![Immobilien-Widgets im Elementor-Editor](../assets/scst-elementor-immobilien-widgets.png ':no-zoom')

## Arten und Kategorien

<div class="two-column-layout"><div>

![Elementor-Immobilien-Widget-Kategorien](../assets/scst-elementor-immobilien-widget-kategorien.png ':no-zoom')

</div><div>

Die meisten Elementor-Immobilien-Widgets sind nach dem jeweiligen Einsatzbereich in den beiden Hauptkategorien für Listen- und Übersichtsseiten (→ ***Immobilienliste***) sowie Objekt-Detailansicht-Templates (→ ***Immobiliendetails***) gruppiert. Der Umfang dieser Kategorien hängt davon ab, ob nebem dem [Kickstart-Basisplugin](https://de.wordpress.org/plugins/immonex-kickstart/) noch weitere [unterstützte Komponenten](/grundlagen/systemanforderungen?id=optionale-komponenten) verwendet werden.

Für Zusatzplugins mit mehreren Frontend-Elementen werden weitere Kategorien ergänzt (z. B. ***Kontakte & Agenturen*** bei aktivem [Team Add-on](https://de.wordpress.org/plugins/immonex-kickstart-team/)), wobei einige der hier enthaltenen Widgets parallel auch in den Hauptkategorien verfügbar sein können.

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

Jedes Immobilien-Widget verfügt über eine Reihe von Optionen, mit denen die hierüber eingebundenen Inhalte und deren Optik individuell angepasst werden können.

Umfasst die Widget-Ausgabe mehrere Elemente unterschiedlicher Art, bspw. eine Überschrift und eine Liste inkl. Icons, können diese im Tab ***Stil*** separat formatiert werden.

Der Tab ***Erweitert*** enthält allgemeine Einstellungen wie Rahmen/Abstände, CSS-Klassen/Regeln, Animationen und benutzerdefinierte Attribute, die sich auf das Container-Element beziehen.

</div></div>

## Hier geht's weiter &#8811; <!-- {docsify-ignore} -->

- [Widget-Übersicht](uebersicht)