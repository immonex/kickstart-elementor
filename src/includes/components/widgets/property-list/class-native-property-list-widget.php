<?php
/**
 * Class Native_Property_List_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\PropertyList;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Native Property List Widget
 *
 * @since 1.0.0
 */
class Native_Property_List_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const WIDGET_NAME              = 'inx-e-native-property-list';
	const WIDGET_ICON              = 'eicon-gallery-grid';
	const WIDGET_CATEGORIES        = [ 'inx-property-list' ];
	const WIDGET_HELP_URL          = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/liste-grid';
	const ENABLE_RENDER_ON_PREVIEW = true;
	const IS_DYNAMIC_CONTENT       = true;

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'List (Grid)', 'immonex-kickstart-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'list', 'immonex-kickstart-elementor' ),
					__( 'grid', 'immonex-kickstart-elementor' ),
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
				'label' => __( 'General', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_main_class_control();
		$this->add_default_controls( [ 'lists' ] );

		$this->add_control(
			'no_results_text',
			[
				'label'       => __( 'No Results Message', 'immonex-kickstart-elementor' ),
				'description' => __( '<strong>Optional</strong> custom text to display if no properties match the search/filter criteria. (Defaults to the message stored in the Kickstart plugin options.)', 'immonex-kickstart-elementor' ),
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
				'label'         => __( 'Custom Sort Priority', 'immonex-kickstart-elementor' ),
				'type'          => \Elementor\Controls_Manager::REPEATER,
				'fields'        => [
					[
						'name'        => 'option',
						'label'       => __( 'Option', 'immonex-kickstart-elementor' ),
						'description' => __( 'By default, lists are sorted in descending order by the publication date of the entries they contain.', 'immonex-kickstart-elementor' ),
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
			'tax_filters_section',
			[
				'label' => __( 'Taxonomy Filters', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_default_controls( [ 'tax_filters' ] );

		$this->end_controls_section();

		$this->start_controls_section(
			'cf_filters_section',
			[
				'label' => __( 'Custom Field Filters', 'immonex-kickstart-elementor' ),
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
					'label' => __( 'Extended', 'immonex-kickstart-elementor' ),
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
