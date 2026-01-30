# Kerndaten

## Beispielansichten

### Kompaktes Standardlayout

![Screenshot: Immobilien-Kerndaten Elementor-Widget](../assets/scst-widget-kerndaten-1.png)

### Alternativlayout

![Screenshot: Immobilien-Kerndaten Elementor-Widget (alternative Darstellung)](../assets/scst-widget-kerndaten-2.png)

## Widget-Details

[](_type-regular.md ':include')

[Skin](/anpassung-erweiterung/skins)-Templates:  
`widgets/single-property/core-details.twig` (Frontend)  
`widgets/single-property/core-details-preview.php` (Editor-Vorschau)

---

<div class="two-column-layout"><div>

![Screenshot: Elementauswahl](../assets/scst-widget-kerndaten-elemente.png)

</div><div>

In den Widget-Optionen können Umfang, Reihenfolge und Layout der *Immobilien-Kerndaten* flexibel angepasst werden – sowohl für die komplette Liste als auch für die einzelnen Elemente.

Die Zuordnung der Kerndaten erfolgt anhand der [Mapping-Tabelle](https://docs.immonex.de/openimmo2wp/#/mapping/tabellen), die für den [OpenImmo-XML-basierten Import](https://docs.immonex.de/kickstart/#/schnellstart/import) mit [immonex OpenImmo2WP](https://plugins.inveris.de/wordpress-plugins/immonex-openimmo2wp) eingesetzt wird. Bei den meisten hiervon enthält der Zielfeldname in der Spalte ***Destination*** das Präfix `_inx_primary_`.

![Mapping-Tabelle für Kerndaten](../assets/scst-mapping-kerndaten.png)

[](_hint-flex-details.md ':include')

</div></div>

## Siehe auch

- [Import von OpenImmo-Immobiliendaten in WordPress-Sites](https://docs.immonex.de/kickstart/#/schnellstart/import)
- [Mapping-Tabellen](https://docs.immonex.de/openimmo2wp/#/mapping/tabellen) (immonex OpenImmo2WP)

[](_backlink.md ':include')