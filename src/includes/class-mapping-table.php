<?php
/**
 * Class Mapping_Table
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor;

/**
 * Mapping table related helper methods.
 */
class Mapping_Table {

	/**
	 * Array of Bootstrap Data
	 *
	 * @var mixed[]
	 */
	private $data;

	/**
	 * User Language
	 *
	 * @var string
	 */
	private $lang;

	/**
	 * Current Mappings
	 *
	 * @var mixed[]
	 */
	private $mappings = [];

	/**
	 * Current Mapping Select Options
	 *
	 * @var string[]
	 */
	private $select_options = [];

	/**
	 * Default Select Option Titles
	 *
	 * @var string[]
	 */
	private $default_select_option_titles = [];

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $bootstrap_data Plugin bootstrap data.
	 */
	public function __construct( $bootstrap_data ) {
		$this->data = is_array( $bootstrap_data ) ? $bootstrap_data : [];
	} // __construct

	/**
	 * Register mapping table related filters.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		$this->lang = substr( determine_locale(), 0, 2 );

		$this->default_select_option_titles = [
			'ausstattung'   => __( 'Features', 'immonex-kickstart-elementor' ),
			'epass'         => __( 'Energy Pass', 'immonex-kickstart-elementor' ),
			'flaechen'      => __( 'Areas', 'immonex-kickstart-elementor' ),
			'infrastruktur' => __( 'Infrastructure', 'immonex-kickstart-elementor' ),
			'kontakt'       => __( 'Contact', 'immonex-kickstart-elementor' ),
			'lage'          => __( 'Location', 'immonex-kickstart-elementor' ),
			'preise'        => __( 'Prices', 'immonex-kickstart-elementor' ),
			'sonstiges'     => __( 'Miscellaneous', 'immonex-kickstart-elementor' ),
			'zustand'       => __( 'Condition', 'immonex-kickstart-elementor' ),
		];

		add_filter( 'inx_elementor_mapping_select_options', [ $this, 'get_select_options' ], 10, 2 );
	} // init

	/**
	 * Return all names/terms (custom field items only) incl. related titles based
	 * the specified column of the current mapping table.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $select_options Empty array.
	 * @param string   $column         Mapping table column.
	 *
	 * @return string[] Associative array: name => title.
	 */
	public function get_select_options( $select_options, $column ) {
		if ( ! empty( $this->select_options[ $column ] ) ) {
			return $this->select_options[ $column ];
		}

		$mappings = $this->get_mappings();
		$options  = [];

		if ( ! empty( $mappings ) ) {
			foreach ( $mappings as $item ) {
				if (
					'custom_field' !== $item['type']
					|| empty( $item[ $column ] )
					|| ( 'destination' === $column && '+' === substr( $item['destination'], -1 ) )
				) {
					continue;
				}

				$divider = 'source' === $column ? '->' : '.';

				if ( 'source' === $column && false !== strpos( $item[ $column ], '=' ) ) {
					$wildcard_part = substr( $item[ $column ], 0, strpos( $item[ $column ], '=' ) );

					if ( ! isset( $options[ $wildcard_part ] ) ) {
						$options[ "{$wildcard_part}=*" ] = "{$wildcard_part} " . $this->get_select_option_title( $item, $column, true );
					}

					continue;
				} elseif ( 'source' === $column && false !== strpos( $item[ $column ], ':' ) ) {
					$wildcard_part = substr( $item[ $column ], 0, strpos( $item[ $column ], ':' ) );

					if ( ! isset( $options[ $wildcard_part ] ) ) {
						$options[ "{$wildcard_part}:*" ] = "{$wildcard_part} (" . __( 'various', 'immonex-kickstart-elementor' ) . ')';
					}

					continue;
				} elseif ( false !== strpos( $item[ $column ], $divider ) ) {
					$parts = explode( $divider, $item[ $column ] );
					array_pop( $parts );

					if ( ! empty( $parts ) ) {
						$part_chain = '';

						foreach ( $parts as $i => $part ) {
							$part_chain   .= ( $i > 0 ? $divider : '' ) . $part;
							$wildcard_part = "{$part_chain}{$divider}*";

							if ( ! isset( $options[ $wildcard_part ] ) ) {
								$options[ $wildcard_part ] = $wildcard_part;
							}
						}
					}
				}

				if ( ! empty( $options[ $item[ $column ] ] ) ) {
					if (
						! isset( $this->default_select_option_titles[ $item[ $column ] ] )
						&& $options[ $item[ $column ] ] !== $this->get_select_option_title( $item, $column )
					) {
						$parent_title                = false !== strpos( $item['source'], '=' ) ? $this->get_select_option_title( $item, $column, true ) : '';
						$options[ $item[ $column ] ] = $item[ $column ] . ( $parent_title ? $parent_title : ' (' . __( 'various', 'immonex-kickstart-elementor' ) . ')' );
					}
					continue;
				}

				$options[ $item[ $column ] ] = $this->get_select_option_title( $item, $column );
			}
		}

		ksort( $options );

		$this->select_options[ $column ] = $options;

		return $options;
	} // get_select_options

	/**
	 * Return the OpenImmo mapping data parsed from the current mapping table.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[] Mapping data.
	 */
	public function get_mappings() {
		if ( ! empty( $this->mappings ) ) {
			return $this->mappings;
		}

		$this->parse_mapping_file();

		return $this->mappings;
	} // get_mappings

	/**
	 * Generate an option title for use in select boxes.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $item        Mapping item.
	 * @param string  $column      Column name.
	 * @param bool    $parent_only If true, only return the parent part of the title
	 *                             in brackets (false by default).
	 *
	 * @return string Option title.
	 */
	private function get_select_option_title( $item, $column, $parent_only = false ) {
		if ( isset( $this->default_select_option_titles[ $item[ $column ] ] ) ) {
			return $item[ $column ] . " ({$this->default_select_option_titles[ $item[ $column ] ]})";
		}

		$title = $item[ "parent {$this->lang}" ] ?
			$item[ "parent {$this->lang}" ] :
			$item['parent'];

		if ( $parent_only ) {
			return $title ? " [{$title}]" : '';
		}

		if ( $title && $item[ "title {$this->lang}" ] ) {
			$title .= ': ' . $item[ "title {$this->lang}" ];
		} elseif ( $title && $item['title'] ) {
			$title .= ': ' . $item['title'];
		}

		return $item[ $column ] . ( $title ? " [{$title}]" : '' );
	} // get_select_option_title

	/**
	 * Parse the current OpenImmo2WP (or included) mapping file.
	 *
	 * @since 1.0.0
	 */
	private function parse_mapping_file() {
		$current_mapping_file = $this->get_current_mapping_file();
		if ( ! file_exists( $current_mapping_file ) ) {
			return;
		}

		$raw_mappings = [];

		// phpcs:disable
		$f   = fopen( $current_mapping_file, 'r' );
		$row = 0;
		while ( false !== ( $row_values = fgetcsv( $f, 1000, ',', '"' ) ) ) {
			/**
			 * Loop through mapping file lines (ignore empty and comment lines).
			 */

			if ( empty( $row_values[0] ) || '#' === $row_values[0][0] ) {
				continue;
			}

			++$row;
			if ( 1 === $row ) {
				// First line: split column types and continue.
				$column_types = $row_values;
				continue;
			}

			$row_values_named = array();
			foreach ( $row_values as $i_row => $value ) {
				// Create a mapping record of attribute-value pairs.
				if ( isset( $column_types[ $i_row ] ) ) {
					$row_values_named[ strtolower( $column_types[ $i_row ] ) ] = trim( $value );
				}
			}

			$raw_mappings[] = $row_values_named;
		}
		fclose( $f );
		// phpcs:enable

		if ( count( $raw_mappings ) > 0 ) {
			$this->mappings = [];
			$cnt            = 0;

			foreach ( $raw_mappings as $i => $mapping ) {
				if ( ! isset( $mapping['type'] ) || ! isset( $mapping['source'] ) ) {
					continue;
				}

				/**
				 * Loop through "raw mappings" and create the real mapping table.
				 */

				$this->mappings[ $cnt ] = [
					'type'        => $mapping['type'],
					'source'      => $mapping['source'],
					'name'        => ! empty( $mapping['name'] ) ? $mapping['name'] : '',
					'group'       => ! empty( $mapping['group'] ) ? $mapping['group'] : '',
					'destination' => ! empty( $mapping['destination'] ) ? $mapping['destination'] : '',
				];

				foreach ( $mapping as $field => $value ) {
					if (
						'title' === substr( $field, 0, 5 )
						|| 'parent' === substr( $field, 0, 6 )
					) {
						// Add all title and parent fields (including multiple languages).
						$this->mappings[ $cnt ][ $field ] = $mapping[ $field ];
					}
				}

				++$cnt;
			}
		}
	} // parse_mapping_file

	/**
	 * Determine and return the path of the current OpenImmo2WP mapping table
	 * file (default file included in this plugin if undeterminable).
	 *
	 * @since 1.0.0
	 *
	 * @return string Mapping file path.
	 */
	private function get_current_mapping_file() {
		$current_mapping_file = apply_filters( 'immonex_oi2wp_current_mapping_file', '' );

		if ( $current_mapping_file && file_exists( $current_mapping_file ) ) {
			// phpcs:ignore
			if ( ! (bool) preg_match( '//u', file_get_contents( $current_mapping_file ) ) ) {
				// User-defined mapping file encoding is not proper UTF-8.
				$current_mapping_file = '';
			}
		}

		if ( ! $current_mapping_file ) {
			$current_mapping_file = trailingslashit( $this->data['plugin_dir'] ) . 'assets/kickstart.csv';
		}

		return $current_mapping_file;
	} // get_current_mapping_file

} // class Mapping_Table
