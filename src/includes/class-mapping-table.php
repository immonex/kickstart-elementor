<?php
/**
 * Class Mapping_Table
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Mapping table related helper methods.
 */
class Mapping_Table {

	const SUB_TITLES_MAX_LENGTH = 40;

	/**
	 * Bootstrap Data
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
	 * Mapping File Last Modified Timestamp (Cache)
	 *
	 * @var int
	 */
	private $mapping_file_mtime = 0;

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
	 * Wildcard Mapping Names (Cache)
	 *
	 * @var string[]
	 */
	private $wildcard_names = [];

	/**
	 * Wildcard Mapping Names to exclude
	 *
	 * @var string[]
	 */
	private $exclude_wildcard_names = [
		'geo.*',
		'freitexte.*',
		'preise.nebenkostenprom2von',
		'preise.nebenkostenprom2bis',
		'preise.nebenkostenprom2von:nebenkostenprom2bis',
		'preise.gesamtkostenprom2von',
		'preise.gesamtkostenprom2bis',
		'kontaktperson.email_sonstige',
		'kontaktperson.email_sonstige.*',
		'kontaktperson.tel_sonstige',
		'kontaktperson.tel_sonstige.*',
		'verwaltung_techn.*',
		'verwaltung_objekt.*',
	];

	/**
	 * Wildcard Mapping Sources to exclude
	 *
	 * @var string[]
	 */
	private $exclude_wildcard_sources = [
		'geo->*',
		'freitexte->*',
		'verwaltung_techn->*',
		'verwaltung_objekt->*',
	];

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
			'name|freitexte.*'                             => __( 'Description Texts', 'immonex-kickstart-for-elementor' ),
			'name|freitexte.lage'                          => __( 'Location Description', 'immonex-kickstart-for-elementor' ),
			'name|freitexte.ausstatt_beschr'               => __( 'Features Description', 'immonex-kickstart-for-elementor' ),
			'name|freitexte.sonstige_angaben'              => __( 'Miscellaneous', 'immonex-kickstart-for-elementor' ) .
				' (' . __( 'Description Text', 'immonex-kickstart-for-elementor' ) . ')',
			'name|verwaltung_techn.objektnr_extern'        => __( 'Property #', 'immonex-kickstart-for-elementor' )
				. ' (' . __( 'external', 'immonex-kickstart-for-elementor' ) . ')',
			'name|primaerpreis'                            => __( 'Primary Price', 'immonex-kickstart-for-elementor' ),
			'name|primaerflaeche'                          => __( 'Primary Area', 'immonex-kickstart-for-elementor' ),
			'name|primaeranzahl_zimmer'                    => __( 'Primary Number of Rooms', 'immonex-kickstart-for-elementor' ),
			'name|primaeranzahl_einheiten'                 => __( 'Primary Number of Units', 'immonex-kickstart-for-elementor' ),
			'name|gewerbeflaeche'                          => __( 'Commercial Area', 'immonex-kickstart-for-elementor' ),
			'name|verkaufsflaeche'                         => __( 'Retail Area', 'immonex-kickstart-for-elementor' ),
			'name|bueroflaeche'                            => __( 'Office Area', 'immonex-kickstart-for-elementor' ),
			'name|ausstattung.*'                           => __( 'Features', 'immonex-kickstart-for-elementor' ),
			'name|bieterverfahren.*'                       => __( 'Bidding Process', 'immonex-kickstart-for-elementor' ),
			'name|flaechen.*'                              => __( 'Areas', 'immonex-kickstart-for-elementor' ),
			'name|geo.*'                                   => __( 'Geo Data', 'immonex-kickstart-for-elementor' ),
			'name|infrastruktur.*'                         => __( 'Infrastructure', 'immonex-kickstart-for-elementor' ),
			'name|infrastruktur.distanzen.*'               => __( 'Distances', 'immonex-kickstart-for-elementor' ),
			'name|infrastruktur.distanzen_sport.*'         => wp_sprintf( '%s (%s)', __( 'Distances', 'immonex-kickstart-for-elementor' ), __( 'Sport', 'immonex-kickstart-for-elementor' ) ),
			'name|kontaktperson.*'                         => __( 'Contact Data', 'immonex-kickstart-for-elementor' ),
			'name|kontaktperson.email_sonstige.emailart.*' => __( 'Other Email Addresses', 'immonex-kickstart-for-elementor' ),
			'name|kontaktperson.tel_sonstige.telefonart.*' => __( 'Other Phone Numbers', 'immonex-kickstart-for-elementor' ),
			'name|preise.*'                                => __( 'Prices', 'immonex-kickstart-for-elementor' ),
			'name|preise.mieteinnahmen_ist.*'              => __( 'Actual Rental Revenues', 'immonex-kickstart-for-elementor' ),
			'name|preise.mieteinnahmen_soll.*'             => __( 'Target Rental Revenues', 'immonex-kickstart-for-elementor' ),
			'name|versteigerung.*'                         => __( 'Foreclosure Auction', 'immonex-kickstart-for-elementor' ),
			'name|verwaltung_objekt.*'                     => __( 'Property Management', 'immonex-kickstart-for-elementor' ),
			'name|zustand_angaben.*'                       => __( 'Condition Details', 'immonex-kickstart-for-elementor' ),
			'name|zustand_angaben.energiepass.*'           => __( 'Energy Pass Details', 'immonex-kickstart-for-elementor' ),
			'group|ausstattung'                            => __( 'Features', 'immonex-kickstart-for-elementor' ),
			'group|epass'                                  => __( 'Energy Pass', 'immonex-kickstart-for-elementor' ),
			'group|flaechen'                               => __( 'Areas', 'immonex-kickstart-for-elementor' ),
			'group|infrastruktur'                          => __( 'Infrastructure', 'immonex-kickstart-for-elementor' ),
			'group|kontakt'                                => __( 'Contact', 'immonex-kickstart-for-elementor' ),
			'group|lage'                                   => __( 'Location', 'immonex-kickstart-for-elementor' ),
			'group|preise'                                 => __( 'Prices', 'immonex-kickstart-for-elementor' ),
			'group|sonstiges'                              => __( 'Miscellaneous', 'immonex-kickstart-for-elementor' ),
			'group|zustand'                                => __( 'Condition', 'immonex-kickstart-for-elementor' ),
			'destination|_inx_primary_price'               => __( 'Primary Price', 'immonex-kickstart-for-elementor' ),
			'destination|_inx_primary_area'                => __( 'Primary Area', 'immonex-kickstart-for-elementor' ),
			'destination|_inx_primary_rooms'               => __( 'Primary Number of Rooms', 'immonex-kickstart-for-elementor' ),
			'destination|_inx_primary_units'               => __( 'Primary Number of Units', 'immonex-kickstart-for-elementor' ),
			'destination|_inx_commercial_area'             => __( 'Commercial Area', 'immonex-kickstart-for-elementor' ),
			'destination|_inx_features_descr'              => __( 'Features Description', 'immonex-kickstart-for-elementor' ),
			'destination|_inx_location_descr'              => __( 'Location Description', 'immonex-kickstart-for-elementor' ),
			'destination|_inx_office_area'                 => __( 'Office Area', 'immonex-kickstart-for-elementor' ),
			'destination|_inx_retail_area'                 => __( 'Retail Area', 'immonex-kickstart-for-elementor' ),
			'source|freitexte->*'                          => __( 'Description Texts', 'immonex-kickstart-for-elementor' ),
			'source|freitexte->lage'                       => __( 'Location Description', 'immonex-kickstart-for-elementor' ),
			'source|freitexte->ausstatt_beschr'            => __( 'Features Description', 'immonex-kickstart-for-elementor' ),
			'source|ausstattung->*'                        => __( 'Features', 'immonex-kickstart-for-elementor' ),
			'source|ausstattung->befeuerung:*'             => __( 'Firing', 'immonex-kickstart-for-elementor' ),
			'source|geo->*'                                => __( 'Geo Data', 'immonex-kickstart-for-elementor' ),
			'source|geo->etage*'                           => _x( 'Floor', 'Building Level', 'immonex-kickstart-for-elementor' ),
			'source|infrastruktur->*'                      => __( 'Infrastructure', 'immonex-kickstart-for-elementor' ),
			'source|infrastruktur->distanzen:*'            => __( 'Distances', 'immonex-kickstart-for-elementor' ),
			'source|infrastruktur->distanzen_sport*'       => wp_sprintf( '%s (%s)', __( 'Distances', 'immonex-kickstart-for-elementor' ), __( 'Sport', 'immonex-kickstart-for-elementor' ) ),
			'source|kontaktperson->*'                      => __( 'Contact Data', 'immonex-kickstart-for-elementor' ),
			'source|kontaktperson->email_sonstige*'        => __( 'Other Email Addresses', 'immonex-kickstart-for-elementor' ),
			'source|kontaktperson->tel_sonstige*'          => __( 'Other Phone Numbers', 'immonex-kickstart-for-elementor' ),
			'source|preise->mieteinnahmen_ist*'            => __( 'Actual Rental Revenues', 'immonex-kickstart-for-elementor' ),
			'source|preise->mieteinnahmen_soll*'           => __( 'Target Rental Revenues', 'immonex-kickstart-for-elementor' ),
			'source|preise->stp_carport*'                  => __( 'Carport Parking Spaces', 'immonex-kickstart-for-elementor' )
				. ' (' . __( 'Number and/or Price', 'immonex-kickstart-for-elementor' ) . ')',
			'source|preise->stp_duplex*'                   => __( 'Duplex Parking Spaces', 'immonex-kickstart-for-elementor' )
				. ' (' . __( 'Number and/or Price', 'immonex-kickstart-for-elementor' ) . ')',
			'source|preise->stp_freiplatz*'                => __( 'Outdoor Parking Spaces', 'immonex-kickstart-for-elementor' )
				. ' (' . __( 'Number and/or Price', 'immonex-kickstart-for-elementor' ) . ')',
			'source|preise->stp_garage*'                   => __( 'Garage Parking Spaces', 'immonex-kickstart-for-elementor' )
				. ' (' . __( 'Number and/or Price', 'immonex-kickstart-for-elementor' ) . ')',
			'source|preise->stp_parkhaus*'                 => __( 'Parking Garage Parking Spaces', 'immonex-kickstart-for-elementor' )
				. ' (' . __( 'Number and/or Price', 'immonex-kickstart-for-elementor' ) . ')',
			'source|preise->stp_tiefgarage*'               => __( 'Underground Parking Spaces', 'immonex-kickstart-for-elementor' )
				. ' (' . __( 'Number and/or Price', 'immonex-kickstart-for-elementor' ) . ')',
			'source|zustand_angaben->erschliessung_umfang*' => __( 'Development Scope', 'immonex-kickstart-for-elementor' ),
			'source|verwaltung_techn->objektnr_extern'     => __( 'Property #', 'immonex-kickstart-for-elementor' )
				. ' (' . __( 'external', 'immonex-kickstart-for-elementor' ) . ')',
			'source|geo->land*'                            => __( 'Country Code', 'immonex-kickstart-for-elementor' ) . ' (ISO-3166-1 ALPHA-3)',
		];

		add_filter( 'inxkickel_mapping_select_options', [ $this, 'get_select_options' ], 10, 2 );
		add_filter( 'inxkickel_mapping_combined_select_options', [ $this, 'get_combined_select_options' ], 10, 2 );
	} // init

	/**
	 * Return all names/terms (custom field items only) incl. related titles based
	 * the specified column of the current mapping table (filter callback).
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

		$options_cache = get_transient( "inxkickel_mapping_select_options_{$column}" );

		if ( ! empty( $options_cache ) ) {
			if ( ! $this->mapping_file_mtime ) {
				$current_mapping_file = $this->get_current_mapping_file();
			}

			if (
				! empty( $options_cache['options'] )
				&& $options_cache['mapping_file_mtime'] >= $this->mapping_file_mtime
			) {
				$this->select_options[ $column ] = $options_cache['options'];

				return $options_cache['options'];
			}
		}

		$mappings                       = $this->get_mappings();
		$not_equal_operator_source_defs = [];
		$options                        = [];

		if ( empty( $mappings ) ) {
			return $options;
		}

		foreach ( $mappings as $item ) {
			if (
				'custom_field' !== $item['type']
				|| empty( $item[ $column ] )
				|| empty( $item['name'] )
				|| in_array( $item['name'], $this->exclude_wildcard_names, true )
				|| ( 'destination' === $column && '+' === substr( $item['destination'], -1 ) )
				|| ( 'source' === $column && preg_match( '/[+\#]$/', $item['source'] ) && $item['parent'] && ! $item['title'] )
				|| ( 'source' === $column && false !== strpos( $item['source'], 'user_defined_' ) )
				|| 'boolean' === $item['filter']
			) {
				continue;
			}

			$divider = 'source' === $column ? '->' : '.';

			if ( 'source' === $column && preg_match( '/[:!=+\#]/', $item[ $column ] ) ) {
				$wildcard_part = preg_match( '/([^:!=]+)(:|=|!=)/', $item[ $column ], $matches ) ? "{$matches[1]}{$matches[2]}*" : '';
				if ( ! $wildcard_part ) {
					$wildcard_part = substr( $item[ $column ], 0, -1 ) . '*';
				}

				if ( '!=' === $matches[2] ) {
					$not_equal_operator_source_defs[] = $matches[1];
					$wildcard_part                    = preg_replace( '/!=.*/', '*', $wildcard_part );
					$equal_wildcard_part              = "{$matches[0]}=*";
					if ( isset( $options[ $equal_wildcard_part ] ) ) {
						$options[ $wildcard_part ] = $options[ $equal_wildcard_part ];
						unset( $options[ $equal_wildcard_part ] );
					}
					continue;
				} elseif ( in_array( substr( $wildcard_part, 0, -2 ), $not_equal_operator_source_defs, true ) ) {
					$wildcard_part = str_replace( '=*', '*', $wildcard_part );
				} elseif ( ':' === $matches[2] ) {
					$wildcard_part = preg_replace( '/:\*$/', '*', $wildcard_part );
				}

				if ( ! isset( $options[ $wildcard_part ] ) ) {
					$title = $this->get_select_option_title( $item, $column, $wildcard_part );
					if ( ! $title ) {
						continue;
					}

					if ( false !== strpos( $title, '[' ) ) {
						$sub_titles = $this->get_item_sub_titles( $wildcard_part, 'source' );

						if ( $sub_titles ) {
							$title = str_replace( ' (' . __( 'all', 'immonex-kickstart-for-elementor' ) . ')', '', $title );
							$title = str_replace( '[', "({$sub_titles}) [", $title );
						}
					}

					$options[ $wildcard_part ] = $title;
				}

				if ( $item['title'] || '+' !== substr( $item['destination'], -1 ) ) {
					continue;
				}
			} elseif ( false !== strpos( $item[ $column ], $divider ) ) {
				$parts = explode( $divider, $item[ $column ] );
				array_pop( $parts );

				if ( ! empty( $parts ) ) {
					$part_chain = '';

					foreach ( $parts as $i => $part ) {
						$part_chain   .= ( $i > 0 ? $divider : '' ) . $part;
						$wildcard_part = "{$part_chain}{$divider}*";

						if (
							in_array( $wildcard_part, $this->exclude_wildcard_sources, true )
							|| in_array( $wildcard_part, $this->exclude_wildcard_names, true )
						) {
							continue;
						}

						if ( ! isset( $options[ $wildcard_part ] ) ) {
							$title = $this->get_select_option_title( $item, $column, $wildcard_part );
							if ( ! $title ) {
								continue;
							}

							$options[ $wildcard_part ] = $title;
						}
					}
				}
			}

			if ( ! empty( $options[ $item[ $column ] ] ) ) {
				continue;
			}

			$title = $this->get_select_option_title( $item, $column, $item[ $column ] );
			if ( ! $title ) {
				continue;
			}

			if ( 'name' === $column && false !== strpos( $title, '[' ) ) {
				$sub_titles = $this->get_item_sub_titles( $item['name'], 'name' );

				if ( $sub_titles ) {
					$title = str_replace( ' (' . __( 'all', 'immonex-kickstart-for-elementor' ) . ')', '', $title );
					$title = str_replace( '[', "({$sub_titles}) [", $title );
				}
			}

			$options[ $item[ $column ] ] = $title;
		}

		if ( 'source' === $column ) {
			if ( isset( $options['preise->kaufpreis*'] ) && isset( $options['preise->kaufpreis'] ) ) {
				unset( $options['preise->kaufpreis'] );
			}
		}

		if ( 'name' === $column ) {
			$this->remove_single_value_wildcard_names( $options );
		}

		ksort( $options );

		$this->select_options[ $column ] = $options;

		set_transient(
			"inxkickel_mapping_select_options_{$column}",
			[
				'mapping_file_mtime' => $this->mapping_file_mtime,
				'options'            => $options,
			],
			WEEK_IN_SECONDS
		);

		return $options;
	} // get_select_options

	/**
	 * Generate a combined list of select options based on the current mapping table
	 * (filter callback).
	 *
	 * @since 1.4.0
	 *
	 * @param string[] $select_options Empty array.
	 * @param string[] $include_col    Mapping table column.
	 *
	 * @return string[] Associative array: name => title.
	 */
	public function get_combined_select_options( $select_options, $include_col = [ 'name', 'group', 'destination' ] ) {
		if ( ! empty( $this->select_options['combined'] ) ) {
			return $this->select_options['combined'];
		}

		$options_cache = get_transient( 'inxkickel_mapping_select_options_combined' );

		if ( ! empty( $options_cache ) ) {
			if ( ! $this->mapping_file_mtime ) {
				$current_mapping_file = $this->get_current_mapping_file();
			}

			if (
				! empty( $options_cache['options'] )
				&& $options_cache['mapping_file_mtime'] >= $this->mapping_file_mtime
			) {
				$this->select_options['combined'] = $options_cache['options'];

				return $options_cache['options'];
			}
		}

		$utils        = apply_filters( 'inxkickel_get_utils', [] );
		$mappings     = $this->get_mappings();
		$temp         = [];
		$groups       = [];
		$destinations = [];
		$options      = [];

		if ( empty( $mappings ) ) {
			return $options;
		}

		foreach ( $mappings as $item ) {
			if (
				'custom_field' !== $item['type']
				|| in_array( $item['name'], $this->exclude_wildcard_names, true )
				|| empty( $item['destination'] )
				|| 'boolean' === $item['filter']
			) {
				continue;
			}

			/**
			 * Name
			 */

			if ( in_array( 'name', $include_col, true ) && ! empty( $item['name'] ) ) {
				$parent_title     = $this->get_select_option_title( $item, 'parent' );
				$title            = $item[ "title {$this->lang}" ] ?
					$item[ "title {$this->lang}" ] :
					$item['title'];
				$org_parent_title = $item[ "parent {$this->lang}" ] ?
					$item[ "parent {$this->lang}" ] :
					$item['parent'];

				if ( ! $title && $parent_title !== $org_parent_title ) {
					$title = $org_parent_title;
				}

				if ( empty( $temp[ $parent_title ] ) ) {
					$temp[ $parent_title ] = [
						'key'    => $item['name'],
						'titles' => $title ? [ $title ] : [],
					];
				} else {
					$temp[ $parent_title ]['key'] = $item['name'];
					if ( ! empty( $title ) && ! in_array( $title, $temp[ $parent_title ]['titles'], true ) ) {
						if ( '+' !== substr( $item['destination'], -1 ) ) {
							array_unshift( $temp[ $parent_title ]['titles'], $title );
						} else {
							$temp[ $parent_title ]['titles'][] = $title;
						}
					}
				}
			}

			/**
			 * Group
			 */

			if ( in_array( 'group', $include_col, true ) && ! empty( $item['group'] ) ) {
				$full_key = 'group|' . $item['group'];

				if ( ! isset( $groups[ $full_key ] ) ) {
					$groups[ $full_key ] = $this->get_select_option_title( $item, 'group' );
				}
			}

			/**
			 * Destination (Custom Field)
			 */

			if (
				in_array( 'destination', $include_col, true )
				&& ! empty( $item['destination'] )
				&& '+' !== substr( $item['destination'], -1 )
				&& ! empty( $item['parent'] )
			) {
				$full_key = 'destination|' . $item['destination'];

				if ( ! isset( $destinations[ $full_key ] ) ) {
					$destinations[ $full_key ] = $this->get_select_option_title( $item, 'destination' );
				}
			}
		}

		if ( in_array( 'name', $include_col, true ) ) {
			foreach ( $temp as $parent_title => $item ) {
				$full_key = 'name|' . $item['key'];

				$this->add_wildcard_names( $item['key'], $options );

				if ( isset( $options[ $item['key'] ] ) && $parent_title !== $options[ $item['key'] ] ) {
					if ( false !== strpos( $parent_title, '(' ) && false !== strpos( $options[ $item['key'] ], '(' ) ) {
						/**
						 * If multiple mapping items with the same name exist, remove eventually existing
						 * unique parent title parts in brackets.
						 */
						$parent_title = substr( $parent_title, 0, strpos( $parent_title, '(' ) - 1 );
						unset( $options[ $item['key'] ] );
					}
				}

				$title_values = '';
				if ( count( $item['titles'] ) > 1 ) {
					$title_values = ' (' . implode( ', ', array_unique( $item['titles'] ) ) . ')';
				}

				$options[ $full_key ] = wp_sprintf(
					'%1$s%2$s [%3$s: %4$s]',
					$parent_title,
					$title_values,
					__( 'Name', 'immonex-kickstart-for-elementor' ),
					$item['key']
				);
			}

			$this->remove_single_value_wildcard_names( $options );

			ksort( $options );
		}

		ksort( $groups );
		ksort( $destinations );

		$options = array_merge( $options, $groups, $destinations );

		set_transient(
			'inxkickel_mapping_select_options_combined',
			[
				'mapping_file_mtime' => $this->mapping_file_mtime,
				'options'            => $options,
			],
			WEEK_IN_SECONDS
		);

		return $options;
	} // get_combined_select_options

	/**
	 * Generate an option title for use in select boxes.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $item                Mapping item.
	 * @param string  $column              Column name.
	 * @param string  $alternate_col_value Alternative column value (optional).
	 *
	 * @return string Option title.
	 */
	private function get_select_option_title( $item, $column = 'name', $alternate_col_value = '' ) {
		if ( empty( $item[ $column ] ) ) {
			return '';
		}

		if ( $alternate_col_value ) {
			$key = "{$column}|{$alternate_col_value}";
		} else {
			$key = ! empty( $item[ "{$column} {$this->lang}" ] ) ?
				"{$column}|{$item[ "{$column} {$this->lang}" ]}" :
				"{$column}|{$item[ $column ]}";
		}

		switch ( $column ) {
			case 'parent':
				$key = "name|{$item['name']}";

				if ( ! empty( $this->default_select_option_titles[ $key ] ) ) {
					return $this->default_select_option_titles[ $key ];
				}

				return $item[ "parent {$this->lang}" ] ?
					$item[ "parent {$this->lang}" ] :
					$item['name'];
			case 'name':
				$parent = $item[ "parent {$this->lang}" ] ?
					$item[ "parent {$this->lang}" ] :
					$item['parent'];
				$title  = ! empty( $this->default_select_option_titles[ $key ] ) ?
					$this->default_select_option_titles[ $key ] :
					$parent;
				$name   = $alternate_col_value ?
					$alternate_col_value :
					$item['name'];

				if ( '*' === substr( $name, -1 ) ) {
					return $this->get_wildcard_option_title( $name, $key );
				}

				return $title ? wp_sprintf( '%s [%s]', $title, $name ) : $name;
			case 'group':
				$title = ! empty( $this->default_select_option_titles[ $key ] ) ?
					$this->default_select_option_titles[ $key ] :
					ucfirst( $item['group'] );

				return $alternate_col_value ?
					wp_sprintf( '%s [%s]', $title, $item['group'] ) :
					wp_sprintf( '%s [%s: %s]', $title, __( 'Group', 'immonex-kickstart-for-elementor' ), $item['group'] );
			case 'source':
				if (
					( $alternate_col_value && '*' === substr( $alternate_col_value, -1 ) )
					|| '*' === substr( $item['source'], -1 )
				) {
					return $this->get_wildcard_option_title( $alternate_col_value ? $alternate_col_value : $item['source'], $key );
				}

				$parent_title = $item[ "parent {$this->lang}" ] ?
					$item[ "parent {$this->lang}" ] :
					$item['parent'];
				$title        = ! empty( $this->default_select_option_titles[ $key ] ) ?
					$this->default_select_option_titles[ $key ] :
					$parent_title;

				if ( ! $title ) {
					return '';
				}

				$title = preg_replace( '/(?<!->)(=)?\*$/', '', $title );

				return $alternate_col_value ?
					wp_sprintf( '%s [%s]', $title, $alternate_col_value ) :
					wp_sprintf( '%s [%s: %s]', $title, __( 'Source', 'immonex-kickstart-for-elementor' ), $item['source'] );
			case 'destination':
				$parent_title = $item[ "parent {$this->lang}" ] ?
					$item[ "parent {$this->lang}" ] :
					$item['parent'];
				$title        = ! empty( $this->default_select_option_titles[ $key ] ) ?
					$this->default_select_option_titles[ $key ] :
					$parent_title;

				return $alternate_col_value ?
					wp_sprintf( '%s [%s]', $title, $item['destination'] ) :
					wp_sprintf( '%s [%s: %s]', $title, __( 'Dest. Field', 'immonex-kickstart-for-elementor' ), $item['destination'] );
		}
	} // get_select_option_title

	/**
	 * Add wildcard names related to the specified mapping name to the options list.
	 *
	 * @since 1.4.0
	 *
	 * @param string   $mapping_name Mapping name.
	 * @param string[] $options      Reference to options array.
	 */
	private function add_wildcard_names( $mapping_name, &$options ) {
		$wildcard_names = $this->get_wildcard_names( $mapping_name );

		if ( ! empty( $wildcard_names ) ) {
			foreach ( $wildcard_names as $wildcard_name ) {
				if ( in_array( $wildcard_name, $this->exclude_wildcard_names, true ) ) {
					continue;
				}

				$full_key = "name|{$wildcard_name}";

				if ( ! isset( $options[ $full_key ] ) ) {
					$options[ $full_key ] = $this->get_wildcard_option_title( $wildcard_name, $full_key );
				}
			}
		}
	} // add_wildcard_names

	/**
	 * Generate an option title for a wildcard name.
	 *
	 * @since 1.4.0
	 *
	 * @param string $wildcard_name Wildcard name.
	 * @param string $full_key      Full option key (e.g. 'name|freitexte.*').
	 *
	 * @return string Option title.
	 */
	private function get_wildcard_option_title( $wildcard_name, $full_key ) {
		$option_title = '';

		if ( isset( $this->default_select_option_titles[ $full_key ] ) ) {
			$option_title = $this->default_select_option_titles[ $full_key ];
		} elseif ( 'source' === substr( $full_key, 0, 6 ) ) {
			$parent_titles = $this->get_source_item_parent_titles( $wildcard_name );

			if ( 1 === count( $parent_titles ) ) {
				$option_title = $parent_titles[0];
			}
		}

		if ( ! $option_title ) {
			$parts        = preg_split( '/(\.|:|=|->)/', substr( $wildcard_name, 0, -1 ), -1, PREG_SPLIT_NO_EMPTY );
			$option_title = ucwords( str_replace( '_', ' ', array_pop( $parts ) ) );
			$option_title = str_replace( [ 'Min ', 'Max ' ], [ 'Min. ', 'Max. ' ], $option_title );
		}

		return preg_match( '/(\.|->)\*$/', $wildcard_name ) ?
			wp_sprintf( '%s (%s) [%s]', $option_title, __( 'all', 'immonex-kickstart-for-elementor' ), $wildcard_name ) :
			wp_sprintf( '%s [%s]', $option_title, preg_replace( '/(?<!->)(=)?\*$/', '', $wildcard_name ) );
	} // get_wildcard_option_title

	/**
	 * Remove wildcard names from the options list if only one mapping item
	 * exists for the related name.
	 *
	 * @since 1.4.0
	 *
	 * @param string[] $options Reference to options array.
	 */
	private function remove_single_value_wildcard_names( &$options ) {
		if ( empty( $options ) ) {
			return;
		}

		foreach ( $options as $full_key => $option ) {
			if ( '*' !== substr( $full_key, -1 ) ) {
				continue;
			}

			$wildcard_key = substr( $full_key, 0, -1 );
			$sub_keys     = array_filter(
				array_keys( $options ),
				function ( $key ) use ( $wildcard_key ) {
					return strpos( $key, $wildcard_key ) === 0;
				}
			);

			if ( count( $sub_keys ) <= 2 ) {
				unset( $options[ $full_key ] );
			}
		}
	} // remove_single_value_wildcard_names

	/**
	 * Get wildcard names related to the specified mapping name.
	 *
	 * @since 1.4.0
	 *
	 * @param string $mapping_name Mapping name.
	 *
	 * @return string[]|false Wildcard names or false if no wildcard names exist.
	 */
	private function get_wildcard_names( $mapping_name ) {
		if ( isset( $this->wildcard_names[ $mapping_name ] ) ) {
			return $this->wildcard_names[ $mapping_name ];
		}

		$parts = preg_split( '/[.|:]/', $mapping_name, -1, PREG_SPLIT_NO_EMPTY );
		if ( empty( $parts ) ) {
			return false;
		}

		$wildcard_names = [];
		$current_part   = '';

		foreach ( $parts as $i => $part ) {
			if ( count( $parts ) - 1 === $i ) {
				break;
			}

			$current_part .= ( $i > 0 ? '.' : '' ) . $part;

			if ( ! in_array( $current_part, $this->exclude_wildcard_names, true ) ) {
				$wildcard_names[] = "{$current_part}.*";
			}
		}

		$this->wildcard_names[ $mapping_name ] = $wildcard_names;

		return $wildcard_names;
	} // get_wildcard_names

	/**
	 * Get parent titles of mapping items with the specified source wildcard part.
	 *
	 * @since 1.4.0
	 *
	 * @param string $wildcard_part Source wildcard part (e.g. 'freitexte->*').
	 *
	 * @return string[] Parent titles.
	 */
	private function get_source_item_parent_titles( $wildcard_part ) {
		$mappings      = $this->get_mappings();
		$parent_titles = [];

		foreach ( $mappings as $item ) {
			if (
				'custom_field' !== $item['type']
				|| empty( $item['name'] )
			) {
				continue;
			}

			if ( preg_match( "/^{$wildcard_part}/", $item['source'] ) ) {
				$parent_titles[] = ! empty( $item[ "parent {$this->lang}" ] ) ?
					$item[ "parent {$this->lang}" ] :
					$item['parent'];
			}
		}

		return array_unique( $parent_titles );
	} // get_source_item_parent_titles

	/**
	 * Get a (possibly shortened) list of sub titles related to mapping items
	 * with the specified name or source wildcard part.
	 *
	 * @since 1.4.0
	 *
	 * @param string $compare Value to compare with the specified column.
	 * @param string $column  Column name ('name' or 'source').
	 *
	 * @return string Comma-separated title list with the maximum length defined aboves.
	 */
	private function get_item_sub_titles( $compare, $column ) {
		$mappings   = $this->get_mappings();
		$sub_titles = [];

		foreach ( $mappings as $item ) {
			if ( 'custom_field' !== $item['type'] ) {
				continue;
			}

			if (
				(
					( 'name' === $column && $compare === $item[ $column ] )
					|| ( 'name' !== $column && preg_match( "/^{$compare}[:=]/", $item[ $column ] ) )
				)
				&& ! empty( $item['title'] )
			) {
				$sub_titles[] = ! empty( $item[ "title {$this->lang}" ] ) ?
					$item[ "title {$this->lang}" ] : $item['title'];
			}
		}

		if ( count( $sub_titles ) > 1 ) {
			$utils = apply_filters( 'inxkickel_get_utils', [] );

			return str_replace( ',…', '…', $utils['string']->get_excerpt( implode( ', ', $sub_titles ), self::SUB_TITLES_MAX_LENGTH, '…' ) );
		}

		return '';
	} // get_item_sub_titles

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
	 * Parse the current OpenImmo2WP (or included) mapping file.
	 *
	 * @since 1.0.0
	 */
	private function parse_mapping_file() {
		$current_mapping_file = $this->get_current_mapping_file();
		$mapping_cache        = get_transient( 'inxkickel_mappings' );

		if ( ! file_exists( $current_mapping_file ) ) {
			if ( ! empty( $mapping_cache['mappings'] ) ) {
				$this->mapping_file_mtime = isset( $mapping_cache['mtime'] ) ? (int) $mapping_cache['mtime'] : 0;
				$this->mappings           = $mapping_cache['mappings'];
			}

			return;
		}

		if ( ! empty( $mapping_cache ) ) {
			if ( isset( $mapping_cache['mtime'] ) && $mapping_cache['mtime'] >= $this->mapping_file_mtime ) {
				$this->mappings = $mapping_cache['mappings'];

				return;
			}
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
				if ( ! isset( $mapping['type'] ) || '#' === $mapping['type'][0] || ! isset( $mapping['source'] ) ) {
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
					'filter'      => ! empty( $mapping['filter'] ) ? $mapping['filter'] : '',
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

		set_transient(
			'inxkickel_mappings',
			[
				'mtime'    => $this->mapping_file_mtime,
				'mappings' => $this->mappings,
			],
			WEEK_IN_SECONDS
		);
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
		$utils                = apply_filters( 'inxkickel_get_utils', [] );
		$current_mapping_file = apply_filters( 'immonex_oi2wp_current_mapping_file', '' ); // phpcs:ignore -- Filter hook belongs to another immonex plugin.

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

		$this->mapping_file_mtime = (int) $utils['local_fs']->get_mtime( $current_mapping_file );

		return $current_mapping_file;
	} // get_current_mapping_file

} // class Mapping_Table
