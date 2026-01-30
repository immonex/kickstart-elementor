<?php
/**
 * Class Native_Virtual_Tour_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Native Video Gallery Widget
 *
 * @since 1.0.0
 */
class Native_Virtual_Tour_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Gallery_Widget {

	const WIDGET_NAME     = 'inx-e-single-property-native-virtual-tour';
	const WIDGET_ICON     = 'eicon-video-camera';
	const WIDGET_HELP_URL = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/virtuelle-360-grad-tour';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '360° Virtual Tour', 'immonex-kickstart-elementor' ) . self::NATIVE_POSTFIX;
	} // get_title

	/**
	 * Register widget controls.
	 *
	 * @since 1.0.0
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-elementor' ),
			]
		);

		$this->add_main_class_control();

		$this->add_control(
			'headline',
			[
				'label'       => __( 'Heading', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		$this->add_default_controls(
			[ 'heading_style' ],
			[
				'heading_style' => [
					'condition' => [
						'headline!' => '',
					],
				],
			],
			false
		);

		$this->add_control(
			'disable_heading_dividing_line',
			[
				'label'        => __( 'Hide Dividing Line', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => '0',
				'selectors'    => [
					'{{WRAPPER}} .inx-single-property__section-title.uk-heading-divider' => 'padding-bottom: {{VALUE}}; border-bottom: {{VALUE}};',
				],
				'separator'    => 'before',
			]
		);
	} // register_controls

	/**
	 * Return widget contents for frontend template rendering.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[]|bool Template data array or false if unavailable.
	 */
	protected function get_template_data() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'shortcode', 'elements', 'virtual_tour' );

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$virtual_tour_embed_code  = '<p style="margin:0; padding:16px; text-align:center; color:#FFF; background-color:#4C93EC">' . __( 'This is a <strong>demo</strong> of the <a href="https://www.cloudpano.com/" target="_blank">CloudPano</a> 360° tour service.', 'immonex-kickstart-elementor' ) . '</p>' . PHP_EOL;
			$virtual_tour_embed_code .= '<iframe width="640" height="480" src="' . \immonex\Kickstart\Elementor\Demo_Media::DEMO_VIRTUAL_TOUR_URL . '"></iframe>';

			$this->add_render_attribute( 'shortcode', 'is_preview', '1' );
			$this->add_render_attribute( 'shortcode', 'virtual_tour_embed_code', $virtual_tour_embed_code );
			$this->add_render_attribute( 'shortcode', 'virtual_tours_require_consent', '0' );
		}

		if ( ! empty( $settings['headline'] ) ) {
			$this->add_render_attribute( 'shortcode', 'headline', $settings['headline'] );
		}

		$shortcode_output = do_shortcode( '[inx-property-details ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $shortcode_output ?
			[
				'settings'         => $settings,
				'shortcode_output' => $shortcode_output,
			] :
			false;
	} // get_template_data

} // class Native_Virtual_Tour_Widget
