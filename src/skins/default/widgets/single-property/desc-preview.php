<?php
/**
 * Widget Preview Template (Description Text)
 *
 * @package immonex\KickstartElementor
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<#
let contents = <?php echo $template_data['demo_content_escaped']; // phpcs:ignore ?>;

if (settings.format_ctext === 'yes') {
	contents[settings.field_name] = '<p>' + contents[settings.field_name].replace(/(\r\n|\r|\n){2}/g, '</p><p>') + '</p>'
}

let hLevel = parseInt(settings.heading_level) + parseInt(contents['heading_base_level']) - 1
let h = 'h' + hLevel

if (settings.heading) {
	#>
	<{{{ h }}} class="inx-e-heading">{{{ settings.heading }}}</{{{ h }}}>
	<#
}
#>
<div class="inx-e-desc-text">
	{{{ contents[settings.field_name] }}}
</div>
