<?php
/**
 * Class Demo_Media
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Demo Media Processing
 *
 * @since 1.0.0
 */
class Demo_Media {

	const DEMO_VIDEO_URLS       = [
		'https://www.youtube.com/watch?v=tBr_wFsHnOM',
		'https://www.youtube.com/watch?v=cMnZ25NyV-I&t=2s',
	];
	const DEMO_VIRTUAL_TOUR_URL = 'https://app.cloudpano.com/tours/t1LtFvj5DK';
	const DEMO_IMAGE_FILENAMES  = [
		'gallery'      => [
			'exterior-daniel-dinuzzo-676370-unsplash.jpg',
			'exterior-flo-pappert-201009-unsplash.jpg',
			'exterior-jared-brashier-272116-unsplash.jpg',
			'exterior-garrett-parker-247217-unsplash.jpg',
			'exterior-brandon-griggs-82205-unsplash.jpg',
			'interior-aaron-huber-384315-unsplash.jpg',
			'interior-daniil-silantev-574966-unsplash.jpg',
			'interior-ialicante-mediterranean-homes-475751-unsplash.jpg',
			'interior-neonbrand-381363-unsplash.jpg',
			'interior-neonbrand-381367-unsplash.jpg',
		],
		'floor_plans'  => [
			'floor-plan-architect-architecture-artist-268362.jpg',
			'floor-plan-pexels_com_architectural-design-architecture-blueprint-239886.jpg',
			'floor-plan-StockSnap_N3BPNPN0FY.jpg',
		],
		'epass_images' => [
			'epass.png',
		],
	];

	/**
	 * Retrieve and return the gallery demo image IDs.
	 *
	 * @return int[] Attachment IDs as two sub arrays ("gallery" and "floor_plans").
	 */
	public static function get_demo_image_ids() {
		$image_ids = [];

		foreach ( array_keys( self::DEMO_IMAGE_FILENAMES ) as $type ) {
			$args = [
				'post_type'   => 'attachment',
				'numberposts' => -1,
				'fields'      => 'ids',
				'meta_query'  => [
					[
						'key'   => '_inx_elementor_demo_content',
						'value' => "gallery_image_{$type}",
					],
				],
			];
			$ids  = get_posts( $args );

			if ( ! empty( $ids ) ) {
				$image_ids[ $type ] = $ids;
			}
		}

		if ( ! empty( $image_ids ) ) {
			return $image_ids;
		}

		return self::create_demo_images();
	} // get_demo_image

	/**
	 * Add the demo images to the WP media library and return the IDs.
	 *
	 * @return int[] Attachment IDs as two sub arrays ("gallery" and "floor_plans").
	 */
	public static function create_demo_images() {
		$image_ids  = [];
		$plugin_dir = apply_filters( 'inx_elementor_get_plugin_dir', '' ); // phpcs:ignore

		foreach ( array_keys( self::DEMO_IMAGE_FILENAMES ) as $type ) {
			$image_ids[ $type ] = [];

			foreach ( self::DEMO_IMAGE_FILENAMES[ $type ] as $filename ) {
				$source_image = trailingslashit( $plugin_dir ) . "assets/demo-images/{$filename}";

				$image_type_title = '';
				if ( 'exterior' === substr( $filename, 0, 8 ) ) {
					$image_type_title = __( 'Exterior', 'immonex-kickstart-elementor' );
				}
				if ( 'interior' === substr( $filename, 0, 8 ) ) {
					$image_type_title = __( 'Interior', 'immonex-kickstart-elementor' );
				}
				if ( 'floor-plan' === substr( $filename, 0, 10 ) ) {
					$image_type_title = __( 'Floor Plan', 'immonex-kickstart-elementor' );
				}
				if ( 'epass' === substr( $filename, 0, 5 ) ) {
					$image_type_title = __( 'Energy Pass', 'immonex-kickstart-elementor' );
				}

				$post_title_ext = $image_type_title ? ' (' . $image_type_title . ')' : '';

				$meta = [
					'post_title'                  => __( 'Gallery Image', 'immonex-kickstart-elementor' ) . $post_title_ext,
					'post_excerpt'                => ( $image_type_title ? $image_type_title : __( 'Gallery', 'immonex-kickstart-elementor' ) )
						. ' ' . __( 'Demo Image', 'immonex-kickstart-elementor' ),
					'_inx_elementor_demo_content' => "gallery_image_{$type}",
					'_wp_attachment_image_alt'    => 'immonex Kickstart Elementor ' . __( 'Demo Image', 'immonex-kickstart-elementor' ),
				];

				$image_data = \immonex\Kickstart\Elementor\Media_Utils::add_image_to_media_lib( $source_image, $meta );

				if ( $image_data['id'] ) {
					$image_ids[ $type ][] = $image_data['id'];
				}
			}
		}

		return $image_ids;
	} // create_demo_image

} // class Demo_Media
