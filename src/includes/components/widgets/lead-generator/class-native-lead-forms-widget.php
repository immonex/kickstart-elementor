<?php
/**
 * Class Native_Lead_Forms_Widget
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor\Components\Widgets\LeadGenerator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Native Lead Generation Forms Widget
 *
 * @since 1.0.0
 */
class Native_Lead_Forms_Widget extends \immonex\Kickstart\ForElementor\Components\Widgets\Widget_Base {

	const WIDGET_NAME              = 'inx-e-native-lead-forms';
	const WIDGET_ICON              = 'eicon-price-table';
	const WIDGET_CATEGORIES        = [ 'inx-marketing-acquisition' ];
	const WIDGET_HELP_URL          = 'https://docs.immonex.de/kickstart-for-elementor/#/elementor-immobilien-widgets/lead-generator';
	const ENABLE_RENDER_ON_PREVIEW = true;
	const PARENT_PLUGIN_NAME       = 'immonex Lead Generator';
	const PARENT_PLUGIN_SHOP_URL   = 'https://plugins.inveris.de/wordpress-plugins/immonex-lead-generator';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Lead Generator', 'immonex-kickstart-for-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'lead', 'immonex-kickstart-for-elementor' ),
					__( 'forms', 'immonex-kickstart-for-elementor' ),
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

		$lead_gen_options     = apply_filters( 'immonex_options', [], 'lead_gen' ); // phpcs:ignore -- Common framework filter hook for all immonex plugins.
		$additional_form_sets = ! empty( $lead_gen_options['additional_form_sets'] ) ?
			(int) $lead_gen_options['additional_form_sets'] : 0;

		if ( ! empty( $lead_gen_options['property_type_form_data'] ) ) {
			$ff_options = [
				'' => __( 'Default', 'immonex-kickstart-for-elementor' ),
			];

			foreach ( $lead_gen_options['property_type_form_data'] as $i => $option ) {
				$ff_options[ (string) $i ] = $option['form_title'];
			}
		}

		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_main_class_control();

		if ( $additional_form_sets > 0 ) {
			$options = [
				'' => __( 'Default Form', 'immonex-kickstart-for-elementor' ),
			];

			for ( $i = 1; $i <= $additional_form_sets; $i++ ) {
				$options[ (string) $i ] = (string) $i;
			}

			$this->add_control(
				'form_set_id',
				[
					'label'       => __( 'Form Set ID', 'immonex-kickstart-for-elementor' ),
					'type'        => \Elementor\Controls_Manager::SELECT,
					'description' => sprintf(
						/* translators: 1: Link open tag, 2: Link close tag. */
						__( 'If an %1$salternative set of property type forms%2$s shall be embedded, select its ID here.', 'immonex-kickstart-for-elementor' ),
						'<a href="https://docs.immonex.de/lead-generator/#/installation-einrichtung/einbindung?id=formularset" target="_blank">',
						'</a>'
					),
					'options'     => $options,
				]
			);
		}

		$this->add_control(
			'fast-forward',
			[
				'label'       => __( 'Fast Forward', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => sprintf(
					/* translators: 1: Link open tag, 2: Link close tag. */
					__( 'A %1$sspecific property type form%2$s to start with can be selected here.', 'immonex-kickstart-for-elementor' ),
					'<a href="https://docs.immonex.de/lead-generator/#/installation-einrichtung/einbindung?id=fast-forward" target="_blank">',
					'</a>'
				),
				'options'     => $ff_options,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'general_frame_style_section',
			[
				'label' => __( 'General', 'immonex-kickstart-for-elementor' ) . ' / ' .
					__( 'Frame', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'enable_custom_bg',
			[
				'label'        => __( 'Custom Background', 'immonex-kickstart-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'description'  => __( 'Enable the use of custom background/border settings (<strong>Advanced</strong> tab).', 'immonex-kickstart-for-elementor' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'custom_bg',
			[
				'type'      => \Elementor\Controls_Manager::HIDDEN,
				'default'   => 'initial',
				'selectors' => [
					'{{WRAPPER}} #immonex-lead-generator .immonex-lead-gen-form' => 'background: {{VALUE}}',
					'{{WRAPPER}} #immonex-lead-generator .immonex-lead-gen-form .immonex-lead-gen-element' => 'background: {{VALUE}}',
				],
				'condition' => [
					'enable_custom_bg' => '1',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-lead-gen-form' => 'color: {{VALUE}}',
					'{{WRAPPER}} .immonex-lead-gen-element--textblock' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} #immonex-lead-generator .immonex-lead-gen-form .immonex-lead-gen-element--textblock',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'form_page_hl_style_section',
			[
				'label' => __( 'Headline', 'immonex-kickstart-for-elementor' ) .
					' (' . __( 'Form Page', 'immonex-kickstart-for-elementor' ) . ')',
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_page_hl_text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #immonex-lead-generator .immonex-lead-gen-form .immonex-lead-gen-element--headline' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'form_page_hl_typography',
				'selector' => '{{WRAPPER}} #immonex-lead-generator .immonex-lead-gen-form .immonex-lead-gen-element--headline',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'buttons_style_section',
			[
				'label' => __( 'Buttons', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'buttons_bg_color',
			[
				'label'     => __( 'Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .el-button' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .el-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} #immonex-lead-generator .immonex-lead-gen-form .el-button',
			]
		);

		$this->add_control(
			'buttons_margin',
			[
				'label'      => __( 'Margin', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .el-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'buttons_padding',
			[
				'label'      => __( 'Padding', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} #immonex-lead-generator .immonex-lead-gen-form .el-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'after',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'buttons_border',
				'selector' => '{{WRAPPER}} .el-button',
			]
		);

		$this->add_responsive_control(
			'buttons_border_radius',
			[
				'label'      => __( 'Corner Radius', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .el-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'buttons_box_shadow',
				'label'    => __( 'Box Shadow', 'immonex-kickstart-for-elementor' ),
				'selector' => '{{WRAPPER}} .el-button',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'select_cards_style_section',
			[
				'label' => __( 'Selection Cards', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'select_cards_border',
				'selector' => '{{WRAPPER}} .immonex-lead-gen-element--card-select--item',
			]
		);

		$this->add_responsive_control(
			'select_cards_border_radius',
			[
				'label'      => __( 'Corner Radius', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .immonex-lead-gen-element--card-select--item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'select_cards_box_shadow',
				'label'    => __( 'Box Shadow', 'immonex-kickstart-for-elementor' ),
				'selector' => '{{WRAPPER}} .immonex-lead-gen-element--card-select--item',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'select_cards_typography',
				'selector' => '{{WRAPPER}} .immonex-lead-gen-element--card-select--item',
			]
		);

		$this->add_control(
			'select_cards_inactive_header',
			[
				'label'     => __( 'Inactive', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'select_cards_bg_color',
			[
				'label'     => __( 'Background Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-lead-gen-element--card-select--item' => 'background: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'select_cards_text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-lead-gen-element--card-select--item' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'select_cards_hover_header',
			[
				'label' => __( 'Hover', 'immonex-kickstart-for-elementor' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'select_cards_bg_color_hover',
			[
				'label'     => __( 'Background Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-lead-gen-element--card-select--item:hover' => 'background: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'select_cards_text_color_hover',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-lead-gen-element--card-select--item:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'select_cards_active_header',
			[
				'label' => __( 'Active', 'immonex-kickstart-for-elementor' ) . ' (' . __( 'Selected', 'immonex-kickstart-for-elementor' ) . ')',
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'select_cards_bg_color_active',
			[
				'label'     => __( 'Background Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-lead-gen-element--card-select--item.is-selected' => 'background: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'select_cards_text_color_active',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-lead-gen-element--card-select--item.is-selected' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'progress_bar_style_section',
			[
				'label' => __( 'Progress Bar', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'progress_bar_base_color',
			[
				'label'     => __( 'Base Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-lead-gen-progress-bar .el-step__title.is-wait' => 'color: {{VALUE}}',
					'{{WRAPPER}} .immonex-lead-gen-progress-bar .el-step__head.is-wait' => 'border-color: {{VALUE}}; color: {{VALUE}}',
					'{{WRAPPER}} .immonex-lead-gen-progress-bar .el-step__line' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .immonex-lead-gen-progress-bar .el-step .el-step__head.is-process .el-step__icon.is-text' => 'background-color: {{VALUE}}; border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'progress_bar_active_item_text_color',
			[
				'label'     => __( 'Active Step Text Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-lead-gen-progress-bar .el-step .el-step__title.is-process' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'progress_bar_completed_item_color',
			[
				'label'     => __( 'Completed Step Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-lead-gen-progress-bar .el-step .el-step__title.is-success' => 'color: {{VALUE}}',
					'{{WRAPPER}} .el-step__head.is-success' => 'border-color: {{VALUE}}; color: {{VALUE}}',
					'{{WRAPPER}} .immonex-lead-gen-progress-bar .el-step__head.is-success .el-step__line' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'value_slider_style_section',
			[
				'label' => __( 'Value Slider', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'value_slider_base_color',
			[
				'label'     => __( 'Base Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .el-slider__runway' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'value_slider_active_bar_color',
			[
				'label'     => __( 'Active Bar Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .el-slider__bar'    => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .el-slider__button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'input_style_section',
			[
				'label' => __( 'Input Fields', 'immonex-kickstart-for-elementor' ) . ' / ' . __( 'Select Boxes', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'input_bg_color',
			[
				'label'     => __( 'Background Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .el-input__wrapper, {{WRAPPER}} .el-select__wrapper, {{WRAPPER}} .el-radio__inner' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} #immonex-lead-generator .el-textarea textarea.el-textarea__inner' => 'border: none !important; background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .el-input__inner, {{WRAPPER}} .el-select__selected-item, {{WRAPPER}} #immonex-lead-generator input::placeholder' => 'color: {{VALUE}}',
					'{{WRAPPER}} .el-select__placeholder, {{WRAPPER}} .el-select__placeholder.is-transparent, {{WRAPPER}} .el-radio__inner, {{WRAPPER}} .el-select__caret' => 'color: {{VALUE}}',
					'{{WRAPPER}} #immonex-lead-generator textarea.el-textarea__inner' => 'border: none !important; color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_radio_selection_color',
			[
				'label'     => __( 'Current Radio Selection Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .el-radio__input.is-checked + .el-radio__label' => 'color: {{VALUE}}',
					'{{WRAPPER}} .el-radio__input.is-checked .el-radio__inner' => 'background: {{VALUE}}; border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'input_box_shadow',
				'label'    => __( 'Box Shadow', 'immonex-kickstart-for-elementor' ),
				'selector' => '{{WRAPPER}} .el-input__wrapper, {{WRAPPER}} .el-textarea',
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

		$template_data = [
			'settings' => $this->get_settings_for_display(),
		];

		$ext_atts = [ 'form_set_id', 'fast-forward' ];

		$this->add_extended_sc_atts( $ext_atts, $template_data );

		$template_data['shortcode_output'] = do_shortcode( '[immonex-lead-generator ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $template_data['shortcode_output'] ? $template_data : false;
	} // get_template_data

} // class Native_Lead_Forms_Widget
