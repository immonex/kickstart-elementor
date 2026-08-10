<?php
/**
 * Class Native_Notify_Form_Widget
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor\Components\Widgets\Notify;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Native Elementor Notify Form Widget
 *
 * @since 1.0.0
 */
class Native_Notify_Form_Widget extends \immonex\Kickstart\ForElementor\Components\Widgets\Widget_Base {

	const WIDGET_NAME              = 'inx-e-native-notify-form';
	const WIDGET_ICON              = 'eicon-mail';
	const WIDGET_CATEGORIES        = [ 'inx-marketing-acquisition' ];
	const WIDGET_HELP_URL          = 'https://docs.immonex.de/kickstart-for-elementor/#/elementor-immobilien-widgets/suchagent-formular';
	const ENABLE_RENDER_ON_PREVIEW = true;
	const PARENT_PLUGIN_NAME       = 'immonex Notify';
	const PARENT_PLUGIN_SHOP_URL   = 'https://plugins.inveris.de/wordpress-plugins/immonex-notify';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Search Agent Form', 'immonex-kickstart-for-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'notify', 'immonex-kickstart-for-elementor' ),
					__( 'search', 'immonex-kickstart-for-elementor' ),
					__( 'agent', 'immonex-kickstart-for-elementor' ),
					__( 'form', 'immonex-kickstart-for-elementor' ),
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

		$form_elements      = apply_filters( 'immonex_notify_request_form_elements', [] ); // phpcs:ignore -- Filter hook belongs to another immonex plugin.
		$element_options    = [];
		$default_elements   = [];
		$mandatory_elements = [];

		if ( ! empty( $form_elements ) ) {
			foreach ( $form_elements as $key => $element ) {
				$title = ! empty( $element['title'] ) ? $element['title'] : '';
				if ( ! $title ) {
					$title = ! empty( $element['placeholder'] ) ? $element['placeholder'] : $key;
				}

				$element_options[ $key ] = $title;

				if ( ! empty( $element['required'] ) ) {
					$default_elements[]   = [ 'element' => $key ];
					$mandatory_elements[] = $title;
				}
			}
		}

		$element_options_json = wp_json_encode( $element_options );

		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_main_class_control();

		$this->add_control(
			'form_scope',
			[
				'label'       => __( 'Form Scope', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => sprintf(
					/* translators: 1: Link open tag, 2: Link close tag. */
					__( 'See %1$sdetailed form description%2$s.', 'immonex-kickstart-for-elementor' ),
					'<a href="https://docs.immonex.de/notify/#/immobilien-suchauftraege/frontend-formular" target="_blank">',
					'</a>'
				),
				'default'     => 'compact',
				'options'     => [
					'compact'      => __( 'compact', 'immonex-kickstart-for-elementor' ),
					'all'          => __( 'complete', 'immonex-kickstart-for-elementor' ),
					'user-defined' => __( 'user-defined', 'immonex-kickstart-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'mandatory_elements_notice',
			[
				'type'        => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'content'     => wp_sprintf(
					__( 'The following mandatory elements are always included in the form:', 'immonex-kickstart-for-elementor' ) . '<br>%s',
					implode( ', ', $mandatory_elements )
				),
				'condition'   => [
					'form_scope' => 'user-defined',
				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'element',
			[
				'label'       => __( 'Element', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => $element_options,
			]
		);

		$this->add_control(
			'form_elements',
			[
				'label'         => __( 'User-defined Elements', 'immonex-kickstart-for-elementor' ),
				'type'          => \Elementor\Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'title_field'   => "<# const labels = {$element_options_json}; const label = labels[element]; #>{{{ label }}}",
				'prevent_empty' => false,
				'default'       => $default_elements,
				'condition'     => [
					'form_scope' => 'user-defined',
				],
			]
		);

		$this->end_controls_section();

		$element_sections = [
			'introtext'    => [
				'label'    => __( 'Intro Text', 'immonex-kickstart-for-elementor' ),
				'selector' => '{{WRAPPER}} .immonex-notify-req-form__introtext',
				'exclude'  => [ 'placeholder_color', 'radio_selection_color', 'height' ],
			],
			'radio'        => [
				'label'    => __( 'Radio Selection', 'immonex-kickstart-for-elementor' ),
				'selector' => '{{WRAPPER}} .immonex-notify-req-form__input--name--salutation',
				'exclude'  => [ 'placeholder_color' ],
			],
			'text_select'  => [
				'label'    => __( 'Form Fields', 'immonex-kickstart-for-elementor' ) .
					' / ' . __( 'Select Boxes', 'immonex-kickstart-for-elementor' ),
				'selector' => '{{WRAPPER}} .immonex-notify-req-form__input > input, ' .
					'{{WRAPPER}} .immonex-notify-req-form__input > select',
				'exclude'  => [ 'radio_selection_color' ],
			],
			'consent_text' => [
				'label'    => __( 'Consent Text', 'immonex-kickstart-for-elementor' ),
				'selector' => '{{WRAPPER}} .immonex-notify-req-form__confirm-consent-text, {{WRAPPER}} .immonex-notify-req-form__general-consent-text',
				'exclude'  => [ 'placeholder_color', 'radio_selection_color' ],
			],
		];

		foreach ( $element_sections as $key => $element ) {
			$this->start_controls_section(
				"{$key}_style_section",
				[
					'label' => $element['label'],
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				"{$key}_bg_color",
				[
					'label'     => __( 'Background Color', 'immonex-kickstart-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						$element['selector'] => 'background-color: {{VALUE}}',
					],
				]
			);

			if ( empty( $element['exclude'] ) || ! in_array( 'radio_selection_color', $element['exclude'], true ) ) {
				$this->add_control(
					"{$key}__selection_color",
					[
						'label'     => __( 'Current Radio Selection Color', 'immonex-kickstart-for-elementor' ),
						'type'      => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .immonex-notify-req-form__input input[type="radio"]:checked' => 'accent-color: {{VALUE}}',
							'{{WRAPPER}} .immonex-notify-req-form__input input[type="radio"]:focus' => 'border-color: {{VALUE}}',
						],
					]
				);
			}

			if ( empty( $element['exclude'] ) || ! in_array( 'placeholder_color', $element['exclude'], true ) ) {
				$this->add_control(
					"{$key}_placeholder_color",
					[
						'label'     => __( 'Placeholder Color', 'immonex-kickstart-for-elementor' ),
						'type'      => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							preg_replace( '/\> (input|textarea)/', '> $1::placeholder', $element['selector'] ) => 'color: {{VALUE}}',
						],
					]
				);
			}

			$this->add_control(
				"{$key}_text_color",
				[
					'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						$element['selector'] => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => "{$key}_typography",
					'selector' => $element['selector'],
					'exclude'  => ! empty( $element['exclude'] ) ? $element['exclude'] : [],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name'     => "{$key}_shadow",
					'selector' => $element['selector'],
				]
			);

			$this->add_control(
				"{$key}_padding",
				[
					'label'      => __( 'Padding', 'immonex-kickstart-for-elementor' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'default'    => [
						'unit'     => 'px',
						'isLinked' => true,
					],
					'selectors'  => [
						$element['selector'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
						"{$element['selector']} p:last-child" => 'margin-bottom:0',
					],
					'separator'  => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name'     => "{$key}_border",
					'selector' => $element['selector'],
				]
			);

			$this->add_responsive_control(
				"{$key}_border_radius",
				[
					'label'      => __( 'Border Radius', 'immonex-kickstart-for-elementor' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', 'rem' ],
					'selectors'  => [
						$element['selector'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					],
				]
			);

			$this->end_controls_section();
		}

		$this->start_controls_section(
			'section_value_sliders_style',
			[
				'label' => __( 'Value Sliders', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'value_slider_text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-notify-req-form__input--type--value-slider .immonex-notify-req-form__slider-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'value_slider_text_align',
			[
				'label'     => __( 'Alignment', 'immonex-kickstart-for-elementor' ) .
					' (' . __( 'Text', 'immonex-kickstart-for-elementor' ) . ')',
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
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
				'selectors' => [
					'{{WRAPPER}} .immonex-notify-req-form__input--type--value-slider .immonex-notify-req-form__slider-text' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'value_slider_typography',
				'selector' => '{{WRAPPER}} .immonex-notify-req-form__input--type--value-slider .immonex-notify-req-form__slider-text',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'value_slider_typography_value',
				'label'    => __( 'Typography', 'immonex-kickstart-for-elementor' ) .
					' (' . __( 'Value', 'immonex-kickstart-for-elementor' ) . ')',
				'selector' => '{{WRAPPER}} .immonex-notify-req-form__input--type--value-slider .immonex-notify-req-form__slider-text > span',
				'exclude'  => [ 'font_family', 'text_decoration', 'line_height', 'letter_spacing', 'word_spacing' ],
			]
		);

		$this->add_control(
			'value_slider_text_color_value',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ) .
					' (' . __( 'Value', 'immonex-kickstart-for-elementor' ) . ')',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-notify-req-form__input--type--value-slider .immonex-notify-req-form__slider-text > span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'value_slider_text_shadow',
				'selector' => '{{WRAPPER}} .immonex-notify-req-form__input--type--value-slider .immonex-notify-req-form__slider-text',
			]
		);

		$this->add_responsive_control(
			'value_slider_size',
			[
				'label'      => __( 'Size', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 16,
						'max' => 64,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .immonex-notify-req-form__input--type--value-slider .immonex-notify-req-form__nouislider' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'value_slider_accent_color',
			[
				'label'     => __( 'Accent Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-notify-req-form__input--type--value-slider .immonex-notify-req-form__nouislider .noUi-handle' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .immonex-notify-req-form__input--type--value-slider .immonex-notify-req-form__nouislider .noUi-handle::before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .immonex-notify-req-form__input--type--value-slider .immonex-notify-req-form__nouislider .noUi-handle::after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .immonex-notify-req-form__input--type--value-slider .immonex-notify-req-form__nouislider .noUi-connect' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'value_slider_boxshadow',
			[
				'type'      => \Elementor\Controls_Manager::HIDDEN,
				'default'   => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .immonex-notify-req-form__input--type--value-slider .immonex-notify-req-form__nouislider .noUi-handle' => 'box-shadow: inset .04em 0 .3em #C5C5C5, 0 0 .2em {{value_slider_accent_color.VALUE}}',
				],
				'condition' => [
					'value_slider_accent_color!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'submit_button_section',
			[
				'label' => __( 'Submit Button', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'submit_button_align',
			[
				'label'     => __( 'Alignment', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'immonex-kickstart-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'immonex-kickstart-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'immonex-kickstart-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .immonex-notify-req-form__input--name--submit' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'agent_list_grid_element_gap',
			[
				'label'      => __( 'Margin Top', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'      => [
					'px' => [
						'max' => 64,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .immonex-notify-req-form__input--name--submit' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
				'separator'  => 'after',
			]
		);

		$this->add_control(
			'submit_button_disabled_color',
			[
				'label'     => __( 'Color (Disabled)', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-notify-req-form__submit:disabled' => 'border-color: {{VALUE}}; color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_button_disabled_transparent',
			[
				'type'      => \Elementor\Controls_Manager::HIDDEN,
				'default'   => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .immonex-notify-req-form__submit:disabled' => 'background: {{VALUE}}',
				],
				'condition' => [
					'submit_button_disabled_color!' => '',
				],
			]
		);

		$this->add_control(
			'submit_button_bg_color',
			[
				'label'     => __( 'Background Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-notify-req-form__submit:not(:disabled)' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_button_hover_color',
			[
				'label'     => __( 'Hover Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-notify-req-form__submit:not(:disabled):hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_button_text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-notify-req-form__submit:not(:disabled)' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'submit_button_typography',
				'selector' => '{{WRAPPER}} .immonex-notify-req-form__submit',
			]
		);

		$this->add_control(
			'submit_button_border_color',
			[
				'label'     => __( 'Border Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .immonex-notify-req-form__submit:not(:disabled)' => 'border-color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'submit_button_border',
				'selector' => '{{WRAPPER}} .immonex-notify-req-form__submit',
			]
		);

		$this->add_responsive_control(
			'submit_button_border_radius',
			[
				'label'      => __( 'Border Radius', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .immonex-notify-req-form__submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'submit_button_box_shadow',
				'selector' => '{{WRAPPER}} .immonex-notify-req-form__submit',
			]
		);

		$this->end_controls_section();
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

		if ( 'user-defined' === $settings['form_scope'] ) {
			$elements = [];

			foreach ( $settings['form_elements'] as $element ) {
				if ( ! in_array( $element['element'], $elements, true ) ) {
					$elements[] = $element['element'];
				}
			}

			$this->add_render_attribute( 'shortcode', 'elements', implode( ',', $elements ) );
		} else {
			$this->add_render_attribute( 'shortcode', 'elements', $settings['form_scope'] );
		}

		$shortcode_output = do_shortcode( '[immonex-notify-form ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $shortcode_output ? [
			'settings'         => $settings,
			'shortcode_output' => $shortcode_output,
		] :
		false;
	} // get_template_data

} // class Native_Notify_Form_Widget
