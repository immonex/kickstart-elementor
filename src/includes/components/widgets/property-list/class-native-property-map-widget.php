<?php
/**
 * Class Native_Property_Map_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\PropertyList;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Native Property Map Widget
 *
 * @since 1.0.0
 */
class Native_Property_Map_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Map_Widget {

	const WIDGET_NAME       = 'inx-e-native-property-map';
	const WIDGET_CATEGORIES = [ 'inx-property-list' ];
	const WIDGET_HELP_URL   = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/uebersichtskarte';
	const DEFAULT_MAP_ZOOM  = 12;

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Overview Map', 'immonex-kickstart-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'map', 'immonex-kickstart-elementor' ),
					__( 'markers', 'immonex-kickstart-elementor' ),
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

		$this->add_main_class_control();

		$this->add_control(
			'type',
			[
				'label'       => __( 'Map Type', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'Google-based maps require a valid API key supplied in the field below or via the Kickstart plugin options.', 'immonex-kickstart-elementor' ),
				'default'     => 'osm_german',
				'options'     => [
					'osm'          => 'OpenStreetMap (' . __( 'road map view', 'immonex-kickstart-elementor' )
						. ' ' . __( 'with property location marker', 'immonex-kickstart-elementor' ) . ')',
					'osm_german'   => 'OpenStreetMap (' . __( 'road map view', 'immonex-kickstart-elementor' )
						. ' ' . __( 'with property location marker', 'immonex-kickstart-elementor' )
						. ' – ' . __( 'German Style', 'immonex-kickstart-elementor' ) . ')',
					'osm_otm'      => 'OpenTopoMap (' . __( 'OSM-based topographic view', 'immonex-kickstart-elementor' )
						. ' ' . __( 'with road map layer and property location marker', 'immonex-kickstart-elementor' ) . ')',
					'gmap'         => 'Google Map (' . __( 'road map view', 'immonex-kickstart-elementor' )
						. ' ' . __( 'with property location marker', 'immonex-kickstart-elementor' ) . ')',
					'gmap_terrain' => 'Google Map Terrain (' . __( 'topographic view', 'immonex-kickstart-elementor' )
						. ' ' . __( 'with road map layer and property location marker', 'immonex-kickstart-elementor' ) . ')',
					'gmap_hybrid'  => 'Google Map Hybrid (' . __( 'satellite view', 'immonex-kickstart-elementor' ) . ')',
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'google_api_key',
			[
				'label'       => __( 'Google API Key', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( '<strong>Alternative</strong> key to be used instead of the one in the plugin options.', 'immonex-kickstart-elementor' ),
				'label_block' => true,
				'condition'   => [
					'type' => [
						'gmap',
						'gmap_terrain',
						'gmap_hybrid',
					],
				],
			]
		);

		$this->add_control(
			'options',
			[
				'label'       => __( 'Map Options', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Additional <strong>provider-related</strong> options (OpenLayers/OpenStreetMap or Google Maps).', 'immonex-kickstart-elementor' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'auto_fit',
			[
				'label'        => __( 'Auto Fit', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'description'  => __( 'Center and zoom the map according to the contained markers automatically.', 'immonex-kickstart-elementor' ),
				'default'      => '1',
				'label_off'    => __( 'Off', 'immonex-kickstart-elementor' ),
				'label_on'     => __( 'On', 'immonex-kickstart-elementor' ),
				'return_value' => '1',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'lat',
			[
				'label'     => __( 'Default Center Latitude', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => $this->get_default( 'lat', 0 ),
				'min'       => -90,
				'max'       => 90,
				'condition' => [
					'auto_fit!' => '1',
				],
			]
		);

		$this->add_control(
			'lng',
			[
				'label'     => __( 'Default Center Longitude', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => $this->get_default( 'lng', 0 ),
				'min'       => -90,
				'max'       => 90,
				'condition' => [
					'auto_fit!' => '1',
				],
			]
		);

		$this->add_control(
			'map_zoom',
			[
				'label'      => __( 'Default Zoom', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default(
					'zoom',
					[
						'size' => self::DEFAULT_MAP_ZOOM,
						'unit' => 'px',
					]
				),
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 8,
						'max' => 18,
					],
				],
				'condition'  => [
					'auto_fit!' => '1',
				],
			]
		);

		$this->add_default_controls(
			[
				'overview_map',
				'disable_links',
			],
			[
				'limit'         => [
					'label'       => __( 'Property/Marker Limit', 'immonex-kickstart-elementor' ),
					'description' => __( 'Maximum number of markers that can be contained in the map.', 'immonex-kickstart-elementor' ),
					'separator'   => 'before',
				],
				'disable_links' => [ 'separator' => 'before' ],
			]
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

		$this->end_controls_section();

		$this->start_controls_section(
			'authors_filter_section',
			[
				'label' => __( 'Author Filters', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_default_controls(
			[ 'authors' ],
			[
				'authors' => [
					'show_label' => false,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'extended_content_section',
			[
				'label' => __( 'Extended', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_default_controls( [ 'force-lang' ] );

		$this->end_controls_section();

		$this->start_controls_section(
			'marker_section',
			[
				'label' => __( 'Marker', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'marker_type',
			[
				'label'   => __( 'Marker Type', 'immonex-kickstart-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => $this->get_default( 'marker_type', 'integrated' ),
				'options' => [
					'integrated' => __( 'integrated', 'immonex-kickstart-elementor' ),
					'custom'     => __( 'custom', 'immonex-kickstart-elementor' ),
				],
			]
		);

		$this->add_control(
			'marker_fill_color',
			[
				'label'     => __( 'Fill Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => $this->get_default( 'marker_fill_color', self::DEFAULT_MARKER_FILL_COLOR ),
				'condition' => [
					'marker_type' => 'integrated',
				],
			]
		);

		$this->add_control(
			'marker_fill_opacity',
			[
				'label'      => __( 'Fill Opacity', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default(
					'marker_fill_opacity',
					[
						'size' => self::DEFAULT_MARKER_FILL_OPACITY_PCT,
						'unit' => '%',
					]
				),
				'size_units' => [ '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition'  => [
					'marker_type' => 'integrated',
				],
			]
		);

		$this->add_control(
			'marker_stroke_color',
			[
				'label'     => __( 'Stroke Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => $this->get_default( 'marker_fill_color', self::DEFAULT_MARKER_STROKE_COLOR ),
				'condition' => [
					'marker_type' => 'integrated',
				],
			]
		);

		$this->add_control(
			'marker_stroke_width',
			[
				'label'      => __( 'Stroke Width', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default(
					'marker_stroke_width',
					[
						'size' => self::DEFAULT_MARKER_STROKE_WIDTH_PX,
						'unit' => 'px',
					]
				),
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'condition'  => [
					'marker_type' => 'integrated',
				],
			]
		);

		$this->add_control(
			'marker_scale',
			[
				'label'      => __( 'Scale Factor', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default(
					'marker_scale',
					[
						'size' => self::DEFAULT_MARKER_SCALE_PCT,
						'unit' => '%',
					]
				),
				'size_units' => [ '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition'  => [
					'marker_type' => 'integrated',
				],
			]
		);

		$this->add_control(
			'marker_icon_url',
			[
				'label'     => __( 'Marker Icon URL', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'condition' => [
					'marker_type' => 'custom',
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
					'folder' => 'property-list',
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
		$template_data = parent::get_template_data();

		$ext_atts = array_merge(
			array_keys( $this->get_tax_atts() ),
			array_keys( $this->get_explicit_cf_flags() ),
			[
				'template',
				'limit',
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

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'shortcode', 'require-consent', '0' );
		}

		if ( ! $template_data['settings']['auto_fit'] ) {
			if ( ! empty( $template_data['settings']['lat'] ) ) {
				$this->add_render_attribute( 'shortcode', 'lat', $template_data['settings']['lat'] );
			}
			if ( ! empty( $template_data['settings']['lng'] ) ) {
				$this->add_render_attribute( 'shortcode', 'lng', $template_data['settings']['lng'] );
			}
			if ( ! empty( $template_data['settings']['map_zoom'] ) ) {
				$this->add_render_attribute( 'shortcode', 'zoom', $template_data['settings']['map_zoom']['size'] );
			}
		}

		$author_query_attr_value = $this->get_author_query_sc_attr_value( $template_data['settings'] );
		if ( $author_query_attr_value ) {
			$this->add_render_attribute( 'shortcode', 'author', $author_query_attr_value );
		}

		$shortcode_output = do_shortcode( '[inx-property-map ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		$template_data['shortcode_output'] = $shortcode_output;

		return $template_data['shortcode_output'] ? $template_data : false;
	} // get_template_data

} // class Native_Property_Map_Widget
