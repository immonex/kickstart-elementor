<?php
/**
 * Class Native_Footer_Widget
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Native Footer Widget
 *
 * @since 1.0.0
 */
class Native_Footer_Widget extends \immonex\Kickstart\ForElementor\Components\Widgets\Widget_Base {

	const POST_TYPE                = 'inx_property';
	const WIDGET_NAME              = 'inx-e-single-property-native-footer';
	const WIDGET_ICON              = 'eicon-footer';
	const WIDGET_CATEGORIES        = [ 'inx-single-property' ];
	const WIDGET_HELP_URL          = 'https://docs.immonex.de/kickstart-for-elementor/#/elementor-immobilien-widgets/footer';
	const ENABLE_RENDER_ON_PREVIEW = true;

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Footer', 'immonex-kickstart-for-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'footer', 'immonex-kickstart-for-elementor' ),
					__( 'bottom', 'immonex-kickstart-for-elementor' ),
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

		$this->add_main_class_control();

		$this->add_control(
			'footer_notice',
			[
				'type'        => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'content'     => __( 'The native footer box contains an (optional) property post navigation and a link to the overview page.', 'immonex-kickstart-for-elementor' ),
			]
		);

		$this->add_control(
			'post_nav',
			[
				'label'       => __( 'Previous/Next Navigation', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'The previous/next navigation is available by default if the property is part of a selection based on certain search criteria. Alternatively, it can be generally enabled (optionally including first/last property links) or disabled (overview link only).', 'immonex-kickstart-for-elementor' ),
				'options'     => [
					''                          => __( 'Default', 'immonex-kickstart-for-elementor' ),
					'never'                     => __( 'never', 'immonex-kickstart-for-elementor' ),
					'selection'                 => __( 'in search results', 'immonex-kickstart-for-elementor' ),
					'selection_incl_first_last' => __( 'in search results', 'immonex-kickstart-for-elementor' ) . ' (' . __( 'incl. first/last property', 'immonex-kickstart-for-elementor' ) . ')',
					'always'                    => __( 'always', 'immonex-kickstart-for-elementor' ),
					'always_incl_first_last'    => __( 'always', 'immonex-kickstart-for-elementor' ) . ' (' . __( 'incl. first/last property', 'immonex-kickstart-for-elementor' ) . ')',
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'overview_link_text',
			[
				'label'       => __( 'Overview Link Text', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'Here, an <strong>alternative</strong> link text can be specified to be used instead of the default "Back to overview" (or "-" to hide it).', 'immonex-kickstart-for-elementor' ),
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
				'default'   => 'none',
				'selectors' => [
					'{{WRAPPER}} .inx-single-property__footer' => 'background: {{VALUE}}',
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
					'{{WRAPPER}} .inx-single-property__footer' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .inx-single-property__footer' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_links_style',
			[
				'label' => __( 'Links', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'link_color',
			[
				'label'       => __( 'Link Color', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'description' => wp_sprintf(
					/* translators: %1$s = color type, e.g. "all action elements"; %2$s = plugin options tab URL */
					__( 'Instead of selecting an <strong>element-related</strong> color here, setting a <strong>global</strong> color for <strong>%1$s</strong> in the <a href="%2$s" target="_blank">Kickstart plugin options</a> makes more sense in most cases.', 'immonex-kickstart-for-elementor' ),
					__( 'all action elements', 'immonex-kickstart-for-elementor' ),
					admin_url( 'admin.php?page=immonex-kickstart_settings&section_tab=3' )
				),
				'selectors'   => [
					'{{WRAPPER}} .inx-single-property__post-nav-item > span' => 'color: {{VALUE}}',
					'{{WRAPPER}} a.inx-link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'link_typography',
				'selector' => '{{WRAPPER}} a',
			]
		);

		$this->add_control(
			'icons_header',
			[
				'label'     => __( 'Icons', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_ratio',
			[
				'label'      => __( 'Ratio', 'immonex-kickstart-for-elementor' )
					. ' (' . __( 'back/next', 'immonex-kickstart-for-elementor' ) . ')',
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'custom' ],
				'range'      => [
					[
						'min'  => 0.5,
						'max'  => 5,
						'step' => .5,
					],
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'icon_ratio_first_last',
			[
				'label'      => __( 'Ratio', 'immonex-kickstart-for-elementor' )
					. ' (' . __( 'first/last Property', 'immonex-kickstart-for-elementor' ) . ')',
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'custom' ],
				'range'      => [
					[
						'min'  => 0.5,
						'max'  => 5,
						'step' => .5,
					],
				],
			]
		);

		$this->add_control(
			'enable_tooltips',
			[
				'label'       => __( 'Enable Tooltips', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'By default, the titles of the linked properties and "Back to overview" are displayed as tooltips.', 'immonex-kickstart-for-elementor' ),
				'default'     => 'yes',
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
		$template_data = [
			'settings' => $this->get_settings_for_display(),
		];

		$this->add_render_attribute( 'shortcode', 'elements', 'footer' );
		$this->add_render_attribute( 'shortcode', 'enable_tooltips', 'yes' === $template_data['settings']['enable_tooltips'] ? '1' : '0' );

		foreach ( [ 'icon_ratio', 'icon_ratio_first_last' ] as $key ) {
			if ( ! empty( $template_data['settings'][ $key ]['size'] ) ) {
				$this->add_render_attribute( 'shortcode', $key, $template_data['settings'][ $key ]['size'] );
			}
		}

		$ext_atts = [ 'post_nav', 'overview_link_text', 'template' ];

		$this->add_extended_sc_atts( $ext_atts, $template_data, 'single-property' );

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'shortcode', 'is_preview', '1' );
		}

		$template_data['shortcode_output'] = do_shortcode( '[inx-property-details ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $template_data['shortcode_output'] ? $template_data : false;
	} // get_template_data

} // class Native_Footer_Widget
