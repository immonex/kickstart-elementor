<?php
/**
 * Class Widget_Base
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets;

/**
 * Extended Elementor Widget Base Class.
 */
abstract class Widget_Base extends \Elementor\Widget_Base {

	/**
	 * Plugin Slug.
	 */
	const PLUGIN_SLUG = 'immonex-kickstart-elementor';

	/**
	 * Specific post type the widget is bound to.
	 */
	const POST_TYPE = '';

	/**
	 * Widget Name (Key)
	 */
	const WIDGET_NAME = '';

	/**
	 * Widget Icon
	 */
	const WIDGET_ICON = 'eicon-t-letter';

	/**
	 * Widget Categories
	 */
	const WIDGET_CATEGORIES = [];

	/**
	 * Widget Base Keywords
	 */
	const WIDGET_BASE_KEYWORDS = [ 'immonex', 'kickstart', 'openimmo' ];

	/**
	 * Widget Help URL
	 */
	const WIDGET_HELP_URL = 'https://docs.immonex.de/kickstart-elementor/';

	/**
	 * Default Heading Level
	 */
	const DEFAULT_HEADING_LEVEL = 2;

	/**
	 * Enable render method for preview generation.
	 */
	const ENABLE_RENDER_ON_PREVIEW = false;

	/**
	 * Native Postfix
	 */
	const NATIVE_POSTFIX = ' 🄽';

	/**
	 * Dynamic Content Flag (true = disable cache)
	 */
	const IS_DYNAMIC_CONTENT = false;

	/**
	 * Parent Plugin Name
	 */
	const PARENT_PLUGIN_NAME = '';

	/**
	 * Parent Plugin WP Repository URL
	 */
	const PARENT_PLUGIN_WP_REPO_URL = '';

	/**
	 * Parent Plugin Shop URL
	 */
	const PARENT_PLUGIN_SHOP_URL = '';

	/**
	 * Main CSS Classes (Widget DOM Element)
	 *
	 * @var string[]
	 */
	protected $main_classes = [];

	/**
	 * Post ID
	 *
	 * @var int
	 */
	protected $post_id = 0;

	/**
	 * Keywords
	 *
	 * @var string[]
	 */
	protected $keywords = [];

	/**
	 * Users (ID => Name/Login)
	 *
	 * @var string[]
	 */
	protected $users = [];

	/**
	 * Plugin Assets Folder URL
	 *
	 * @var string
	 */
	protected $plugin_assets_url = '';

	/**
	 * Parent Plugin Availability Flag
	 *
	 * @var bool
	 */
	protected $parent_plugin_available = true;

	/**
	 * Localized Plugin WP Repository URL
	 *
	 * @var string
	 */
	protected $parent_plugin_wp_repo_url = '';

	/**
	 * Widget base constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[]      $data Widget data. Default is an empty array.
	 * @param mixed[]|null $args Widget default arguments (optional, default null).
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		$this->plugin_assets_url = plugins_url( 'assets', 'immonex-kickstart-elementor/immonex-kickstart-elementor.php' ) . '/';

		if ( static::PARENT_PLUGIN_WP_REPO_URL ) {
			$lang = substr( determine_locale(), 0, 2 );

			$this->parent_plugin_wp_repo_url = 'de' === $lang ?
				str_replace( '//wordpress.org', '//de.wordpress.org', static::PARENT_PLUGIN_WP_REPO_URL ) :
				static::PARENT_PLUGIN_WP_REPO_URL;
		}

		if ( isset( $args['parent_plugin_available'] ) && ! $args['parent_plugin_available'] ) {
			$this->parent_plugin_available = false;
		}
	} // __construct

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return static::WIDGET_NAME;
	} // get_name

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return static::WIDGET_ICON;
	} // get_icon

	/**
	 * Get widget categories.
	 *
	 * @since 1.0.0
	 *
	 * @return string[] Widget categories.
	 */
	public function get_categories() {
		return static::WIDGET_CATEGORIES;
	} // get_categories

	/**
	 * Get custom help URL.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget help URL.
	 */
	public function get_custom_help_url() {
		return $this->parent_plugin_available ? static::WIDGET_HELP_URL : null;
	} // get_custom_help_url

	/**
	 * Get widget keywords.
	 *
	 * @since 1.0.0
	 *
	 * @return string[] Widget keywords.
	 */
	public function get_keywords() {
		$this->add_keywords();

		return $this->keywords;
	} // get_keywords

	/**
	 * Get widget element main classes.
	 *
	 * @since 1.0.0
	 *
	 * @return string[] Class names.
	 */
	public function get_main_classes() {
		$this->add_main_class();

		return array_unique( $this->main_classes );
	} // get_main_classes

	/**
	 * Get the controls stack (info tab only if required parent plugin is missing).
	 *
	 * @since 1.0.0
	 *
	 * @param bool $with_common_controls True to add common controls.
	 *
	 * @return mixed[] Controls stack data.
	 */
	public function get_stack( $with_common_controls = true ) {
		if ( ! $this->parent_plugin_available ) {
			return [
				'tabs'     => [
					'content' => __( 'Plugin required', 'immonex-kickstart-elementor' ),
				],
				'controls' => [],
			];
		}

		return parent::get_stack( $with_common_controls );
	} // get_stack

	/**
	 * Generate and return the widget upsale/promotion data.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[] Widget promotion data or empty array.
	 */
	protected function get_upsale_data() {
		if ( $this->parent_plugin_available ) {
			return [];
		}

		$plugin_link    = '';
		$is_free_plugin = ! static::PARENT_PLUGIN_SHOP_URL;

		if ( $is_free_plugin && $this->parent_plugin_wp_repo_url ) {
			$plugin_link = '<a href="' . $this->parent_plugin_wp_repo_url . '" target="_blank" style="white-space:nowrap">' . static::PARENT_PLUGIN_NAME . '</a>';
		} elseif ( static::PARENT_PLUGIN_SHOP_URL ) {
			$plugin_link = '<a href="' . static::PARENT_PLUGIN_SHOP_URL . '" target="_blank" style="white-space:nowrap">' . static::PARENT_PLUGIN_NAME . '</a>';
		} elseif ( static::PARENT_PLUGIN_NAME ) {
			$plugin_link = '<strong>' . static::PARENT_PLUGIN_NAME . '</strong> (' . __( 'available soon', 'immonex-kickstart-elementor' ) . ')';
		}

		return [
			'condition'    => ! $this->parent_plugin_available,
			'image'        => esc_url( $this->plugin_assets_url . 'immonex-plus-icon.png' ),
			'image_alt'    => esc_attr__( 'Plugin required', 'immonex-kickstart-elementor' ),
			'description'  => wp_sprintf(
				// translators: %s = plugin name/link string.
				__( 'This widget requires %s.', 'immonex-kickstart-elementor' ),
				$plugin_link ?
					(
						$is_free_plugin ?
						wp_sprintf(
							// translators: %s = plugin name/link.
							__( 'the free %s plugin', 'immonex-kickstart-elementor' ),
							$plugin_link
						) :
						wp_sprintf(
							// translators: %s = plugin name/link.
							__( 'the %s premium plugin', 'immonex-kickstart-elementor' ),
							$plugin_link
						)
					) :
					__( 'an additional plugin', 'immonex-kickstart-elementor' )
			),
			'upgrade_url'  => static::PARENT_PLUGIN_SHOP_URL ? esc_url( static::PARENT_PLUGIN_SHOP_URL ) : '',
			'upgrade_text' => static::PARENT_PLUGIN_SHOP_URL ? esc_html__( 'Order it now!', 'immonex-kickstart-elementor' ) : '',
		];
	} // get_upsale_data

	/**
	 * Add widget keywords.
	 *
	 * @since 1.0.0
	 */
	protected function add_keywords() {
		$this->keywords = static::WIDGET_BASE_KEYWORDS;
	} // add_keywords

	/**
	 * Return the dynamic content state.
	 *
	 * @since 1.0.0
	 */
	protected function is_dynamic_content(): bool {
		return static::IS_DYNAMIC_CONTENT;
	} // is_dynamic_content

	/**
	 * Add widget element main classes.
	 *
	 * @since 1.0.0
	 */
	protected function add_main_class() {
		$parent_class = get_parent_class( $this );

		if (
			defined( "{$parent_class}::WIDGET_NAME" ) && $parent_class::WIDGET_NAME ) {
			$this->main_classes[] = $parent_class::WIDGET_NAME;
		}

		if ( static::WIDGET_NAME ) {
			$this->main_classes[] = static::WIDGET_NAME;
		}
	} // add_main_class

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		if (
			static::POST_TYPE
			&& get_post_type( $this->get_post_id() ) !== static::POST_TYPE
			&& ! static::ENABLE_RENDER_ON_PREVIEW
		) {
			return;
		}

		$template_file_info = $this->get_template_file_info();
		if ( ! $template_file_info ) {
			return;
		}

		$template_data_escaped = $this->get_template_data();
		if ( false === $template_data_escaped ) {
			return;
		}

		// phpcs:ignore
		$utils          = apply_filters( 'inx_elementor_get_utils', [] );
		$plain_template = $template_file_info['folder'] . DIRECTORY_SEPARATOR . $template_file_info['plain_name'];
		$template_file  = '';

		$template_file_path = $utils['template']->locate_template_file( [ $plain_template . '.twig', $plain_template . '.php' ] );
		if ( $template_file_path ) {
			$template_file = $plain_template . ( 'twig' === substr( $template_file_path, -4 ) ? '.twig' : '.php' );
		}

		if ( ! $template_file && 'native-' === substr( $template_file_info['plain_name'], 0, 7 ) ) {
			$template_file_path = $utils['template']->locate_template_file( 'widgets' . DIRECTORY_SEPARATOR . 'native-default.twig' );
			if ( $template_file_path ) {
				$template_file = 'widgets' . DIRECTORY_SEPARATOR . 'native-default.twig';
			}
		}

		if ( ! $template_file ) {
			return;
		}

		echo 'php' === substr( strtolower( $template_file ), -3 ) ?
			$utils['template']->render_php_template( $template_file, $template_data_escaped ) : // phpcs:ignore
			$utils['template']->render_twig_template( $template_file, $template_data_escaped ); // phpcs:ignore
	} // render

	/**
	 * Render widget preview content in the editor (WP backend).
	 *
	 * @since 1.0.0
	 */
	protected function content_template() {
		if ( static::ENABLE_RENDER_ON_PREVIEW || ! $this->parent_plugin_available ) {
			return;
		}

		$template_file_info = $this->get_template_file_info();

		if ( ! $template_file_info ) {
			return;
		}

		// phpcs:ignore
		$utils    = apply_filters( 'inx_elementor_get_utils', [] );
		$template = $template_file_info['folder'] . DIRECTORY_SEPARATOR . $template_file_info['plain_name'] . '-preview';

		if ( ! $utils['template']->locate_template_file( $template ) ) {
			return;
		}

		$demo_content = $this->get_demo_content();

		$template_data_escaped = [
			'demo_content'         => $demo_content,
			'demo_content_escaped' => $demo_content,
		];

		// phpcs:ignore
		echo $utils['template']->render_php_template( $template, $template_data_escaped );
	} // content_template

	/**
	 * Return default value for the given control.
	 *
	 * @since 1.0.0
	 *
	 * @param string        $control_id    Control ID.
	 * @param mixed|mixed[] $default_value Default value if not specified otherwise (optional).
	 * @param string        $breakpoint    Elementor breakpoint key (optional).
	 *
	 * @return mixed[] Defaul data.
	 */
	protected function get_default( $control_id, $default_value = '', $breakpoint = 'default' ) {
		return $default_value;
	} // get_default

	/**
	 * Return widget demo content.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[]|null $contents Source Demo contents (optional).
	 *
	 * @return string[] Demo contents.
	 */
	protected function get_demo_content( $contents = [] ) {
		if ( ! is_array( $contents ) ) {
			$contents = [];
		}

		$contents = array_merge(
			[
				// phpcs:ignore
				'heading_base_level' => apply_filters( 'inx_get_option_value', 1, 'heading_base_level' ),
			],
			$contents
		);

		return wp_json_encode( $contents );
	} // get_demo_content

	/**
	 * Return template folder and "plain" name based on class directory and name.
	 *
	 * @since 1.0.0
	 *
	 * @return string[]|false Associative array containing folder and plain template name.
	 */
	protected function get_template_file_info() {
		$r    = new \ReflectionClass( get_class( $this ) );
		$path = $r->getFileName();

		if ( ! $path ) {
			return false;
		}

		$path = str_replace( '_', '-', $r->getFileName() );

		if ( '/' !== DIRECTORY_SEPARATOR ) {
			$path = str_replace( DIRECTORY_SEPARATOR, '/', $path );
		}

		$found = preg_match( '/(widgets\/[a-zA-Z-]+)\/class-([a-z-]+)-widget.php/', $path, $matches );

		if ( ! $found ) {
			return false;
		}

		if ( '/' !== DIRECTORY_SEPARATOR ) {
			$matches[1] = str_replace( '/', DIRECTORY_SEPARATOR, $matches[1] );
		}

		return [
			'folder'     => $matches[1],
			'plain_name' => $matches[2],
		];
	} // get_template_file_info

	/**
	 * Determine/Set the ID of the page or post in which the widget content
	 * should be rendered (once).
	 *
	 * @since 1.0.0
	 *
	 * @return int Post ID.
	 */
	protected function get_post_id() {
		if ( $this->post_id ) {
			return $this->post_id;
		}

		$this->post_id = 'inx_property' === static::POST_TYPE ?
			apply_filters( 'inx_current_property_post_id', get_the_ID() ) : // phpcs:ignore
			get_the_ID();

		return $this->post_id;
	} // get_post_id

	/**
	 * Check the given heading level and return the corresponding H tag.
	 *
	 * @since 1.0.0
	 *
	 * @param int $heading_level Heading level.
	 *
	 * @return string H tag without brackets.
	 */
	protected function get_h_tag( $heading_level ) {
		$heading_level = $heading_level >= 1 && $heading_level <= 6 ?
			$heading_level :
			static::DEFAULT_HEADING_LEVEL;

		return 'h' . $heading_level;
	} // get_h_tag

	/**
	 * Return property related taxonomies.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[] Property taxonomies (key => label).
	 */
	protected function get_tax_atts() {
		return [
			'property-type'  => __( 'Property Types', 'immonex-kickstart-elementor' ),
			'marketing-type' => __( 'Marketing Types', 'immonex-kickstart-elementor' ),
			'type-of-use'    => __( 'Types of Use', 'immonex-kickstart-elementor' ),
			'project'        => __( 'Projects', 'immonex-kickstart-elementor' ),
			'locality'       => __( 'Localities', 'immonex-kickstart-elementor' ),
			'labels'         => __( 'Labels', 'immonex-kickstart-elementor' ),
			'features'       => __( 'Features', 'immonex-kickstart-elementor' ),
		];
	} // get_tax_atts

	/**
	 * Return explicit (custom field) flags.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[] Flags (key => label + "not option").
	 */
	protected function get_explicit_cf_flags() {
		return [
			'available'        => [
				'label'      => __( 'Available Properties', 'immonex-kickstart-elementor' ),
				'not_option' => __( 'Unavailable only', 'immonex-kickstart-elementor' ),
			],
			'reserved'         => [
				'label'      => __( 'Reserved Properties', 'immonex-kickstart-elementor' ),
				'not_option' => __( 'Unreserved only', 'immonex-kickstart-elementor' ),
			],
			'sold'             => [
				'label'      => __( 'Sold/Rented Properties', 'immonex-kickstart-elementor' ),
				'not_option' => __( 'Unsold/Unrented only', 'immonex-kickstart-elementor' ),
			],
			'featured'         => [
				'label'      => __( 'Featured Properties', 'immonex-kickstart-elementor' ),
				'not_option' => __( 'Non-featured only', 'immonex-kickstart-elementor' ),
			],
			'front-page-offer' => [
				'label'      => __( 'Front Page Offers', 'immonex-kickstart-elementor' ),
				'not_option' => __( 'Non-Front-Page-Offers only', 'immonex-kickstart-elementor' ),
			],
		];
	} // get_explicit_cf_flags

	/**
	 * Return the default property list sort options.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[] Default sort options.
	 */
	protected function get_property_sort_options() {
		$sort_options = [
			''                    => __( 'Automatic', 'immonex-kickstart-elementor' ),
			'date_desc'           => __( 'Most Current', 'immonex-kickstart-elementor' ),
			'marketing_type_desc' => __( 'For Sale first', 'immonex-kickstart-elementor' ),
			'marketing_type_asc'  => __( 'For Rent first', 'immonex-kickstart-elementor' ),
			'availability_desc'   => __( 'Available first', 'immonex-kickstart-elementor' ),
			'price_asc'           => __( 'Primary Price ascending', 'immonex-kickstart-elementor' ),
			'price_desc'          => __( 'Primary Price descending', 'immonex-kickstart-elementor' ),
			'area_asc'            => __( 'Area ascending', 'immonex-kickstart-elementor' ),
			'rooms_asc'           => __( 'Rooms ascending', 'immonex-kickstart-elementor' ),
		];

		return [
			'array' => $sort_options,
			'json'  => wp_json_encode( $sort_options ),
		];
	} // get_property_sort_options

	/**
	 * Register common widget controls.
	 *
	 * @since 1.0.0
	 *
	 * @param string|string[] $scopes               Scopes of controls to be added (optional).
	 * @param mixed[]         $args                 Arguments to be merged with the control defaults (optional).
	 * @param bool            $end_controls_section End control section(s) started within the method (optional).
	 */
	protected function add_default_controls( $scopes = [ 'heading' ], $args = [], $end_controls_section = true ) {
		if ( ! is_array( $scopes ) ) {
			$scopes = [ $scopes ];
		}

		$tax_atts       = $this->get_tax_atts();
		$explicit_flags = $this->get_explicit_cf_flags();

		$template_desc = __( 'Enter the <strong>filename</strong> if an <strong>alternative</strong> PHP template should be used for rendering the component.', 'immonex-kickstart-elementor' );
		if (
			! empty( $args['template']['folder'] )
			&& ! empty( $args['template']['plugin'] )
		) {
			$template_desc .= wp_sprintf(
				/* translators: %1$s: skin subfolder name, %2$s: plugin name */
				'(' . __( 'The file must be included in the subfolder %1$s of the <strong>skin</strong> folder selected in the %2$s plugin options.', 'immonex-kickstart-elementor' ) . ')',
				"<code>{$args['template']['folder']}</code>",
				"<strong>{$args['template']['plugin']}</strong>"
			);
		}

		$all_controls = [
			'heading'                    => [
				'label' => __( 'Heading', 'immonex-kickstart-elementor' ),
				'type'  => \Elementor\Controls_Manager::TEXT,
				'scope' => [ 'heading' ],
			],
			'heading_level'              => [
				'label'   => __( 'H Level (relative)', 'immonex-kickstart-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					1 => 'H1',
					2 => 'H2',
					3 => 'H3',
					4 => 'H4',
					5 => 'H5',
					6 => 'H6',
				],
				'default' => static::DEFAULT_HEADING_LEVEL,
				'scope'   => [ 'heading' ],
			],
			'heading_style_section'      => [
				'label' => __( 'Heading', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'scope' => [ 'heading_style' ],
			],
			'heading_align'              => [
				'label'         => __( 'Alignment', 'immonex-kickstart-elementor' ),
				'type'          => \Elementor\Controls_Manager::CHOOSE,
				'options'       => [
					'left'   => [
						'title' => __( 'Left', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors'     => [
					'{{WRAPPER}} .inx-e-heading' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .inx-single-property__section-title' => 'text-align: {{VALUE}};',
				],
				'separator'     => 'after',
				'is_responsive' => true,
				'scope'         => [ 'heading_style' ],
			],
			'heading_title_color'        => [
				'label'     => __( 'Text Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-e-heading' => 'color: {{VALUE}};',
					'{{WRAPPER}} .inx-single-property__section-title' => 'color: {{VALUE}};',
				],
				'scope'     => [ 'heading_style' ],
			],
			'heading_typography'         => [
				'group_control' => [
					'label'   => __( 'Typograhy', 'immonex-kickstart-elementor' ),
					'type'    => \Elementor\Group_Control_Typography::get_type(),
					'options' => [
						'selector' => '{{WRAPPER}} .inx-e-heading, {{WRAPPER}} .inx-single-property__section-title',
					],
				],
				'scope'         => [ 'heading_style' ],
			],
			'heading_text_stroke'        => [
				'group_control' => [
					'label'   => __( 'Text Stroke', 'immonex-kickstart-elementor' ),
					'type'    => \Elementor\Group_Control_Text_Stroke::get_type(),
					'options' => [
						'selector' => '{{WRAPPER}} .inx-e-heading, {{WRAPPER}} .inx-single-property__section-title',
					],
				],
				'scope'         => [ 'heading_style' ],
			],
			'heading_text_shadow'        => [
				'group_control' => [
					'type'    => \Elementor\Group_Control_Text_Shadow::get_type(),
					'options' => [
						'label'    => __( 'Text Shadow', 'immonex-kickstart-elementor' ),
						'selector' => '{{WRAPPER}} .inx-e-heading, {{WRAPPER}} .inx-single-property__section-title',
					],
				],
				'scope'         => [ 'heading_style' ],
			],
			'blend_mode'                 => [
				'label'     => __( 'Blend Mode', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					''            => __( 'Normal', 'immonex-kickstart-elementor' ),
					'multiply'    => __( 'Multiply', 'immonex-kickstart-elementor' ),
					'screen'      => __( 'Screen', 'immonex-kickstart-elementor' ),
					'overlay'     => __( 'Overlay', 'immonex-kickstart-elementor' ),
					'darken'      => __( 'Darken', 'immonex-kickstart-elementor' ),
					'lighten'     => __( 'Lighten', 'immonex-kickstart-elementor' ),
					'color-dodge' => __( 'Color Dodge', 'immonex-kickstart-elementor' ),
					'saturation'  => __( 'Saturation', 'immonex-kickstart-elementor' ),
					'color'       => __( 'Color', 'immonex-kickstart-elementor' ),
					'difference'  => __( 'Difference', 'immonex-kickstart-elementor' ),
					'exclusion'   => __( 'Exclusion', 'immonex-kickstart-elementor' ),
					'hue'         => __( 'Hue', 'immonex-kickstart-elementor' ),
					'luminosity'  => __( 'Luminosity', 'immonex-kickstart-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .inx-e-heading' => 'mix-blend-mode: {{VALUE}}',
					'{{WRAPPER}} .inx-single-property__section-title' => 'mix-blend-mode: {{VALUE}}',
				],
				'scope'     => [ 'heading_style' ],
			],
			'limit'                      => [
				'label'       => __( 'Post Limit', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'description' => __( 'Maximum <strong>total</strong> number of displayed posts.', 'immonex-kickstart-elementor' ),
				'min'         => 0,
				'max'         => 100,
				'default'     => 0,
				'scope'       => [ 'lists', 'overview_map' ],
			],
			'limit-page'                 => [
				'label'       => __( 'Page Limit', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'description' => __( 'Number of displayed posts <strong>per page</strong> unless a maximum number has been specified.', 'immonex-kickstart-elementor' ),
				'min'         => 0,
				'max'         => 100,
				'default'     => 0,
				'condition'   => [ 'limit' => [ 0, '' ] ],
				'scope'       => [ 'lists' ],
			],
			'authors'                    => [
				'label'       => __( 'Authors Filter', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'description' => __( 'Show/Hide only posts of selected authors.', 'immonex-kickstart-elementor' ),
				'multiple'    => true,
				'options'     => $this->get_user_list(),
				'label_block' => true,
				'scope'       => [ 'authors' ],
			],
			'exclude_authors'            => [
				'label'       => __( 'Exclude Authors', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Exclude posts of selected authors instead of explicitely including them.', 'immonex-kickstart-elementor' ),
				'conditions'  => [
					'terms' => [
						[
							'name'     => 'authors',
							'operator' => '!=',
							'value'    => '',
						],
					],
				],
				'scope'       => [ 'authors' ],
			],
			'order'                      => [
				'label'       => __( 'Order', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => [
					''         => __( 'Default (Publishing Date descending)', 'immonex-kickstart-elementor' ),
					'ID'       => __( 'Post ID', 'immonex-kickstart-elementor' ),
					'title'    => __( 'Post Title', 'immonex-kickstart-elementor' ),
					'name'     => __( 'Slug', 'immonex-kickstart-elementor' ),
					'date'     => __( 'Publishing Date', 'immonex-kickstart-elementor' ),
					'modified' => __( 'Modification Date', 'immonex-kickstart-elementor' ),
					'rand'     => __( 'Random', 'immonex-kickstart-elementor' ),
				],
				'label_block' => true,
				'scope'       => [ 'team_common' ],
			],
			'order_dir'                  => [
				'label'     => __( 'Sort Direction', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					'ASC'  => __( 'Ascending', 'immonex-kickstart-elementor' ),
					'DESC' => __( 'Descending', 'immonex-kickstart-elementor' ),
				],
				'default'   => 'ASC',
				'condition' => [ 'order!' => '' ],
				'scope'     => [ 'team_common' ],
			],
			'demo'                       => [
				'label'       => __( 'Demo', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'Display posts that are marked as demo content.', 'immonex-kickstart-elementor' ),
				'options'     => [
					''     => __( 'Default (Yes)', 'immonex-kickstart-elementor' ),
					'yes'  => __( 'Yes', 'immonex-kickstart-elementor' ),
					'no'   => __( 'No', 'immonex-kickstart-elementor' ),
					'only' => __( 'Only', 'immonex-kickstart-elementor' ),
				],
				'scope'       => [ 'team_common' ],
			],
			'tax_filter_notice'          => [
				'type'        => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'content'     => __( 'Add comma-separated <strong>taxonomy term slugs</strong> in the following fields to explicitely include (e.g. "houses, flats") or exclude (e.g. "-lots") related properties.', 'immonex-kickstart-elementor' ),
				'scope'       => [ 'tax_filters' ],
			],
			'min-rooms'                  => [
				'label'   => __( 'Minimum Rooms (primary)', 'immonex-kickstart-elementor' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 100,
				'default' => 0,
				'scope'   => [ 'cf_filters' ],
			],
			'min-area'                   =>
			[
				'label'   => __( 'Minimum Area (primary)', 'immonex-kickstart-elementor' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 10000,
				'default' => 0,
				'scope'   => [ 'cf_filters' ],
			],
			'price_min'                  => [
				'label'   => __( 'Minimum Price (primary)', 'immonex-kickstart-elementor' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 0,
				'scope'   => [ 'cf_filters' ],
			],
			'price_max'                  => [
				'label'   => __( 'Maximum Price (primary)', 'immonex-kickstart-elementor' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 0,
				'scope'   => [ 'cf_filters' ],
			],
			'iso-country'                => [
				'label'       => __( 'Countries', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Comma-separated list of <strong>ISO 3166-1 ALPHA-3</strong> country codes (e.g. "DEU,AUT,CHE")', 'immonex-kickstart-elementor' ),
				'label_block' => true,
				'scope'       => [ 'cf_filters' ],
			],
			'references'                 => [
				'label'       => __( 'Reference Properties', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => [
					''     => __( 'Default', 'immonex-kickstart-elementor' ),
					'yes'  => __( 'Yes', 'immonex-kickstart-elementor' ),
					'no'   => __( 'No', 'immonex-kickstart-elementor' ),
					'only' => __( 'Only', 'immonex-kickstart-elementor' ),
				],
				'label_block' => true,
				'scope'       => [ 'cf_filters' ],
			],
			'masters'                    => [
				'label'       => __( 'Master Properties', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => [
					''     => __( 'Default', 'immonex-kickstart-elementor' ),
					'yes'  => __( 'Yes', 'immonex-kickstart-elementor' ),
					'no'   => __( 'No', 'immonex-kickstart-elementor' ),
					'only' => __( 'Only', 'immonex-kickstart-elementor' ),
				],
				'label_block' => true,
				'scope'       => [ 'cf_filters' ],
			],
			'disable_links'              => [
				'label'       => __( 'Disable Property Detail Links', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => [
					''            => __( 'No', 'immonex-kickstart-elementor' ),
					'all'         => __( 'All', 'immonex-kickstart-elementor' ),
					'unavailable' => __( 'For unavailable Properties', 'immonex-kickstart-elementor' ),
					'references'  => __( 'For Reference Properties', 'immonex-kickstart-elementor' ),
				],
				'label_block' => true,
			],
			'force-lang'                 => [
				'label'       => __( 'Force Detail Page Language', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Required in special cases in multilingual environments only: Enter a two letter ISO 639-1 language code (de, en, fr ...) to force a specific language for the <strong>linked property detail pages</strong>.', 'immonex-kickstart-elementor' ),
				'label_block' => true,
			],
			'template'                   => [
				'label'       => __( 'Custom Template', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => $template_desc,
				'label_block' => true,
			],
			'list_element_style_section' => [
				'label' => __( 'List Elements', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'scope' => [ 'list_element_style' ],
			],
			'list_element_bg_color'      => [
				'label'     => __( 'Background Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-property-list-item, {{WRAPPER}} .inx-team-agent-list-item' => 'background-color: {{VALUE}};',
				],
				'scope'     => [ 'list_element_style' ],
			],
			'list_element_shadow'        => [
				'group_control' => [
					'type'    => \Elementor\Group_Control_Box_Shadow::get_type(),
					'options' => [
						'label'    => __( 'Box Shadow', 'immonex-kickstart-elementor' ),
						'selector' => '{{WRAPPER}} .inx-property-list-item.inx-property-list-item--card, {{WRAPPER}} .inx-team-agent-list-item.inx-team-agent-list-item--type--card',
						'exclude'  => [ 'box_shadow_position' ],
					],
				],
				'scope'         => [ 'list_element_style' ],
			],
			'list_element_border'        => [
				'group_control' => [
					'type'    => \Elementor\Group_Control_Border::get_type(),
					'options' => [
						'fields_options' => [
							'border' => [
								'label' => __( 'Border Type', 'immonex-kickstart-elementor' ),
							],
						],
						'selector'       => '{{WRAPPER}} .inx-property-list-item.inx-property-list-item--card, {{WRAPPER}} .inx-team-agent-list-item.inx-team-agent-list-item--type--card',
						'separator'      => 'before',
					],
				],
				'scope'         => [ 'list_element_style' ],
			],
			'list_element_border_radius' => [
				'label'      => __( 'Border Radius', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .inx-property-list-item.inx-property-list-item--card, {{WRAPPER}} .inx-team-agent-list-item.inx-team-agent-list-item--type--card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'scope'      => [ 'list_element_style' ],
			],
			'list_element_icon_color'    => [
				'label'     => __( 'Icon Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-core-detail-icon, {{WRAPPER}} .inx-team-agent-list-item__element-icon svg' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
				'scope'     => [ 'list_element_style' ],
			],
			'list_element_text_color'    => [
				'label'       => __( 'Text Color', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'description' => __( 'This color is <strong>not</strong> applied to links.', 'immonex-kickstart-elementor' ),
				'selectors'   => [
					'{{WRAPPER}} .inx-property-list-item, {{WRAPPER}} .inx-team-agent-list-item' => 'color: {{VALUE}};',
				],
				'scope'       => [ 'list_element_style' ],
			],
			'list_element_typography'    => [
				'group_control' => [
					'label'   => __( 'Typograhy', 'immonex-kickstart-elementor' ),
					'type'    => \Elementor\Group_Control_Typography::get_type(),
					'options' => [
						'selector' => '{{WRAPPER}} .inx-property-list-item, {{WRAPPER}} .inx-team-agent-list-item',
					],
				],
				'scope'         => [ 'list_element_style' ],
			],
		];

		foreach ( $tax_atts as $key => $label ) {
			$all_controls[ $key ] = [
				'label'       => $label,
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'scope'       => [ 'tax_filters' ],
			];
		}

		foreach ( $explicit_flags as $key => $flag ) {
			$all_controls[ $key ] = [
				'label'       => $flag['label'],
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => [
					''    => __( 'Default', 'immonex-kickstart-elementor' ),
					'yes' => __( 'Only', 'immonex-kickstart-elementor' ),
					'no'  => $flag['not_option'],
				],
				'label_block' => true,
				'scope'       => [ 'cf_filters' ],
			];
		}

		if ( ! empty( $args ) ) {
			foreach ( $args as $key => $control_args ) {
				if ( isset( $all_controls[ $key ] ) ) {
					if ( isset( $all_controls[ $key ]['group_control'] ) ) {
						if ( ! empty( $control_args['selector'] ) && ! empty( $all_controls[ $key ]['group_control']['options']['selector'] ) ) {
							$control_args['selector'] .= $all_controls[ $key ]['group_control']['options']['selector'] . ', ' . $control_args['selector'];
						}

						$all_controls[ $key ]['group_control']['options'] = array_merge( $all_controls[ $key ]['group_control']['options'], $control_args );
					} else {
						if ( ! empty( $control_args['selectors'] ) ) {
							$control_args['selectors'] = array_merge(
								$all_controls[ $key ]['selectors'],
								$control_args['selectors']
							);
						}

						$all_controls[ $key ] = array_merge( $all_controls[ $key ], $control_args );
					}
				}
			}
		}

		foreach ( $scopes as $scope ) {
			$controls = array_filter(
				$all_controls,
				function ( $control, $key ) use ( $scope ) {
					return ( ! empty( $control['scope'] ) && in_array( $scope, $control['scope'], true ) )
						|| $key === $scope;
				},
				ARRAY_FILTER_USE_BOTH
			);

			if ( empty( $controls ) ) {
				continue;
			}

			$section_started = false;

			foreach ( $controls as $key => $control ) {
				if (
					isset( $control['group_control'] )
					&& empty( $control['group_control']['options']['name'] )
				) {
					$control['group_control']['options']['name'] = $key;
				}

				if ( ! empty( $args[ $scope ]['prefix'] ) ) {
					$key = $args[ $scope ]['prefix'] . '_' . $key;

					if ( isset( $control['group_control']['options']['name'] ) ) {
						$control['group_control']['options']['name'] = $args[ $scope ]['prefix'] . '_' . $control['group_control']['options']['name'];
					}
				}

				if ( ! empty( $control['tab'] ) ) {
					if ( $this->get_current_section() ) {
						continue;
					}

					$this->start_controls_section(
						$key,
						[
							'label'     => $control['label'],
							'tab'       => $control['tab'],
							'condition' => ! empty( $control['condition'] ) ?
								$control['condition'] : null,
						]
					);
					$section_started = true;
				} elseif ( ! empty( $control['group_control'] ) ) {
					$this->add_group_control( $control['group_control']['type'], $control['group_control']['options'] );
				} elseif ( ! empty( $control['is_responsive'] ) ) {
					$this->add_responsive_control( $key, $control );
				} else {
					$this->add_control( $key, $control );
				}
			}

			if ( $section_started && $end_controls_section ) {
				$this->end_controls_section();
			}
		}
	} // add_default_controls

	/**
	 * Multiply given selector data to match all available device/display breakpoints.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $source_selector Source selector string.
	 * @param string   $styles          Styles to be applied.
	 * @param string[] $breakpoints     Breakpoints to be considered (optional).
	 *
	 * @return mixed[]|bool Template data array or false if unavailable.
	 */
	protected function get_responsive_selectors( $source_selector, $styles, $breakpoints = [] ) {
		$selectors = [];

		if ( empty( $breakpoints ) ) {
			$breakpoints = array_merge(
				[ 'desktop' ],
				array_keys( \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints() )
			);
		}

		foreach ( $breakpoints as $breakpoint ) {
			$selector = wp_sprintf( $source_selector, 'desktop' !== $breakpoint ? "-{$breakpoint}" : '' );
			$selectors[ wp_sprintf( 'body[data-elementor-device-mode="%2$s"] %1$s', $selector, $breakpoint ) ] = $styles;
		}

		return $selectors;
	} // get_responsive_selectors

	/**
	 * Add hidden control for adding the element's main class(es) during rendering.
	 *
	 * @since 1.0.0
	 */
	protected function add_main_class_control() {
		$classes = $this->get_main_classes();

		// Strip "inx-e-" prefix from the first class (following prefix_class argument is required).
		$classes[0] = preg_replace( '/^inx-e-/', '', $classes[0] );

		$this->add_control(
			'element_main_classes',
			[
				'type'         => \Elementor\Controls_Manager::HIDDEN,
				'default'      => implode( ' ', $classes ),
				'prefix_class' => 'inx-e-',
			]
		);
	} // add_main_class_control

	/**
	 * Add extended shortcode render attributes.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $ext_atts       Extended attributes.
	 * @param mixed[]  $template_data  Template data.
	 * @param string   $skin_subfolder Skin subfolder name (optional).
	 */
	protected function add_extended_sc_atts( $ext_atts, &$template_data, $skin_subfolder = '' ) {
		foreach ( $ext_atts as $att ) {
			if ( ! isset( $template_data['settings'][ $att ] ) ) {
				continue;
			}

			if (
				! empty( $template_data['settings'][ $att ] )
				|| ( is_string( $template_data['settings'][ $att ] ) && '0' === $template_data['settings'][ $att ] )
			) {
				if ( 'template' === $att ) {
					$template_data['settings'][ $att ] = ( $skin_subfolder ? $skin_subfolder . DIRECTORY_SEPARATOR : '' )
						. sanitize_file_name( basename( $template_data['settings'][ $att ] ) );
				}

				$this->add_render_attribute( 'shortcode', $att, $template_data['settings'][ $att ] );
			}
		}
	} // add_extended_sc_atts

	/**
	 * Generate a list of all system users for selection purposes.
	 *
	 * @since 1.0.0
	 *
	 * @return string[] User list (ID => Name/Login).
	 */
	protected function get_user_list() {
		if ( ! empty( $this->users ) ) {
			return $this->users;
		}

		$users = get_users();

		if ( ! empty( $users ) ) {
			foreach ( $users as $user ) {
				$name = $user->display_name ?
					$user->display_name . " ({$user->user_login})" :
					$user->user_login;

				$this->users[ $user->ID ] = $name;
			}
		}

		return $this->users;
	} // get_user_list

	/**
	 * Generate shortcode attribute value for author queries.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $settings Widget settings.
	 *
	 * @return string Comma-separated list of author user IDs or login names.
	 */
	protected function get_author_query_sc_attr_value( $settings ) {
		if ( empty( $settings['authors'] ) ) {
			return '';
		}

		if ( ! is_array( $settings['authors'] ) ) {
			$settings['authors'] = [ $settings['authors'] ];
		}

		if (
			isset( $settings['exclude_authors'] )
			&& 'yes' === $settings['exclude_authors']
		) {
			foreach ( $settings['authors'] as $id => $author ) {
				$settings['authors'][ $id ] = "-{$author}";
			}
		}

		return implode( ',', $settings['authors'] );
	} // get_author_query_sc_attr_value

} // class Widget_Base
