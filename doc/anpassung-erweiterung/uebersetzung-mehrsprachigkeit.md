# Übersetzungen & Mehrsprachigkeit

Die offiziellen Übersetzungen werden via [translate.wordpress.org (GlotPress)](https://translate.wordpress.org/projects/wp-plugins/immonex-kickstart-elementor/) bereitgestellt. Die Varianten *de_DE* (**informell/Du**) und *de_DE_formal* (**formell/Sie**) sind hier immer vollständig verfügbar. Weitere Sprachen und länderspezifische Varianten können ebenfalls hierüber ergänzt werden (Infos zu Hintergrund und Vorgehensweise im offiziellen [Handbuch für Übersetzer](https://make.wordpress.org/polyglots/handbook/)).

Die Übersetzungen von translate.wordpress.org werden automatisch in den globalen WordPress-Übersetzungs-Ordner `.../wp-content/languages/plugins` heruntergeladen, sofern diese für die unter ***Einstellungen → Allgemein*** eingestellte Website-Sprache verfügbar sind:

<pre class="tree">
<strong>…/wp-content/languages/plugins</strong>
╷
├── immonex-kickstart-elementor-de_DE.po
├── immonex-kickstart-elementor-de_DE.mo
├── immonex-kickstart-elementor-de_DE_formal.po
└── immonex-kickstart-elementor-de_DE_formal.mo
</pre>

!> Die Übersetzungen im globalen WP-Sprachordner haben Priorität. Die gleichnamigen Dateien, die **zusätzlich** im Unterordner `languages` des Plugin-Verzeichnisses enthalten sind, werden im Regelfall **nicht** eingebunden.

**Individuelle lokale Übersetzungen** können mit [Loco Translate](https://de.wordpress.org/plugins/loco-translate/) erstellt und aktualisiert werden.

Detaillierte Infos und Screenshots zu diesen Themen sind in der [Dokumentation des Kickstart-Basis-Plugins](https://docs.immonex.de/kickstart/#/anpassung-erweiterung/uebersetzung-mehrsprachigkeit) abrufbar. (Bitte hier auch die [Besonderheit beim Einsatz von Beta-Versionen](https://docs.immonex.de/kickstart/#/anpassung-erweiterung/uebersetzung-mehrsprachigkeit?id=besonderheit-bei-beta-versionen) beachten!)

## Übersetzung von Plugin-Optionen

In **mehrsprachigen Websites** (mit Sprachumschalter im Frontend) sind im Regelfall auch Texte zu übersetzen, die bspw. in den Plugin-Optionen (WordPress-Backend) hinterlegt sind.

Das betrifft die Elementor-Erweiterung zwar nicht direkt, im hierüber eingebundenen [Kickstart-Basisplugin](https://docs.immonex.de/kickstart/) sowie in anderen [Add-ons](https://docs.immonex.de/kickstart/#/add-ons) sind aber Inhalte dieser Art vorhanden.

Für die Übersetzung (*String Translation*) kann eine der folgenden – auch von Elementor unterstützten – Lösungen eingesetzt werden:

- [Polylang](https://de.wordpress.org/plugins/polylang/)
- [Polylang Pro](https://polylang.pro/)
- [WPML](https://wpml.org/?aid=94091&affiliate_key=EhQuN5PNVZE6)