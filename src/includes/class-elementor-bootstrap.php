<?php
/**
 * Class Elementor_Bootstrap
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor;

/**
 * Register Elementor elements.
 */
class Elementor_Bootstrap {

	const MIN_REQ_VERSIONS = [
		'esp'            => '2.1.0',
		'print'          => '0.3.0',
		'team'           => '1.5.12',
		'lead-generator' => '3.0.0-rc6',
		'notify'         => '1.1.6',
	];

	/**
	 * Array of Bootstrap Data
	 *
	 * @var mixed[]
	 */
	private $data;

	/**
	 * Plugin Prefix
	 *
	 * @var string
	 */
	private $prefix;

	/**
	 * Kickstart Elementor Widgets
	 *
	 * @var \immonex\Kickstart\Elementor\Components\Widgets[]
	 */
	private $widgets = [];

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $bootstrap_data Plugin bootstrap data.
	 */
	public function __construct( $bootstrap_data ) {
		$this->data   = is_array( $bootstrap_data ) ? $bootstrap_data : [];
		$this->prefix = $bootstrap_data['plugin_prefix'];
	} // __construct

	/**
	 * Register Elementor related actions.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_widget_categories' ], 90 );
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_css_js' ] );

		if ( apply_filters( 'inx_elementor_is_plugin_available', false, 'elementor-pro' ) ) {
			add_action( 'elementor/dynamic_tags/register', [ $this, 'register_inx_dynamic_tag_group' ] );
			add_action( 'elementor/dynamic_tags/register', [ $this, 'register_inx_dynamic_tags' ] );
		}

		add_filter( 'option_elementor_element_cache_ttl', [ $this, 'disable_element_cache_for_template_pages' ] );
	} // init

	/**
	 * Add custom Elementor widget categories for Kickstart (action callback).
	 *
	 * @since 1.0.0
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function add_widget_categories( $widgets_manager ) {
		$house_icon     = '<span style="font-size:150%">&#8962;</span> ';
		$add_categories = [
			'inx-single-property' => [
				'title' => $house_icon . __( 'Property Details', 'immonex-kickstart-elementor' ),
				'icon'  => 'fa fa-home',
			],
			'inx-property-list'   => [
				'title' => $house_icon . __( 'Property Lists and Search', 'immonex-kickstart-elementor' ),
				'icon'  => 'fa fa-home',
			],
		];

		if ( apply_filters( 'inx_elementor_is_plugin_available', false, 'immonex-kickstart-team', self::MIN_REQ_VERSIONS['team'] ) ) {
			$add_categories['inx-team'] = [
				'title' => $house_icon . __( 'Contacts & Agencies', 'immonex-kickstart-elementor' ) . ' (+Team)',
				'icon'  => 'fa fa-home',
			];
		}

		$add_categories['inx-marketing-acquisition'] = [
			'title' => $house_icon . __( 'Marketing & Acquisition', 'immonex-kickstart-elementor' ),
			'icon'  => 'fa fa-home',
		];

		$current_categories = $widgets_manager->get_categories();
		$categories         = array_merge( $add_categories, $current_categories );

		$set_categories = function ( $categories ) {
			$this->categories = $categories;
		};

		$set_categories->call( $widgets_manager, $categories );
	} // add_widget_categories

	/**
	 * Register New Dynamic Tag Group.
	 *
	 * Register new group for Kickstart-related tags (action callback).
	 *
	 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Elementor dynamic tags manager.
	 */
	public function register_inx_dynamic_tag_group( $dynamic_tags_manager ) {
		$dynamic_tags_manager->register_group(
			'inx',
			[
				'title' => 'immonex Kickstart',
			]
		);
	} // register_inx_dynamic_tag_group

	/**
	 * Register Kickstart Dynamic Tags (action callback).
	 *
	 * Include dynamic tag file and register tag class.
	 *
	 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Elementor dynamic tags manager.
	 */
	public function register_inx_dynamic_tags( $dynamic_tags_manager ) {
		$dynamic_tags_manager->register( new Components\DynamicTags\Kickstart_Gallery() );
		$dynamic_tags_manager->register( new Components\DynamicTags\Kickstart_Template_Data() );
	} // register_inx_dynamic_tags

	/**
	 * Register single property Elementor widgets (action callback).
	 *
	 * @since 1.0.0
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {
		$esp_active          = apply_filters( 'inx_elementor_is_plugin_available', false, 'immonex-energy-scale-pro', self::MIN_REQ_VERSIONS['esp'] );
		$print_add_on_active = apply_filters( 'inx_elementor_is_plugin_available', false, 'immonex-kickstart-print', self::MIN_REQ_VERSIONS['print'] );
		$team_add_on_active  = apply_filters( 'inx_elementor_is_plugin_available', false, 'immonex-kickstart-team', self::MIN_REQ_VERSIONS['team'] );
		$lead_gen_active     = apply_filters( 'inx_elementor_is_plugin_available', false, 'immonex-lead-generator', self::MIN_REQ_VERSIONS['lead-generator'] );
		$notify_active       = apply_filters( 'inx_elementor_is_plugin_available', false, 'immonex-notify', self::MIN_REQ_VERSIONS['notify'] );

		$this->widgets = [
			new Components\Widgets\SingleProperty\Native_Head_Widget(),
			new Components\Widgets\SingleProperty\Property_Type_Widget(),
			new Components\Widgets\SingleProperty\Title_Widget(),
			new Components\Widgets\SingleProperty\Labels_Widget(),
			new Components\Widgets\SingleProperty\Print_PDF_Link_Widget( [], [ 'parent_plugin_available' => $print_add_on_active ] ),
			new Components\Widgets\SingleProperty\Main_Image_Widget(),
			new Components\Widgets\SingleProperty\Short_Desc_Widget(),
			new Components\Widgets\SingleProperty\Desc_Widget(),
			new Components\Widgets\SingleProperty\Core_Details_Widget(),
			new Components\Widgets\SingleProperty\Areas_Widget(),
			new Components\Widgets\SingleProperty\Condition_Widget(),
			new Components\Widgets\SingleProperty\Prices_Widget(),
			new Components\Widgets\SingleProperty\Epass_Widget(),
			new Components\Widgets\SingleProperty\Native_Energy_Scale_Widget( [], [ 'parent_plugin_available' => $esp_active ] ),
			new Components\Widgets\SingleProperty\Basic_Gallery_Widget(),
			new Components\Widgets\SingleProperty\Native_Gallery_Widget(),
			new Components\Widgets\SingleProperty\Native_Video_Gallery_Widget(),
			new Components\Widgets\SingleProperty\Native_Virtual_Tour_Widget(),
			new Components\Widgets\SingleProperty\Native_Location_Map_Widget(),
			new Components\Widgets\SingleProperty\Feature_List_Widget(),
			new Components\Widgets\SingleProperty\Detail_List_Widget(),
			new Components\Widgets\SingleProperty\Downloads_Links_Widget(),
			new Components\Widgets\Team\Native_Agent_Widget( [], [ 'parent_plugin_available' => $team_add_on_active ] ),
			new Components\Widgets\SingleProperty\Native_Footer_Widget(),
			new Components\Widgets\PropertyList\Native_Search_Form_Widget(),
			new Components\Widgets\PropertyList\Native_Property_Map_Widget(),
			new Components\Widgets\PropertyList\Native_Filters_Sort_Widget(),
			new Components\Widgets\PropertyList\Native_Property_List_Widget(),
			new Components\Widgets\PropertyList\Native_Property_Carousel_Widget(),
			new Components\Widgets\PropertyList\Native_Pagination_Widget(),
			new Components\Widgets\Team\Native_Agent_List_Widget( [], [ 'parent_plugin_available' => $team_add_on_active ] ),
			new Components\Widgets\Team\Native_Agency_Widget( [], [ 'parent_plugin_available' => $team_add_on_active ] ),
			new Components\Widgets\Team\Native_Agency_List_Widget( [], [ 'parent_plugin_available' => $team_add_on_active ] ),
			new Components\Widgets\LeadGenerator\Native_Lead_Forms_Widget( [], [ 'parent_plugin_available' => $lead_gen_active ] ),
			new Components\Widgets\Notify\Native_Notify_Form_Widget( [], [ 'parent_plugin_available' => $notify_active ] ),
		];

		foreach ( $this->widgets as $widget ) {
			$widgets_manager->register( $widget );
		}
	} // register_widgets

	/**
	 * Register/Enqueue Elementor editor CSS/JS files (action callback).
	 *
	 * @since 1.0.0
	 */
	public function enqueue_editor_css_js() {
		$plugin_dir     = trailingslashit( $this->data['plugin_dir'] );
		$base_js_folder = '';

		foreach ( [ 'assets/js', 'js' ] as $folder ) {
			if ( file_exists( "{$plugin_dir}{$folder}/elementor-editor.js" ) ) {
				wp_register_script(
					'inx-elementor-editor',
					plugins_url( $this->data['plugin_slug'] . "/{$folder}/elementor-editor.js" ),
					array( 'jquery' ),
					$this->data['plugin_version'],
					true
				);
				wp_enqueue_script( 'inx-elementor-editor' );

				break;
			}
		}

		foreach ( [ 'assets/css', 'css' ] as $folder ) {
			if ( file_exists( "{$plugin_dir}{$folder}/elementor-editor.css" ) ) {
				wp_enqueue_style(
					'inx-elementor-editor',
					plugins_url( $this->data['plugin_slug'] . "/{$folder}/elementor-editor.css" ),
					[],
					$this->data['plugin_version']
				);

				break;
			}
		}
	} // enqueue_editor_css_js

	/**
	 * Disable element cache option when rendering a template page (filter callback).
	 *
	 * @since 1.0.0
	 *
	 * @param string $value Current option value.
	 *
	 * @return string Possibly modified option value.
	 */
	public function disable_element_cache_for_template_pages( $value ) {
		$options           = apply_filters( 'inx_options', [], 'core' );
		$template_page_ids = array_filter(
			[
				! empty( $options['property_list_page_id'] ) ? (int) $options['property_list_page_id'] : null,
				! empty( $options['property_details_page_id'] ) ? (int) $options['property_details_page_id'] : null,
			]
		);

		if (
			! empty( $template_page_ids )
			&& in_array( get_the_ID(), $template_page_ids, true )
		) {
			return 'disable';
		}

		return $value;
	} // disable_element_cache_for_template_pages

} // class Elementor_Bootstrap
