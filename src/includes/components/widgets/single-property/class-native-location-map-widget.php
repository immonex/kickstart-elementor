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

/**
 * Elementor Single Property Native Head Widget
 *
 * @since 1.0.0
 */
class Native_Location_Map_Widget extends \immonex\Kickstart\ForElementor\Components\Widgets\Map_Widget {

	const POST_TYPE         = 'inx_property';
	const WIDGET_NAME       = 'inx-e-single-property-native-location-map';
	const WIDGET_CATEGORIES = [ 'inx-single-property' ];
	const WIDGET_HELP_URL   = 'https://docs.immonex.de/kickstart-for-elementor/#/elementor-immobilien-widgets/standortkarte';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Location Map', 'immonex-kickstart-for-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'location', 'immonex-kickstart-for-elementor' ),
					__( 'map', 'immonex-kickstart-for-elementor' ),
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
			'type',
			[
				'label'       => __( 'Map Type', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'Google-based maps require a valid API key supplied in the field below or via the Kickstart plugin options.', 'immonex-kickstart-for-elementor' ),
				'default'     => 'ol_osm_map_german',
				'options'     => [
					'ol_osm_map_marker' => 'OpenStreetMap (' . __( 'road map view', 'immonex-kickstart-for-elementor' )
						. ' ' . __( 'with property location marker', 'immonex-kickstart-for-elementor' ) . ')',
					'ol_osm_map_german' => 'OpenStreetMap (' . __( 'road map view', 'immonex-kickstart-for-elementor' )
						. ' ' . __( 'with property location marker', 'immonex-kickstart-for-elementor' )
						. ' – ' . __( 'German Style', 'immonex-kickstart-for-elementor' ) . ')',
					'ol_osm_map_otm'    => 'OpenTopoMap (' . __( 'OSM-based topographic view', 'immonex-kickstart-for-elementor' )
						. ' ' . __( 'with road map layer and property location marker', 'immonex-kickstart-for-elementor' ) . ')',
					'gmap_marker'       => 'Google Map (' . __( 'road map view', 'immonex-kickstart-for-elementor' )
						. ' ' . __( 'with property location marker', 'immonex-kickstart-for-elementor' ) . ')',
					'gmap_terrain'      => 'Google Map Terrain (' . __( 'topographic view', 'immonex-kickstart-for-elementor' )
						. ' ' . __( 'with road map layer and property location marker', 'immonex-kickstart-for-elementor' ) . ')',
					'gmap_hybrid'       => 'Google Map Hybrid (' . __( 'satellite view', 'immonex-kickstart-for-elementor' )
						. ' ' . __( 'with road map layer and property location marker', 'immonex-kickstart-for-elementor' ) . ')',
					'gmap_embed'        => __( "Google Area Map of property's neighborhood", 'immonex-kickstart-for-elementor' )
						. ' (' . __( 'road map view', 'immonex-kickstart-for-elementor' ) . ')',
					'gmap_embed_sat'    => __( "Google Area Map of property's neighborhood", 'immonex-kickstart-for-elementor' )
						. ' (' . __( 'satellite view', 'immonex-kickstart-for-elementor' )
						. ' ' . __( 'with road map layer', 'immonex-kickstart-for-elementor' ) . ')',
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'google_api_key',
			[
				'label'       => __( 'Google API Key', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( '<strong>Alternative</strong> key to be used instead of the one in the plugin options.', 'immonex-kickstart-for-elementor' ),
				'label_block' => true,
				'condition'   => [
					'type' => [
						'gmap_marker',
						'gmap_terrain',
						'gmap_hybrid',
						'gmap_embed',
						'gmap_embed_sat',
					],
				],
			]
		);

		$this->add_control(
			'options',
			[
				'label'       => __( 'Map Options', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Additional <strong>provider-related</strong> options (OpenLayers/OpenStreetMap or Google Maps).', 'immonex-kickstart-for-elementor' ),
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'frame_section',
			[
				'label' => __( 'Frame', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label'      => __( 'Corner Radius', 'immonex-kickstart-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .inx-property-location-map' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'marker_section',
			[
				'label' => __( 'Marker', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'marker_type',
			[
				'label'   => __( 'Marker Type', 'immonex-kickstart-for-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => $this->get_default( 'marker_type', 'integrated' ),
				'options' => [
					'integrated' => __( 'integrated', 'immonex-kickstart-for-elementor' ),
					'custom'     => __( 'custom', 'immonex-kickstart-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'marker_fill_color',
			[
				'label'     => __( 'Fill Color', 'immonex-kickstart-for-elementor' ),
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
				'label'      => __( 'Fill Opacity', 'immonex-kickstart-for-elementor' ),
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
				'label'     => __( 'Stroke Color', 'immonex-kickstart-for-elementor' ),
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
				'label'      => __( 'Stroke Width', 'immonex-kickstart-for-elementor' ),
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
				'label'      => __( 'Scale Factor', 'immonex-kickstart-for-elementor' ),
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
				'label'     => __( 'Marker Icon URL', 'immonex-kickstart-for-elementor' ),
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
		$this->add_render_attribute( 'shortcode', 'elements', 'location_map' );

		$template_data = parent::get_template_data();
		$ext_atts      = [ 'template' ];

		$this->add_extended_sc_atts( $ext_atts, $template_data, 'single-property' );

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'shortcode', 'maps_require_consent', '0' );
		}

		$template_data['shortcode_output'] = do_shortcode( '[inx-property-details ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $template_data['shortcode_output'] ? $template_data : false;
	} // get_template_data

} // class Native_Location_Map_Widget
