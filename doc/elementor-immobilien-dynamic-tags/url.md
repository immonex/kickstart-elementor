# URL

<div class="two-column-layout"><div>

![Screenshot: Dynamic-Tag-Auswahl (URL)](../assets/scst-dynamic-tag-url-1.png)

</div><div>

In Immobilien-Listenansichten auf Basis von [Loop-Grid-](https://elementor.com/help/loop-grid/) oder [Loop-Karussell-Widgets](https://elementor.com/help/loop-carousel/) werden typischerweise die jeweiligen Objekt-Detailseiten verlinkt.

Damit in diesen wiederum auf die korrekte Ausgangsseite verwiesen werden kann, sollte für die Generierung der URLs **auf beiden Seiten** auf diesen Dynamic Tag zurückgegriffen werden.

![Screenshot: Immobilien-Loop-Item](../assets/scst-dynamic-tag-url-3.png)

</div></div>

<div class="two-column-layout"><div>

![Screenshot: Dynamic-Tag-Auswahl (URL)](../assets/scst-dynamic-tag-url-2.png)

</div><div>

Die Art der URL kann im Regelfall automatisch ermittelt werden, bei Bedarf ist aber auch eine explizite Auswahl möglich:

*Immobiliendetails* in Übersichtsseiten (Listen), *Backlink (Übersicht)* in Detailseiten.

In Übersichtsseiten wird den Detailseiten-*Permalinks* ein *Backlink*-Parameter mit der Rücksprung-URL angehangen. Ist neben dem Loop-Element bspw. auch ein [Immobilien-Suchformular](/elementor-immobilien-widgets/suchformular) in der Seite enthalten, enhält die Backlink-URL zudem die jeweiligen Suchkriterien in Form von *GET-Parametern*.

Diese Backlink-URL wird in den Objekt-Detailseiten für die *Zurück-Links* übernommen.

![Screenshot: Zurück-Link Immobilien-Detailansicht](../assets/scst-dynamic-tag-url-4.png)

</div></div>

[](_backlink.md ':include')