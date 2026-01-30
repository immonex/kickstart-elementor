# Objektart (Pfad)

## Beispielansicht

![Screenshot: Objektart (Pfad) Elementor-Widget](../assets/scst-widget-objektart-pfad.png)

## Widget-Details

[](_type-regular.md ':include')

[Skin](/anpassung-erweiterung/skins)-Templates:  
`widgets/single-property/property-type.twig` (Frontend)  
`widgets/single-property/property-type-preview.php` (Editor-Vorschau)

---

<div class="two-column-layout"><div>

![Screenshot: Optionen des Objektart/Pfad-Widgets im Elementor-Editor](../assets/scst-widget-objektart-pfad-optionen.png)

</div><div>

Die Objektart wird als *Brotkrumen-Pfad* (*Breadcrumb Trail*) gerendert – optional mit Links zu den Übersichts-/Archivseiten der betreffenden Kategorien.

Alternativ kann durch Ausblenden von Nutzungsart und Hauptkategorie auch nur die eigentliche Objektart (Unterkategorie) angezeigt werden.

[](_hint-link-color.md ':include')

Die Objektarten/-kategorien werden beim OpenImmo-Import in Form von *Terms* der *Taxonomien* `inx_type_of_use` (Nutzungsart) und `inx_property_type` (Objektart) zugewiesen. Diese sind in der Spalte ***Destination*** der Mapping-Tabelle hinterlegt.

![Mapping-Tabelle für den OpenImmo-Import: Objektarten/-kategorien](../assets/scst-mapping-objektkategorien.png)

</div></div>

## Siehe auch

- [Beitragsarten und Taxonomien](https://docs.immonex.de/kickstart/#/beitragsarten-taxonomien) (immonex Kickstart)
- [Import von OpenImmo-Immobiliendaten in WordPress-Sites](https://docs.immonex.de/kickstart/#/schnellstart/import)
- [Mapping-Tabellen](https://docs.immonex.de/openimmo2wp/#/mapping/tabellen) (immonex OpenImmo2WP)

[](_backlink.md ':include')