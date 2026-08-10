<?php
/**
 * Class Kickstart_Template_Data
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor\Components\DynamicTags;

/**
 * Kickstart Template Dynamic Data Tag
 */
class Kickstart_Template_Data extends \Elementor\Core\DynamicTags\Data_Tag {

	const POST_TYPE          = 'inx_property';
	const SHOW_SOURCE_NOTICE = true;

	/**
	 * Value Formatting Filters
	 *
	 * @var mixed[]
	 */
	protected $format_filters = [];

	/**
	 * Get dynamic tag name.
	 *
	 * Retrieve the name of the tag.
	 *
	 * @return string Dynamic tag name.
	 */
	public function get_name() {
		return 'inx-template-data';
	} // get_name

	/**
	 * Get dynamic tag title.
	 *
	 * Returns the title of the tag.
	 *
	 * @return string Dynamic tag title.
	 */
	public function get_title() {
		return esc_html__( 'Kickstart Template Data', 'immonex-kickstart-for-elementor' );
	}

	/**
	 * Get dynamic tag groups.
	 *
	 * Retrieve the list of groups the tag belongs to.
	 *
	 * @return array Dynamic tag groups.
	 */
	public function get_group() {
		return [ 'inx' ];
	} // get_group

	/**
	 * Get dynamic tag categories.
	 *
	 * Retrieve the list of categories the tag belongs to.
	 *
	 * @return array Dynamic tag categories.
	 */
	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	} // get_categories

	/**
	 * Register dynamic tag controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$element_type_select_options = $this->get_element_selection_type_options();
		$repeater                    = new \Elementor\Repeater();

		$this->add_control(
			'type',
			[
				'label'       => __( 'Type', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'flex_elements',
				'options'     => [
					'flex_elements' => wp_sprintf(
						'%1$s (%2$s)',
						__( 'Flex Elements', 'immonex-kickstart-for-elementor' ),
						__( 'recommended', 'immonex-kickstart-for-elementor' )
					),
					'array_keys'    => __( 'Array Keys', 'immonex-kickstart-for-elementor' ),
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'wrap_elements',
			[
				'label'       => __( 'Wrap Elements', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'Wrap element output in a &lt;span&gt; container.', 'immonex-kickstart-for-elementor' ),
			]
		);

		$this->add_control(
			'divider',
			[
				'label'       => __( 'Separator', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'String used to separate multiple elements or values during output', 'immonex-kickstart-for-elementor' ),
			]
		);

		$repeater->add_control(
			'element_type',
			[
				'label'       => __( 'Element Selection', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'combined',
				'options'     => $element_type_select_options,
				'label_block' => true,
			]
		);

		$all_options = [];

		foreach ( $element_type_select_options as $option_type => $option_title ) {
			if ( in_array( $option_type, [ 'combined', 'user_defined' ], true ) ) {
				continue;
			}

			$options = $this->add_extended_element_select_options(
				apply_filters( 'inxkickel_mapping_select_options', [], $option_type ),
				$option_type
			);

			if ( empty( $options ) ) {
				continue;
			}

			$options = array_filter(
				$options,
				function ( $option ) {
					return 0 === preg_match( '/(\.|-\>)\*/', $option );
				}
			);

			$repeater->add_control(
				"element_{$option_type}",
				[
					'label'       => __( 'Element', 'immonex-kickstart-for-elementor' ),
					'type'        => \Elementor\Controls_Manager::SELECT2,
					'options'     => $options,
					'condition'   => [
						'element_type' => $option_type,
					],
					'label_block' => true,
				]
			);

			$all_options = array_merge( $all_options, $options );
		}

		$combined_options = array_filter(
			apply_filters( 'inxkickel_mapping_combined_select_options', [], [ 'name', 'destination' ] ),
			function ( $option ) {
				return 0 === preg_match( '/(\.|-\>)\*/', $option );
			}
		);
		$all_options      = array_merge( $all_options, $combined_options );
		$options_json     = wp_json_encode( $all_options );

		$repeater->add_control(
			'element_combined',
			[
				'label'       => __( 'Element', 'immonex-kickstart-for-elementor' ),
				'type'        => 'inxkickel-extended-select2',
				'description' => __( '🄶 = Group, 🄳 = Destination. Hover over elements for more information.', 'immonex-kickstart-for-elementor' ),
				'options'     => $combined_options,
				'condition'   => [
					'element_type' => 'combined',
				],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'element_user_defined',
			[
				'label'       => __( 'Element', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'condition'   => [
					'element_type' => 'user_defined',
				],
				'label_block' => true,
			]
		);

		$format_filters = $this->get_format_filters();
		$format_options = [
			'' => __( 'no change', 'immonex-kickstart-for-elementor' ),
		];
		if ( ! empty( $format_filters ) ) {
			foreach ( $format_filters as $key => $filter ) {
				$format_options[ $key ] = $filter['title'];
			}
		}

		$repeater->add_control(
			'format',
			[
				'label'       => __( 'Format', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'Most values are already formatted during import (see <strong>Filter</strong> column of the mapping table).', 'immonex-kickstart-for-elementor' ),
				'options'     => $format_options,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'decimal_places',
			[
				'label'     => __( 'Decimal Places', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 9,
				'options'   => [
					9 => __( 'auto', 'immonex-kickstart-for-elementor' ),
					1 => '1',
					2 => '2',
				],
				'condition' => [
					'format' => [
						'inx_format_price',
						'inx_format_area',
						'inx_format_number',
					],
				],
			]
		);

		$repeater->add_control(
			'force_format',
			[
				'label'       => __( 'Always apply Format', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'If enabled, already formatted values will be reformatted.', 'immonex-kickstart-for-elementor' ),
				'condition'   => [
					'format!' => '',
				],
			]
		);

		$repeater->add_control(
			'before_value',
			[
				'label'       => __( 'Before Value', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'separator'   => 'before',
			]
		);

		$repeater->add_control(
			'after_value',
			[
				'label'       => __( 'After Value', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'separator'   => 'after',
			]
		);

		if ( static::SHOW_SOURCE_NOTICE ) {
			$this->add_control(
				'elements_notice',
				[
					'type'        => \Elementor\Controls_Manager::NOTICE,
					'notice_type' => 'warning',
					'dismissible' => true,
					'content'     => wp_sprintf(
							/* translators: %1$s = mapping table documentation page URL, %2$s = OpenImmo2WP product page URL */
						__( 'OpenImmo Elements can be selected based on their entries in the <a href="%1$s" target="_blank">import mapping table</a> (<a href="%2$s" target="_blank">&rarr; immonex OpenImmo2WP</a>) and combined as desired.', 'immonex-kickstart-for-elementor' ),
						'https://docs.immonex.de/openimmo2wp/#/mapping/tabellen',
						'https://plugins.inveris.de/wordpress-plugins/immonex-openimmo2wp'
					) .
						( ! apply_filters( 'inxkickel_is_plugin_available', false, 'elementor-pro' ) ?
							'<br><br>(' . __( 'Type and scope of the sample data shown depend on the selected formatting option and do not match the actual information.', 'immonex-kickstart-for-elementor' ) . ')' : '' ),
				]
			);
		}

		$title_field = "<# const labels = {$options_json}; const labelKey = eval('element_' + element_type); "
			. "const label = (labels[labelKey] || labelKey).replace(/[\(\[].*/, '').trim(); #>{{{ label }}}";

		$this->add_control(
			'elements',
			[
				'label'         => __( 'Elements', 'immonex-kickstart-for-elementor' ),
				'type'          => \Elementor\Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'title_field'   => $title_field,
				'prevent_empty' => false,
				'condition'     => [
					'type' => 'flex_elements',
				],
			]
		);

		$this->add_control(
			'key',
			[
				'label'       => esc_html__( 'Key(s)', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'condition'   => [
					'type' => 'array_keys',
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'repeater_bugfix',
			[
				'type' => \Elementor\Controls_Manager::HIDDEN,
			]
		);
	} // register_controls

	/**
	 * Get dynamic tag value.
	 *
	 * @param mixed[] $options Dynamic tag options (optional).
	 *
	 * @return string Dynamic tag value.
	 */
	public function get_value( $options = [] ) {
		$settings = $this->get_settings();

		if ( empty( $settings['elements'] ) ) {
			return '';
		}

		$is_edit_mode = ! empty( $_REQUEST['editor_post_id'] ) // phpcs:ignore
			|| \Elementor\Plugin::$instance->editor->is_edit_mode();

		if ( 'array_keys' === $this->get_settings( 'type' ) ) {
			if ( $is_edit_mode ) {
				return '[' . $this->get_settings( 'key' ) . ']';
			} else {
				return $this->get_combined_array_values( $options );
			}
		}

		$post_id      = null;
		$temp_post_id = get_the_ID();

		if ( static::POST_TYPE === get_post_type( $temp_post_id ) ) {
			$post_id = $temp_post_id;
		}

		$element_values = [];

		foreach ( $settings['elements'] as $element ) {
			$scope = 'name' === $element['element_type'] ? $element['element_type'] : false;

			if ( ! empty( $element[ "element_{$element['element_type']}" ] ) ) {
				$element[ "element_{$element['element_type']}" ] = html_entity_decode( $element[ "element_{$element['element_type']}" ], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 );
				$element['element']                              = $element[ "element_{$element['element_type']}" ];

				if ( false !== strpos( $element['element'], '|' ) ) {
					$element_split      = explode( '|', $element['element'] );
					$element['element'] = $element_split[1];
					$scope              = 'name' === $element_split[0] ? $element_split[0] : false;
				}
			}

			if ( empty( $element['element'] ) ) {
				continue;
			}

			$element_data = apply_filters( 'inx_get_flex_items', [], $element['element'], $scope, $post_id ); // phpcs:ignore -- Parent plugin filter hook that can't be changed (yet) for compatibility reasons.

			if ( empty( $element_data ) ) {
				if ( $is_edit_mode ) {
					$value            = ! empty( $element['element_type'] ) ?
						$element['element_type'] . ': ' . $element['element'] :
						$element['element'];
					$element['value'] = "[{$value}]";
					$element_data     = [ $element ];
				} else {
					continue;
				}
			}

			$format_filters = $this->get_format_filters();

			foreach ( $element_data as $i => $element_return ) {
				if ( ! empty( $element['format'] ) && isset( $format_filters[ $element['format'] ] ) ) {
					$format         = $format_filters[ $element['format'] ];
					$format['args'] = ! empty( $format['args'] ) ? $format['args'] : [];

					if ( ! empty( $element['decimal_places'] ) && empty( $format['args']['decimals'] ) ) {
						$format['args']['decimals'] = $element['decimal_places'];
					}

					if ( $element['force_format'] ) {
						$element_meta = ! empty( $element_return['meta_json'] ) ? json_decode( $element_return['meta_json'], true ) : false;
						if ( $element_meta && ! empty( $element_meta['value_before_filter'] ) ) {
							$element_value = $element_meta['value_before_filter'];
						}
					}

					$element_return['value'] = apply_filters(
						'inx_format', // phpcs:ignore -- Parent plugin filter hook that can't be changed (yet) for compatibility reasons.
						$element_return['value'],
						$format['type'],
						$format['args']
					);
				}

				if ( $settings['wrap_elements'] ) {
					$wrap_class       = 'inx-e-template-data-element';
					$value_wrap_class = empty( $element['before_value'] ) && empty( $element['after_value'] ) ?
						"{$wrap_class} inx-e-template-data-element__value" :
						'inx-e-template-data-element__value';

					$element_return['value'] = wp_sprintf( '<span class="%1$s">%2$s</span>', $value_wrap_class, $element_return['value'] );
				}

				if ( ! empty( $element['before_value'] ) ) {
					if ( ' ' === $element['before_value'] ) {
						$element['before_value'] = '&nbsp;';
					}
					$element_return['value'] = $element['before_value'] . $element_return['value'];
				}

				if ( ! empty( $element['after_value'] ) ) {
					if ( ' ' === $element['after_value'] ) {
						$element['after_value'] = '&nbsp;';
					}
					$element_return['value'] .= $element['after_value'];
				}

				if (
					$settings['wrap_elements']
					&& (
						! empty( $element['before_value'] )
						|| ! empty( $element['after_value'] )
					)
				) {
					$element_return['value'] = wp_sprintf( '<span class="%1$s">%2$s</span>', $wrap_class, $element_return['value'] );
				}

				$element_values[] = $element_return['value'];
			}
		}

		return implode( $settings['divider'], $element_values );
	} // get_value

	/**
	 * Get a combined string of template data array values (as alternative to
	 * preserve compatibility with older plugin versions).
	 *
	 * @param mixed[] $options Dynamic tag options (optional).
	 *
	 * @return string Dynamic tag value.
	 */
	private function get_combined_array_values( $options = [] ) {
		$key_string = $this->get_settings( 'key' );
		if ( empty( trim( $key_string ) ) ) {
			return '';
		}

		$template_data = apply_filters( 'inx_get_property_template_data', [] ); // phpcs:ignore -- Parent plugin filter hook that can't be changed (yet) for compatibility reasons.
		if ( empty( $template_data ) ) {
			return '';
		}

		$key_group = explode( ' ', trim( $key_string ) );
		$values    = [];

		foreach ( $key_group as $full_key ) {
			$contains_keys = preg_match_all( '/\[([^\]]*)\]/', $full_key, $matches, PREG_PATTERN_ORDER );
			if ( $contains_keys ) {
				$keys = $matches[1];
			} else {
				$keys = [ $full_key ];
			}

			$data_branch = $template_data;

			foreach ( $keys as $i => $key ) {
				if ( is_numeric( $key ) ) {
					$key = (int) $key;
				}

				if (
					( is_array( $data_branch ) && ! isset( $data_branch[ $key ] ) )
					|| ( is_object( $data_branch ) && ( is_int( $key ) || empty( $data_branch->$key ) ) )
				) {
					break;
				}

				$current = is_object( $data_branch ) ? $data_branch->$key : $data_branch[ $key ];

				if ( count( $keys ) - 1 === $i && is_scalar( $current ) ) {
					$values[] = $current;
					break;
				}

				$data_branch = $current;
			}
		}

		return implode( ' ', $values );
	} // get_combined_array_values

	/**
	 * Return the element (selection) type control options.
	 *
	 * @since 1.4.0
	 *
	 * @return string[] Associative array: key => title.
	 */
	protected function get_element_selection_type_options() {
		return [
			'combined'     => wp_sprintf(
				'%s (%s)',
				__( 'combined', 'immonex-kickstart-for-elementor' ),
				__( 'recommended', 'immonex-kickstart-for-elementor' )
			),
			'name'         => __( 'Name', 'immonex-kickstart-for-elementor' ),
			'source'       => __( 'Source', 'immonex-kickstart-for-elementor' ),
			'destination'  => __( 'Destination (Custom Field)', 'immonex-kickstart-for-elementor' ),
			'user_defined' => __( 'User-defined/RegEx', 'immonex-kickstart-for-elementor' ),
		];
	} // get_element_selection_type_options

	/**
	 * Return available value formatting filters.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[] Value filter data.
	 */
	private function get_format_filters() {
		if ( ! empty( $this->format_filters ) ) {
			return $this->format_filters;
		}

		$this->format_filters = apply_filters(
			'inxkickel_format_filters',
			[
				'inx_format_price'  => [
					'title' => __( 'Price', 'immonex-kickstart-for-elementor' ),
					'type'  => 'price',
					'args'  => [],
				],
				'inx_format_area'   => [
					'title' => __( 'Area', 'immonex-kickstart-for-elementor' ),
					'type'  => 'area',
					'args'  => [],
				],
				'inx_format_number' => [
					'title' => _x( 'Number', 'general', 'immonex-kickstart-for-elementor' ),
					'type'  => 'number',
					'args'  => [],
				],
				'inx_format_link'   => [
					'title' => __( 'Link (URL/E-Mail/Phone)', 'immonex-kickstart-for-elementor' ),
					'type'  => 'link',
					'args'  => [],
				],
			]
		);

		return $this->format_filters;
	} // get_format_filters

	/**
	 * Add extended element select options that are usually not listed in the
	 * mapping table (currently only relevant for destination type elements).
	 *
	 * Changes have to be applied in the single property flex details class, too.
	 *
	 * @since 1.4.0
	 *
	 * @param string[] $options Original select options.
	 * @param string   $type    Element type.
	 *
	 * @return string[] Associative array: key => title.
	 */
	protected function add_extended_element_select_options( $options, $type ) {
		$raw_ext_options = [
			'destination' => [
				'_inx_full_address'                    => __( 'Full Address', 'immonex-kickstart-for-elementor' ),
				'_inx_street'                          => __( 'Street', 'immonex-kickstart-for-elementor' ),
				'_inx_lat'                             => __( 'Latitude', 'immonex-kickstart-for-elementor' ),
				'_inx_lng'                             => __( 'Longitude', 'immonex-kickstart-for-elementor' ),
				'_inx_virtual_tour_embed_code'         => __( 'Virtual Tour Embed Code', 'immonex-kickstart-for-elementor' ),
				'_openimmo_obid'                       => __( 'OpenImmo ID (OBID)', 'immonex-kickstart-for-elementor' ),
				'_immonex_energy_class'                => __( 'Energy Efficiency Class', 'immonex-kickstart-for-elementor' ),
				'_immonex_areabutler_url'              => __( 'AreaButler URL', 'immonex-kickstart-for-elementor' ),
				'_immonex_areabutler_url_no_address'   => __( 'AreaButler URL without Address', 'immonex-kickstart-for-elementor' ),
				'_immonex_areabutler_url_with_address' => __( 'AreaButler URL with Address', 'immonex-kickstart-for-elementor' ),
			],
		];

		if ( ! isset( $raw_ext_options[ $type ] ) ) {
			return $options;
		}

		foreach ( $raw_ext_options[ $type ] as $key => $title ) {
			if ( isset( $options[ $key ] ) ) {
				continue;
			}

			$options[ $key ] = wp_sprintf( '%s [%s]', $title, $key );
		}

		ksort( $options );

		return $options;
	} // add_extended_element_select_options

} // class Kickstart_Template_Data
