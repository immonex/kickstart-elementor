# Galerie

## Beispielansicht

![Screenshot: Immobilienobjekt-Galerie Elementor-Widget](../assets/scst-widget-galerie.png)

## Widget-Details

[](_type-native.md ':include')

[](_parent-kickstart.md ':include')

[Skin](/anpassung-erweiterung/skins)-Template (Parent Plugin): `single-property/gallery.php`

---

<div class="two-column-layout"><div>

![Screenshot: Optionen des nativen Immobilien-Galerie-Widgets im Elementor-Editor](../assets/scst-widget-galerie-optionen.png)

</div><div>

Wie alle [nativen Widgets](einleitung?id=native-widgets) basiert auch die Galerie auf dem *Standard-Skin* des Kickstart-Basisplugins.

Neben statischen Bildern sind [Videos](videogalerie) und eine [360°-Tour](virtuelle-360-grad-tour) standardmäßig Teil der Galerie. Alternativ ist aber auch eine Deaktivierung und separate Einbindung mit den entsprechenden Widgets möglich.

Mit der gleichnamigen Option können der Typ bzw. die Quelle der einzubindenden Medien festgelegt werden:

- Hauptgalerie (Außen- und Innenansichten, Objekt-Detailfotos, Illustrationen, Videos, virtuelle Tour etc.)
- Grundrisse und Lagepläne
- Energieausweisbilder/-dokumente
- *Benutzerdefinierte Felder*

Bei Auswahl letzterer Option können in einem zusätzlichen Eingabefeld (kommagetrennt) die **Namen** der benutzerdefinierten Felder (*Custom Fields*) hinterlegt werden, die die IDs der einzubindenden Medienanhang-Beiträge – wiederum in Form kommagetrennter Listen – enthalten.

!> Die Lightbox-Optionen im Tab ***Stil*** beziehen sich nur auf [Kickstart-interne Lightbox-Funktion](https://docs.immonex.de/kickstart/#/schnellstart/einrichtung?id=lightbox) – sofern aktiv. Bei Verwendung der Elementor-Lightbox sollten alle Auswahloptionen hier auf *Standard* belassen werden.

Alle Widget-Einstellungen entsprechen weitestgehend den [galeriebezogenen Attributen](https://docs.immonex.de/kickstart/#/komponenten/detailansicht?id=galerien) des Kickstart-Shortcodes für Immobilien-Detailseiten-Abschnitte, [`[inx-property-details]`](https://docs.immonex.de/kickstart/#/komponenten/detailansicht?id=detail-abschnitte-gruppierte-angaben).

</div></div>

?> Beim Einsatz von **Elementor Pro** können Galerie-Inhalte alternativ auch mit **Elementor-Standard-Widgets** eingebunden werden, die den [*Dynamic Tag* Galerie](../elementor-immobilien-dynamic-tags/galerie) unterstützen.

## Siehe auch

- Widget: [Basisgalerie](basisgalerie)
- Widget: [Videogalerie 🄽](videogalerie)
- Widget: [Virtuelle 360° Tour 🄽](virtuelle-360-grad-tour)
- Dynamic Tag: [Galerie](../elementor-immobilien-dynamic-tags/galerie)
- [Shortcode-Attribute mit Galeriebezug](https://docs.immonex.de/kickstart/#/komponenten/detailansicht?id=galerien) (`[inx-property-details]`)

[](_backlink.md ':include')