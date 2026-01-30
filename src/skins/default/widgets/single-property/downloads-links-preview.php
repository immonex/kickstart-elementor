<?php
/**
 * Widget Preview Template (Downloads and Links)
 *
 * @package immonex\KickstartElementor
 */

?>
<#
view.addRenderAttribute('list_items', 'class', 'inx-e-icon-list__items');
view.addRenderAttribute('list_item', 'class', 'inx-e-icon-list__item');

const contents = <?php echo $template_data['demo_content']; ?>;
const hLevel = parseInt(settings.heading_level) + parseInt(contents['heading_base_level']) - 1
const h = 'h' + hLevel

if (settings.heading) {
	#>
	<{{{ h }}} class="inx-e-heading">{{{ settings.heading }}}</{{{ h }}}>
	<#
}
#>
<ul {{{ view.getRenderAttributeString('list_items') }}}>
	<#
	_.each(contents.items, function(item, i) {
		const iconHTML = elementor.helpers.renderIcon(view, item.icon, { 'aria-hidden': true }, 'i' , 'object');
		if (item.type === 'link') {
			view.addRenderAttribute('link-' + i, 'target', '_blank');
		}
		#>
	<li {{{ view.getRenderAttributeString('list_item') }}}>
		<span class="inx-e-icon-list__icon">{{{ iconHTML.value }}}</span>
		<span class="inx-e-icon-list__text">
			<a href="{{{ item.url }}}" {{{ view.getRenderAttributeString('link-' + i) }}}>{{{ item.title }}}</a>
		</span>
	</li>
		<#
	});
	#>
</ul>
