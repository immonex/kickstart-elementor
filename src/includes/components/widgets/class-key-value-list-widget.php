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
 * Elementor Single Property Key/Value List Base Widget
 *
 * @since 1.0.0
 */
class Key_Value_List_Widget extends Widget_Base {

	const POST_TYPE                         = 'inx_property';
	const WIDGET_NAME                       = 'inx-e-key-value-list';
	const WIDGET_ICON                       = 'eicon-table-of-contents';
	const WIDGET_CATEGORIES                 = [ 'inx-single-property' ];
	const DEFAULT_CONTROL_SCOPES            = [ 'heading', 'heading_style' ];
	const ENABLE_ICONS                      = true;
	const ENABLE_BEFORE_AFTER_ITEM_CONTENTS = false;
	const FIXED_ELEMENTS                    = [];
	const DISABLE_SOURCE_NOTICE             = false;

	/**
	 * Value Formatting Filters
	 *
	 * @var mixed[]
	 */
	protected $format_filters = [];

	/**
	 * Predefined Elements
	 *
	 * @var mixed[]
	 */
	protected $predefined_elements = [];

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Key/Value List', 'immonex-kickstart-elementor' );
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
					__( 'key-value', 'immonex-kickstart-elementor' ),
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
		$tweaks = apply_filters(
			'inx_elementor_tweaks',
			[ 'enable_before_after_item_contents' => self::ENABLE_BEFORE_AFTER_ITEM_CONTENTS ],
			90
		);

		if ( empty( static::FIXED_ELEMENTS ) ) {
			$predefined_elements         = $this->get_predefined_elements();
			$element_type_select_options = $this->get_element_type_select_options();

			$selectable_elements = [];
			foreach ( $predefined_elements as $key => $element ) {
				$selectable_elements[ $key ] = $element['title'];
			}
		}

		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		if ( in_array( 'heading', static::DEFAULT_CONTROL_SCOPES, true ) ) {
			$default_control_args = [
				'heading' => [
					'default' => $this->get_default( 'heading', __( 'Detail Section', 'immonex-kickstart-elementor' ) ),
				],
			];

			$this->add_default_controls( [ 'heading' ], $default_control_args );
		}

		$this->add_main_class_control();

		if ( empty( static::FIXED_ELEMENTS ) ) {
			$repeater = new \Elementor\Repeater();

			if ( ! empty( $predefined_elements ) ) {
				$repeater->add_control(
					'predefined_element',
					[
						'label'   => __( 'Element', 'immonex-kickstart-elementor' ),
						'type'    => \Elementor\Controls_Manager::SELECT,
						'options' => $selectable_elements,
					]
				);

				$options_json = wp_json_encode( $selectable_elements );
			} else {
				$repeater->add_control(
					'element_type',
					[
						'label'       => __( 'Element Type', 'immonex-kickstart-elementor' ),
						'type'        => \Elementor\Controls_Manager::SELECT,
						'default'     => $this->get_default( 'element_type', 'group' ),
						'options'     => $element_type_select_options,
						'label_block' => true,
					]
				);

				$all_options = [];

				foreach ( $element_type_select_options as $option_type => $option_title ) {
					if ( 'user_defined' === $option_type ) {
						continue;
					}

					$options = $this->add_extended_element_select_options(
						apply_filters( 'inx_elementor_mapping_select_options', [], $option_type ),
						$option_type
					);

					if ( empty( $options ) ) {
						continue;
					}

					$repeater->add_control(
						"element_{$option_type}",
						[
							'label'       => __( 'Element', 'immonex-kickstart-elementor' ),
							'type'        => \Elementor\Controls_Manager::SELECT,
							'options'     => $options,
							'condition'   => [
								'element_type' => $option_type,
							],
							'label_block' => true,
						]
					);

					$all_options = array_merge( $all_options, $options );
				}

				$repeater->add_control(
					'element_user_defined',
					[
						'label'       => __( 'Element', 'immonex-kickstart-elementor' ),
						'type'        => \Elementor\Controls_Manager::TEXT,
						'condition'   => [
							'element_type' => 'user_defined',
						],
						'label_block' => true,
					]
				);

				$format_filters = $this->get_format_filters();
				$format_options = [
					'' => __( 'no formatting', 'immonex-kickstart-elementor' ),
				];
				if ( ! empty( $format_filters ) ) {
					foreach ( $format_filters as $key => $filter ) {
						$format_options[ $key ] = $filter['title'];
					}
				}

				$repeater->add_control(
					'format',
					[
						'label'       => __( 'Format', 'immonex-kickstart-elementor' ),
						'type'        => \Elementor\Controls_Manager::SELECT,
						'options'     => $format_options,
						'label_block' => true,
					]
				);

				$repeater->add_control(
					'decimal_places',
					[
						'label'     => __( 'Decimal Places', 'immonex-kickstart-elementor' ),
						'type'      => \Elementor\Controls_Manager::SELECT,
						'default'   => $this->get_default( 'decimal_places', 9 ),
						'options'   => [
							9 => __( 'auto', 'immonex-kickstart-elementor' ),
							1 => '1',
							2 => '2',
						],
						'condition' => [
							'format' => [
								'inx_format_price',
								'inx_format_area',
								'inx_format_number',
							],
						],
					]
				);

				$options_json = wp_json_encode( $all_options );
			}

			$repeater->add_control(
				'before_value',
				[
					'label'       => __( 'Before Value', 'immonex-kickstart-elementor' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'label_block' => true,
					'separator'   => 'before',
				]
			);

			$repeater->add_control(
				'after_value',
				[
					'label'       => __( 'After Value', 'immonex-kickstart-elementor' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'label_block' => true,
					'separator'   => 'after',
				]
			);

			$repeater->add_control(
				'icon',
				[
					'label'       => __( 'Icon', 'immonex-kickstart-elementor' ),
					'type'        => \Elementor\Controls_Manager::ICONS,
					'recommended' => [
						'fa-solid'   => [
							'hashtag',
							'calendar-alt',
							'ruler',
							'ruler-combined',
							'ruler-horizontal',
							'ruler-vertical',
							'expand',
							'expand-alt',
							'expand-arrows-alt',
							'vector-square',
							'object-group',
							'location-arrow',
							'map',
							'map-marked',
							'map-marked-alt',
							'map-marker',
							'map-marker-alt',
							'map-pin',
							'map-signs',
							'format_area',
							'door-open',
							'bed',
							'bath',
							'toilet',
							'toilet-paper',
							'home',
							'house-user',
							'house-damage',
							'laptop-house',
							'warehouse',
							'building',
							'industry',
							'store',
							'store-alt',
							'hospital',
							'hospital-alt',
							'clinic-medical',
							'hotel',
							'school',
							'tree',
							'car',
							'parking',
							'restroom',
							'sign',
							'key',
							'hammer',
							'euro-sign',
							'money-bill',
							'money-bill-alt',
							'money-bill-wave',
							'money-bill-wave-alt',
						],
						'fa-regular' => [
							'map',
							'object-group',
							'building',
							'hospital',
							'money-bill-alt',
						],
					],
				]
			);

			if ( $tweaks['enable_before_after_item_contents'] ) {
				$repeater->add_control(
					'before_item',
					[
						'label' => __( 'Before Item', 'immonex-kickstart-elementor' ),
						'type'  => \Elementor\Controls_Manager::TEXT,
					]
				);

				$repeater->add_control(
					'after_item',
					[
						'label' => __( 'After Item', 'immonex-kickstart-elementor' ),
						'type'  => \Elementor\Controls_Manager::TEXT,
					]
				);
			}

			if ( ! static::DISABLE_SOURCE_NOTICE ) {
				$this->add_control(
					'elements_notice',
					[
						'type'        => \Elementor\Controls_Manager::NOTICE,
						'notice_type' => 'warning',
						'content'     => __( 'Elements can be combined based on the entries in the <strong>import mapping table</strong> (immonex OpenImmo2WP).', 'immonex-kickstart-elementor' ) .
							'<br><br>(' .
							__( 'The sample data shown may not match the actual type of information.', 'immonex-kickstart-elementor' ) .
							')',
					]
				);
			}

			$title_field = "<# const labels = {$options_json}; ";
			if ( ! empty( $predefined_elements ) ) {
				$title_field .= "const label = typeof predefined_element !== 'undefined' ? labels[predefined_element] : ''; #>{{{ label }}}";
			} else {
				$title_field .= "let label = eval('element_' + element_type); label = label.replace(/[:=]\*/, ''); #>{{{ label }}}";
			}

			$this->add_control(
				'elements',
				[
					'label'       => __( 'Elements', 'immonex-kickstart-elementor' ),
					'type'        => \Elementor\Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'title_field' => $title_field,
					'default'     => $this->get_default_elements(),
				]
			);
		}

		$this->end_controls_section();

		if ( in_array( 'heading_style', static::DEFAULT_CONTROL_SCOPES, true ) ) {
			$this->add_default_controls( [ 'heading_style' ] );
		}

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
						'title' => __( 'Columns', 'immonex-kickstart-elementor' ),
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
				'widescreen_default'   => $this->get_default( 'columns', '3', 'widescreen' ),
				'default'              => $this->get_default( 'columns', '3' ),
				'laptop_default'       => $this->get_default( 'columns', '3', 'laptop' ),
				'tablet_default'       => $this->get_default( 'columns', '2', 'tablet' ),
				'tablet_extra_default' => $this->get_default( 'columns', '2', 'tablet_extra' ),
				'mobile_default'       => $this->get_default( 'columns', '1', 'mobile' ),
				'mobile_extra_default' => $this->get_default( 'columns', '1', 'mobile_extra' ),
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
					'{{WRAPPER}}:not(.inx-e--layout--inline) .inx-e-key-value-list__items' => 'display: grid; grid-template-columns: repeat({{VALUE}}, 1fr)',
				],
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
						'title' => __( 'left', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'center', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => __( 'right', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'inx-e%s--align--',
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
						'{{WRAPPER}}:not(.inx-e--layout--inline) .inx-e-key-value-list__item:not(:first-child)' => 'padding-top: calc({{SIZE}}{{UNIT}}/2)',
						'{{WRAPPER}}:not(.inx-e--layout--inline) .inx-e-key-value-list__item:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
						'{{WRAPPER}}.inx-e--layout--inline .inx-e-key-value-list__item' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
						'{{WRAPPER}}.inx-e--layout--inline .inx-e-key-value-list__items' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
						'body.rtl {{WRAPPER}}.inx-e--layout--inline .inx-e-key-value-list__item:after' => 'left: calc(-{{SIZE}}{{UNIT}}/2)',
						'body:not(.rtl) {{WRAPPER}}.inx-e--layout--inline .inx-e-key-value-list__item:after' => 'right: calc(-{{SIZE}}{{UNIT}}/2)',
					],
					$this->get_responsive_selectors( '{{WRAPPER}}:not(.inx-e--layout--inline):not(.inx-e%s--columns--1) .inx-e-key-value-list__item:last-child', 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)' )
				),
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
					[ '{{WRAPPER}} .inx-e-key-value-list__item:not(:last-child):after' => 'content: ""' ],
					$this->get_responsive_selectors( '{{WRAPPER}}:not(.inx-e--layout--inline):not(.inx-e%s--columns--1) .inx-e-key-value-list__item:last-child:after', 'content: ""' )
				),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label'     => __( 'Style', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					'solid'  => __( 'Solid', 'immonex-kickstart-elementor' ),
					'double' => __( 'Double', 'immonex-kickstart-elementor' ),
					'dotted' => __( 'Dotted', 'immonex-kickstart-elementor' ),
					'dashed' => __( 'Dashed', 'immonex-kickstart-elementor' ),
				],
				'default'   => $this->get_default( 'divider_style', 'solid' ),
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => array_merge(
					[
						'{{WRAPPER}}.inx-e--layout--inline .inx-e-key-value-list__item:not(:last-child):after' => 'border-left-style: {{VALUE}}',
						'{{WRAPPER}}:not(.inx-e--layout--inline) .inx-e-key-value-list__item:not(:last-child):after' => 'border-top-style: {{VALUE}}',
					],
					$this->get_responsive_selectors( '{{WRAPPER}}:not(.inx-e--layout--inline):not(.inx-e%s--columns--1) .inx-e-key-value-list__item:last-child:after', 'border-top-style: {{VALUE}}' )
				),
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label'      => __( 'Weight', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default(
					'divider_height',
					[
						'size' => 1,
						'unit' => 'px',
					]
				),
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
						'{{WRAPPER}}.inx-e--layout--inline .inx-e-key-value-list__item:not(:last-child)' => 'padding-right: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}}.inx-e--layout--inline .inx-e-key-value-list__item:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}}:not(.inx-e--layout--inline) .inx-e-key-value-list__inner-item' => 'padding-bottom: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}}:not(.inx-e--layout--inline) .inx-e-key-value-list__item:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}',
					],
					$this->get_responsive_selectors( '{{WRAPPER}}:not(.inx-e--layout--inline):not(.inx-e%s--columns--1) .inx-e-key-value-list__item:last-child:after', 'border-top-width: {{SIZE}}{{UNIT}}' )
				),
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label'      => __( 'Width', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default(
					'divider_width',
					[
						'size' => 1,
						'unit' => 'px',
					]
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'condition'  => [
					'divider' => 'yes',
					'view!'   => 'inline',
				],
				'selectors'  => array_merge(
					[ '{{WRAPPER}} .inx-e-key-value-list__item:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}' ],
					$this->get_responsive_selectors( '{{WRAPPER}}:not(.inx-e%s--columns--1) .inx-e-key-value-list__item:last-child:after', 'width: {{SIZE}}{{UNIT}}' )
				),
			]
		);

		$this->add_control(
			'divider_height',
			[
				'label'      => __( 'Height', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default(
					'divider_height',
					[
						'size' => 1,
						'unit' => 'px',
					]
				),
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
					[ '{{WRAPPER}} .inx-e-key-value-list__item:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}' ],
					$this->get_responsive_selectors( '{{WRAPPER}}:not(.inx-e%s--columns--1) .inx-e-key-value-list__item:after', 'height: {{SIZE}}{{UNIT}}' )
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
					[ '{{WRAPPER}} .inx-e-key-value-list__item:not(:last-child):after' => 'border-color: {{VALUE}}' ],
					$this->get_responsive_selectors( '{{WRAPPER}}:not(.inx-e%s--columns--1) .inx-e-key-value-list__item:after', 'border-color: {{VALUE}}' )
				),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'item_section',
			[
				'label' => __( 'Item', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_layout',
			[
				'label'        => __( 'Layout', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::CHOOSE,
				'default'      => $this->get_default( 'item_layout', 'vertical' ),
				'options'      => [
					'horizontal' => [
						'title' => __( 'Horizontal', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-ellipsis-h',
					],
					'vertical'   => [
						'title' => __( 'Vertical', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-editor-list-ul',
					],
				],
				'toggle'       => false,
				'prefix_class' => 'inx-e-key-value-list--item-layout--',
			]
		);

		$this->add_control(
			'item_horizontal_align',
			[
				'label'     => __( 'Horizontal Alignment', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'default'   => $this->get_default( 'item_horizontal_align', 'flex-start' ),
				'options'   => [
					'flex-start' => [
						'title' => __( 'Left', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-h-align-center',
					],
					'flex-end'   => [
						'title' => __( 'Right', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'condition' => [
					'item_layout' => 'vertical',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--inx-e-key-value-list-item-h-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_vertical_align',
			[
				'label'     => __( 'Vertical Alignment', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'default'   => $this->get_default( 'item_vertical_align', 'baseline' ),
				'options'   => [
					'flex-start' => [
						'title' => __( 'Start', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-v-align-top',
					],
					'center'     => [
						'title' => __( 'Center', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'baseline'   => [
						'title' => __( 'Baseline', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-align-end-v',
					],
					'flex-end'   => [
						'title' => __( 'End', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--inx-e-key-value-list-item-v-align: {{VALUE}};',
				],
			]
		);

		$item_order_options = [
			'label-value' => __( 'Label, Value', 'immonex-kickstart-elementor' ),
			'value-label' => __( 'Value, Label', 'immonex-kickstart-elementor' ),
		];

		if ( static::ENABLE_ICONS ) {
			$item_order_options = array_merge(
				$item_order_options,
				[
					'label-value-icon' => __( 'Label, Value, Icon', 'immonex-kickstart-elementor' ),
					'icon-label-value' => __( 'Icon, Label, Value', 'immonex-kickstart-elementor' ),
					'icon-value-label' => __( 'Icon, Value, Label', 'immonex-kickstart-elementor' ),
					'value-label-icon' => __( 'Value, Label, Icon', 'immonex-kickstart-elementor' ),
					'icon-value'       => __( 'Icon, Value', 'immonex-kickstart-elementor' ),
					'value-icon'       => __( 'Value, Icon', 'immonex-kickstart-elementor' ),
				]
			);
		}

		$this->add_control(
			'item_order',
			[
				'label'        => __( 'Scope/Order', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => $this->get_default( 'item_order', static::ENABLE_ICONS ? 'icon-label-value' : 'label-value' ),
				'options'      => $item_order_options,
				'prefix_class' => 'inx-e-key-value-list--item-order--',
				'render_type'  => 'template',
				'label_block'  => true,
			]
		);

		$this->end_controls_section();

		if ( static::ENABLE_ICONS ) {
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
					'selectors' => [
						'{{WRAPPER}} .inx-e-key-value-list__icon i'   => 'color: {{VALUE}};',
						'{{WRAPPER}} .inx-e-key-value-list__icon svg' => 'fill: {{VALUE}};',
					],
					'global'    => [
						'default' => $this->get_default( 'global_icon_color', \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY ),
					],
				]
			);

			$this->add_responsive_control(
				'icon_size',
				[
					'label'      => __( 'Size', 'immonex-kickstart-elementor' ),
					'type'       => \Elementor\Controls_Manager::SLIDER,
					'default'    => $this->get_default( 'icon_size', [ 'size' => 16 ] ),
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
						'{{WRAPPER}}' => '--inx-e-key-value-list-icon-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'icon_gap',
				[
					'label'      => __( 'Gap', 'immonex-kickstart-elementor' ),
					'type'       => \Elementor\Controls_Manager::SLIDER,
					'default'    => $this->get_default( 'icon_gap', [ 'size' => 4 ] ),
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
					'range'      => [
						'px' => [
							'max' => 50,
						],
					],
					'selectors'  => [
						'{{WRAPPER}}' => '--inx-e-key-value-list-icon-gap: {{SIZE}}{{UNIT}};',
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
							'min' => -16,
							'max' => 16,
						],
						'em' => [
							'min' => -1,
							'max' => 1,
						],
					],
					'selectors'  => [
						'{{WRAPPER}}' => '--inx-e-key-value-list-icon-vertical-offset: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->end_controls_section();
		}

		$typo_sections = [
			'label'       => [
				'label'         => __( 'Label', 'immonex-kickstart-elementor' ),
				'typo_default'  => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_SECONDARY,
				'color_default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_SECONDARY,
			],
			'value'       => [
				'label'         => __( 'Value', 'immonex-kickstart-elementor' ),
				'typo_default'  => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				'color_default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
			],
			'before_item' => [
				'label'         => __( 'Before Item', 'immonex-kickstart-elementor' ),
				'typo_default'  => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_TEXT,
				'color_default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT,
			],
			'after_item'  => [
				'label'         => __( 'After Item', 'immonex-kickstart-elementor' ),
				'typo_default'  => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_TEXT,
				'color_default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT,
			],
		];

		foreach ( $typo_sections as $key => $element ) {
			$class_key = str_replace( '_', '-', $key );

			$this->start_controls_section(
				"section_{$key}_style",
				[
					'label' => $element['label'],
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => "{$key}_typography",
					'selector' => "{{WRAPPER}} .inx-e-key-value-list__{$class_key}",
					'global'   => [
						'default' => $element['typo_default'],
					],
					'exclude'  => ! empty( $element['exclude'] ) ? $element['exclude'] : [],
				]
			);

			if ( isset( $element['exclude'] ) && in_array( 'font_size', $element['exclude'], true ) ) {
				$this->add_control(
					"{$key}_font_size",
					[
						'label'      => __( 'Font Size', 'immonex-kickstart-elementor' ),
						'type'       => \Elementor\Controls_Manager::SLIDER,
						'default'    => $this->get_default( "{$key}_font_size", [ 'size' => 20 ] ),
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
						'range'      => [
							'px' => [
								'max' => 64,
							],
						],
						'selectors'  => [
							'{{WRAPPER}}' => "--inx-e-key-value-list-{$key}-font-size: {{SIZE}}{{UNIT}};",
						],
					]
				);
			}

			$this->add_group_control(
				\Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name'     => "{$key}_shadow",
					'selector' => "{{WRAPPER}} .inx-e-key-value-list__{$class_key}",
				]
			);

			$this->add_control(
				"{$key}_color",
				[
					'label'     => __( 'Color', 'immonex-kickstart-elementor' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						"{{WRAPPER}} .inx-e-key-value-list__{$class_key}" => 'color: {{VALUE}};',
					],
					'global'    => [
						'default' => $this->get_default( "global_{$key}_color", $element['color_default'] ),
					],
				]
			);

			if ( 'label' === $key ) {
				$this->add_control(
					"{$key}_gap",
					[
						'label'      => __( 'Gap', 'immonex-kickstart-elementor' ),
						'type'       => \Elementor\Controls_Manager::SLIDER,
						'default'    => $this->get_default( "{$key}_gap", [ 'size' => 8 ] ),
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
						'range'      => [
							'px' => [
								'max' => 64,
							],
						],
						'selectors'  => [
							'{{WRAPPER}}' => "--inx-e-key-value-list-{$key}-gap: {{SIZE}}{{UNIT}};",
						],
					]
				);
			}

			$this->end_controls_section();
		}
	} // register_controls

	/**
	 * Return widget contents and settings for frontend template rendering.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[]|bool Template data array or false if not available.
	 */
	protected function get_template_data() {
		$settings            = $this->get_settings_for_display();
		$predefined_elements = $this->get_predefined_elements();

		if ( ! empty( static::FIXED_ELEMENTS ) ) {
			$elements = [];

			foreach ( static::FIXED_ELEMENTS as $element ) {
				if ( ! empty( $predefined_elements[ $element ] ) ) {
					if ( empty( $predefined_elements[ $element ]['element'] ) ) {
						$predefined_elements[ $element ]['element'] = $element;
					}

					$elements[] = $predefined_elements[ $element ];
				}
			}
		} else {
			$elements = ! empty( $settings['elements'] ) ? $settings['elements'] : [];
		}

		if ( empty( $elements ) ) {
			return false;
		}

		$this->add_render_attribute( 'list_items', 'class', 'inx-e-key-value-list__items' );
		$this->add_render_attribute( 'list_item', 'class', 'inx-e-key-value-list__item' );

		$show          = explode( '-', $settings['item_order'] );
		$template_data = [
			'settings'        => $settings,
			'list_items_attr' => $this->get_render_attribute_string( 'list_items' ),
			'list_item_attr'  => $this->get_render_attribute_string( 'list_item' ),
			'h_tag'           => ! empty( $settings['heading_level'] ) ? $this->get_h_tag( $settings['heading_level'] ) : '',
			'items'           => [],
		];

		foreach ( $elements as $element ) {
			if (
				! empty( $element['predefined_element'] )
				&& ! empty( $predefined_elements[ $element['predefined_element'] ] )
			) {
				$element = array_merge( $predefined_elements[ $element['predefined_element'] ], $element );
			}

			if ( empty( $element['element_type'] ) ) {
				continue;
			}

			$scope = in_array( $element['element_type'], [ 'name', 'group' ], true ) ? $element['element_type'] : false;

			if ( ! empty( $element[ "element_{$element['element_type']}" ] ) ) {
				$element['element'] = $element[ "element_{$element['element_type']}" ];
				if ( '*' === substr( $element['element'], -1 ) ) {
					// Submit wildcard queries as RegEx.
					$element['element'] = '/' . substr( $element['element'], 0, -1 ) . '.*/';
				}
			}

			if ( empty( $element['element'] ) ) {
				continue;
			}

			$element_data = apply_filters( 'inx_get_flex_items', [], $element['element'], $scope );
			if ( empty( $element_data ) ) {
				continue;
			}

			$format_filters = $this->get_format_filters();

			foreach ( $element_data as $i => $element_return ) {
				$element['icon_html'] = in_array( 'icon', $show, true ) && ! empty( $element['icon'] ) ?
					\Elementor\Icons_Manager::try_get_icon_html(
						$element['icon'],
						[
							'aria-hidden' => empty( $element_return['title'] )
								|| in_array( 'label', $show, true ) ? 'true' : 'false',
							'aria-label'  => ! empty( $element_return['title'] )
								&& ! in_array( 'label', $show, true ) ? esc_attr( $element_return['title'] ) : '',
						]
					) :
					'';

				if ( ! in_array( 'label', $show, true ) ) {
					$element_return['title'] = '';
				}

				if ( ! empty( $element['format'] ) && isset( $format_filters[ $element['format'] ] ) ) {
					$format         = $format_filters[ $element['format'] ];
					$format['args'] = ! empty( $format['args'] ) ? $format['args'] : [];

					if ( ! empty( $element['decimal_places'] ) && empty( $format['args']['decimals'] ) ) {
						$format['args']['decimals'] = $element['decimal_places'];
					}

					$element_return['value'] = apply_filters(
						'inx_format',
						$element_return['value'],
						$format['type'],
						$format['args']
					);
				}

				if ( ! empty( $element['before_value'] ) ) {
					if ( ' ' === $element['before_value'] ) {
						$element['before_value'] = '&nbsp;';
					}
					$element_return['value'] = $element['before_value'] . $element_return['value'];
				}

				if ( ! empty( $element['after_value'] ) ) {
					if ( ' ' === $element['after_value'] ) {
						$element['after_value'] = '&nbsp;';
					}
					$element_return['value'] .= $element['after_value'];
				}

				$element_data[ $i ] = array_merge( $element, $element_return );
			}

			$template_data['items'] = array_merge(
				$template_data['items'],
				$element_data
			);
		}

		return ! empty( $template_data['items'] ) ? $template_data : false;
	} // get_template_data

	/**
	 * Return default elements.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[] Element data.
	 */
	protected function get_default_elements() {
		return [ [] ];
	} // get_default_elements

	/**
	 * Extend and return predefined elements (if defined in derived class).
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[] Element data.
	 */
	protected function get_predefined_elements() {
		if ( empty( $this->predefined_elements ) ) {
			return [];
		}

		foreach ( $this->predefined_elements as $element_key => $element ) {
			if ( empty( $element['element'] ) ) {
				$this->predefined_elements[ $element_key ]['element'] = $element_key;
			}
		}

		return $this->predefined_elements;
	} // get_predefined_elements

	/**
	 * Return available value formatting filters.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[] Value filter data.
	 */
	protected function get_format_filters() {
		if ( ! empty( $this->format_filters ) ) {
			return $this->format_filters;
		}

		$this->format_filters = apply_filters(
			'inx_elementor_format_filters',
			[
				'inx_format_price'  => [
					'title' => __( 'Price', 'immonex-kickstart-elementor' ),
					'type'  => 'price',
					'args'  => [],
				],
				'inx_format_area'   => [
					'title' => __( 'Area', 'immonex-kickstart-elementor' ),
					'type'  => 'area',
					'args'  => [],
				],
				'inx_format_number' => [
					'title' => __( 'Number', 'immonex-kickstart-elementor' ),
					'type'  => 'number',
					'args'  => [],
				],
				'inx_format_link'   => [
					'title' => __( 'Link (URL/E-Mail/Phone)', 'immonex-kickstart-elementor' ),
					'type'  => 'link',
					'args'  => [],
				],
			]
		);

		return $this->format_filters;
	} // get_format_filters

	/**
	 * Return the element type control select options.
	 *
	 * @since 1.0.0
	 *
	 * @return string[] Associative array: key => title.
	 */
	protected function get_element_type_select_options() {
		return [
			'group'        => __( 'Group', 'immonex-kickstart-elementor' ),
			'name'         => __( 'Name', 'immonex-kickstart-elementor' ),
			'source'       => __( 'Source', 'immonex-kickstart-elementor' ),
			'destination'  => __( 'Destination (Custom Field)', 'immonex-kickstart-elementor' ),
			'user_defined' => __( 'User-defined/RegEx', 'immonex-kickstart-elementor' ),
		];
	} // get_element_type_select_options

	/**
	 * Add extended element select options that are usually not listed in the
	 * mapping table (currently only relevant for destination type elements).
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $options Original select options.
	 * @param string   $type    Element type.
	 *
	 * @return string[] Associative array: key => title.
	 */
	protected function add_extended_element_select_options( $options, $type ) {
		$raw_ext_options = [
			'destination' => [
				'_inx_full_address'                    => __( 'full Address', 'immonex-kickstart-elementor' ),
				'_inx_street'                          => __( 'Street', 'immonex-kickstart-elementor' ),
				'_inx_lat'                             => __( 'Latitude', 'immonex-kickstart-elementor' ),
				'_inx_lng'                             => __( 'Longitude', 'immonex-kickstart-elementor' ),
				'_inx_virtual_tour_embed_code'         => __( 'Virtual Tour Embed Code', 'immonex-kickstart-elementor' ),
				'_openimmo_obid'                       => __( 'OpenImmo ID (OBID)', 'immonex-kickstart-elementor' ),
				'_immonex_energy_class'                => __( 'Energy Efficiency Class', 'immonex-kickstart-elementor' ),
				'_immonex_areabutler_url'              => __( 'AreaButler URL', 'immonex-kickstart-elementor' ),
				'_immonex_areabutler_url_no_address'   => __( 'AreaButler URL without Address', 'immonex-kickstart-elementor' ),
				'_immonex_areabutler_url_with_address' => __( 'AreaButler URL with Address', 'immonex-kickstart-elementor' ),
			],
		];

		if ( ! isset( $raw_ext_options[ $type ] ) ) {
			return $options;
		}

		foreach ( $raw_ext_options[ $type ] as $key => $title ) {
			if ( isset( $options[ $key ] ) ) {
				continue;
			}

			$options[ $key ] = wp_sprintf( '%s [%s]', $key, $title );
		}

		ksort( $options );

		return $options;
	} // add_extended_element_select_options

} // class Key_Value_List_Widget
