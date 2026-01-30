<?php
/**
 * Class Icon_List_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Icon List Base Widget
 *
 * @since 1.0.0
 */
class Icon_List_Widget extends Widget_Base {

	const POST_TYPE             = 'inx_property';
	const WIDGET_NAME           = 'inx-e-icon-list';
	const WIDGET_ICON           = 'eicon-bullet-list';
	const WIDGET_CATEGORIES     = [ 'inx-single-property' ];
	const ENABLE_ICON_SELECTION = true;

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Icon List', 'immonex-kickstart-elementor' );
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
					__( 'icon', 'immonex-kickstart-elementor' ),
					__( 'list', 'immonex-kickstart-elementor' ),
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
				'label' => __( 'General', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$default_control_args = [
			'heading' => [
				'default' => $this->get_default( 'heading', __( 'Features', 'immonex-kickstart-elementor' ) ),
			],
		];

		$this->add_default_controls( [ 'heading' ], $default_control_args );

		$this->add_main_class_control();

		if ( static::ENABLE_ICON_SELECTION ) {
			$this->add_control(
				'icon',
				[
					'label'       => __( 'Icon', 'immonex-kickstart-elementor' ),
					'type'        => \Elementor\Controls_Manager::ICONS,
					'default'     => $this->get_default(
						'icon',
						[
							'value'   => 'fas fa-check',
							'library' => 'fa-solid',
						]
					),
					'recommended' => [
						'fa-solid'   => [
							'check',
							'check-circle',
							'circle',
							'square-full',
						],
						'fa-regular' => [
							'check-circle',
							'circle',
							'square',
						],
					],
				]
			);
		}

		$this->end_controls_section();

		$this->add_default_controls( [ 'heading_style' ] );

		$this->start_controls_section(
			'list_section',
			[
				'label' => __( 'List', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'layout',
			[
				'label'          => __( 'Layout', 'immonex-kickstart-elementor' ),
				'type'           => \Elementor\Controls_Manager::CHOOSE,
				'default'        => $this->get_default( 'layout', 'columns' ),
				'options'        => [
					'columns' => [
						'title' => __( 'Default', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-editor-list-ul',
					],
					'inline'  => [
						'title' => __( 'Inline', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-ellipsis-h',
					],
				],
				'render_type'    => 'template',
				'classes'        => 'elementor-control-start-end',
				'style_transfer' => true,
				'toggle'         => false,
				'prefix_class'   => 'inx-e--layout--',
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'                => __( 'Columns', 'immonex-kickstart-elementor' ),
				'type'                 => \Elementor\Controls_Manager::SELECT,
				'widescreen_default'   => $this->get_default( 'columns', '3' ),
				'default'              => $this->get_default( 'columns', '3' ),
				'laptop_default'       => $this->get_default( 'columns', '3' ),
				'tablet_default'       => $this->get_default( 'columns', '2' ),
				'tablet_extra_default' => $this->get_default( 'columns', '2' ),
				'mobile_default'       => $this->get_default( 'columns', '1' ),
				'mobile_extra_default' => $this->get_default( 'columns', '1' ),
				'options'              => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				],
				'condition'            => [
					'layout' => 'columns',
				],
				'prefix_class'         => 'inx-e%s--columns--',
				'selectors'            => [
					'{{WRAPPER}}:not(.inx-e--layout--inline) .inx-e-icon-list__items' => 'display: grid; grid-template-columns: repeat({{VALUE}}, 1fr)',
				],
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label'      => __( 'Space Between', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default(
					'space_between',
					[
						'size' => 16,
						'unit' => 'px',
					]
				),
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px'  => [
						'max' => 64,
					],
					'em'  => [
						'max' => 16,
					],
					'rem' => [
						'max' => 16,
					],
				],
				'selectors'  => array_merge(
					[
						'{{WRAPPER}}:not(.inx-e--layout--inline) .inx-e-icon-list__item:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
						'{{WRAPPER}}.inx-e--layout--inline .inx-e-icon-list__item'                        => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
						'{{WRAPPER}}.inx-e--layout--inline .inx-e-icon-list__items'                       => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
						'body.rtl {{WRAPPER}}.inx-e--layout--inline .inx-e-icon-list__item:after'         => 'left: calc(-{{SIZE}}{{UNIT}}/2)',
						'body:not(.rtl) {{WRAPPER}}.inx-e--layout--inline .inx-e-icon-list__item:after'   => 'right: calc(-{{SIZE}}{{UNIT}}/2)',
					],
					$this->get_responsive_selectors( '{{WRAPPER}}:not(.inx-e--layout--inline):not(.inx-e%s--columns--1) .inx-e-icon-list__item:last-child', 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)' )
				),
			]
		);

		$this->add_responsive_control(
			'item_align',
			[
				'label'        => __( 'Alignment', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::CHOOSE,
				'default'      => $this->get_default( 'item_align', 'left' ),
				'options'      => [
					'left'   => [
						'title' => __( 'Left', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'inx-e%s--align--',
			]
		);

		$this->add_control(
			'divider',
			[
				'label'     => __( 'Divider', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'immonex-kickstart-elementor' ),
				'label_on'  => __( 'On', 'immonex-kickstart-elementor' ),
				'selectors' => array_merge(
					[ '{{WRAPPER}} .inx-e-icon-list__item:not(:last-child):after' => 'content: ""' ],
					$this->get_responsive_selectors( '{{WRAPPER}}:not(.inx-e--layout--inline):not(.inx-e%s--columns--1) .inx-e-icon-list__item:last-child:after', 'content: ""' )
				),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label'     => __( 'Style', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => $this->get_default( 'divider_style', 'solid' ),
				'options'   => [
					'solid'  => __( 'Solid', 'immonex-kickstart-elementor' ),
					'double' => __( 'Double', 'immonex-kickstart-elementor' ),
					'dotted' => __( 'Dotted', 'immonex-kickstart-elementor' ),
					'dashed' => __( 'Dashed', 'immonex-kickstart-elementor' ),
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => array_merge(
					[
						'{{WRAPPER}}.inx-e--layout--inline .inx-e-icon-list__item:not(:last-child):after'       => 'border-left-style: {{VALUE}}',
						'{{WRAPPER}}:not(.inx-e--layout--inline) .inx-e-icon-list__item:not(:last-child):after' => 'border-top-style: {{VALUE}}',
					],
					$this->get_responsive_selectors( '{{WRAPPER}}:not(.inx-e--layout--inline):not(.inx-e%s--columns--1) .inx-e-icon-list__item:last-child:after', 'border-top-style: {{VALUE}}' )
				),
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label'      => __( 'Weight', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default( 'divider_weight', [ 'size' => 1 ] ),
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'condition'  => [
					'divider' => 'yes',
				],
				'selectors'  => array_merge(
					[
						'{{WRAPPER}}.inx-e--layout--inline .inx-e-icon-list__item:not(:last-child):after'       => 'border-left-width: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}}:not(.inx-e--layout--inline) .inx-e-icon-list__item:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}',
					],
					$this->get_responsive_selectors( '{{WRAPPER}}:not(.inx-e--layout--inline):not(.inx-e%s--columns--1) .inx-e-icon-list__item:last-child:after', 'border-top-width: {{SIZE}}{{UNIT}}' )
				),
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label'      => __( 'Width', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default( 'divider_width', [ 'unit' => '%' ] ),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'condition'  => [
					'divider' => 'yes',
					'view!'   => 'inline',
				],
				'selectors'  => array_merge(
					[ '{{WRAPPER}} .inx-e-icon-list__item:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}' ],
					$this->get_responsive_selectors( '{{WRAPPER}}:not(.inx-e%s--columns--1) .inx-e-icon-list__item:last-child:after', 'width: {{SIZE}}{{UNIT}}' )
				),
			]
		);

		$this->add_control(
			'divider_height',
			[
				'label'      => __( 'Height', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default( 'divider_height', [ 'unit' => '%' ] ),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'condition'  => [
					'divider' => 'yes',
					'view'    => 'inline',
				],
				'selectors'  => array_merge(
					[ '{{WRAPPER}} .inx-e-icon-list__item:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}' ],
					$this->get_responsive_selectors( '{{WRAPPER}}:not(.inx-e%s--columns--1) .inx-e-icon-list__item:after', 'height: {{SIZE}}{{UNIT}}' )
				),
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label'     => __( 'Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'global'    => [
					'default' => $this->get_default( 'global_divider_color', \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT ),
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => array_merge(
					[ '{{WRAPPER}} .inx-e-icon-list__item:not(:last-child):after' => 'border-color: {{VALUE}}' ],
					$this->get_responsive_selectors( '{{WRAPPER}}:not(.inx-e%s--columns--1) .inx-e-icon-list__item:after', 'border-color: {{VALUE}}' )
				),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Icon', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'global'    => [
					'default' => $this->get_default( 'global_icon_color', \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY ),
				],
				'selectors' => [
					'{{WRAPPER}} .inx-e-icon-list__icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .inx-e-icon-list__icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => __( 'Size', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default( 'icon_size', [ 'size' => 14 ] ),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 6,
					],
					'%'  => [
						'min' => 6,
					],
					'vw' => [
						'min' => 6,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--inx-e-icon-list-icon-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'text_indent',
			[
				'label'      => __( 'Gap', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'      => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .inx-e-icon-list__icon' => is_rtl() ? 'padding-left: {{SIZE}}{{UNIT}};' : 'padding-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$list_icon_css_var      = 'var(--inx-e-icon-list-icon-size, 1em)';
		$list_icon_align_left   = sprintf( '0 calc(%s * 0.25) 0 0', $list_icon_css_var );
		$list_icon_align_center = sprintf( '0 calc(%s * 0.125)', $list_icon_css_var );
		$list_icon_align_right  = sprintf( '0 0 0 calc(%s * 0.25)', $list_icon_css_var );

		$this->add_responsive_control(
			'icon_self_align',
			[
				'label'                => __( 'Horizontal Alignment', 'immonex-kickstart-elementor' ),
				'type'                 => \Elementor\Controls_Manager::CHOOSE,
				'default'              => $this->get_default( 'icon_self_align', 'center' ),
				'options'              => [
					'left'   => [
						'title' => __( 'Left', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'selectors_dictionary' => [
					'left'   => sprintf( '--inx-e-icon-list-icon-align: left; --inx-e-icon-list-icon-margin: %s;', $list_icon_align_left ),
					'center' => sprintf( '--inx-e-icon-list-icon-align: center; --inx-e-icon-list-icon-margin: %s;', $list_icon_align_center ),
					'right'  => sprintf( '--inx-e-icon-list-icon-align: right; --inx-e-icon-list-icon-margin: %s;', $list_icon_align_right ),
				],
				'selectors'            => [
					'{{WRAPPER}} .inx-e-icon-list__items' => '{{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_self_vertical_align',
			[
				'label'     => __( 'Vertical Alignment', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'default'   => $this->get_default( 'icon_self_vertical_align', 'center' ),
				'options'   => [
					'flex-start' => [
						'title' => __( 'Start', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-v-align-top',
					],
					'center'     => [
						'title' => __( 'Center', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'flex-end'   => [
						'title' => __( 'End', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--inx-e-icon-list-icon-vertical-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_vertical_offset',
			[
				'label'      => __( 'Adjust Vertical Position', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => -15,
						'max' => 15,
					],
					'em' => [
						'min' => -1,
						'max' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--inx-e-icon-list-icon-vertical-offset: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'text_style_section',
			[
				'label' => __( 'Text', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'icon_typography',
				'global'   => [
					'default' => $this->get_default( 'global_icon_typography', \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_TEXT ),
				],
				'selector' => '{{WRAPPER}} .inx-e-icon-list__item > .inx-e-icon-list__text',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'text_shadow',
				'selector' => '{{WRAPPER}} .inx-e-icon-list__text',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'global'    => [
					'default' => $this->get_default( 'global_text_color', \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_SECONDARY ),
				],
				'selectors' => [
					'{{WRAPPER}} .inx-e-icon-list__text' => 'color: {{VALUE}};',
				],
			]
		);
	} // register_controls

	/**
	 * Return widget contents and settings for frontend template rendering.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[] Template data array or false if not available.
	 */
	protected function get_template_data() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'list_items', 'class', 'inx-e-icon-list__items' );
		$this->add_render_attribute( 'list_item', 'class', 'inx-e-icon-list__item' );

		$icon = static::ENABLE_ICON_SELECTION && ! empty( $settings['icon']['value'] ) ?
			\Elementor\Icons_Manager::try_get_icon_html( $settings['icon'], [ 'aria-hidden' => 'true' ] ) :
			'';

		return [
			'settings'        => $settings,
			'list_items_attr' => $this->get_render_attribute_string( 'list_items' ),
			'list_item_attr'  => $this->get_render_attribute_string( 'list_item' ),
			'icon'            => $icon,
			'h_tag'           => $this->get_h_tag( $settings['heading_level'] ),
			'items'           => [],
		];
	} // get_template_data

} // class Icon_List_Widget
