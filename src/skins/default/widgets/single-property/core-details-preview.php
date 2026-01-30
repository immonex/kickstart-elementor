<?php
/**
 * Widget Preview Template (Core Details List)
 *
 * @package immonex\KickstartElementor
 */

?>
<#
view.addRenderAttribute('list_items', 'class', 'inx-e-key-value-list__items');
view.addRenderAttribute('list_item', 'class', 'inx-e-key-value-list__item');

const contents = <?php echo $template_data['demo_content']; ?>;
const show = settings.item_order.split('-');
#>
<ul {{{ view.getRenderAttributeString('list_items') }}}>
	<#
	_.each(settings.elements, function(element) {
		if (!contents.predefined_elements.hasOwnProperty(element.predefined_element)) {
			return;
		}

		const item = contents.predefined_elements[element.predefined_element];
		if (!item.hasOwnProperty('value')) {
			return;
		}

		const iconHTML = show.includes('icon') && element.icon ?
			elementor.helpers.renderIcon(view, element.icon, { 'aria-hidden': true }, 'i' , 'object') :
			false;
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
						{{{ item.value }}}
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
	#>
</ul>
