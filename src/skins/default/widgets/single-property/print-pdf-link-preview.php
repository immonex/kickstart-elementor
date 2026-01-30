<?php
/**
 * Widget Preview Template (Print/PDF Link)
 *
 * @package immonex\KickstartElementor
 */

?>
<#
const contents = <?php echo $template_data['demo_content']; ?>;
const link_text = settings.link_text || (settings.icon && settings.icon.value) ?
	settings.link_text : contents.default_link_text;
const iconHTML = settings.icon ?
	elementor.helpers.renderIcon(view, settings.icon, { 'aria-hidden': true }, 'i' , 'object') :
	'';
#>
<a href="{{ contents.url }}" class="inx-e-print-pdf-link">
	<# if (iconHTML && settings.icon_align === 'left') { #>
	<span class="inx-e-print-pdf-link__icon">
		{{{ iconHTML.value }}}
	</span>
	<# } #>

	<# if (link_text) { #>
	<span class="inx-e-print-pdf-link__link-text">{{ link_text }}</span>
	<# } #>

	<# if (iconHTML && settings.icon_align === 'right') { #>
	<span class="inx-e-print-pdf-link__icon">
		{{{ iconHTML.value }}}
	</span>
	<# } #>
</div>
