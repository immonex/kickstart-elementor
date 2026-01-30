=== immonex Kickstart Elementor ===
Contributors: immonex
Tags: realestate, elementor, immobilien, immobilienmakler, openimmo
Requires at least: 6.5
Tested up to: 7.0
Stable Tag: 1.0.0
Requires PHP: 8.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

35+ widgets, dynamic tags, and more to create professional real estate websites with immonex Kickstart and Elementor

== Description ==

This **add-on plugin** extends [immonex Kickstart](https://wordpress.org/plugins/immonex-kickstart/) powered WordPress installations by a variety of widgets and other dynamic content elements for the quick and easy implementation of real estate agency and portal sites with the [Elementor Website Builder](https://wordpress.org/plugins/elementor/).

The focus here is on the presentation of real estate offers, the data of which is managed using a common software solution for real estate agents and continuously synchronized via an OpenImmo® XML interface with [immonex OpenImmo2WP](https://plugins.inveris.de/wordpress-plugins/immonex-openimmo2wp) ([Doc](https://docs.immonex.de/openimmo2wp/)) on the WP import side.

All widgets can be used in full with the **free Elementor version**. [Elementor Pro](https://elementor.com/products/website-builder/) is only required for using the *dynamic tags*.

The plugin supports both Kickstart's basic frontend components – property list and detail views, search form, image/video galleries, maps, 360° tours ... – and those of its add-ons like [Team](https://wordpress.org/plugins/immonex-kickstart-team/) (contact persons, agencies, inquiry forms etc.) and *Print* (PDF exposé generation).

Suitable widgets are also available for embedding content elements provided by the following immonex plugins:

- [Energy Scale Pro](https://plugins.inveris.de/wordpress-plugins/immonex-energy-scale-pro) [2] ([Doc](https://docs.immonex.de/energy-scale-pro/)): energy scales according to EnEV or GEG
- [Lead Generator](https://plugins.inveris.de/wordpress-plugins/immonex-lead-generator) [2] ([Doc](https://docs.immonex.de/lead-generator/)): valuation request forms for generating qualified seller contacts
- [Notify](https://plugins.inveris.de/wordpress-plugins/notify) [2] ([Doc](https://docs.immonex.de/notify/)): property search agent with email notifications

With the widgets and dynamic tags provided by this add-on, **template pages** (e.g., for real estate archives and individual pages) can be implemented both as Elementor Pro templates and as regular pages that can be used in combination with the free version of Elementor.

Custom layouts created in this way can be seamlessly integrated into the framework of any WordPress theme. Furthermore, multilingual websites can also be created on this basis with [WPML](https://wpml.org/) or [Polylang](https://wordpress.org/plugins/polylang/).

= immonex® =

**immonex** is the *PropTech umbrella brand* of a versatile portfolio of software solutions for the German-speaking real estate industry.

As a part of this, the **immonex WP Plugin Suite** includes a wide range of WordPress plugins for the implementation of sophisticated real estate agency websites and portals, which can be flexibly combined depending on the specific project requirements.

[immonex Kickstart](https://wordpress.org/plugins/immonex-kickstart/) is a license-fee free [Open Source Plugin](https://github.com/immonex/kickstart/) that extends WordPress sites – regardless of the theme used – by essential components for publishing real estate offers, which are synchronized via an import interface: list and detail views, property search, location maps etc. The range of functions and content elements can be expanded modularly by various add-ons as needed.

= OpenImmo® =

[OpenImmo-XML](http://openimmo.de/) is a proven standard for the exchange of real estate data, which is supported primarily in German-speaking markets by almost all common software solutions and portals for real estate professionals in the form of corresponding import/export interfaces.

immonex OpenImmo2WP [2], initially released in 2015, is a tried and tested solution for importing OpenImmo-XML data into WordPress sites that supports the specific data structures of various popular real estate themes and frontend plugins by means of customizable *mapping tables*.

= Main Features =

* 35+ special Elementor widgets for real estate presentation and marketing with extensive configuration options
* Dynamic Tags for embedding real estate text and image contents with Elementor Pro standard widgets
* Use of regular pages as templates with the free version of Elementor
* Support for multilingual websites (WPML, Polylang)

== Installation ==

= Prerequisities =

The following required plugins must be installed and active:

- [Elementor Website Builder](https://wordpress.org/plugins/elementor/)
- [immonex Kickstart](https://wordpress.org/plugins/immonex-kickstart/)
- [immonex OpenImmo2WP](https://plugins.inveris.de/wordpress-plugins/immonex-openimmo2wp) [2]
- [immonex Kickstart Team Add-on](https://wordpress.org/plugins/immonex-kickstart-team/)

Optional plugins (additional functions and frontend elements):

- [Elementor Pro](https://elementor.com/products/website-builder/) (required for using *dynamic tags*)
- immonex Kickstart Print Add-on (release in preparation)
- [immonex Energy Scale Pro](https://plugins.inveris.de/wordpress-plugins/immonex-energy-scale-pro) [2]
- [immonex Lead Generator](https://plugins.inveris.de/wordpress-plugins/immonex-lead-generator) [2]
- [immonex Notify](https://plugins.inveris.de/wordpress-plugins/notify) [2]

= Steps =

immonex Kickstart Elementor is available in the official [WordPress Plugin Directory](https://wordpress.org/plugins/) and can be installed via the WordPress backend.

1. *Plugins > Add New > Search for "immonex" ...* [1]
2. Elementor Pro only: Enable Properties and optionally Agencies/Agents under *Elementor > Editor > Settings > Post Types*
3. Create property list and detail view templates with the Elementor Editor [3].
4. Assign templates under *immonex > Settings > Lists (Property Overview)* and *... > Detail View (Property Details Page)*: previously created pages (free Elementor version) or "none (use **theme** template)" (Elementor Pro).

[1] Alternative: Download the plugin ZIP file from [immonex.dev](https://immonex.dev/) and install it via *Upload Plugin* **or** unzip and transfer it to the folder `wp-content/plugins` of the WP installation manually, activate it under *Plugins > Installed Plugins* afterwards.

[2] Current and fully functional versions (including betas) of all immonex plugins (free and premium) as well as OpenImmo demo data can be downloaded and licensed **free of charge** at the [immonex Developer Portal](https://immonex.dev/) for testing, development and demonstration purposes.

[3] With the free version of Elementor, templates can be created as regular pages. These can then be assigned in the immonex Kickstart settings. Elementor Pro allows the direct creation and assignment of archive/single templates for the property, agency and agent post types.

= Documentation & Development =

A detailed plugin documentation in German can be found here:

[docs.immonex.de/kickstart-elementor](https://docs.immonex.de/kickstart-elementor/)

immonex Kickstart Elementor is free software. Sources, development docs/support and issue tracking is provided via GitHub:

[github.com/immonex/kickstart-elementor](https://github.com/immonex/kickstart-elementor)

== Screenshots ==

1. Creating a property overview page including map and search form
2. Creating a property detail page template (1)
3. Creating a property detail page template (2)
4. Creating a property detail page template (3)
5. Agency list view (Kickstart Team Add-on)
6. Agency detail view (Kickstart Team Add-on)
7. Agent detail view (Kickstart Team Add-on)
8. Property overview map widget options
9. Property carousel view (slider)
10. Property features widget options
11. Lead Generator widget (immonex Lead Generator)
12. Search Agent Form widget (immonex Notify)

== Changelog ==

= 1.0.0 "Ice" =
* Release date: 2026-01-30
* Initial release.

See changelog.txt for the complete version history.