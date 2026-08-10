<?php
/**
 * Class Native_Filters_Sort_Widget
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor\Components\Widgets\PropertyList;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Native Filters and Sort Widget
 *
 * @since 1.0.0
 */
class Native_Filters_Sort_Widget extends \immonex\Kickstart\ForElementor\Components\Widgets\Widget_Base {

	const WIDGET_NAME              = 'inx-e-native-filters-sort';
	const WIDGET_ICON              = 'eicon-filter';
	const WIDGET_CATEGORIES        = [ 'inx-property-list' ];
	const WIDGET_HELP_URL          = 'https://docs.immonex.de/kickstart-for-elementor/#/elementor-immobilien-widgets/filter-sortierung';
	const ENABLE_RENDER_ON_PREVIEW = true;

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Filters/Sort', 'immonex-kickstart-for-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'filters', 'immonex-kickstart-for-elementor' ),
					__( 'sort', 'immonex-kickstart-for-elementor' ),
					__( 'order', 'immonex-kickstart-for-elementor' ),
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
		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$sort_options = $this->get_property_sort_options();

		$this->add_main_class_control();

		$this->add_control(
			'elements',
			[
				'label'         => __( 'Custom Option Scope', 'immonex-kickstart-for-elementor' ),
				'type'          => \Elementor\Controls_Manager::REPEATER,
				'label_block'   => true,
				'fields'        => [
					[
						'name'    => 'option',
						'label'   => __( 'Option', 'immonex-kickstart-for-elementor' ),
						'type'    => \Elementor\Controls_Manager::SELECT,
						'options' => $sort_options['array'],
					],
				],
				'title_field'   => "<# const labels = {$sort_options['json']}; const label = typeof option !== 'undefined' ? labels[option] : ''; #>{{{ label }}}",
				'prevent_empty' => false,
			]
		);

		$this->add_control(
			'exclude',
			[
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'label'       => __( 'Exclude Options', 'immonex-kickstart-for-elementor' ),
				'description' => __( 'Exclude selected options instead of explicitely including them.', 'immonex-kickstart-for-elementor' ),
			]
		);

		$this->add_control(
			'default',
			[
				'label'       => __( 'Default Option', 'immonex-kickstart-for-elementor' ),
				'description' => __( 'The default sorting option only needs to be selected here if it does not correspond to the first selection option and is not defined by GET parameter or filter function.', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => $sort_options['array'],
				'separator'   => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'frame_style_section',
			[
				'label' => __( 'Frame', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'background',
			[
				'label'       => __( 'Background', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'The <strong>default</strong> background color can be adjusted in the Kickstart plugin options.', 'immonex-kickstart-for-elementor' ),
				'options'     => [
					''            => __( 'Default', 'immonex-kickstart-for-elementor' ),
					'transparent' => __( 'Transparent', 'immonex-kickstart-for-elementor' ),
					'custom'      => __( 'Custom', 'immonex-kickstart-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'bg_transparent',
			[
				'type'      => \Elementor\Controls_Manager::HIDDEN,
				'default'   => $this->get_default( 'bg_transparent', 'none' ),
				'selectors' => [
					'{{WRAPPER}} .inx-property-filters' => 'background: {{VALUE}}',
				],
				'condition' => [
					'background' => 'transparent',
				],
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label'     => __( 'Background Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-property-filters' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'background' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label'      => __( 'Corner Radius', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .inx-property-filters' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'select_style',
			[
				'label' => __( 'Select Boxes', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'   => __( 'Alignment', 'immonex-kickstart-for-elementor' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'default' => $this->get_default( 'select_align', 'right' ),
				'options' => [
					'left'   => [
						'title' => __( 'Left', 'immonex-kickstart-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'immonex-kickstart-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'immonex-kickstart-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
			]
		);

		$this->add_responsive_control(
			'select_height',
			[
				'label'      => __( 'Height', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 16,
						'max' => 64,
					],
				],
				'default'    => $this->get_default( 'select_height', [ 'height' => '40px' ] ),
				'selectors'  => [
					'{{WRAPPER}} .inx-form-element--select > select.inx-select' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'after',
			]
		);

		$this->add_control(
			'select_bg_color',
			[
				'label'     => __( 'Background Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-form-element--select > select.inx-select' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'select_text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-form-element--select > select.inx-select' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'select_typography',
				'selector' => '{{WRAPPER}} .inx-form-element--select > select.inx-select',
				'exclude'  => ! empty( $element['exclude'] ) ? $element['exclude'] : [],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'select_shadow',
				'selector' => '{{WRAPPER}} .inx-form-element--select > select.inx-select',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'select_border',
				'selector'  => '{{WRAPPER}} .inx-form-element--select > select.inx-select',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'select_border_radius',
			[
				'label'      => __( 'Border Radius', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .inx-form-element--select > select.inx-select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'extended_style_section',
			[
				'label' => __( 'Extended', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_default_controls(
			'template',
			[
				'template' => [
					'folder' => 'property-list',
					'plugin' => 'immonex Kickstart',
				],
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
		$settings      = $this->get_settings_for_display();
		$template_data = [
			'settings' => $settings,
		];

		$ext_atts = [ 'align', 'template' ];

		$this->add_extended_sc_atts( $ext_atts, $template_data, 'property-list' );

		if ( ! empty( $settings['elements'] ) ) {
			$elements = [];

			foreach ( $settings['elements'] as $element ) {
				$elements[] = $element['option'];
			}

			$this->add_render_attribute( 'shortcode', $settings['exclude'] ? 'exclude' : 'elements', implode( ',', $elements ) );
		}

		if ( $settings['default'] ) {
			$this->add_render_attribute( 'shortcode', 'default', $settings['default'] );
		}

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'shortcode', 'is_preview', '1' );
		}

		$template_data['shortcode_output'] = do_shortcode( '[inx-filters-sort ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $template_data['shortcode_output'] ? $template_data : false;
	} // get_template_data

} // class Native_Filters_Sort_Widget
