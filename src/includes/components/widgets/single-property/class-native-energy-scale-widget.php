<?php
/**
 * Class Native_Energy_Scale
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Native Energy Scale Widget
 *
 * @since 1.0.0
 */
class Native_Energy_Scale_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const POST_TYPE                = 'inx_property';
	const WIDGET_NAME              = 'inx-e-single-property-native-energy-scale';
	const WIDGET_ICON              = 'eicon-dashboard';
	const WIDGET_CATEGORIES        = [ 'inx-single-property' ];
	const WIDGET_HELP_URL          = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/energieskala';
	const ENABLE_RENDER_ON_PREVIEW = true;
	const PARENT_PLUGIN_NAME       = 'immonex Energy Scale Pro';
	const PARENT_PLUGIN_SHOP_URL   = 'https://plugins.inveris.de/wordpress-plugins/immonex-energy-scale-pro';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Energy Scale', 'immonex-kickstart-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'scale', 'immonex-kickstart-elementor' ),
				]
			)
		);
	} // add_keywords

	/**
	 * Register widget controls.
	 *
	 * @since 1.0.0
	 */
	protected function register_controls() {
		if ( ! $this->parent_plugin_available ) {
			return;
		}

		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_main_class_control();

		$this->add_control(
			'display_if_unavailable',
			[
				'label'        => __( 'Always show', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'description'  => __( 'Display a notice and a preview scale if the energy data of the respective property are not available (yet).', 'immonex-kickstart-elementor' ),
				'default'      => '1',
				'return_value' => '1',
			]
		);

		$this->add_control(
			'display_errors',
			[
				'label'        => __( 'Display Errors', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'description'  => __( 'This option can be enabled <strong>temporary</strong> for troubleshooting purposes.', 'immonex-kickstart-elementor' ),
				'default'      => '0',
				'return_value' => '1',
			]
		);

		$this->add_control(
			'remarks',
			[
				'label'       => __( 'Remarks', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Optional short text to display under the graphical scale', 'immonex-kickstart-elementor' ),
				'label_block' => true,
				'separator'   => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'scale_section',
			[
				'label' => __( 'Scale', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'template',
			[
				'label'   => __( 'Template', 'immonex-kickstart-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''             => __( 'Default', 'immonex-kickstart-elementor' ),
					'auto'         => __( 'Automatic Selection', 'immonex-kickstart-elementor' ),
					'bandtacho'    => __( 'Bandtacho', 'immonex-kickstart-elementor' ),
					'stacked_bars' => __( 'Stacked Bars', 'immonex-kickstart-elementor' ),
				],
			]
		);

		$this->add_control(
			'display',
			[
				'label'       => __( 'Display Type', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'The compact display type is better suited for embedding in narrow columns.', 'immonex-kickstart-elementor' ),
				'options'     => [
					''        => __( 'Standard', 'immonex-kickstart-elementor' ),
					'compact' => __( 'Compact', 'immonex-kickstart-elementor' ),
				],
			]
		);

		$this->add_control(
			'scale_text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-energy-scale > *:not(.immonex-energy-scale-remarks)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'scale_typography',
				'selector' => '{{WRAPPER}} .immonex-energy-scale > *:not(.immonex-energy-scale-remarks)',
				'exclude'  => [ 'font_size' ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'remarks_section',
			[
				'label' => __( 'Remarks', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'remarks_bg_color',
			[
				'label'     => __( 'Background Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-energy-scale-remarks' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'remarks_text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-energy-scale-remarks' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'remarks_typography',
				'selector' => '{{WRAPPER}} .immonex-energy-scale-remarks',
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
		if ( ! $this->parent_plugin_available ) {
			return false;
		}

		$settings = $this->get_settings_for_display();

		$ext_atts = [
			'template',
			'display',
			'remarks',
			'display_if_unavailable',
			'display_errors',
		];

		foreach ( $ext_atts as $att ) {
			if ( ! empty( $settings[ $att ] ) ) {
				$this->add_render_attribute( 'shortcode', $att, $settings[ $att ] );
			}
		}

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$preview_data = [
				'type'          => 'consumption',
				'class'         => 'E',
				'year'          => '2014',
				'final_energy'  => 148,
				'co2_emissions' => 123,
			];

			foreach ( $preview_data as $att => $value ) {
				$this->add_render_attribute( 'shortcode', $att, $value );
			}
		}

		\Elementor\Plugin::$instance->frontend->remove_content_filter();
		$shortcode_output = do_shortcode( '[immonex-energy-scale ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );
		\Elementor\Plugin::$instance->frontend->add_content_filter();

		return $shortcode_output ?
			[
				'settings'         => $settings,
				'shortcode_output' => $shortcode_output,
			] :
			false;
	} // get_template_data

} // class Native_Energy_Scale_Widget
