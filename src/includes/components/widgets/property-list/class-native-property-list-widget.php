<?php
/**
 * Class Native_Property_List_Widget
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor\Components\Widgets\PropertyList;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Native Property List Widget
 *
 * @since 1.0.0
 */
class Native_Property_List_Widget extends \immonex\Kickstart\ForElementor\Components\Widgets\Widget_Base {

	const WIDGET_NAME              = 'inx-e-native-property-list';
	const WIDGET_ICON              = 'eicon-gallery-grid';
	const WIDGET_CATEGORIES        = [ 'inx-property-list' ];
	const WIDGET_HELP_URL          = 'https://docs.immonex.de/kickstart-for-elementor/#/elementor-immobilien-widgets/liste-grid';
	const ENABLE_RENDER_ON_PREVIEW = true;

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'List (Grid)', 'immonex-kickstart-for-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'list', 'immonex-kickstart-for-elementor' ),
					__( 'grid', 'immonex-kickstart-for-elementor' ),
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
		$sort_options = $this->get_property_sort_options();

		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_main_class_control();
		$this->add_default_controls( [ 'lists' ] );

		$this->add_control(
			'no_results_text',
			[
				'label'       => __( 'No Results Message', 'immonex-kickstart-for-elementor' ),
				'description' => __( '<strong>Optional</strong> custom text to display if no properties match the search/filter criteria. (Defaults to the message stored in the Kickstart plugin options.)', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'separator'   => 'before',
			]
		);

		$this->add_default_controls(
			[
				'disable_links',
				'force-lang',
			],
			[
				'disable_links' => [ 'separator' => 'before' ],
				'force-lang'    => [ 'separator' => 'before' ],
			]
		);

		$this->add_control(
			'sort',
			[
				'label'         => __( 'Custom Sort Priority', 'immonex-kickstart-for-elementor' ),
				'type'          => \Elementor\Controls_Manager::REPEATER,
				'fields'        => [
					[
						'name'        => 'option',
						'label'       => __( 'Option', 'immonex-kickstart-for-elementor' ),
						'description' => __( 'By default, lists are sorted in descending order by the publication date of the entries they contain.', 'immonex-kickstart-for-elementor' ),
						'type'        => \Elementor\Controls_Manager::SELECT,
						'options'     => $sort_options['array'],
					],
				],
				'title_field'   => "<# const labels = {$sort_options['json']}; const label = typeof option !== 'undefined' ? labels[option] : ''; #>{{{ label }}}",
				'prevent_empty' => false,
				'label_block'   => true,
				'separator'     => 'before',
			]
		);

		$this->add_default_controls(
			[ 'authors' ],
			[ 'authors' => [ 'separator' => 'before' ] ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'property_tile_content_section',
			[
				'label' => _x( 'Property Tiles', 'real estate property', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$property_tile_elements = [
			'media-top'      => __( 'Image', 'immonex-kickstart-for-elementor' ),
			'labels'         => __( 'Labels', 'immonex-kickstart-for-elementor' ),
			'property-type'  => __( 'Property Type', 'immonex-kickstart-for-elementor' ),
			'location'       => __( 'Location', 'immonex-kickstart-for-elementor' ),
			'excerpt'        => __( 'Excerpt', 'immonex-kickstart-for-elementor' ),
			'core-details'   => __( 'Core Details', 'immonex-kickstart-for-elementor' ),
			'property-price' => __( 'Price', 'immonex-kickstart-for-elementor' ),
		];

		foreach ( $property_tile_elements as $key => $label ) {
			$this->add_control(
				"include_{$key}",
				[
					'label'        => $label,
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'default'      => '1',
					'return_value' => '1',
				]
			);

			$selectors = [ "{{WRAPPER}} .inx-property-list-item__{$key}" => 'display: none' ];
			if ( 'core-details' === $key ) {
				$selectors['{{WRAPPER}} .inx-property-list-item__footer'] = 'border-top: 0';
			}

			$this->add_control(
				"hide_{$key}",
				[
					'type'      => \Elementor\Controls_Manager::HIDDEN,
					'default'   => '1',
					'selectors' => $selectors,
					'condition' => [
						"include_{$key}!" => '1',
					],
				]
			);
		}

		$this->add_control(
			'hide_footer',
			[
				'type'      => \Elementor\Controls_Manager::HIDDEN,
				'default'   => '1',
				'selectors' => [ '{{WRAPPER}} .inx-property-list-item__footer' => 'display: none' ],
				'condition' => [
					'include_core-details!'   => '1',
					'include_property-price!' => '1',
				],
			]
		);

		$this->add_control(
			'remove_body_margin',
			[
				'type'      => \Elementor\Controls_Manager::HIDDEN,
				'default'   => '1',
				'selectors' => [ '{{WRAPPER}} .inx-property-list-item__body .uk-margin-bottom' => 'margin-bottom: 0 !important' ],
				'condition' => [
					'include_excerpt!'        => '1',
					'include_core-details!'   => '1',
					'include_property-price!' => '1',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tax_filters_section',
			[
				'label' => __( 'Taxonomy Filters', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_default_controls( [ 'tax_filters' ] );

		$this->end_controls_section();

		$this->start_controls_section(
			'cf_filters_section',
			[
				'label' => __( 'Custom Field Filters', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_default_controls(
			[ 'cf_filters' ],
			[
				'iso-country' => [ 'separator' => 'before' ],
				'references'  => [ 'separator' => 'before' ],
				'available'   => [ 'separator' => 'before' ],
				'featured'    => [ 'separator' => 'before' ],
			]
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
			'column_min_width',
			[
				'label'      => __( 'Column Minimum Width', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 180,
						'max' => 1200,
					],
				],
				'selectors'  => [ '{{WRAPPER}} .inx-property-list' => 'grid-template-columns: repeat(auto-fit, minmax({{SIZE}}{{UNIT}}, 1fr))' ],
			]
		);

		$this->add_control(
			'remove_max_width',
			[
				'type'       => \Elementor\Controls_Manager::HIDDEN,
				'default'    => '1',
				'selectors'  => [
					'{{WRAPPER}} .inx-property-list .inx-property-list__item-wrap' => 'width: 100%',
					'{{WRAPPER}} .inx-property-list .inx-property-list-item' => 'max-width: 100%',
				],
				'conditions' => [
					'terms' => [
						[
							'name'     => 'column_min_width[size]',
							'operator' => '!=',
							'value'    => '',
						],
					],
				],
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
					'{{WRAPPER}} .inx-property-list' => 'grid-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_property_tile_frame_style',
			[
				'label' => _x( 'Property Tile', 'real estate property', 'immonex-kickstart-for-elementor' ) .
					' (' . __( 'Frame', 'immonex-kickstart-for-elementor' ) . ')',
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'property_tile_border',
				'selector' => '{{WRAPPER}} .inx-property-list-item',
			]
		);

		$this->add_responsive_control(
			'property_tile_border_radius',
			[
				'label'      => __( 'Border Radius', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .inx-property-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'property_tile_box_shadow_type',
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
			'property_tile_remove_native_box_shadow',
			[
				'type'      => \Elementor\Controls_Manager::HIDDEN,
				'default'   => '1',
				'selectors' => [
					'{{WRAPPER}} .inx-property-list-item' => 'box-shadow: none',
				],
				'condition' => [
					'property_tile_box_shadow_type' => 'disabled',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'property_tile_box_shadow',
				'label'     => __( 'Box Shadow', 'immonex-kickstart-for-elementor' ) .
					' (' . __( 'Custom', 'immonex-kickstart-for-elementor' ) . ')',
				'selector'  => '{{WRAPPER}} .inx-property-list-item',
				'condition' => [
					'property_tile_box_shadow_type' => 'custom',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			[
				'label' => __( 'Image', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_container_height',
			[
				'label'      => __( 'Container Height', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 180,
						'max' => 1200,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .inx-property-list-item__media-top' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => __( 'Border Radius', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .inx-property-list-item__media-top' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_labels_style',
			[
				'label' => __( 'Labels', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'labels_layout',
			[
				'label'   => __( 'Layout', 'immonex-kickstart-for-elementor' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'default' => $this->get_default( 'labels_layout', 'vertical' ),
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
			'labels_horizontal_layout',
			[
				'type'      => \Elementor\Controls_Manager::HIDDEN,
				'default'   => '1',
				'selectors' => [
					'{{WRAPPER}} .inx-property-list-item__labels'                          => 'display: flex; flex-wrap: wrap; gap: 0.5em',
					'{{WRAPPER}} .inx-property-list-item__labels .inx-property-label'      => 'margin-bottom: 0',
					'{{WRAPPER}} .inx-property-list-item__labels .inx-property-label > br' => 'display: none',
				],
				'condition' => [
					'labels_layout' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'labels_border_radius',
			[
				'label'      => __( 'Corner Radius', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default(
					'border_radius',
					[
						'size' => 4,
						'unit' => 'px',
					]
				),
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
					'{{WRAPPER}} .inx-property-label' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'labels_box_shadow',
				'label'    => __( 'Box Shadow', 'immonex-kickstart-for-elementor' ),
				'selector' => '{{WRAPPER}} .inx-property-label',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'           => 'label_typography',
				'selector'       => '{WRAPPER}} .inx-property-label.uk-label',
				'fields_options' => [
					'font_size'      => [
						'default' => [
							'size' => 1,
							'unit' => 'em',
						],
					],
					'font_weight'    => [
						'default' => 'bold',
					],
					'text_transform' => [
						'default' => 'uppercase',
					],
					'line_height'    => [
						'default' => [
							'size' => 1.2,
							'unit' => 'em',
						],
					],
				],
				'separator'      => 'after',
			]
		);

		$this->add_control(
			'color',
			[
				'label'       => __( 'Color', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'description' => __( 'The label colors can be customized in the Kickstart plugin options. <strong>If required</strong>, an alternative uniform color for all labels of this element can be selected here.', 'immonex-kickstart-for-elementor' ),
				'selectors'   => [
					'{{WRAPPER}} .inx-property-label' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-property-label' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .inx-property-list-item__body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'body_bg_color',
			[
				'label'     => __( 'Background Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-property-list-item__body' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'body_border_radius',
			[
				'label'      => __( 'Border Radius', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .inx-property-list-item__body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$body_parts = [
			'title'         => __( 'Title', 'immonex-kickstart-for-elementor' ),
			'property-type' => __( 'Property Type', 'immonex-kickstart-for-elementor' ),
			'location'      => __( 'Location', 'immonex-kickstart-for-elementor' ),
			'excerpt'       => __( 'Excerpt', 'immonex-kickstart-for-elementor' ),
		];

		foreach ( $body_parts as $key => $label ) {
			$this->add_control(
				"body_{$key}_header",
				[
					'label'     => $label,
					'type'      => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				"body_{$key}_padding",
				[
					'label'      => __( 'Padding', 'immonex-kickstart-for-elementor' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'default'    => [
						'unit'     => 'px',
						'isLinked' => true,
					],
					'selectors'  => [
						"{{WRAPPER}} .inx-property-list-item__{$key}" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				"body_{$key}_bg_color",
				[
					'label'     => __( 'Background Color', 'immonex-kickstart-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						"{{WRAPPER}} .inx-property-list-item__{$key}" => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->add_responsive_control(
				"body_{$key}_border_radius",
				[
					'label'      => __( 'Border Radius', 'immonex-kickstart-for-elementor' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', 'rem' ],
					'selectors'  => [
						"{{WRAPPER}} .inx-property-list-item__{$key}" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					],
				]
			);

			$this->add_control(
				"body_{$key}_text_color",
				[
					'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						"{{WRAPPER}} .inx-property-list-item__{$key}" => 'color: {{VALUE}}',
						"{{WRAPPER}} .inx-property-list-item__{$key} a" => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => "{$key}_typography",
					'selector' => "{{WRAPPER}} .inx-property-list-item__{$key}, " .
						"{{WRAPPER}} .inx-property-list-item__{$key} [class*=\" flaticon-\"]::before, [class*=\" flaticon-\"]::after",
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name'     => "{$key}_shadow",
					'selector' => "{{WRAPPER}} .inx-property-list-item__{$key}",
				]
			);

			if ( in_array( $key, [ 'property-type', 'location' ], true ) ) {
				$this->add_control(
					"body_{$key}_icon_color",
					[
						'label'     => __( 'Icon Color', 'immonex-kickstart-for-elementor' ),
						'type'      => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							"{{WRAPPER}} .inx-property-list-item__{$key} i" => 'color: {{VALUE}}',
						],
					]
				);
			}
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'section_footer_style',
			[
				'label' => __( 'Footer', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'footer_padding',
			[
				'label'      => __( 'Padding', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'    => [
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .inx-property-list-item__footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'footer_bg_color',
			[
				'label'     => __( 'Background Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-property-list-item__footer' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'footer_border_radius',
			[
				'label'      => __( 'Border Radius', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .inx-property-list-item__footer' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$footer_parts = [
			'core-details'   => __( 'Core Data', 'immonex-kickstart-for-elementor' ),
			'property-price' => __( 'Price', 'immonex-kickstart-for-elementor' ),
		];

		foreach ( $footer_parts as $key => $label ) {
			$this->add_control(
				"footer_{$key}_header",
				[
					'label'     => $label,
					'type'      => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				"footer_{$key}_bg_color",
				[
					'label'     => __( 'Background Color', 'immonex-kickstart-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						"{{WRAPPER}} .inx-property-list-item__{$key}" => 'background: {{VALUE}}',
					],
				]
			);

			$this->add_responsive_control(
				"footer_{$key}_border_radius",
				[
					'label'      => __( 'Border Radius', 'immonex-kickstart-for-elementor' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', 'rem' ],
					'selectors'  => [
						"{{WRAPPER}} .inx-property-list-item__{$key}" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					],
				]
			);

			$this->add_control(
				"footer_{$key}_text_color",
				[
					'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						"{{WRAPPER}} .inx-property-list-item__footer .inx-property-list-item__{$key}" => 'color: {{VALUE}}',
						"{{WRAPPER}} .inx-property-list-item__footer .inx-property-list-item__{$key} a" => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => "footer_{$key}_typography",
					'selector' => "{{WRAPPER}} .inx-property-list-item__footer .inx-property-list-item__{$key}, " .
						"{{WRAPPER}} .inx-property-list-item__footer .inx-property-list-item__{$key} [class*=\" flaticon-\"]::before, " .
						"{{WRAPPER}} .inx-property-list-item__footer .inx-property-list-item__{$key} [class*=\" flaticon-\"]::after",
				]
			);

			if ( 'core-details' === $key ) {
				$this->add_control(
					"footer_{$key}_icon_color",
					[
						'label'     => __( 'Icon Color', 'immonex-kickstart-for-elementor' ),
						'type'      => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							"{{WRAPPER}} .inx-property-list-item__{$key} i" => 'color: {{VALUE}}',
						],
					]
				);
			}
		}

		if ( defined( 'static::TEMPLATE' ) ) {
			$this->add_control(
				'template',
				[
					'type'    => \Elementor\Controls_Manager::HIDDEN,
					'default' => static::TEMPLATE,
				]
			);
		} else {
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
		}
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

		$ext_atts = array_merge(
			array_keys( $this->get_tax_atts() ),
			array_keys( $this->get_explicit_cf_flags() ),
			[
				'template',
				'limit',
				'limit-page',
				'no_results_text',
				'disable_links',
				'force-lang',
				'min-rooms',
				'min-area',
				'iso-country',
				'references',
				'masters',
			]
		);

		$this->add_extended_sc_atts( $ext_atts, $template_data, 'property-list' );

		$price_min = $settings['price_min'] ? $settings['price_min'] : '';
		$price_max = $settings['price_max'] ? $settings['price_max'] : '';
		if ( $price_min && ! $price_max ) {
			$price_max = PHP_INT_MAX;
		} elseif ( $price_max && ! $price_min ) {
			$price_min = 0;
		}
		$price_range = implode( ',', [ $price_min, $price_max ] );
		if ( strlen( $price_range ) > 1 ) {
			$this->add_render_attribute( 'shortcode', 'price-range', $price_range );
		}

		$author_query_attr_value = $this->get_author_query_sc_attr_value( $settings );
		if ( $author_query_attr_value ) {
			$this->add_render_attribute( 'shortcode', 'author', $author_query_attr_value );
		}

		if ( ! empty( $settings['sort'] ) ) {
			$sort = [];

			foreach ( $settings['sort'] as $sort_option ) {
				$sort[] = $sort_option['option'];
			}

			$this->add_render_attribute( 'shortcode', 'sort', implode( ',', $sort ) );
		}

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'shortcode', 'is_preview', '1' );
		}

		$template_data['shortcode_output'] = do_shortcode( '[inx-property-list ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $template_data['shortcode_output'] ? $template_data : false;
	} // get_template_data

} // class Native_Property_List_Widget
