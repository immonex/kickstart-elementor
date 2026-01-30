<?php
/**
 * Widget Preview Template (Areas List)
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
	<# _.each(contents.items, function(item) { #>
	<li {{{ view.getRenderAttributeString('list_item') }}}>
		<div class="inx-e-key-value-list__inner-item">
			<# if (item.before_item) { #>
			<span class="inx-e-key-value-list__before-item">{{{ item.before_item }}}</span>
			<# } #>
			<span class="inx-e-key-value-list__label">{{{ item.title }}}</span>
			<span class="inx-e-key-value-list__value">{{{ item.value }}}</span>
			<# if (item.after_item) { #>
			<span class="inx-e-key-value-list__after-item">{{{ item.after_item }}}</span>
			<# } #>
		</div>
	</li>
	<# }); #>
</ul>
