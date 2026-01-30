<?php
/**
 * Widget Preview Template (Short Description)
 *
 * @package immonex\KickstartElementor
 */

?>
<#
let contents = <?php echo $template_data['demo_content']; ?>;
let short_desc = contents.short_desc

if (contents.short_desc.length > settings.max_length) {
	const words = contents.short_desc.split(' ')
	short_desc = ''

	for (let i = 0; i < words.length; i++) {
		if (i > 0 && short_desc.length + words[i].length > settings.max_length) {
			break
		}

		short_desc += words[i] + ' '
	}

	short_desc = short_desc.trim()

	if (settings.continuation_points) {
		short_desc += ' ' + settings.continuation_points
	}
}
#>
<div class="inx-e-short-desc">
	<# if (settings.p_wrap) { #><p><# } #>
	{{{ short_desc }}}
	<# if (settings.p_wrap) { #></p><# } #>
</div>
