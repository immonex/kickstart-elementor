<?php
/**
 * Class Kickstart_for_Elementor
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 */
class Kickstart_For_Elementor extends \immonex\WordPressFreePluginCore\V2_13_4\Base {

	const PLUGIN_NAME                = 'immonex Kickstart for Elementor';
	const ADDON_NAME                 = 'Elementor';
	const ADDON_TAB_ID               = 'addon_elementor';
	const PLUGIN_PREFIX              = 'inxkickel_';
	const PUBLIC_PREFIX              = 'inxkickel-';
	const TEXTDOMAIN                 = 'immonex-kickstart-for-elementor';
	const PLUGIN_VERSION             = '1.4.0';
	const PLUGIN_VERSION_BYNAME      = 'Crushed Ice';
	const PLUGIN_HOME_URL            = 'https://immonex.dev/wordpress-immobilien-plugin/immonex-kickstart-for-elementor';
	const PLUGIN_DOC_URLS            = [
		'de' => 'https://docs.immonex.de/kickstart-for-elementor/',
	];
	const PLUGIN_SUPPORT_URLS        = [
		'de' => 'https://wordpress.org/support/plugin/immonex-kickstart-for-elementor',
	];
	const PLUGIN_DEV_URLS            = [
		'de' => 'https://github.com/immonex/kickstart-for-elementor',
	];
	const OPTIONS_LINK_MENU_LOCATION = false;
	const PARENT_PLUGIN_MAIN_CLASS   = '\immonex\Kickstart\Kickstart';
	const SUPPORTED_POST_TYPES       = [
		'inx_property' => [
			'plain_key' => 'property',
			'name'      => '',
		],
		'inx_agency'   => [
			'plain_key' => 'agency',
			'name'      => '',
		],
		'inx_agent'    => [
			'plain_key' => 'agent',
			'name'      => '',
		],
	];

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
	 * Supported Post Types
	 *
	 * @var mixed[]
	 */
	private $supported_post_types = [];

	/**
	 * Supported Taxonomy Terms
	 *
	 * @var mixed[]
	 */
	private $supported_tax_terms = [];

	/**
	 * Elementor Bootstrap Instance
	 *
	 * @var \immonex\Kickstart\ForElementor\Elementor_Bootstrap
	 */
	private $elementor_bootstrap = [];

	/**
	 * Here we go!
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_slug Plugin name slug.
	 */
	public function __construct( $plugin_slug ) {
		parent::__construct( $plugin_slug, self::TEXTDOMAIN );

		$this->bootstrap_data['supported_post_types'] = self::SUPPORTED_POST_TYPES;

		$this->elementor_bootstrap = new Elementor_Bootstrap( $this->bootstrap_data );
		$this->elementor_bootstrap->init();
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
		/**
		 * TEMPORARY: Check if the plugin is installed with its deprecated
		 * name/slug and remove it if so.
		 */

		$deprecated_plugin_slug_dir       = WP_PLUGIN_DIR . '/immonex-kickstart-elementor';
		$deprecated_plugin_slug_main_file = 'immonex-kickstart-elementor/immonex-kickstart-elementor.php';

		if ( is_plugin_active( $deprecated_plugin_slug_main_file ) ) {
			deactivate_plugins( $deprecated_plugin_slug_main_file );
		}

		if (
			file_exists( $deprecated_plugin_slug_dir )
			&& realpath( $deprecated_plugin_slug_dir ) === $deprecated_plugin_slug_dir
		) {
			$result = delete_plugins( [ $deprecated_plugin_slug_main_file ] );
		}

		/**
		 * TEMPORARY: Delete demo images with outdated tags (custom fields).
		 */

		$args = [
			'post_type'   => 'attachment',
			'numberposts' => -1,
			'fields'      => 'ids',
			'meta_query'  => [
				[
					'key'     => '_inxkickel_demo_content',
					'compare' => 'EXISTS',
				],
			],
		];
		$ids  = get_posts( $args );

		if ( ! empty( $ids ) ) {
			foreach ( $ids as $id ) {
				wp_delete_attachment( $id, true );
				clean_post_cache( $id );
			}
		}

		parent::activate_plugin_single_site( true, false );

		do_action( 'immonex_core_after_activation', $this->plugin_slug ); // phpcs:ignore -- Common framework action hook for all immonex plugins.
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
			'inxkickel_get_plugin_dir',
			function ( $plugin_dir ) { // phpcs:ignore
				return $this->plugin_dir;
			}
		);

		// Internal filter.
		add_filter(
			'inxkickel_get_utils',
			function ( $utils ) { // phpcs:ignore
				return $this->utils;
			}
		);

		if ( is_admin() ) {
			add_filter( 'immonex-kickstart_option_tabs', [ $this, 'extend_tabs' ], 15 );
			add_filter( 'immonex-kickstart_option_sections', [ $this, 'extend_sections' ], 15 );
			add_filter( 'immonex-kickstart_option_fields', [ $this, 'extend_fields' ], 15 );
		}

		add_filter( 'inxkickel_supported_post_types', [ $this, 'get_supported_post_types' ] );
		add_filter( 'inxkickel_supported_tax_terms', [ $this, 'get_supported_tax_terms' ] );

		do_action( 'immonex_core_after_init', $this->plugin_slug ); // phpcs:ignore -- Common framework action hook for all immonex plugins.
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

		do_action( 'immonex_core_after_init_admin', $this->plugin_slug ); // phpcs:ignore -- Common framework action hook for all immonex plugins.
	} // init_plugin_admin

	/**
	 * Add tabs to an options page of another compatible plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $tabs Original tab array.
	 *
	 * @return mixed[] Extended tab array.
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

		do_action( 'immonex_plugin_options_add_extension_tabs', $this->plugin_slug, $addon_tabs ); // phpcs:ignore -- Common framework action hook for all immonex plugins.

		return array_merge( $tabs, $addon_tabs );
	} // extend_tabs

	/**
	 * Add configuration sections to an options page/tab of another compatible plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $sections Original sections array.
	 *
	 * @return mixed[] Extended sections array.
	 */
	public function extend_sections( $sections ) {
		$prefix = self::ADDON_TAB_ID . '_';

		$addon_sections = [
			"{$prefix}layout" => [
				'title'       => __( 'Layout & Design', 'immonex-kickstart-for-elementor' ),
				'description' => '',
				'tab'         => self::ADDON_TAB_ID,
			],
		];

		do_action( 'immonex_plugin_options_add_extension_sections', $this->plugin_slug, $addon_sections ); // phpcs:ignore -- Common framework action hook for all immonex plugins.

		return array_merge( $sections, $addon_sections );
	} // extend_sections

	/**
	 * Add configuration fields to an options page/section of another compatible plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $fields Original fields array.
	 *
	 * @return mixed[] Extended fields array.
	 */
	public function extend_fields( $fields ) {
		$prefix = self::ADDON_TAB_ID . '_';

		$addon_fields = [
			[
				'name'    => 'skin',
				'type'    => 'select',
				'label'   => __( 'Skin', 'immonex-kickstart-for-elementor' ),
				'section' => "{$prefix}layout",
				'args'    => [
					'plugin_slug' => $this->plugin_slug,
					'option_name' => $this->plugin_options_name,
					'description' => __( 'A skin is a set of templates files (PHP, Twig, CSS, JS etc.) and related resources like images and fonts for plugin frontend elements and pages.', 'immonex-kickstart-for-elementor' ),
					'options'     => $this->utils['template']->get_frontend_skins(),
					'value'       => $this->plugin_options['skin'],
				],
			],
		];

		do_action( 'immonex_plugin_options_add_extension_fields', $this->plugin_slug, $addon_fields ); // phpcs:ignore -- Common framework action hook for all immonex plugins.

		return array_merge( $fields, $addon_fields );
	} // extend_fields

	/**
	 * Return an array (key => plain key + translated plural output name) of all
	 * Kickstart-related post types supported by this plugin (filter callback).
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $post_types Empty array.
	 *
	 * @return mixed[] Supported post types.
	 */
	public function get_supported_post_types( $post_types ) {
		if ( ! empty( $this->supported_post_types ) ) {
			return $this->supported_post_types;
		}

		$names = [
			'inx_property' => __( 'Properties', 'immonex-kickstart-for-elementor' ),
			'inx_agency'   => __( 'Agencies', 'immonex-kickstart-for-elementor' ),
			'inx_agent'    => __( 'Agents', 'immonex-kickstart-for-elementor' ),
		];

		$supported_post_types = self::SUPPORTED_POST_TYPES;

		foreach ( $supported_post_types as $key => $post_type ) {
			if ( isset( $names[ $key ] ) ) {
				$supported_post_types[ $key ]['name'] = $names[ $key ];
			}
		}

		$this->supported_post_types = $supported_post_types;

		return $supported_post_types;
	} // get_supported_post_types

	/**
	 * Return a structured array of all supported taxonomy tags related to
	 * Kickstart custom post types (filter callback).
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $tax_terms Empty array.
	 *
	 * @return mixed[] Supported taxonomy terms.
	 */
	public function get_supported_tax_terms( $tax_terms ) {
		if ( ! empty( $this->supported_tax_terms ) ) {
			return $this->supported_tax_terms;
		}

		$supported_post_types = $this->get_supported_post_types( [] );
		$supported_tax_terms  = [];

		foreach ( $supported_post_types as $post_type_key => $post_type ) {
			$taxonomies = get_object_taxonomies( $post_type_key, 'objects' );

			if ( empty( $taxonomies ) ) {
				continue;
			}

			foreach ( $taxonomies as $taxonomy_key => $taxonomy ) {
				if ( 'inx_' !== substr( $taxonomy_key, 0, 4 ) ) {
					continue;
				}

				$terms = get_terms(
					[
						'taxonomy'   => $taxonomy_key,
						'hide_empty' => false,
					]
				);

				if ( empty( $terms ) || is_wp_error( $terms ) ) {
					continue;
				}

				$terms   = $this->maybe_filter_and_add_ancestor_terms( $terms );
				$options = $this->get_hierarchical_option_list( $terms, 0, 0 );

				foreach ( $options as $term_slug => $term_name ) {
					if ( ! isset( $supported_tax_terms[ $post_type_key ] ) ) {
						$supported_tax_terms[ $post_type_key ] = [];
					}
					if ( ! isset( $supported_tax_terms[ $post_type_key ][ $taxonomy_key ] ) ) {
						$supported_tax_terms[ $post_type_key ][ $taxonomy_key ] = [];
					}

					$supported_tax_terms[ $post_type_key ][ $taxonomy_key ][ $term_slug ] = [
						'post_type_name' => $post_type['name'],
						'taxonomy_name'  => $taxonomy->labels->singular_name,
						'name'           => $term_name,
					];
				}
			}
		}

		$this->supported_tax_terms = $supported_tax_terms;

		return $supported_tax_terms;
	} // get_supported_post_types

	/**
	 * Maybe add ancestor taxonomy terms before building hierarchical
	 * option lists.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Term[] $terms Array of WP term objects.
	 *
	 * @return \WP_Term[] Possibly extended term array.
	 */
	private function maybe_filter_and_add_ancestor_terms( $terms ) {
		if ( empty( $terms ) ) {
			return $terms;
		}

		$taxonomy     = $terms[0]->taxonomy;
		$term_ids     = [];
		$add_term_ids = [];

		foreach ( $terms as $term ) {
			$term_ids[] = $term->term_id;
		}

		foreach ( $terms as $i => $term ) {
			$ancestor_ids = get_ancestors( $term->term_id, $term->taxonomy, 'taxonomy' );

			if ( ! empty( $ancestor_ids ) ) {
				foreach ( $ancestor_ids as $id ) {
					if ( $id && ! in_array( $id, $term_ids, true ) ) {
						$add_term_ids[] = $id;
					}
				}
			}
		}

		$add_term_ids = array_unique( $add_term_ids );

		if ( ! empty( $add_term_ids ) ) {
			$terms = array_merge(
				$terms,
				get_terms(
					[
						'taxonomy' => $taxonomy,
						'include'  => $add_term_ids,
					]
				)
			);

			uasort(
				$terms,
				function ( $a, $b ) {
					if ( $a->name === $b->name ) {
						return 0;
					}

					return $a->name < $b->name ? -1 : 1;
				}
			);
		}

		return $terms;
	} // maybe_filter_and_add_ancestor_terms

	/**
	 * Recursively create and return an hierarchical option list.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Term[] $terms     WP term objects.
	 * @param int        $parent_id Parent term ID (optional, defaults to 0).
	 * @param int        $level     Start level (optional, defaults to 0).
	 *
	 * @return mixed[] Term options.
	 */
	private function get_hierarchical_option_list( $terms, $parent_id = 0, $level = 0 ) {
		$level_options = [];

		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( $term->parent === $parent_id ) {
					$level_options[ $term->slug ] = str_repeat(
						'&ndash;',
						$level
					) . " {$term->name}";

					$level_options = array_merge(
						$level_options,
						$this->get_hierarchical_option_list(
							$terms,
							$term->term_id,
							$level + 1
						)
					);
				}
			}
		}

		return $level_options;
	} // get_hierarchical_option_list

} // class Kickstart_for_Elementor
