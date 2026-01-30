<?php
/**
 * Class Native_Head_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Native Head Widget
 *
 * @since 1.0.0
 */
class Native_Head_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const POST_TYPE                = 'inx_property';
	const WIDGET_NAME              = 'inx-e-single-property-native-head';
	const WIDGET_ICON              = 'eicon-archive-title';
	const WIDGET_CATEGORIES        = [ 'inx-single-property' ];
	const WIDGET_HELP_URL          = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/standard-header';
	const ENABLE_RENDER_ON_PREVIEW = true;

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Standard Header', 'immonex-kickstart-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'head', 'immonex-kickstart-elementor' ),
					__( 'header', 'immonex-kickstart-elementor' ),
					__( 'top', 'immonex-kickstart-elementor' ),
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
			'labels'        => __( 'Labels', 'immonex-kickstart-elementor' ),
			'type'          => __( 'Use/Property Type', 'immonex-kickstart-elementor' ),
			'location'      => __( 'Address/Location', 'immonex-kickstart-elementor' ),
			'primary-price' => __( 'Primary Price', 'immonex-kickstart-elementor' ),
			'element-title' => __( 'Core Data', 'immonex-kickstart-elementor' ),
		];

		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_main_class_control();

		$this->add_control(
			'info',
			[
				'type'        => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'content'     => __( "The standard header contains the property's type, title, address and price as well as other core data and labels.", 'immonex-kickstart-elementor' ) .
					'<br><br>' .
					__( '<strong>Alternatively</strong>, these contens can also be inserted as separate elements.', 'immonex-kickstart-elementor' ),
			]
		);

		foreach ( $contents as $key => $label ) {
			$this->add_control(
				"show_{$key}",
				[
					'label'   => $label,
					'type'    => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
				]
			);
		}

		// phpcs:ignore
		if ( apply_filters( 'inx_elementor_is_addon_active', false, 'print' ) ) {
			$text_style_sections['print_link'] = __( 'Print/PDF Link', 'immonex-kickstart-elementor' );

			$this->add_control(
				'print_link',
				[
					'label'       => __( 'Print/PDF Link', 'immonex-kickstart-elementor' ),
					'type'        => \Elementor\Controls_Manager::SELECT,
					'description' => __( 'The default is defined in the Kickstart Print add-on options.', 'immonex-kickstart-elementor' ),
					'options'     => [
						''     => __( 'Default', 'immonex-kickstart-elementor' ),
						'show' => __( 'Show', 'immonex-kickstart-elementor' ),
						'hide' => __( 'Hide', 'immonex-kickstart-elementor' ),
					],
					'separator'   => 'before',
				]
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'general_style_section',
			[
				'label' => __( 'General', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-single-property__head' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'background',
			[
				'label'       => __( 'Background', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'The <strong>default</strong> background color for header and footer sections can be adjusted in the Kickstart plugin options.', 'immonex-kickstart-elementor' ),
				'options'     => [
					''            => __( 'Default', 'immonex-kickstart-elementor' ),
					'transparent' => __( 'Transparent', 'immonex-kickstart-elementor' ),
					'custom'      => __( 'Custom', 'immonex-kickstart-elementor' ),
				],
			]
		);

		$this->add_control(
			'bg_transparent',
			[
				'type'      => \Elementor\Controls_Manager::HIDDEN,
				'default'   => 'none',
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
				'label'     => __( 'Background Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-single-property__head' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'background' => 'custom',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'title_section',
			[
				'label' => __( 'Title', 'immonex-kickstart-elementor' ),
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

			if ( 'labels' !== $key ) {
				$this->add_control(
					"{$key}_color",
					[
						'label'     => __( 'Text Color', 'immonex-kickstart-elementor' ),
						'type'      => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'print_link' === $key ?
								'{{WRAPPER}} .inx-print-link-wrap__link' :
								"{{WRAPPER}} .inx-single-property__{$class_key}" => 'color: {{VALUE}}',
						],
					]
				);
			}

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => "{$key}_typography",
					'selector' => 'print_link' === $key ?
						'{{WRAPPER}} .inx-print-link-wrap__link' :
						"{{WRAPPER}} .inx-single-property__{$class_key}",
				]
			);

			if ( 'print_link' === $key ) {
				$this->add_control(
					'print_link_icon_color',
					[
						'label'     => __( 'Icon Color', 'immonex-kickstart-elementor' ),
						'type'      => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .inx-single-property__head .inx-print-link-wrap__link svg' => 'color: {{VALUE}}',
						],
					]
				);
			}

			$this->end_controls_section();
		}

		$this->start_controls_section(
			'icons_section',
			[
				'label' => __( 'Icons', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-single-property__head .inx-core-detail-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'extended_style_section',
			[
				'label' => __( 'Extended', 'immonex-kickstart-elementor' ),
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
				'inx_print_standard_header_print_link',
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
			'type'      => __( 'Use/Property Type', 'immonex-kickstart-elementor' ),
			'labels'    => __( 'Labels', 'immonex-kickstart-elementor' ),
			'title'     => __( 'Title', 'immonex-kickstart-elementor' ),
			'location'  => __( 'Address/Location', 'immonex-kickstart-elementor' ),
			'price'     => __( 'Primary Price', 'immonex-kickstart-elementor' ),
			'core_data' => __( 'Core Data', 'immonex-kickstart-elementor' ),
		];
	} // get_head_contents

} // class Native_Head_Widget
