<?php
/**
 * Widget Preview Template (Main Image)
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

if ( contents.image['id'] ) {
	const image = {
		id: contents.image.id,
		url: contents.image['sizes'][settings.image_size],
		size: settings.image_size,
		dimension: settings.image_custom_dimension,
		model: view.getEditModel()
	};

	const imageUrl = elementor.imagesManager.getImageUrl( image );

	if ( ! imageUrl ) {
		return;
	}

	const imgClass = '';

	if ( '' !== settings.hover_animation ) {
		imgClass = 'elementor-animation-' + settings.hover_animation;
	}

	if ( settings.open_lightbox !== 'no' ) {
		#>
		<a class="elementor-clickable" data-elementor-open-lightbox="{{ settings.open_lightbox }}" href="{{ elementor.helpers.sanitizeUrl( contents.image.url ) }}">
		<#
	}
	#>
	<img src="{{ imageUrl }}" class="{{ imgClass }}">
	<#
	if ( settings.open_lightbox !== 'no' ) {
	#>
	</a>
	<#
	}
}
#>
