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
 * Elementor Single Property Condition Widget
 *
 * @since 1.0.0
 */
class Condition_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Key_Value_List_Widget {

	const WIDGET_NAME     = 'inx-e-single-property-condition';
	const WIDGET_ICON     = 'eicon-rating';
	const WIDGET_HELP_URL = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/zustand-und-erschliessung';
	const ENABLE_ICONS    = false;
	const FIXED_ELEMENTS  = [ 'zustand' ];

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Condition & Development', 'immonex-kickstart-elementor' );
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
					__( 'condition', 'immonex-kickstart-elementor' ),
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
			'heading'            => __( 'Condition and Development', 'immonex-kickstart-elementor' ),
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
			'zustand' => [
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
						'title' => __( 'Build Year', 'immonex-kickstart-elementor' ),
						'value' => '1982',
					],
					[
						'title' => __( 'Condition', 'immonex-kickstart-elementor' ),
						'value' => __( 'modernized', 'immonex-kickstart-elementor' ),
					],
					[
						'title' => __( 'Construction Type', 'immonex-kickstart-elementor' ),
						'value' => __( 'massive', 'immonex-kickstart-elementor' ),
					],
					[
						'title' => __( 'Roof Type', 'immonex-kickstart-elementor' ),
						'value' => __( 'Gabled Roof', 'immonex-kickstart-elementor' ),
					],
				],
			]
		);
	} // get_demo_content

} // class Condition_Widget
