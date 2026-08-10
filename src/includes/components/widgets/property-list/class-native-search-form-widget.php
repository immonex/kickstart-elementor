<?php
/**
 * Class Native_Search_Form_Widget
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor\Components\Widgets\PropertyList;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Property List Native Search Form Widget
 *
 * @since 1.0.0
 */
class Native_Search_Form_Widget extends \immonex\Kickstart\ForElementor\Components\Widgets\Widget_Base {

	const WIDGET_NAME              = 'inx-e-native-search-form';
	const WIDGET_ICON              = 'eicon-search';
	const WIDGET_CATEGORIES        = [ 'inx-property-list' ];
	const WIDGET_HELP_URL          = 'https://docs.immonex.de/kickstart-for-elementor/#/elementor-immobilien-widgets/suchformular';
	const ENABLE_RENDER_ON_PREVIEW = true;

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Search Form', 'immonex-kickstart-for-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'search', 'immonex-kickstart-for-elementor' ),
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
		$utils                  = apply_filters( 'inxkickel_get_utils', [] );
		$search_form_elements   = apply_filters( 'inx_get_search_form_elements', [] ); // phpcs:ignore -- Parent plugin filter hook that can't be changed (yet) for compatibility reasons.
		$pages                  = get_pages();
		$element_options        = [];
		$element_control_titles = [];
		$default_elements       = [];
		$results_page_options   = [
			''        => __( 'default', 'immonex-kickstart-for-elementor' ),
			'current' => __( 'current page (explicitly)', 'immonex-kickstart-for-elementor' ),
		];

		if ( ! empty( $search_form_elements ) ) {
			uasort(
				$search_form_elements,
				function ( $a, $b ) {
					return $a['order'] <=> $b['order'];
				}
			);

			foreach ( $search_form_elements as $key => $element ) {
				if ( ! $element['enabled'] ) {
					continue;
				}

				$title                 = ! empty( $element['description'] ) ? $element['description'] : $key;
				$element_control_title = preg_replace( '/ \(.*\)/', '', $title );

				$element_options[ $key ]        = $title;
				$element_control_titles[ $key ] = $utils['string']->get_excerpt( $element_control_title, 24, '…' );

				if ( ! $element['hidden'] ) {
					$default_elements[] = [
						'element'  => $key,
						'extended' => $element['extended'] ? '1' : '0',
					];
				}
			}
		}

		$element_control_titles_json = wp_json_encode( $element_control_titles );

		if ( ! empty( $pages ) ) {
			foreach ( $pages as $page ) {
				$results_page_options[ $page->ID ] = $page->post_title;
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

		$this->add_control(
			'dynamic-update',
			[
				'label'       => __( 'Dynamic Updates', 'immonex-kickstart-for-elementor' ),
				'description' => __( 'Enable dynamic updates of <strong>list and map elements</strong> on the same page when search options are changed.', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => [
					''  => __( 'Default', 'immonex-kickstart-for-elementor' ),
					'1' => __( 'Enabled', 'immonex-kickstart-for-elementor' ),
					'0' => __( 'Disabled', 'immonex-kickstart-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'results-page-id',
			[
				'label'       => __( 'Alternative Results Page', 'immonex-kickstart-for-elementor' ),
				'description' => __( 'Defaults to the <strong>current page</strong> if a property list shortcode is included.', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => $results_page_options,
				'label_block' => true,
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'references',
			[
				'label'        => __( 'Sold/Rented Selection', 'immonex-kickstart-for-elementor' ),
				'description'  => __( 'Show "Sold" and "Rented" in the <strong>marketing type select box</strong> if related properties exist.', 'immonex-kickstart-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => '1',
				'separator'    => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'elements_section',
			[
				'label' => __( 'Elements', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'use_default_elements',
			[
				'label'        => __( 'Use default elements', 'immonex-kickstart-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'return_value' => '1',
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'element',
			[
				'label'       => __( 'Element', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => $element_options,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'extended',
			[
				'label'        => __( 'Extended Search', 'immonex-kickstart-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '0',
				'return_value' => '1',
			]
		);

		$this->add_control(
			'form_elements',
			[
				'label'       => __( 'User-defined Elements', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => "<# const labels = {$element_control_titles_json}; const label = labels[element]; #>{{{ label }}}",
				'default'     => $default_elements,
				'condition'   => [
					'use_default_elements' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tax_select_defaults_section',
			[
				'label' => __( 'Taxonomy Selection Defaults', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'top-level-only',
			[
				'label'        => __( 'Main Categories only', 'immonex-kickstart-for-elementor' ),
				'description'  => __( 'Display only top-level entries in select boxes of hierarchical taxonomies (e.g. property type).', 'immonex-kickstart-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => '1',
				'separator'    => 'after',
			]
		);

		$this->add_control(
			'tax_filter_notice',
			[
				'type'        => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'content'     => __( 'If the selectable taxonomy terms (e.g. "houses, flats") are to be limited to certain <strong>top-level options</strong>, these can be selected below.', 'immonex-kickstart-for-elementor' ),
				'dismissible' => true,
			]
		);

		$tax_filters = [
			'force-location'       => [
				'taxonomy' => 'inx_location',
				'label'    => __( 'Locations', 'immonex-kickstart-for-elementor' ),
			],
			'force-type-of-use'    => [
				'taxonomy' => 'inx_type_of_use',
				'label'    => __( 'Types of Use', 'immonex-kickstart-for-elementor' ),
			],
			'force-property-type'  => [
				'taxonomy' => 'inx_property_type',
				'label'    => __( 'Property Types', 'immonex-kickstart-for-elementor' ),
			],
			'force-marketing-type' => [
				'taxonomy' => 'inx_marketing_type',
				'label'    => __( 'Marketing Types', 'immonex-kickstart-for-elementor' ),
			],
			'force-feature'        => [
				'taxonomy' => 'inx_feature',
				'label'    => __( 'Features', 'immonex-kickstart-for-elementor' ),
			],
		];

		$tax_filter_options = [];

		foreach ( $tax_filters as $key => $filter ) {
			$options = [];
			$terms   = get_terms(
				[
					'taxonomy'   => $filter['taxonomy'],
					'parent'     => 0,
					'hide_empty' => false,
				]
			);

			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					$options[ $term->slug ] = $term->name;
				}
			}

			$this->add_control(
				$key,
				[
					'label'       => $filter['label'],
					'type'        => \Elementor\Controls_Manager::SELECT2,
					'label_block' => true,
					'options'     => $options,
					'multiple'    => true,
				]
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'distance_search_section',
			[
				'label' => __( 'Distance Search', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'autocomplete-countries',
			[
				'label'       => __( 'Autocomplete Countries', 'immonex-kickstart-for-elementor' ),
				'description' => __( 'Comma-separated <strong>ISO 3166-1 ALPHA-2</strong> country code list (e.g. "de,at,ch,be,nl") – leave empty to use defaults.', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->add_control(
			'autocomplete-osm-place-tags',
			[
				'label'       => __( 'Autocomplete OSM Place Tags', 'immonex-kickstart-for-elementor' ),
				'description' => __( 'Comma-separated <strong>OpenStreetMap Place Tag List</strong> for filtering/prioritizing autocomplete search results – defaults to "city,town,village,borough,suburb" if empty.', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
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
						'min' => 128,
						'max' => 384,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .inx-property-search__elements' => 'grid-template-columns: repeat(auto-fit, minmax({{SIZE}}{{UNIT}}, 1fr));',
				],
			]
		);

		$this->add_responsive_control(
			'column_min_height',
			[
				'label'      => __( 'Column Minimum Height', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 24,
						'max' => 128,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .inx-property-search__element > div:not(.inx-form-element--reset):not(.inx-form-element--extended-search-toggle)' => 'min-height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .inx-property-search__element input' => 'max-height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .inx-property-search__element select' => 'max-height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .inx-property-search__element button' => 'max-height: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .inx-property-search__elements' => 'grid-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'grid_element_align',
			[
				'label'     => __( 'Vertical Element Alignment', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'default'   => $this->get_default( 'container_element_align', 'center' ),
				'options'   => [
					'flex-start' => [
						'title' => __( 'Top', 'immonex-kickstart-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					],
					'center'     => [
						'title' => __( 'Center', 'immonex-kickstart-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'flex-end'   => [
						'title' => __( 'Bottom', 'immonex-kickstart-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .inx-form-element--text'  => 'align-items: {{VALUE}};',
					'{{WRAPPER}} .inx-form-element--select' => 'align-items: {{VALUE}};',
					'{{WRAPPER}} .inx-form-element--range' => 'align-items: {{VALUE}};',
					'{{WRAPPER}} .inx-form-element--submit' => 'align-items: {{VALUE}};',
					'{{WRAPPER}} div[class*="-autocomplete"]' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$element_sections = [
			'text_select' => [
				'label'    => __( 'Form Fields', 'immonex-kickstart-for-elementor' ) .
					' / ' . __( 'Select Boxes', 'immonex-kickstart-for-elementor' ),
				'selector' => '{{WRAPPER}} .inx-form-element--text > input, ' .
					'{{WRAPPER}} .inx-form-element--select > select.inx-select:not([multiple]):not([size]), ' .
					'{{WRAPPER}} .multiselect__tags, {{WRAPPER}} .multiselect--active .multiselect__tags',
			],
		];

		foreach ( $element_sections as $key => $element ) {
			$this->start_controls_section(
				"section_{$key}_style",
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
						$element['selector']              => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .multiselect__input' => 'background-color: {{VALUE}}',
					],
				]
			);

			if ( empty( $element['exclude'] ) || ! in_array( 'placeholder_color', $element['exclude'], true ) ) {
				$this->add_control(
					"{$key}_placeholder_color",
					[
						'label'     => __( 'Placeholder Color', 'immonex-kickstart-for-elementor' ),
						'type'      => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							str_replace( '> input', '> input::placeholder', $element['selector'] ) => 'color: {{VALUE}}',
							'{{WRAPPER}} .multiselect__placeholder'                                => 'color: {{VALUE}}',
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
						$element['selector']              => 'color: {{VALUE}}',
						'{{WRAPPER}} .multiselect__input' => 'color: {{VALUE}}',
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
						$element['selector']       => 'height: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .inx-form-element--text > button' => 'height: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .multiselect' => 'height: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .inx-form-element--range .inx-range-slider__label-value' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'value_slider_text_align',
			[
				'label'     => __( 'Alignment', 'immonex-kickstart-for-elementor' ) .
					' (' . __( 'Text', 'immonex-kickstart-for-elementor' ) . ')',
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'default'   => $this->get_default( 'value_slider_text_align', 'left' ),
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
					'{{WRAPPER}} .inx-form-element--range .inx-range-slider__label-value' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'value_slider_typography',
				'selector' => '{{WRAPPER}} .inx-form-element--range .inx-range-slider__label-value',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'value_slider_typography_value',
				'label'    => __( 'Typography', 'immonex-kickstart-for-elementor' ) .
					' (' . __( 'Value', 'immonex-kickstart-for-elementor' ) . ')',
				'selector' => '{{WRAPPER}} .inx-form-element--range .inx-range-slider__value',
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
					'{{WRAPPER}} .inx-form-element--range .inx-range-slider__value' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'value_slider_text_shadow',
				'selector' => '{{WRAPPER}} .inx-form-element--range .inx-range-slider__label-value',
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
					'{{WRAPPER}} .inx-form-element--range .inx-range-slider__nouislider' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'value_slider_accent_color',
			[
				'label'       => __( 'Accent Color', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'description' => wp_sprintf(
					/* translators: %1$s = color type, e.g. "all action elements"; %2$s = plugin options tab URL */
					__( 'Instead of selecting an <strong>element-related</strong> color here, setting a <strong>global</strong> color for <strong>%1$s</strong> in the <a href="%2$s" target="_blank">Kickstart plugin options</a> makes more sense in most cases.', 'immonex-kickstart-for-elementor' ),
					__( 'all action elements', 'immonex-kickstart-for-elementor' ),
					admin_url( 'admin.php?page=immonex-kickstart_settings&section_tab=3' )
				),
				'selectors'   => [
					'{{WRAPPER}} .inx-range-slider .inx-range-slider__nouislider .noUi-handle' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .inx-range-slider .inx-range-slider__nouislider .noUi-handle::before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .inx-range-slider .inx-range-slider__nouislider .noUi-handle::after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .inx-form-element .inx-range-slider .inx-range-slider__nouislider .noUi-connect' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'value_slider_boxshadow',
			[
				'type'      => \Elementor\Controls_Manager::HIDDEN,
				'default'   => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .inx-range-slider .inx-range-slider__nouislider .noUi-handle' => 'box-shadow: inset .04em 0 .3em #C5C5C5, 0 0 .2em {{value_slider_accent_color.VALUE}}',
				],
				'condition' => [
					'value_slider_accent_color!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_features_style',
			[
				'label' => __( 'Features', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'features_heading_checkboxes',
			[
				'label' => __( 'Checkboxes', 'immonex-kickstart-for-elementor' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'features_layout',
			[
				'label'        => __( 'Layout', 'immonex-kickstart-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::CHOOSE,
				'default'      => $this->get_default( 'features_layout', 'inline' ),
				'options'      => [
					'columns' => [
						'title' => __( 'Columns', 'immonex-kickstart-for-elementor' ),
						'icon'  => 'eicon-editor-list-ul',
					],
					'inline'  => [
						'title' => __( 'Inline', 'immonex-kickstart-for-elementor' ),
						'icon'  => 'eicon-ellipsis-h',
					],
				],
				'prefix_class' => 'inx-e--layout--',
			]
		);

		$this->add_responsive_control(
			'features_columns',
			[
				'label'                => __( 'Columns', 'immonex-kickstart-for-elementor' ),
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
					'features_layout' => 'columns',
				],
				'prefix_class'         => 'inx-e%s--columns--',
				'selectors'            => [
					'{{WRAPPER}}:not(.inx-e--layout--inline) .inx-form-element--checkbox .inx-form-element__options' => 'display: grid; grid-template-columns: repeat({{VALUE}}, 1fr)',
				],
			]
		);

		$this->add_responsive_control(
			'features_checkboxes_size',
			[
				'label'      => __( 'Size', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 16,
						'max' => 64,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .inx-form-element--checkbox input.inx-checkbox' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'features_checkboxes_bg_color',
			[
				'label'     => __( 'Background Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-form-element--checkbox input.inx-checkbox' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'features_checkboxes_border',
				'selector' => '{{WRAPPER}} .inx-form-element--checkbox input.inx-checkbox',
			]
		);

		$this->add_responsive_control(
			'features_checkboxes_border_radius',
			[
				'label'      => __( 'Border Radius', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .inx-form-element--checkbox input.inx-checkbox' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'features_checkboxes_box_shadow',
				'selector' => '{{WRAPPER}} .inx-form-element--checkbox input.inx-checkbox',
			]
		);

		$this->add_control(
			'features_heading',
			[
				'label' => __( 'Heading', 'immonex-kickstart-for-elementor' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'features_text_color_heading',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-form-element--checkbox .inx-form-element__label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'features_text_align_heading',
			[
				'label'     => __( 'Alignment', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'default'   => $this->get_default( 'features_text_align_heading', 'left' ),
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
					'{{WRAPPER}} .inx-form-element--checkbox .inx-form-element__label' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'features_typography_heading',
				'label'    => __( 'Typography', 'immonex-kickstart-for-elementor' ),
				'selector' => '{{WRAPPER}} .inx-form-element--checkbox .inx-form-element__label',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'features_text_shadow_heading',
				'label'    => __( 'Text Shadow', 'immonex-kickstart-for-elementor' ),
				'selector' => '{{WRAPPER}} .inx-form-element--checkbox .inx-form-element__label',
			]
		);

		$this->add_control(
			'features_heading_checkbox_labels',
			[
				'label'     => __( 'Checkbox Labels', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'features_text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-form-element--checkbox .inx-label--checkbox' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'features_typography',
				'label'    => __( 'Typography', 'immonex-kickstart-for-elementor' ),
				'selector' => '{{WRAPPER}} .inx-form-element--checkbox .inx-label--checkbox',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'features_text_shadow',
				'label'    => __( 'Text Shadow', 'immonex-kickstart-for-elementor' ),
				'selector' => '{{WRAPPER}} .inx-form-element--checkbox .inx-label--checkbox',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Buttons', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'submit_button_color',
			[
				'label'       => __( 'Color', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'description' => wp_sprintf(
					/* translators: %1$s = color type, e.g. "all action elements"; %2$s = plugin options tab URL */
					__( 'Instead of selecting an <strong>element-related</strong> color here, setting a <strong>global</strong> color for <strong>%1$s</strong> in the <a href="%2$s" target="_blank">Kickstart plugin options</a> makes more sense in most cases.', 'immonex-kickstart-for-elementor' ),
					__( 'all action elements', 'immonex-kickstart-for-elementor' ),
					admin_url( 'admin.php?page=immonex-kickstart_settings&section_tab=3' )
				),
				'selectors'   => [
					'{{WRAPPER}} button.inx-button--action' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'submit_button_text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button.inx-button--action' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'submit_button_typography',
				'selector' => '{{WRAPPER}} button.inx-button--action',
			]
		);

		$this->add_responsive_control(
			'submit_button_height',
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
					'{{WRAPPER}} button.inx-button--action' => 'height: {{SIZE}}{{UNIT}}',
				],
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'submit_button_border',
				'selector' => '{{WRAPPER}} button.inx-button--action',
			]
		);

		$this->add_responsive_control(
			'submit_button_border_radius',
			[
				'label'      => __( 'Border Radius', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} button.inx-button--action' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'submit_button_box_shadow',
				'selector' => '{{WRAPPER}} button.inx-button--action',
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
			'value_slider_link_color',
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
					'{{WRAPPER}} a.inx-link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'link_typography',
				'selector' => '{{WRAPPER}} a.inx-link',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_extended_search_style',
			[
				'label' => __( 'Extended Search', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'ext_search_hr',
			[
				'label'        => __( 'Hide Dividing Line', 'immonex-kickstart-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => 'block',
				'return_value' => 'none',
				'selectors'    => [
					'{{WRAPPER}} .inx-form-element--extended-search-toggle > hr' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ext_search_divider_color',
			[
				'label'     => __( 'Dividing Line Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-form-element--extended-search-toggle > hr' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'ext_search_hr!' => 'none',
				],
			]
		);

		$this->add_control(
			'ext_search_bg_color',
			[
				'label'     => __( 'Background Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-property-search__extended' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'ext_search_border',
				'selector' => '{{WRAPPER}} .inx-property-search__extended',
			]
		);

		$this->add_responsive_control(
			'ext_search_border_radius',
			[
				'label'      => __( 'Border Radius', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .inx-property-search__extended' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'ext_search_box_shadow',
				'selector' => '{{WRAPPER}} .inx-property-search__extended',
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

		$this->add_control(
			'template',
			[
				'label'       => __( 'Custom Template', 'immonex-kickstart-for-elementor' ),
				'description' => wp_sprintf(
					__( 'Enter the <strong>filename</strong> if an <strong>alternative</strong> PHP template should be used for rendering the component.', 'immonex-kickstart-for-elementor' ) .
						/* translators: %s: plugin name */
						'(' . __( 'The file must be included in the <strong>skin folder</strong> selected in the %s plugin options.', 'immonex-kickstart-for-elementor' ) . ')',
					'<code>property-list</code>',
					'<strong>Kickstart</strong>'
				),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
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
		$settings = $this->get_settings_for_display();

		if ( ! $settings['use_default_elements'] && ! empty( $settings['form_elements'] ) ) {
			$elements = [];

			foreach ( $settings['form_elements'] as $element ) {
				$elements[] = $element['element'] . ( $element['extended'] ? '+' : '-' );
			}

			$this->add_render_attribute( 'shortcode', 'elements', implode( ',', $elements ) );
		}

		$template_data = [
			'settings' => $settings,
		];

		$ext_atts = [
			'template',
			'dynamic-update',
			'results-page-id',
			'references',
			'top-level-only',
			'force-location',
			'force-type-of-use',
			'force-property-type',
			'force-marketing-type',
			'force-feature',
			'autocomplete-countries',
			'autocomplete-osm-place-tags',
		];

		$this->add_extended_sc_atts( $ext_atts, $template_data );

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'shortcode', 'is_preview', '1' );
		}

		$template_data['shortcode_output'] = do_shortcode( '[inx-search-form ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $template_data['shortcode_output'] ? $template_data : false;
	} // get_template_data

} // class Native_Search_Form_Widget
