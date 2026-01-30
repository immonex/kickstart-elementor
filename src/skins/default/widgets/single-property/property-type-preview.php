<?php
/**
 * Widget Preview Template (Property Type)
 *
 * @package immonex\KickstartElementor
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<#
view.addRenderAttribute('list_items', 'class', 'inx-e-single-property-type__items');
if (settings.link_type) {
	view.addRenderAttribute('list_items', 'itemscope', '');
	view.addRenderAttribute('list_items', 'itemtype', 'https://schema.org/BreadcrumbList');
}

view.addRenderAttribute('list_item', 'class', 'inx-e-single-property-type__item');
if (settings.link_type) {
	view.addRenderAttribute('list_item', 'itemscope', '');
	view.addRenderAttribute('list_item', 'itemprop', 'itemListElement');
	view.addRenderAttribute('list_item', 'itemtype', 'https://schema.org/ListItem');
}

const contents = <?php echo $template_data['demo_content_escaped']; // phpcs:ignore ?>;
const iconHTML = settings.icon ?
	elementor.helpers.renderIcon(view, settings.icon, { 'aria-hidden': true }, 'i' , 'object') :
	false;
#>

<# if (settings.link_type) { #>
	<ol {{{ view.getRenderAttributeString('list_items') }}}>
		<#
		_.each(contents.items, function(item, index) {
			if (
				!settings.include_type_of_use && item.type === 'type_of_use'
				|| !settings.include_parent && item.type === 'parent'
			) {
				return;
			}
			#>
			<li {{{ view.getRenderAttributeString('list_item') }}}>
				<a itemprop="item" href="{{{ item.url }}}" class="inx-e-single-property-type__title">
					<span itemprop="name">{{{ item.title }}}</span>
				</a>
				<meta itemprop="position" content="{{{ index + 1 }}}" />
			</li>

			<# if (index < contents.items.length - 1) { #>
				<li class="inx-e-single-property-type__separator">
					<# if (iconHTML.value) { #>
						{{{ iconHTML.value }}}
					<# } else { #>
						&gt;
					<# } #>
				</li>
			<# } #>

			<#
			});
		#>
	</ol>
<# } else { #>
	<ul {{{ view.getRenderAttributeString('list_items') }}}>
		<#
		_.each(contents.items, function(item, index) {
			if (
				!settings.include_type_of_use && item.type === 'type_of_use'
				|| !settings.include_parent && item.type === 'parent'
			) {
				return;
			}
			#>
			<li {{{ view.getRenderAttributeString('list_item') }}}>
				<span class="inx-e-single-property-type__title">{{ item.title }}</span>
			</li>

			<# if (index < contents.items.length - 1) { #>
				<li class="inx-e-single-property-type__separator">
					<# if (iconHTML.value) { #>
						{{{ iconHTML.value }}}
					<# } else { #>
						&gt;
					<# } #>
				</li>
			<# } #>

			<#
			});
		#>
	</ul>
<# } #>
