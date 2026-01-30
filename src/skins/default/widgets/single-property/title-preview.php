<?php
/**
 * Widget Preview Template (Title)
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
#>
<{{{ settings.html_tag }}} class="inx-e-heading">{{{ contents.title }}}</{{{ settings.html_tag }}}>
