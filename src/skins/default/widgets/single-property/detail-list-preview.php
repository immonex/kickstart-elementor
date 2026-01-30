<?php
/**
 * Widget Preview Template (Detail List)
 *
 * @package immonex\KickstartElementor
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<#
view.addRenderAttribute('list_items', 'class', 'inx-e-key-value-list__items');
view.addRenderAttribute('list_item', 'class', 'inx-e-key-value-list__item');

const contents = <?php echo $template_data['demo_content_escaped']; // phpcs:ignore ?>;
const hLevel = parseInt(settings.heading_level) + parseInt(contents['heading_base_level']) - 1;
const h = 'h' + hLevel;
const show = settings.item_order.split('-');

if (settings.heading) {
	#>
	<{{{ h }}} class="inx-e-heading">{{{ settings.heading }}}</{{{ h }}}>
	<#
}
#>
<ul {{{ view.getRenderAttributeString('list_items') }}}>
	<#
	_.each(settings.elements, function(element) {
		const iconHTML = show.includes('icon') && element.icon ?
			elementor.helpers.renderIcon(view, element.icon, { 'aria-hidden': true }, 'i' , 'object') :
			false;

		const items = element.format in contents.items ? contents.items[element.format] : contents.items['generic'];

		_.each(items, function(item) {
			const value = element.decimal_places && typeof item.value === 'object' && element.decimal_places in item.value ?
				item.value[element.decimal_places] : item.value;
			#>
			<li {{{ view.getRenderAttributeString('list_item') }}}>
					<div class="inx-e-key-value-list__inner-item">
					<# if (element.before_item) { #>
					<span class="inx-e-key-value-list__before-item">{{{ element.before_item }}}</span>
					<# } #>
					<# if (iconHTML.value) { #>
					<span class="inx-e-key-value-list__icon">
						{{{ iconHTML.value }}}
					</span>
					<# } #>
					<# if (show.includes('label')) { #>
					<span class="inx-e-key-value-list__label">{{{ item.title }}}</span>
					<# } #>
					<# if (show.includes('value')) { #>
					<span class="inx-e-key-value-list__value">
						<# if (element.before_value) { #>
							{{{ element.before_value }}}
						<# } #>
						{{{ value }}}
						<# if (element.after_value) { #>
							{{{ element.after_value }}}
						<# } #>
					</span>
					<# } #>
					<# if (element.after_item) { #>
					<span class="inx-e-key-value-list__after-item">{{{ element.after_item }}}</span>
					<# } #>
				</div>
			</li>
			<#
		});
	});
	#>
</ul>
