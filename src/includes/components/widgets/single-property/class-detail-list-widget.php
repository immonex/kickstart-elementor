<?php
/**
 * Class Detail_List_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Detail List Widget
 *
 * @since 1.0.0
 */
class Detail_List_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Key_Value_List_Widget {

	const WIDGET_NAME     = 'inx-e-single-property-detail-list';
	const WIDGET_HELP_URL = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/flex-details';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Flex Details', 'immonex-kickstart-elementor' );
	} // get_title

	/**
	 * Add widget keywords.
	 *
	 * @since 1.0.0
	 */
	protected function add_keywords() {
		parent::add_keywords();

		$this->keywords = array_unique(
			array_merge(
				$this->keywords,
				[
					__( 'flexible', 'immonex-kickstart-elementor' ),
					__( 'details', 'immonex-kickstart-elementor' ),
				]
			)
		);
	} // add_keywords

	/**
	 * Return widget demo content based on the selected format type.
	 *
	 * @since 1.0.0
	 *
	 * @param string[]|null $content Source Demo content.
	 *
	 * @return string|null Demo content or empty string.
	 */
	protected function get_demo_content( $content = null ) {
		return parent::get_demo_content(
			[
				'items' => [
					'inx_format_price'  => [
						[
							'title' => __( 'Purchase Price', 'immonex-kickstart-elementor' ),
							'value' => [
								1 => number_format_i18n( 418000, 1 ) . '&nbsp;€',
								2 => number_format_i18n( 418000, 2 ) . '&nbsp;€',
								9 => number_format_i18n( 418000, 0 ) . '&nbsp;€',
							],
						],
						[
							'title' => __( 'Parking Space', 'immonex-kickstart-elementor' ),
							'value' => [
								1 => number_format_i18n( 12000.5, 1 ) . '&nbsp;€',
								2 => number_format_i18n( 12000.5, 2 ) . '&nbsp;€',
								9 => number_format_i18n( 12000.5, 2 ) . '&nbsp;€',
							],
						],
					],
					'inx_format_area'   => [
						[
							'title' => __( 'Living Space', 'immonex-kickstart-elementor' ),
							'value' => [
								1 => '142,0&nbsp;m²',
								2 => '142,00&nbsp;m²',
								9 => '142&nbsp;m²',
							],
						],
						[
							'title' => __( 'Usable Space', 'immonex-kickstart-elementor' ),
							'value' => [
								1 => number_format_i18n( 23.5, 1 ) . '&nbsp;m²',
								2 => number_format_i18n( 23.5, 2 ) . '&nbsp;m²',
								9 => number_format_i18n( 23.5, 1 ) . '&nbsp;m²',
							],
						],
						[
							'title' => __( 'Plot Area', 'immonex-kickstart-elementor' ),
							'value' => [
								1 => number_format_i18n( 840, 1 ) . '&nbsp;m²',
								2 => number_format_i18n( 840, 2 ) . '&nbsp;m²',
								9 => '840&nbsp;m²',
							],
						],
					],
					'inx_format_number' => [
						[
							'title' => __( 'Rooms', 'immonex-kickstart-elementor' ),
							'value' => [
								1 => number_format_i18n( 6, 1 ),
								2 => number_format_i18n( 6, 2 ),
								9 => '6',
							],
						],
						[
							'title' => __( 'Bedrooms', 'immonex-kickstart-elementor' ),
							'value' => [
								1 => number_format_i18n( 3, 1 ),
								2 => number_format_i18n( 3, 2 ),
								9 => '3',
							],
						],
						[
							'title' => __( 'Bathrooms', 'immonex-kickstart-elementor' ),
							'value' => [
								1 => number_format_i18n( 2, 1 ),
								2 => number_format_i18n( 2, 2 ),
								9 => '2',
							],
						],
						[
							'title' => __( 'Floors', 'immonex-kickstart-elementor' ),
							'value' => [
								1 => number_format_i18n( 2.5, 1 ),
								2 => number_format_i18n( 2.5, 2 ),
								9 => number_format_i18n( 2.5, 1 ),
							],
						],
					],
					'inx_format_link'   => [
						[
							'title' => __( 'Website', 'immonex-kickstart-elementor' ),
							'value' => '<a href="https://www.immonex.one/">www.immonex.one</a>',
						],
						[
							'title' => __( 'E-Mail', 'immonex-kickstart-elementor' ),
							'value' => wp_sprintf(
								'<a href="mailto:%1$s">%1$s</a>',
								_x( 'elena.example@immonex.one', 'sample data', 'immonex-kickstart-elementor' )
							),
						],
						[
							'title' => __( 'Phone', 'immonex-kickstart-elementor' ),
							'value' => '<a href="tel:+49 9999 123456">+49 9999 123456</a>',
						],
					],
					'generic'           => [
						[
							'title' => __( 'Build Year', 'immonex-kickstart-elementor' ),
							'value' => '2004',
						],
						[
							'title' => __( 'Flooring', 'immonex-kickstart-elementor' ),
							'value' => __( 'Parquet, Marble', 'immonex-kickstart-elementor' ),
						],
						[
							'title' => __( 'Condition', 'immonex-kickstart-elementor' ),
							'value' => __( 'modernized', 'immonex-kickstart-elementor' ),
						],
					],
				],
			]
		);
	} // get_demo_content

} // class Detail_List_Widget
