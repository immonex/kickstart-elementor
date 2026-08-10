<?php
/**
 * Class Elementor_Bootstrap
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Elementor elements.
 */
class Elementor_Bootstrap {

	const MIN_REQ_VERSIONS = [
		'esp'            => '2.1.0',
		'print'          => '0.9.2-beta',
		'team'           => '1.5.12',
		'lead-generator' => '3.0.0',
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
	 * @var \immonex\Kickstart\ForElementor\Components\Widgets[]
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
		add_action( 'elementor/widget/render_content', [ $this, 'extend_loop_css_classes' ], 10, 2 );
		add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );

		if ( apply_filters( 'inxkickel_is_plugin_available', false, 'elementor-pro' ) ) {
			add_action( 'elementor/dynamic_tags/register', [ $this, 'register_dynamic_tag_group' ] );
			add_action( 'elementor/dynamic_tags/register', [ $this, 'register_dynamic_tags' ] );
		}

		add_filter( 'option_elementor_element_cache_ttl', [ $this, 'disable_element_cache_for_template_pages' ] );
		add_filter( 'elementor/query/query_args', [ $this, 'maybe_extend_query_args' ], 10, 2 );
		add_filter( 'elementor/posts/wp_link_page', [ $this, 'fix_pagination_url' ] );
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
				'title' => $house_icon . __( 'Property Details', 'immonex-kickstart-for-elementor' ),
				'icon'  => 'fa fa-home',
			],
			'inx-property-list'   => [
				'title' => $house_icon . __( 'Property Lists and Search', 'immonex-kickstart-for-elementor' ),
				'icon'  => 'fa fa-home',
			],
		];

		if ( apply_filters( 'inxkickel_is_plugin_available', false, 'immonex-kickstart-team', self::MIN_REQ_VERSIONS['team'] ) ) {
			$add_categories['inx-team'] = [
				'title' => $house_icon . __( 'Contacts & Agencies', 'immonex-kickstart-for-elementor' ) . ' (+Team)',
				'icon'  => 'fa fa-home',
			];
		}

		if (
			apply_filters( 'inxkickel_is_plugin_available', false, 'immonex-lead-generator', self::MIN_REQ_VERSIONS['lead-generator'] )
			|| apply_filters( 'inxkickel_is_plugin_available', false, 'immonex-notify', self::MIN_REQ_VERSIONS['notify'] )
		) {
			$add_categories['inx-marketing-acquisition'] = [
				'title' => $house_icon . __( 'Marketing & Acquisition', 'immonex-kickstart-for-elementor' ),
				'icon'  => 'fa fa-home',
			];
		}

		$add_categories['inx-special-forms'] = [
			'title' => $house_icon . __( 'Special Forms', 'immonex-kickstart-for-elementor' ),
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
	public function register_dynamic_tag_group( $dynamic_tags_manager ) {
		$dynamic_tags_manager->register_group(
			'inx',
			[
				'title' => 'immonex Kickstart',
			]
		);
	} // register_dynamic_tag_group

	/**
	 * Register Kickstart Dynamic Tags (action callback).
	 *
	 * Include dynamic tag file and register tag class.
	 *
	 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Elementor dynamic tags manager.
	 */
	public function register_dynamic_tags( $dynamic_tags_manager ) {
		$dynamic_tags_manager->register( new Components\DynamicTags\Kickstart_Gallery() );
		$dynamic_tags_manager->register( new Components\DynamicTags\Kickstart_Template_Data() );
		$dynamic_tags_manager->register( new Components\DynamicTags\Kickstart_URL() );
	} // register_dynamic_tags

	/**
	 * Register single property Elementor widgets (action callback).
	 *
	 * @since 1.0.0
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {
		$esp_active          = apply_filters( 'inxkickel_is_plugin_available', false, 'immonex-energy-scale-pro', self::MIN_REQ_VERSIONS['esp'] );
		$print_add_on_active = apply_filters( 'inxkickel_is_plugin_available', false, 'immonex-kickstart-print', self::MIN_REQ_VERSIONS['print'] );
		$team_add_on_active  = apply_filters( 'inxkickel_is_plugin_available', false, 'immonex-kickstart-team', self::MIN_REQ_VERSIONS['team'] );
		$lead_gen_active     = apply_filters( 'inxkickel_is_plugin_available', false, 'immonex-lead-generator', self::MIN_REQ_VERSIONS['lead-generator'] );
		$notify_active       = apply_filters( 'inxkickel_is_plugin_available', false, 'immonex-notify', self::MIN_REQ_VERSIONS['notify'] );

		$this->widgets = [
			new Components\Widgets\SingleProperty\Detail_List_Widget(),
			new Components\Widgets\SingleProperty\Native_Head_Widget(),
			new Components\Widgets\SingleProperty\Property_Type_Widget(),
			new Components\Widgets\SingleProperty\Title_Widget(),
			new Components\Widgets\SingleProperty\Labels_Widget(),
			new Components\Widgets\KickstartPrint\Print_PDF_Link_Widget( [], [ 'parent_plugin_available' => $print_add_on_active ] ),
			new Components\Widgets\SingleProperty\Main_Image_Widget(),
			new Components\Widgets\SingleProperty\Short_Desc_Widget(),
			new Components\Widgets\SingleProperty\Desc_Widget(),
			new Components\Widgets\SingleProperty\Core_Details_Widget(),
			new Components\Widgets\SingleProperty\Areas_Widget(),
			new Components\Widgets\SingleProperty\Condition_Widget(),
			new Components\Widgets\SingleProperty\Prices_Widget(),
			new Components\Widgets\SingleProperty\Epass_Widget(),
			new Components\Widgets\EnergyScalePro\Native_Energy_Scale_Widget( [], [ 'parent_plugin_available' => $esp_active ] ),
			new Components\Widgets\SingleProperty\Basic_Gallery_Widget(),
			new Components\Widgets\SingleProperty\Native_Gallery_Widget(),
			new Components\Widgets\SingleProperty\Native_Video_Gallery_Widget(),
			new Components\Widgets\SingleProperty\Native_Virtual_Tour_Widget(),
			new Components\Widgets\SingleProperty\Native_Location_Map_Widget(),
			new Components\Widgets\SingleProperty\Feature_List_Widget(),
			new Components\Widgets\SingleProperty\Downloads_Links_Widget(),
			new Components\Widgets\KickstartTeam\Native_Agent_Widget( [], [ 'parent_plugin_available' => $team_add_on_active ] ),
			new Components\Widgets\KickstartTeam\Native_Contact_Form_Confirmation_Message_Widget( [], [ 'parent_plugin_available' => $team_add_on_active ] ),
			new Components\Widgets\SingleProperty\Native_Footer_Widget(),
			new Components\Widgets\PropertyList\Native_Search_Form_Widget(),
			new Components\Widgets\PropertyList\Native_Property_Map_Widget(),
			new Components\Widgets\PropertyList\Native_Filters_Sort_Widget(),
			new Components\Widgets\PropertyList\Native_Property_List_Widget(),
			new Components\Widgets\PropertyList\Native_Property_Carousel_Widget(),
			new Components\Widgets\PropertyList\Native_Pagination_Widget(),
			new Components\Widgets\KickstartTeam\Native_Agent_List_Widget( [], [ 'parent_plugin_available' => $team_add_on_active ] ),
			new Components\Widgets\KickstartTeam\Native_Agency_Widget( [], [ 'parent_plugin_available' => $team_add_on_active ] ),
			new Components\Widgets\KickstartTeam\Native_Agency_List_Widget( [], [ 'parent_plugin_available' => $team_add_on_active ] ),
			new Components\Widgets\LeadGenerator\Native_Lead_Forms_Widget( [], [ 'parent_plugin_available' => $lead_gen_active ] ),
			new Components\Widgets\Notify\Native_Notify_Form_Widget( [], [ 'parent_plugin_available' => $notify_active ] ),
			new Components\Widgets\SpecialForms\Native_Withdrawal_Form_Widget(),
			new Components\Widgets\SpecialForms\Native_Withdrawal_Form_Confirmation_Message_Widget(),
		];

		foreach ( $this->widgets as $widget ) {
			$widgets_manager->register( $widget );
		}
	} // register_widgets

	/**
	 * Register custom Elementor controls for Kickstart (action callback).
	 *
	 * @since 1.4.0
	 *
	 * @param \Elementor\Controls_Manager $controls_manager Elementor controls manager.
	 */
	public function register_controls( $controls_manager ) {
		$controls_manager->register( new Controls\Extended_Select2() );
	} // register_controls

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
					'inxkickel-editor',
					plugins_url( $this->data['plugin_slug'] . "/{$folder}/elementor-editor.js" ),
					array( 'jquery' ),
					$this->data['plugin_version'],
					true
				);
				wp_enqueue_script( 'inxkickel-editor' );

				break;
			}
		}

		foreach ( [ 'assets/css', 'css' ] as $folder ) {
			if ( file_exists( "{$plugin_dir}{$folder}/elementor-editor.css" ) ) {
				wp_enqueue_style(
					'inxkickel-editor',
					plugins_url( $this->data['plugin_slug'] . "/{$folder}/elementor-editor.css" ),
					[],
					$this->data['plugin_version']
				);

				break;
			}
		}
	} // enqueue_editor_css_js

	/**
	 * When updating the related option, disable the element cache for
	 * (regular) template pages (filter callback).
	 *
	 * @since 1.0.0
	 *
	 * @param string $value Current option value.
	 *
	 * @return string Possibly modified option value.
	 */
	public function disable_element_cache_for_template_pages( $value ) {
		$options           = apply_filters( 'inx_options', [], 'core' ); // phpcs:ignore -- Parent plugin filter hook that can't be changed (yet) for compatibility reasons.
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

	/**
	 * Add a custom argument to the Elementor query args if related to a
	 * supported Kickstart post type (filter callback).
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[]                $query_args Query args.
	 * @param \Elementor\Widget_Base $widget     Widget instance.
	 *
	 * @return mixed[] Original or extended query args.
	 */
	public function maybe_extend_query_args( $query_args, $widget ) { // phpcs:ignore
		if (
			! empty( $query_args['post_type'] )
			&& isset( $this->data['supported_post_types'][ $query_args['post_type'] ] )
		) {
			$query_args['execute_pre_get_posts_filter'] = true;
		}

		return $query_args;
	} // maybe_extend_query_args

	/**
	 * Add CSS class "inx-real-estate-list" to Elementor loop element containers
	 * if Kickstart properties are listed (action callback).
	 *
	 * @since 1.2.0
	 *
	 * @param string                 $content Widget content.
	 * @param \Elementor\Widget_Base $widget  Widget instance.
	 *
	 * @return string Possibly extended content.
	 */
	public function extend_loop_css_classes( $content, $widget ) {
		if ( false !== strpos( $widget->get_name(), 'loop-' ) ) {
			$settings = $widget->get_active_settings();

			if ( 'inx_property' === $settings['post_query_post_type'] ) {
				return preg_replace( '/class="/', 'class="inx-real-estate-list ', $content, 1 );
			}
		}

		return $content;
	} // extend_loop_css_classes

	/**
	 * Fix a "single property" pagination base URL (filter callback).
	 *
	 * @since 1.2.0
	 *
	 * @param string $url URL generated by Elementor.
	 *
	 * @return string Original or fixed URL.
	 */
	public function fix_pagination_url( $url ) {
		$post_id = url_to_postid( $url );
		if ( ! $post_id || ( $post_id && 'inx_property' !== get_post_type( $post_id ) ) ) {
			return $url;
		}

		$slug = get_post_field( 'post_name', $post_id );

		return $slug ? preg_replace( "/\/{$slug}\/([0-9]+)/", '/page/$1', $url ) : $url;
	} // fix_pagination_url

} // class Elementor_Bootstrap
