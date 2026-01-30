<?php
/**
 * Class Kickstart_Elementor
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 */
class Kickstart_Elementor extends \immonex\WordPressFreePluginCore\V2_6_4\Base {

	const PLUGIN_NAME                = 'immonex Kickstart Elementor';
	const ADDON_NAME                 = 'Elementor';
	const ADDON_TAB_ID               = 'addon_elementor';
	const PLUGIN_PREFIX              = 'inx_elementor_';
	const PUBLIC_PREFIX              = 'inx-elementor-';
	const TEXTDOMAIN                 = 'immonex-kickstart-elementor';
	const PLUGIN_VERSION             = '1.0.0';
	const PLUGIN_VERSION_BYNAME      = 'Ice';
	const PLUGIN_HOME_URL            = 'https://immonex.dev/';
	const PLUGIN_DOC_URLS            = [
		'de' => '',
	];
	const PLUGIN_SUPPORT_URLS        = [
		'de' => 'https://plugins.inveris.de/support/',
	];
	const PLUGIN_DEV_URLS            = [
		'de' => 'https://immonex.dev/',
	];
	const OPTIONS_LINK_MENU_LOCATION = false;
	const PARENT_PLUGIN_MAIN_CLASS   = '\immonex\Kickstart\Kickstart';

	/**
	 * Plugin Options
	 *
	 * @var mixed[]
	 */
	protected $plugin_options = [
		'plugin_version' => self::PLUGIN_VERSION,
		'skin'           => 'default',
	];

	/**
	 * Active Add-on Plugins
	 *
	 * @var bool[]
	 */
	protected $active_addons = [];

	/**
	 * Here we go!
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_slug Plugin name slug.
	 */
	public function __construct( $plugin_slug ) {
		parent::__construct( $plugin_slug, self::TEXTDOMAIN );

		$elementor_bootstrap = new Elementor_Bootstrap( $this->bootstrap_data );
		$elementor_bootstrap->init();
	} // __construct

	/**
	 * Perform activation tasks for a single site.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $fire_before_hook Flag to indicate if an action hook should fire
	 *                               before the actual method execution (optional,
	 *                               true by default).
	 * @param bool $fire_after_hook  Flag to indicate if an action hook should fire
	 *                               after the actual method execution (optional,
	 *                               true by default).
	 */
	protected function activate_plugin_single_site( $fire_before_hook = true, $fire_after_hook = true ) {
		parent::activate_plugin_single_site( true, false );

		// phpcs:ignore
		do_action( 'immonex_core_after_activation', $this->plugin_slug );
	} // activate_plugin_single_site

	/**
	 * Perform common initialization tasks.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $fire_before_hook Flag to indicate if an action hook should fire
	 *                               before the actual method execution (optional,
	 *                               true by default).
	 * @param bool $fire_after_hook  Flag to indicate if an action hook should fire
	 *                               after the actual method execution (optional,
	 *                               true by default).
	 */
	public function init_plugin( $fire_before_hook = true, $fire_after_hook = true ) {
		if ( ! $this->is_parent_plugin_active ) {
			return;
		}

		$this->settings_page = 'admin.php?page=immonex-kickstart_settings&tab=' . self::ADDON_TAB_ID;

		parent::init_plugin( true, false );

		// Internal filter.
		add_filter(
			'inx_elementor_get_plugin_dir',
			function ( $plugin_dir ) {
				return $this->plugin_dir;
			}
		);

		// Internal filter.
		add_filter(
			'inx_elementor_get_utils',
			function ( $utils ) {
				return $this->utils;
			}
		);

		if ( is_admin() ) {
			add_filter( 'immonex-kickstart_option_tabs', [ $this, 'extend_tabs' ], 15 );
			add_filter( 'immonex-kickstart_option_sections', [ $this, 'extend_sections' ], 15 );
			add_filter( 'immonex-kickstart_option_fields', [ $this, 'extend_fields' ], 15 );
		}

		do_action( 'immonex_core_after_init', $this->plugin_slug );
	} // init_plugin

	/**
	 * Initialize the plugin (admin/backend only).
	 *
	 * @since 1.0.0
	 *
	 * @param bool $fire_before_hook Flag to indicate if an action hook should fire
	 *                               before the actual method execution (optional,
	 *                               true by default).
	 * @param bool $fire_after_hook  Flag to indicate if an action hook should fire
	 *                               after the actual method execution (optional,
	 *                               true by default).
	 */
	public function init_plugin_admin( $fire_before_hook = true, $fire_after_hook = true ) {
		parent::init_plugin_admin( true, false );

		$mapping_table = new Mapping_Table( $this->bootstrap_data );
		$mapping_table->init();

		do_action( 'immonex_core_after_init_admin', $this->plugin_slug );
	} // init_plugin_admin

	/**
	 * Add tabs to an options page of another compatible plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param array $tabs Original tab array.
	 *
	 * @return array Extended tab array.
	 */
	public function extend_tabs( $tabs ) {
		$addon_footer_infos = implode( ' | ', $this->get_plugin_footer_infos() );

		$addon_tabs = [
			self::ADDON_TAB_ID => [
				'title'      => self::ADDON_NAME,
				'content'    => '',
				'attributes' => [
					'plugin_slug'     => $this->plugin_slug,
					'tabbed_sections' => true,
					'footer_info'     => $addon_footer_infos,
					'is_addon_tab'    => true,
				],
			],
		];

		// phpcs:ignore
		$addon_tabs = apply_filters( "{$this->plugin_slug}_option_tabs", $addon_tabs );

		do_action( 'immonex_plugin_options_add_extension_tabs', $this->plugin_slug, $addon_tabs );

		return array_merge( $tabs, $addon_tabs );
	} // extend_tabs

	/**
	 * Add configuration sections to an options page/tab of another compatible plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param array $sections Original sections array.
	 *
	 * @return array Extended sections array.
	 */
	public function extend_sections( $sections ) {
		$prefix = self::ADDON_TAB_ID . '_';

		$addon_sections = [
			"{$prefix}layout" => [
				'title'       => __( 'Layout & Design', 'immonex-kickstart-elementor' ),
				'description' => '',
				'tab'         => self::ADDON_TAB_ID,
			],
		];

		// phpcs:ignore
		$addon_sections = apply_filters( "{$this->plugin_slug}_option_sections", $addon_sections );

		do_action( 'immonex_plugin_options_add_extension_sections', $this->plugin_slug, $addon_sections );

		return array_merge( $sections, $addon_sections );
	} // extend_sections

	/**
	 * Add configuration fields to an options page/section of another compatible plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields Original fields array.
	 *
	 * @return array Extended fields array.
	 */
	public function extend_fields( $fields ) {
		$prefix = self::ADDON_TAB_ID . '_';

		$addon_fields = [
			[
				'name'    => 'skin',
				'type'    => 'select',
				'label'   => __( 'Skin', 'immonex-kickstart-elementor' ),
				'section' => "{$prefix}layout",
				'args'    => [
					'plugin_slug' => $this->plugin_slug,
					'option_name' => $this->plugin_options_name,
					'description' => __( 'A skin is a set of templates files (PHP, Twig, CSS, JS etc.) and related resources like images and fonts for plugin frontend elements and pages.', 'immonex-kickstart-elementor' ),
					'options'     => $this->utils['template']->get_frontend_skins(),
					'value'       => $this->plugin_options['skin'],
				],
			],
		];

		// phpcs:ignore
		$addon_fields = apply_filters( "{$this->plugin_slug}_option_fields", $addon_fields );

		do_action( 'immonex_plugin_options_add_extension_fields', $this->plugin_slug, $addon_fields );

		return array_merge( $fields, $addon_fields );
	} // extend_fields

} // class Kickstart_Elementor
