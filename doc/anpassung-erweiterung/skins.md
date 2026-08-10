# Skins

Ein *Skin* ist – vergleichbar mit einem WordPress-Theme – ein Ordner, der alle für die Darstellung der **Frontend-Komponenten** eines immonex-Plugins benötigten Vorlagen und Ressourcen enthält:

- Templates (PHP/[Twig](https://twig.symfony.com/))
- JavaScript- und CSS-Dateien
- Bildmaterial
- Fonts etc.

![Skin-Auswahl](../assets/scst-elementor-add-on-skin-auswahl.png)\
Skin-Auswahl unter ***immonex → Einstellungen → Elementor <sup>ADD-ON</sup>***

## Standard-Skin

Das Elementor-Add-on enthält ein *Standard-Skin* im Unterordner `skins/default` des **Plugin-Verzeichnisses**:

<pre class="tree">
<strong>…/wp-content/plugins</strong>
╷
├── <strong>/immonex-kickstart-for-elementor</strong> ← <em class="token important">Plugin-Verzeichnis</em>
│   ╷
│   └─ /skins
│      ╷
│      └─ <span class="token important">/default</span> ← Standard-Skin
…
</pre>

## Custom Skins

Bei Immobilien-Website-Projekten mit spezielleren Anforderungen ermöglichen *Custom Skins* weitergehende Modifikationen, deren Umsetzung via Elementor-Editor, [CSS](css) oder [Filterfunktionen](https://docs.immonex.de/kickstart/#/anpassung-erweiterung/filters-actions) etc. nicht möglich bzw. zu umständlich wäre.

Custom-Skin-Ordner enthalten Templates und anderweitige Ressourcen, deren Inhalt von den gleichnamigen Dateien des Standard-Skins abweicht oder die hier nicht enthalten sind. Sie werden *update-sicher* im *Custom-Skin-Stammordner* hinterlegt, dem Unterordner `immonex-kickstart-for-elementor` des **Child-Theme-Verzeichnisses**:

`…/wp-content/themes/<CHILD-THEME-NAME>/immonex-kickstart-for-elementor/<SKIN-NAME>`

Der Ordnername des Custom Skins (`<SKIN-NAME>`) kann frei gewählt werden. Entspricht er dem des Standard-Skins (`default`), entfällt die explizite Auswahl in den Plugin-Optionen.

Beispiel-Ordnerstruktur:

<pre class="tree">
<strong>…/wp-content/themes/hello-elementor-child</strong> ← <em class="token important">Child-Theme-Ordner</em>
╷
├── <strong>/immonex-kickstart-for-elementor</strong> ← <em class="token important">Custom-Skin-Stammordner</em>
│   ╷
│   ├─ <span class="token important">/default</span> ← Custom Skin mit Standard-Skin-Ordnername
│   ├─ <span class="token important">/agnus</span> ← Custom Skin "Agnus"
│   ├─ <span class="token important">/denise</span> ← Custom Skin "Denise"
│   └─ <span class="token important">/paula</span> ← Custom Skin "Paula"
…
</pre>

### Sonderfall: Native Widgets

Eine Besonderheit bei Elementor-Add-on-Skins stellt die Unterscheidung zwischen *regulären* und *nativen* [Widgets](/elementor-immobilien-widgets/einleitung) dar: Nur die Vorlagen der **regulären** Varianten im Skin-Unterordner `widgets` – meist eine PHP-Datei für die Editor-Vorschauversion und ein Twig-Template für den eigentlichen Frontend-Output – sind relevant, wenn es um individuelle Anpassungen geht.

<pre class="tree">
skin-name
╷
├── <span class="token important">/widgets</span>
│   ╷
│   ├── /property-list ← (optionale) Templates nativer Immobilien-Listen-Widgets
│   │   ╷
│   │   ├── native-filters-sort.twig
│   │   ├── native-pagination.twig
│   │   ├── native-property-carousel.twig
│   │   ├── native-property-list.twig
│   │   ├── native-property-map.twig
│   │   └── native-search-form.twig
│   │
│   ├── <span class="token important">/single-property</span> <em class="token important">← Templates für Widgets der Immobilien-Detailansicht</em>
│   │   ╷
│   │   ├── <strong>areas-preview.php</strong> ← Vorschau-Template (PHP)
│   │   ├── <strong>areas.twig</strong> ← Frontend-Template (Twig)
│   │   ├── <strong>basic-gallery.twig</strong>
│   │   ├── <strong>condition-preview.php</strong>
│   │   ├── <strong>condition.twig</strong>
│   │   ├── <strong>core-details-preview.php</strong>
│   │   ├── <strong>core-details.twig</strong>
│   │   …
│   ├── /team ← (optionale) Templates nativer Team-Add-on-Widgets
│   │   ╷
│   │   ├── native-agency-list.twig
│   │   ├── native-agency.twig
│   │   ├── native-agent-list.twig
│   │   └── native-agent.twig
│   │
│   ├── /lead-generator ← (optionale) Templates nativer Lead-Generator-Widgets
│   ├── /notify ← (optionale) Templates nativer Notify-Widgets
│   │
│   └── <span class="token important">native-default.twig</span> ← <em class="token important">Fallback-Template für alle nativen Widgets ohne dedizierte Vorlagendatei</em>
… 
</pre>

Die Templates der *nativen* Komponenten hingegen sind Bestandteile der Skins der sog. *Parent-Plugins*, zu denen sie gehören, sprich, die deren *Ausgabe generieren*. Soll also die Frontend-Darstellung eines nativen Widgets grundlegend verändert werden, werden die angepassten Template-Dateien **im jeweiligen Child-Theme-Unterordner des Parent-Plugins** hinterlegt.

Beispiel: Die Ausgabe des nativen Widgets [Standard-Header](/elementor-immobilien-widgets/standard-header) wird normalerweise auf Basis einer Vorlagendatei gerendert, die Teil des Standard-Skins des **Kickstart-Basisplugins** ist:

`…/wp-content/plugins/immonex-kickstart/skins/default/single-property/head.php`

Dementsprechend kann sie mit einer angepassten Variante gleichen Namens im Child-Theme-Unterordner des **Basisplugins** *überschrieben* werden:

<code>…/wp-content/themes/&lt;CHILD-THEME-NAME&gt;/<strong>immonex-kickstart</strong>/default/single-property/head.php</code>

!> Im Gegensatz zu den regulären Widgets wirken sich Änderungen in den Template-Dateien nativer Frontend-Elemente auch auf anderweitige, von Elementor unabhängige Formen der Einbindung aus (bspw. per Shortcode).

Wie anhand der Liste oben ersichtlich ist, enthält das Standard-Skin des Elementor-Add-ons aber – trotz fehlender Relevanz – auch diverse Twig-Vorlagen für native Widgets (in den Ordnern `/widgets/property-list`, `/widgets/team`, `/widgets/lead-generator` und `/widgets/notify` sogar ausschließlich), die am Präfix `native-` zu erkennen sind.

?> Wie jetzt? Es gibt also doch Templates für **native** Widgets im **Elementor-Add-on-Skin-Ordner**? 🤔

Formal gesehen ja, diese sind allerdings **optional** und enthalten lediglich einen Platzhalter ([Twig-Variable](https://twig.symfony.com/doc/3.x/templates.html/)) für die hierüber einzufügenden *Shortcode-Inhalte* der Parent-Plugins. Gleiches gilt für die Datei `native-default.twig` die als *Fallback-Template* für alle nativen Widgets verwendet wird, für die keine dedizierte Vorlagendatei vorhanden ist.

Ergo: Die eigentlichen Inhalte der nativen Elemente können in diesen Templates nicht verändert werden, es ist aber möglich, bei Bedarf etwas davor und/oder dahinter einzufügen.

## Hier geht's weiter &#8811; <!-- {docsify-ignore} -->

- [Details zur Kickstart-Skin-Entwicklung](https://docs.immonex.de/kickstart/#/anpassung-erweiterung/skins)
- [Mehrsprachigkeit & Übersetzungen](uebersetzung-mehrsprachigkeit)