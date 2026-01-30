<?php
/**
 * Class Epass_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Energy Pass Widget
 *
 * @since 1.0.0
 */
class Epass_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Key_Value_List_Widget {

	const WIDGET_NAME     = 'inx-e-single-property-epass';
	const WIDGET_ICON     = 'eicon-plug';
	const WIDGET_HELP_URL = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/energieausweis';
	const ENABLE_ICONS    = false;
	const FIXED_ELEMENTS  = [ 'epass' ];

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Energy Pass', 'immonex-kickstart-elementor' );
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
					__( 'energy', 'immonex-kickstart-elementor' ),
					__( 'pass', 'immonex-kickstart-elementor' ),
					__( 'efficiency', 'immonex-kickstart-elementor' ),
					__( 'enev', 'immonex-kickstart-elementor' ),
					__( 'geg', 'immonex-kickstart-elementor' ),
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
			'heading'            => __( 'Energy Pass', 'immonex-kickstart-elementor' ),
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
			'epass' => [
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
						'title' => __( 'Type', 'immonex-kickstart-elementor' ),
						'value' => __( 'Consumption Pass', 'immonex-kickstart-elementor' ),
					],
					[
						'title' => __( 'Issuing Date', 'immonex-kickstart-elementor' ),
						'value' => __( '2024-10-01', 'immonex-kickstart-elementor' ),
					],
					[
						'title' => __( 'Primary Energy Carrier', 'immonex-kickstart-elementor' ),
						'value' => __( 'Oil', 'immonex-kickstart-elementor' ),
					],
					[
						'title' => __( 'Energy Consumption Parameter', 'immonex-kickstart-elementor' ),
						'value' => '148 kWh/(m²・a)',
					],
					[
						'title' => __( 'Energy Efficiency Class', 'immonex-kickstart-elementor' ),
						'value' => 'E',
					],
				],
			]
		);
	} // get_demo_content

} // class Epass_Widget
