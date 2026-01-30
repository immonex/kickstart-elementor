<?php
/**
 * Widget Preview Template (Feature List)
 *
 * @package immonex\KickstartElementor
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<#
view.addRenderAttribute('list_items', 'class', 'inx-e-icon-list__items');
view.addRenderAttribute('list_item', 'class', 'inx-e-icon-list__item');

const contents = <?php echo $template_data['demo_content_escaped']; // phpcs:ignore ?>;
const hLevel = parseInt(settings.heading_level) + parseInt(contents['heading_base_level']) - 1
const h = 'h' + hLevel
const iconHTML = settings.icon ?
	elementor.helpers.renderIcon(view, settings.icon, { 'aria-hidden': true }, 'i' , 'object') :
	'';
if (settings.heading) {
	#>
	<{{{ h }}} class="inx-e-heading">{{{ settings.heading }}}</{{{ h }}}>
	<#
}
#>
<ul {{{ view.getRenderAttributeString('list_items') }}}>
	<# _.each(contents.items, function(item) { #>
	<li {{{ view.getRenderAttributeString('list_item') }}}>
		<# if (iconHTML) { #>
		<span class="inx-e-icon-list__icon">
			{{{ iconHTML.value }}}
		</span>
		<# } #>

		<span class="inx-e-icon-list__text">{{{ item }}}</span>
	</li>
	<# }); #>
</ul>
