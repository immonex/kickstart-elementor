<?php
/**
 * Class Areas_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Areas Widget
 *
 * @since 1.0.0
 */
class Areas_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Key_Value_List_Widget {

	const WIDGET_NAME     = 'inx-e-single-property-areas';
	const WIDGET_ICON     = 'eicon-layout-settings';
	const ENABLE_ICONS    = false;
	const WIDGET_HELP_URL = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/zimmer-und-flaechen';
	const FIXED_ELEMENTS  = [ 'flaechen' ];

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Rooms & Areas', 'immonex-kickstart-elementor' );
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
					__( 'area', 'immonex-kickstart-elementor' ),
					__( 'areas', 'immonex-kickstart-elementor' ),
					__( 'size', 'immonex-kickstart-elementor' ),
					__( 'sizes', 'immonex-kickstart-elementor' ),
				]
			)
		);
	} // add_keywords

	/**
	 * Return default value for the given control.
	 *
	 * @since 1.0.0
	 *
	 * @param string        $control_id    Control ID.
	 * @param mixed|mixed[] $default_value Default value if not specified otherwise.
	 * @param string        $breakpoint    Elementor breakpoint key (optional).
	 *
	 * @return mixed[] Defaul data.
	 */
	protected function get_default( $control_id, $default_value = '', $breakpoint = 'default' ) {
		$defaults = [
			'item_order'         => 'label-value',
			'global_value_color' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_SECONDARY,
			'heading'            => __( 'Rooms and Areas', 'immonex-kickstart-elementor' ),
		];

		return ! empty( $defaults[ $control_id ] ) ? $defaults[ $control_id ] : $default_value;
	} // get_default

	/**
	 * Return predefined elements.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[] Element data.
	 */
	protected function get_predefined_elements() {
		if ( ! empty( $this->predefined_elements ) ) {
			return $this->predefined_elements;
		}

		$this->predefined_elements = [
			'flaechen' => [
				'element_type' => 'group',
			],
		];

		return parent::get_predefined_elements();
	} // get_predefined_elements

	/**
	 * Return demo contents for preview rendering.
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
					[
						'title' => __( 'Living Area', 'immonex-kickstart-elementor' ),
						'value' => '140 m²',
					],
					[
						'title' => __( 'Plot', 'immonex-kickstart-elementor' ),
						'value' => '820 m²',
					],
					[
						'title' => __( 'Usable Area', 'immonex-kickstart-elementor' ),
						'value' => '20 m²',
					],
					[
						'title' => __( 'Basement Area', 'immonex-kickstart-elementor' ),
						'value' => '34 m²',
					],
					[
						'title' => __( 'Total Rooms', 'immonex-kickstart-elementor' ),
						'value' => '5',
					],
					[
						'title' => __( 'Bathrooms', 'immonex-kickstart-elementor' ),
						'value' => '2',
					],
				],
			]
		);
	} // get_demo_content

} // class Areas_Widget
