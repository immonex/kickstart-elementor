<?php
/**
 * Widget Preview Template (Labels)
 *
 * @package immonex\KickstartElementor
 */

?>
<#
view.addRenderAttribute('list_items', 'class', 'inx-e-labels__items');
view.addRenderAttribute('list_item', 'class', 'inx-e-labels__item');

const contents = <?php echo $template_data['demo_content']; ?>;

let items = [];
if (parseInt(settings.include_label_terms)) items = items.concat(contents.items.label);
if (parseInt(settings.include_marketing_type_terms)) items = items.concat(contents.items.marketing_type);
if (parseInt(settings.include_type_of_use_terms)) items = items.concat(contents.items.type_of_use);
if (parseInt(settings.include_property_type_terms)) items = items.concat(contents.items.property_type);
#>
<ul {{{ view.getRenderAttributeString('list_items') }}}>
	<# _.each(items, function(item) { #>
	<li {{{ view.getRenderAttributeString('list_item') }}}>
		<# if (item.before_item) { #>
		<span class="inx-e-labels__before-item">{{{ item.before_item }}}</span>
		<# } #>
		<span class="inx-e-labels__label {{{ item.class }}}">{{{ item.label }}}</span>
		<# if (item.after_item) { #>
		<span class="inx-e-labels__after-item">{{{ item.after_item }}}</span>
		<# } #>
	</li>
	<# }); #>
</ul>
