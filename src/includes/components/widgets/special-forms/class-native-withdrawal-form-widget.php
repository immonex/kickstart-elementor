<?php
/**
 * Class Native_Withdrawal_Form_Widget
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor\Components\Widgets\SpecialForms;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Native Withdrawal Form Widget
 *
 * @since 1.4.0
 */
class Native_Withdrawal_Form_Widget extends \immonex\Kickstart\ForElementor\Components\Widgets\Widget_Base {

	const WIDGET_NAME              = 'inx-e-native-withdrawal-form';
	const WIDGET_ICON              = 'eicon-ehp-forms';
	const WIDGET_CATEGORIES        = [ 'inx-special-forms' ];
	const WIDGET_HELP_URL          = 'https://docs.immonex.de/kickstart-for-elementor/#/elementor-immobilien-widgets/widerrufsformular';
	const ENABLE_RENDER_ON_PREVIEW = true;

	/**
	 * Get widget title.
	 *
	 * @since 1.4.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Withdrawal Form', 'immonex-kickstart-for-elementor' ) . self::NATIVE_POSTFIX;
	} // get_title

	/**
	 * Add widget keywords.
	 *
	 * @since 1.4.0
	 */
	protected function add_keywords() {
		parent::add_keywords();

		$this->keywords = array_unique(
			array_merge(
				$this->keywords,
				[
					__( 'contract', 'immonex-kickstart-for-elementor' ),
					__( 'withdrawal', 'immonex-kickstart-for-elementor' ),
					__( 'form', 'immonex-kickstart-for-elementor' ),
				]
			)
		);
	} // add_keywords

	/**
	 * Register widget controls.
	 *
	 * @since 1.4.0
	 */
	protected function register_controls() {
		if ( ! $this->parent_plugin_available ) {
			return;
		}

		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_main_class_control();

		$this->add_control(
			'general_component_info',
			[
				'type'        => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'content'     => wp_sprintf(
					/* translators: %s = plugin options tab URL */
					__( 'The withdrawal form and processing must be enabled and configured in the <a href="%s" target="_blank">Kickstart plugin options tab</a> of the same name.', 'immonex-kickstart-for-elementor' ),
					admin_url( 'admin.php?page=immonex-kickstart_settings&tab=tab_withdrawal' )
				),
			]
		);

		$this->end_controls_section();

		$selector_text      = '{{WRAPPER}} .inx-withdrawal-form__input > input, ' .
			'{{WRAPPER}} .inx-withdrawal-form__input > select';
		$selector_text_full = $selector_text . ', {{WRAPPER}} .inx-withdrawal-form__input > textarea';

		$element_sections = [
			'introtext'    => [
				'label'    => __( 'Intro Text', 'immonex-kickstart-for-elementor' ),
				'selector' => '{{WRAPPER}} .inx-withdrawal-form__introtext',
				'exclude'  => [ 'placeholder_color', 'radio_selection_color', 'height' ],
			],
			'radio'        => [
				'label'    => __( 'Radio Selection', 'immonex-kickstart-for-elementor' ),
				'selector' => '{{WRAPPER}} .inx-withdrawal-form__input--name--salutation',
				'exclude'  => [ 'placeholder_color', 'height' ],
			],
			'text_select'  => [
				'label'                  => __( 'Form Fields', 'immonex-kickstart-for-elementor' ) .
					' / ' . __( 'Select Boxes', 'immonex-kickstart-for-elementor' ),
				'selector'               => $selector_text_full,
				'selector_excl_textarea' => $selector_text,
				'exclude'                => [ 'radio_selection_color' ],
			],
			'consent_text' => [
				'label'    => __( 'Consent Text', 'immonex-kickstart-for-elementor' ),
				'selector' => '{{WRAPPER}} .inx-withdrawal-form__consent-text',
				'exclude'  => [ 'placeholder_color', 'radio_selection_color', 'height' ],
			],
		];

		foreach ( $element_sections as $key => $element ) {
			$selector_excl_textarea = ! empty( $element['selector_excl_textarea'] ) ?
				$element['selector_excl_textarea'] :
				$element['selector'];

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
							'{{WRAPPER}} .inx-withdrawal-form__input input[type="radio"]:checked' => 'background-color: {{VALUE}}',
							'{{WRAPPER}} .inx-withdrawal-form__input input[type="radio"]:focus' => 'border-color: {{VALUE}}',
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

			if ( empty( $element['exclude'] ) || ! in_array( 'height', $element['exclude'], true ) ) {
				$this->add_responsive_control(
					"{$key}_height",
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
						'default'    => $this->get_default( "{$key}_height", [ 'height' => '40px' ] ),
						'selectors'  => [
							$selector_excl_textarea => 'height: {{SIZE}}{{UNIT}}',
						],
						'separator'  => 'before',
					]
				);
			}

			if ( empty( $element['exclude'] ) || ! in_array( 'padding', $element['exclude'], true ) ) {
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
						'separator'  => empty( $element['exclude'] ) || in_array( 'height', $element['exclude'], true ) ? 'before' : null,
					]
				);
			}

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
			'submit_button_section',
			[
				'label' => __( 'Submit Button', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'submit_button_bg_color',
			[
				'label'       => __( 'Background Color', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'description' => wp_sprintf(
					/* translators: %1$s = color type, e.g. "all action elements"; %2$s = plugin options tab URL */
					__( 'Instead of selecting an <strong>element-related</strong> color here, setting a <strong>global</strong> color for <strong>%1$s</strong> in the <a href="%2$s" target="_blank">Kickstart plugin options</a> makes more sense in most cases.', 'immonex-kickstart-for-elementor' ),
					__( 'all action elements', 'immonex-kickstart-for-elementor' ),
					admin_url( 'admin.php?page=immonex-kickstart_settings&section_tab=3' )
				),
				'selectors'   => [
					'{{WRAPPER}} .inx-withdrawal-form__submit:not(:disabled)' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_button_hover_color',
			[
				'label'     => __( 'Hover Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-withdrawal-form__submit:not(:disabled):hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_button_text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-withdrawal-form__submit:not(:disabled)' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'submit_button_typography',
				'selector' => '{{WRAPPER}} .inx-withdrawal-form__submit',
			]
		);

		$this->add_control(
			'submit_button_border_color',
			[
				'label'     => __( 'Border Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-withdrawal-form__submit:not(:disabled)' => 'border-color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'submit_button_border',
				'selector' => '{{WRAPPER}} .inx-withdrawal-form__submit',
			]
		);

		$this->add_responsive_control(
			'submit_button_border_radius',
			[
				'label'      => __( 'Border Radius', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .inx-withdrawal-form__submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'submit_button_box_shadow',
				'selector' => '{{WRAPPER}} .inx-withdrawal-form__submit',
			]
		);

		$this->add_control(
			'submit_button_secure_icon',
			[
				'label'        => __( 'Hide "Secure" Icon', 'immonex-kickstart-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => '0',
				'selectors'    => [
					'{{WRAPPER}} .inx-withdrawal-form__input--name--submit > div:first-child' => 'opacity: {{VALUE}}',
				],
				'separator'    => 'before',
			]
		);

		$this->end_controls_section();
	} // register_controls

	/**
	 * Return widget contents for frontend template rendering.
	 *
	 * @since 1.4.0
	 *
	 * @return mixed[]|bool Template data array or false if unavailable.
	 */
	protected function get_template_data() {
		if ( ! $this->parent_plugin_available ) {
			return false;
		}

		$settings = $this->get_settings_for_display();

		$shortcode_output = do_shortcode( '[inx-withdrawal-form]' );

		return $shortcode_output ? [
			'settings'         => $settings,
			'shortcode_output' => $shortcode_output,
		] :
		false;
	} // get_template_data

} // class Native_Withdrawal_Form_Widget
