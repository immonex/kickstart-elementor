<?php
/**
 * Class Native_Head_Widget
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use immonex\Kickstart\ForElementor\Elementor_Bootstrap;

/**
 * Elementor Single Property Native Head Widget
 *
 * @since 1.0.0
 */
class Native_Head_Widget extends \immonex\Kickstart\ForElementor\Components\Widgets\Widget_Base {

	const POST_TYPE                = 'inx_property';
	const WIDGET_NAME              = 'inx-e-single-property-native-head';
	const WIDGET_ICON              = 'eicon-archive-title';
	const WIDGET_CATEGORIES        = [ 'inx-single-property' ];
	const WIDGET_HELP_URL          = 'https://docs.immonex.de/kickstart-for-elementor/#/elementor-immobilien-widgets/standard-header';
	const ENABLE_RENDER_ON_PREVIEW = true;

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Standard Header', 'immonex-kickstart-for-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'head', 'immonex-kickstart-for-elementor' ),
					__( 'header', 'immonex-kickstart-for-elementor' ),
					__( 'top', 'immonex-kickstart-for-elementor' ),
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
		$contents = $this->get_head_contents();

		$text_style_sections = [
			'labels'            => __( 'Labels', 'immonex-kickstart-for-elementor' ),
			'type'              => __( 'Use/Property Type', 'immonex-kickstart-for-elementor' ),
			'location'          => __( 'Address/Location', 'immonex-kickstart-for-elementor' ),
			'primary-price'     => __( 'Primary Price', 'immonex-kickstart-for-elementor' ),
			'core-data-element' => __( 'Core Data', 'immonex-kickstart-for-elementor' ),
		];

		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_main_class_control();

		$this->add_control(
			'info',
			[
				'type'        => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'dismissible' => true,
				'content'     => __( "The standard header contains the property's type, title, address and price as well as other core data and labels.", 'immonex-kickstart-for-elementor' ) .
					'<br><br>' .
					__( '<strong>Alternatively</strong>, these contens can also be inserted as separate elements.', 'immonex-kickstart-for-elementor' ),
			]
		);

		foreach ( $contents as $key => $label ) {
			$this->add_control(
				"show_{$key}",
				[
					'label'   => $label,
					'type'    => \Elementor\Controls_Manager::SWITCHER,
					'default' => $this->get_default( "show_{$key}", 'yes' ),
				]
			);
		}

		if ( apply_filters( 'inxkickel_is_plugin_available', false, 'immonex-kickstart-print', Elementor_Bootstrap::MIN_REQ_VERSIONS['print'] ) ) {
			$text_style_sections['print_link'] = __( 'Print/PDF Link', 'immonex-kickstart-for-elementor' );

			$this->add_control(
				'print_link',
				[
					'label'       => __( 'Print/PDF Link', 'immonex-kickstart-for-elementor' ),
					'type'        => \Elementor\Controls_Manager::SELECT,
					'description' => __( 'The default is defined in the Kickstart Print add-on options.', 'immonex-kickstart-for-elementor' ),
					'options'     => [
						''     => __( 'Default', 'immonex-kickstart-for-elementor' ),
						'show' => __( 'Show', 'immonex-kickstart-for-elementor' ),
						'hide' => __( 'Hide', 'immonex-kickstart-for-elementor' ),
					],
					'separator'   => 'before',
				]
			);
		}

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
				'description' => __( 'The <strong>default</strong> background color for header and footer sections can be adjusted in the Kickstart plugin options.', 'immonex-kickstart-for-elementor' ),
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
					'{{WRAPPER}} .inx-single-property__head' => 'background: {{VALUE}}',
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
					'{{WRAPPER}} .inx-single-property__head' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .inx-single-property__head' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'divider_style_section',
			[
				'label' => __( 'Dividing Lines', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label'       => __( 'Color', 'immonex-kickstart-for-elementor' ),
				'description' => __( 'The <strong>default</strong> color for dividing lines can be adjusted in the Kickstart plugin options.', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'selectors'   => [
					'{{WRAPPER}} .inx-single-property__head hr' => 'color: {{VALUE}}; background-color: {{VALUE}}; border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'divider_height',
			[
				'label'      => __( 'Height', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default(
					'divider_height',
					[
						'size' => 1,
						'unit' => 'px',
					]
				),
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [
					'px'  => [
						'max' => 16,
					],
					'em'  => [
						'min' => 0,
						'max' => 2,
					],
					'rem' => [
						'min' => 0,
						'max' => 2,
					],
				],
				'selectors'  => [ '{{WRAPPER}} .inx-single-property__head hr' => 'height: {{SIZE}}{{UNIT}}' ],
			]
		);

		$this->add_responsive_control(
			'divider_v_margin',
			[
				'label'      => __( 'Vertical Margin', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default(
					'divider_v_margin',
					[
						'size' => 16,
						'unit' => 'px',
					],
				),
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [
					'px'  => [
						'max' => 64,
					],
					'em'  => [
						'min' => 0,
						'max' => 8,
					],
					'rem' => [
						'min' => 0,
						'max' => 8,
					],
				],
				'selectors'  => [ '{{WRAPPER}} .inx-single-property__head hr' => 'margin: {{SIZE}}{{UNIT}} auto' ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'title_section',
			[
				'label' => __( 'Title', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_default_controls(
			'heading_style',
			[
				'heading_align'       => [
					'selectors' => [
						'{{WRAPPER}} .inx-single-property__head-title' => 'text-align: {{VALUE}};',
					],
				],
				'heading_title_color' => [
					'selectors' => [
						'{{WRAPPER}} .inx-single-property__head-title' => 'color: {{VALUE}}',
					],
				],
				'heading_typography'  => [
					'selector' => '{{WRAPPER}} .inx-single-property__head-title',
				],
				'heading_text_stroke' => [
					'selector' => '{{WRAPPER}} .inx-single-property__head-title',
				],
				'heading_text_shadow' => [
					'selector' => '{{WRAPPER}} .inx-single-property__head-title',
				],
				'blend_mode'          => [
					'selectors' => [
						'{{WRAPPER}} .inx-single-property__head-title' => 'mix-blend-mode: {{VALUE}}',
					],
				],
			]
		);

		$this->end_controls_section();

		foreach ( $text_style_sections as $key => $label ) {
			$class_key = 'labels' === $key ? $key : "head-{$key}";

			$this->start_controls_section(
				"section_{$key}",
				[
					'label' => $label,
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

			if ( 'labels' === $key ) {
				$this->add_control(
					"{$key}_layout",
					[
						'label'   => __( 'Layout', 'immonex-kickstart-for-elementor' ),
						'type'    => \Elementor\Controls_Manager::CHOOSE,
						'default' => $this->get_default( 'labels_layout', 'horizontal' ),
						'options' => [
							'vertical'   => [
								'title' => __( 'Vertical', 'immonex-kickstart-for-elementor' ),
								'icon'  => 'eicon-editor-list-ul',
							],
							'horizontal' => [
								'title' => __( 'Horizontal', 'immonex-kickstart-for-elementor' ),
								'icon'  => 'eicon-ellipsis-h',
							],
						],
					]
				);

				$this->add_control(
					"{$key}_vertical_layout",
					[
						'type'      => \Elementor\Controls_Manager::HIDDEN,
						'default'   => '1',
						'selectors' => [
							"{{WRAPPER}} .inx-single-property__{$class_key}" => 'display: flex; flex-direction: column; align-items: end; row-gap: .2em',
						],
						'condition' => [
							'labels_layout' => 'vertical',
						],
					]
				);

				$this->add_control(
					"{$key}_border_radius",
					[
						'label'      => __( 'Corner Radius', 'immonex-kickstart-for-elementor' ),
						'type'       => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range'      => [
							'px' => [
								'max' => 16,
							],
							'%'  => [
								'max' => 25,
							],
						],
						'selectors'  => [
							"{{WRAPPER}} .inx-single-property__{$class_key} .inx-property-label" => 'border-radius: {{SIZE}}{{UNIT}}',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name'     => "{$key}_box_shadow",
						'label'    => __( 'Box Shadow', 'immonex-kickstart-for-elementor' ),
						'selector' => "{{WRAPPER}} .inx-single-property__{$class_key} .inx-property-label",
					]
				);

				$this->add_control(
					"{$key}_bg_color",
					[
						'label'       => __( 'Color', 'immonex-kickstart-for-elementor' ),
						'type'        => \Elementor\Controls_Manager::COLOR,
						'description' => __( 'The label colors can be customized in the Kickstart plugin options. <strong>If required</strong>, an alternative uniform color for all labels of this element can be selected here.', 'immonex-kickstart-for-elementor' ),
						'selectors'   => [
							"{{WRAPPER}} .inx-single-property__{$class_key} .inx-property-label" => 'background: {{VALUE}};',
						],
						'separator'   => 'before',
					]
				);
			}

			if ( 'labels' === $key ) {
				$text_color_selector = "{{WRAPPER}} .inx-single-property__{$class_key} .inx-property-label";
			} elseif ( 'print_link' === $key ) {
				$text_color_selector = '{{WRAPPER}} .inxkickpr-link-wrap__link';
			} else {
				$text_color_selector = "{{WRAPPER}} .inx-single-property__{$class_key}";
			}

			$this->add_control(
				"{$key}_color",
				[
					'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						$text_color_selector => 'color: {{VALUE}}',
					],
				]
			);

			switch ( $key ) {
				case 'print_link':
					$selector = '{{WRAPPER}} .inxkickpr-link-wrap__link, '
						. '{{WRAPPER}} .inxkickpr-link-wrap__link svg';
					break;
				case 'core-data-element':
					$selector = "{{WRAPPER}} .inx-single-property__{$class_key}, " .
						'{{WRAPPER}} .inx-single-property__head-elements:last-child > div, ' .
						"{{WRAPPER}} .inx-single-property__{$class_key} [class^=\"flaticon-\"]::before, " .
						'{{WRAPPER}} .inx-single-property__head-elements:last-child > div [class^=\"flaticon-\"]::before';
					break;
				default:
					$selector = "{{WRAPPER}} .inx-single-property__{$class_key}, " .
						"{{WRAPPER}} .inx-single-property__{$class_key} [class^=\"flaticon-\"]::before";
					break;
			}

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => "{$key}_typography",
					'selector' => $selector,
				]
			);

			if ( in_array( $key, [ 'location', 'core-data-element', 'print_link' ], true ) ) {
				$this->add_control(
					"{$key}_icon_color",
					[
						'label'     => __( 'Icon Color', 'immonex-kickstart-for-elementor' ),
						'type'      => \Elementor\Controls_Manager::COLOR,
						'selectors' => 'print_link' === $key ?
							[ '{{WRAPPER}} .inx-single-property__head .inxkickpr-link-wrap__link svg' => 'color: {{VALUE}}' ] :
							[ "{{WRAPPER}} .inx-single-property__{$class_key} [class^=\"flaticon-\"]::before" => 'color: {{VALUE}}' ],
					]
				);
			}

			$this->end_controls_section();
		}

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
					'folder' => 'single-property',
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
		$all_contents  = $this->get_head_contents();
		$template_data = [
			'settings' => $settings,
		];

		$ext_atts = [ 'template' ];

		$this->add_extended_sc_atts( $ext_atts, $template_data, 'single-property' );

		$this->add_render_attribute( 'shortcode', 'elements', 'head' );

		$contents = [];
		foreach ( $all_contents as $key => $label ) {
			if ( 'yes' === $settings[ "show_{$key}" ] ) {
				$contents[] = $key;
			}
		}

		if ( empty( $contents ) ) {
			return false;
		}

		if ( count( $contents ) !== count( $all_contents ) ) {
			$this->add_render_attribute( 'shortcode', 'head-contents', implode( ',', $contents ) );
		}

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'shortcode', 'is_preview', '1' );
		}

		if ( ! empty( $settings['print_link'] ) ) {
			add_filter(
				'inxkickpr_standard_header_print_link',
				'show' === $settings['print_link'] ? '__return_true' : '__return_false'
			);
		}

		$template_data['shortcode_output'] = do_shortcode( '[inx-property-details ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $template_data['shortcode_output'] ? $template_data : false;
	} // get_template_data

	/**
	 * Return keys and labels of displayable header contents.
	 *
	 * @since 1.0.0
	 *
	 * @return string[] Header contents (key => label).
	 */
	private function get_head_contents() {
		return [
			'type'      => __( 'Use/Property Type', 'immonex-kickstart-for-elementor' ),
			'labels'    => __( 'Labels', 'immonex-kickstart-for-elementor' ),
			'title'     => __( 'Title', 'immonex-kickstart-for-elementor' ),
			'location'  => __( 'Address/Location', 'immonex-kickstart-for-elementor' ),
			'price'     => __( 'Primary Price', 'immonex-kickstart-for-elementor' ),
			'core_data' => __( 'Core Data', 'immonex-kickstart-for-elementor' ),
		];
	} // get_head_contents

} // class Native_Head_Widget
