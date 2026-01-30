<?php
/**
 * Class Prices_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Prices Widget
 *
 * @since 1.0.0
 */
class Prices_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Key_Value_List_Widget {

	const WIDGET_NAME     = 'inx-e-single-property-prices';
	const WIDGET_ICON     = 'eicon-price-list';
	const WIDGET_HELP_URL = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/preise';
	const ENABLE_ICONS    = false;
	const FIXED_ELEMENTS  = [ 'preise' ];

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Prices', 'immonex-kickstart-elementor' );
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
					__( 'price', 'immonex-kickstart-elementor' ),
					__( 'prices', 'immonex-kickstart-elementor' ),
					__( 'provision', 'immonex-kickstart-elementor' ),
					__( 'courtage', 'immonex-kickstart-elementor' ),
					__( 'costs', 'immonex-kickstart-elementor' ),
				]
			)
		);
	} // add_keywords

	/**
	 * Add widget element main class.
	 *
	 * @since 1.0.0
	 */
	protected function add_main_class() {
		parent::add_main_class();

		$this->main_classes[] = self::WIDGET_NAME;
	} // add_main_class

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
			'heading'            => __( 'Prices', 'immonex-kickstart-elementor' ),
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
			'preise' => [
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
						'title' => __( 'Purchase Price', 'immonex-kickstart-elementor' ),
						'value' => '340.000 €',
					],
					[
						'title' => __( 'Rental Revenues/Year', 'immonex-kickstart-elementor' ),
						'value' => '12.000 €',
					],
					[
						'title' => __( "Buyer's Provision", 'immonex-kickstart-elementor' ),
						'value' => '3,57 % ' . __( 'incl. VAT', 'immonex-kickstart-elementor' ),
					],
				],
			]
		);
	} // get_demo_content

} // class Prices_Widget
