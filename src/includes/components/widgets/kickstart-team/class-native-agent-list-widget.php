<?php
/**
 * Class Native_Agent_List_Widget
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor\Components\Widgets\KickstartTeam;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Native Agent List Widget
 *
 * @since 1.0.0
 */
class Native_Agent_List_Widget extends \immonex\Kickstart\ForElementor\Components\Widgets\Widget_Base {

	const WIDGET_NAME               = 'inx-e-native-team-agent-list';
	const WIDGET_ICON               = 'eicon-gallery-grid';
	const WIDGET_CATEGORIES         = [ 'inx-team' ];
	const WIDGET_HELP_URL           = 'https://docs.immonex.de/kickstart-for-elementor/#/elementor-immobilien-widgets/kontaktpersonen-liste';
	const ENABLE_RENDER_ON_PREVIEW  = true;
	const PARENT_PLUGIN_NAME        = 'immonex Kickstart Team';
	const PARENT_PLUGIN_WP_REPO_URL = 'https://wordpress.org/plugins/immonex-kickstart-team/';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Agent List', 'immonex-kickstart-for-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'team', 'immonex-kickstart-for-elementor' ),
					__( 'agent', 'immonex-kickstart-for-elementor' ),
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

		$agency_posts = get_posts(
			[
				'post_type' => 'inx_agency',
				'orderby'   => 'title',
				'order'     => 'ASC',
			]
		);
		$agencies     = [
			'' => __( 'All', 'immonex-kickstart-for-elementor' ),
		];

		if ( ! empty( $agency_posts ) ) {
			foreach ( $agency_posts as $agency ) {
				$agencies[ $agency->ID ] = $agency->post_title;
			}
		}

		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'agency',
			[
				'label'       => __( 'Agency', 'immonex-kickstart-for-elementor' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '',
				'options'     => $agencies,
				'separator'   => 'after',
			]
		);

		$this->add_main_class_control();
		$this->add_default_controls(
			[
				'lists',
				'team_common',
				'authors',
			],
			[ 'authors' => [ 'separator' => 'before' ] ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_grid_style',
			[
				'label' => __( 'Grid', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'column_width',
			[
				'label'      => __( 'Column Width', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 180,
						'max' => 1200,
					],
				],
				'selectors'  => [ '{{WRAPPER}} .inx-team-agent-list__item-wrap' => 'width: {{SIZE}}{{UNIT}}' ],
			]
		);

		$this->add_responsive_control(
			'grid_element_gap',
			[
				'label'      => _x( 'Gap', 'distance between grid elements', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'      => [
					'px' => [
						'max' => 64,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .inx-team-agent-list__item-wrap' => 'padding-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} * + .uk-grid-margin, .uk-grid + .uk-grid, .uk-grid > .uk-grid-margin' => 'margin-top: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .inx-team-agent-list > .uk-grid' => 'margin-left: -{{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_agent_tile_frame_style',
			[
				'label' => _x( 'Agent Tile', 'real estate property', 'immonex-kickstart-for-elementor' ) .
					' (' . __( 'Frame', 'immonex-kickstart-for-elementor' ) . ')',
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'agent_tile_field_border',
				'selector'  => '{{WRAPPER}} .inx-team-agent-list-item',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'agent_tile_border_radius',
			[
				'label'      => __( 'Border Radius', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .inx-team-agent-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'agent_tile_box_shadow_type',
			[
				'label'     => __( 'Box Shadow', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					''         => __( 'Default', 'immonex-kickstart-for-elementor' ),
					'disabled' => __( 'Disabled', 'immonex-kickstart-for-elementor' ),
					'custom'   => __( 'Custom', 'immonex-kickstart-for-elementor' ),
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'agent_tile_remove_native_box_shadow',
			[
				'type'      => \Elementor\Controls_Manager::HIDDEN,
				'default'   => '1',
				'selectors' => [
					'{{WRAPPER}} .inx-team-agent-list-item' => 'box-shadow: none',
				],
				'condition' => [
					'agent_tile_box_shadow_type' => 'disabled',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'agent_tile_box_shadow',
				'label'     => __( 'Box Shadow', 'immonex-kickstart-for-elementor' ) .
					' (' . __( 'Custom', 'immonex-kickstart-for-elementor' ) . ')',
				'selector'  => '{{WRAPPER}} .inx-team-agent-list-item',
				'condition' => [
					'agent_tile_box_shadow_type' => 'custom',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_body_style',
			[
				'label' => __( 'Body', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'body_padding',
			[
				'label'      => __( 'Padding', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'    => [
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .inx-team-agent-list-item__body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'body_bg_color',
			[
				'label'     => __( 'Background Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-team-agent-list-item > div' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'body_text_icon_color',
			[
				'label'     => __( 'Text/Icon Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-team-agent-list-item__body' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'body_link_color',
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
					'{{WRAPPER}} .inx-team-agent-list-item__body a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'body_typography',
				'selector' => '{{WRAPPER}} .inx-team-agent-list-item__body',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_footer_style',
			[
				'label' => __( 'Footer', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'footer_height',
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
				'selectors'  => [
					'{{WRAPPER}} .inx-team-agent-list-item__footer' => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .inx-team-agent-list-item:not(.inx-team-agency-list-item--no-footer)' => 'padding-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'footer_bg_color',
			[
				'label'       => __( 'Background Color', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'description' => wp_sprintf(
					/* translators: %1$s = color type, e.g. "all action elements"; %2$s = plugin options tab URL */
					__( 'Instead of selecting an <strong>element-related</strong> color here, setting a <strong>global</strong> color for <strong>%1$s</strong> in the <a href="%2$s" target="_blank">Kickstart plugin options</a> makes more sense in most cases.', 'immonex-kickstart-for-elementor' ),
					__( 'all background elements', 'immonex-kickstart-for-elementor' ),
					admin_url( 'admin.php?page=immonex-kickstart_settings&section_tab=3' )
				),
				'selectors'   => [
					'{{WRAPPER}} .inx-team-agent-list-item__footer .inx-link.inx-gradient--type--action' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'footer_link_color',
			[
				'label'     => __( 'Link Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-team-agent-list-item__footer .inx-link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'footer_typography',
				'selector' => '{{WRAPPER}} .inx-team-agent-list-item__footer',
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
			'agency',
			'limit',
			'limit-page',
			'demo',
		];

		$this->add_render_attribute( 'shortcode', 'ignore-pagination', '1' );

		foreach ( $ext_atts as $att ) {
			if ( ! empty( $settings[ $att ] ) ) {
				$this->add_render_attribute( 'shortcode', $att, $settings[ $att ] );
			}
		}

		if ( ! empty( $settings['order'] ) ) {
			$order = $settings['order'] . ' ' . $settings['order_dir'];
			$this->add_render_attribute( 'shortcode', 'order', $order );
		}

		$author_query_attr_value = $this->get_author_query_sc_attr_value( $settings );
		if ( $author_query_attr_value ) {
			$this->add_render_attribute( 'shortcode', 'author', $author_query_attr_value );
		}

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'shortcode', 'is_preview', '1' );
		}

		$shortcode_output = do_shortcode( '[inx-team-agent-list ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $shortcode_output ?
			[
				'settings'         => $settings,
				'shortcode_output' => $shortcode_output,
			] :
			false;
	} // get_template_data

} // class Native_Agent_List_Widget
