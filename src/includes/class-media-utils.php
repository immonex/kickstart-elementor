<?php
/**
 * Class Media_Utils
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor;

/**
 * Media related helper methods.
 */
class Media_Utils {

	/**
	 * Add image to media library.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $source_image         Source image (absolute path).
	 * @param mixed[] $meta                 Optional: Image title (post_title) meta data (_*).
	 * @param bool    $placeholder_on_error Return placeholder on error.
	 *
	 * @return mixed[]|bool Image data (ID and URL) or false on error.
	 */
	public static function add_image_to_media_lib( $source_image, $meta = [], $placeholder_on_error = true ) {
		$utils = apply_filters( 'inx_elementor_get_utils', [] );

		$placeholder_url = \Elementor\Utils::get_placeholder_image_src();
		$placeholder     = [
			'id'  => 0,
			'url' => $placeholder_url,
		];

		require_once ABSPATH . 'wp-admin/includes/file.php';

		$temp_image = wp_tempnam( basename( $source_image ) );
		$temp_file  = $utils['wp_fs']->copy( $source_image, $temp_image, true );

		if ( ! $temp_file ) {
			return $placeholder_on_error ? $placeholder : false;
		}

		$dest_image = [
			'name'     => basename( $source_image ),
			'type'     => mime_content_type( $temp_image ),
			'tmp_name' => $temp_image,
			'size'     => filesize( $temp_image ),
		];

		$sideload = wp_handle_sideload(
			$dest_image,
			[ 'test_form' => false ]
		);

		if ( ! empty( $sideload['error'] ) ) {
			return $placeholder_on_error ? $placeholder : false;
		}

		$att_id = wp_insert_attachment(
			array(
				'guid'           => $sideload['url'],
				'post_mime_type' => $sideload['type'],
				'post_title'     => ! empty( $meta['post_title'] ) ? $meta['post_title'] : '',
				'post_excerpt'   => ! empty( $meta['post_excerpt'] ) ? $meta['post_excerpt'] : '',
				'post_content'   => ! empty( $meta['post_content'] ) ? $meta['post_content'] : '',
				'post_status'    => 'inherit',
			),
			$sideload['file']
		);

		if ( is_wp_error( $att_id ) || ! $att_id ) {
			return $placeholder_on_error ? $placeholder : false;
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';

		// phpcs:disable
		wp_update_attachment_metadata(
			$att_id,
			@wp_generate_attachment_metadata( $att_id, $sideload['file'] )
		);
		// phpcs:enable

		if ( ! empty( $meta ) ) {
			foreach ( $meta as $meta_key => $meta_value ) {
				if ( '_' === $meta_key[0] ) {
					update_post_meta( $att_id, $meta_key, $meta_value );
				}
			}
		}

		return [
			'id'  => $att_id,
			'url' => wp_get_attachment_image_url( $att_id, 'full' ),
		];
	} // add_image_to_media_lib

	/**
	 * Add image size URLs to an image data array.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $image_data Image data.
	 *
	 * @return mixed[] Image data with sub array "sizes".
	 */
	public static function add_image_sizes( $image_data ) {
		if ( ! is_array( $image_data ) ) {
			return $image_data;
		}

		$image_sizes         = get_intermediate_image_sizes();
		$image_data['sizes'] = [];

		if ( empty( $image_sizes ) ) {
			return $image_data;
		}

		foreach ( $image_sizes as $size ) {
			$image_data['sizes'][ $size ] = ! empty( $image_data['id'] ) ?
				wp_get_attachment_image_url( $image_data['id'], $size ) :
				$image_data['url'];
		}

		return $image_data;
	} // add_image_sizes

} // Media_Utils
