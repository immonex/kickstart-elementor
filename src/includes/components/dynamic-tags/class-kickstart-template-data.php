<?php
/**
 * Class Kickstart_Template_Data
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\DynamicTags;

/**
 * Kickstart Template Dynamic Data Tag
 */
class Kickstart_Template_Data extends \Elementor\Core\DynamicTags\Data_Tag {

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
		return esc_html__( 'Kickstart Template Data', 'immonex-kickstart-elementor' );
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
		$element_type_select_options = $this->get_element_type_select_options();
		$repeater                    = new \Elementor\Repeater();

		$this->add_control(
			'type',
			[
				'label'       => __( 'Type', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'flex_elements',
				'options'     => [
					'flex_elements' => __( 'Flex Elements', 'immonex-kickstart-elementor' ),
					'array_keys'    => __( 'Array Keys', 'immonex-kickstart-elementor' ),
				],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'element_type',
			[
				'label'       => __( 'Element Type', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'name',
				'options'     => $element_type_select_options,
				'label_block' => true,
			]
		);

		foreach ( $element_type_select_options as $option_type => $option_title ) {
			if ( 'user_defined' === $option_type ) {
				continue;
			}

			$options = apply_filters( 'inx_elementor_mapping_select_options', [], $option_type );
			if ( empty( $options ) ) {
				continue;
			}

			$options = array_filter(
				$options,
				function ( $option ) {
					return false === strpos( $option, '.*' );
				}
			);

			$repeater->add_control(
				"element_{$option_type}",
				[
					'label'       => __( 'Element', 'immonex-kickstart-elementor' ),
					'type'        => \Elementor\Controls_Manager::SELECT,
					'options'     => $options,
					'condition'   => [
						'element_type' => $option_type,
					],
					'label_block' => true,
				]
			);
		}

		$repeater->add_control(
			'element_user_defined',
			[
				'label'       => __( 'Element', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'condition'   => [
					'element_type' => 'user_defined',
				],
				'label_block' => true,
			]
		);

		$format_filters = $this->get_format_filters();
		$format_options = [
			'' => __( 'no formatting', 'immonex-kickstart-elementor' ),
		];
		if ( ! empty( $format_filters ) ) {
			foreach ( $format_filters as $key => $filter ) {
				$format_options[ $key ] = $filter['title'];
			}
		}

		$repeater->add_control(
			'format',
			[
				'label'       => __( 'Format', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => $format_options,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'decimal_places',
			[
				'label'     => __( 'Decimal Places', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 9,
				'options'   => [
					9 => __( 'auto', 'immonex-kickstart-elementor' ),
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
			'before_value',
			[
				'label'       => __( 'Before Value', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'separator'   => 'before',
			]
		);

		$repeater->add_control(
			'after_value',
			[
				'label'       => __( 'After Value', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'separator'   => 'after',
			]
		);

		$title_field = "<# const label = element_type === 'user_defined' ? '" .
			__( 'User-defined/RegEx', 'immonex-kickstart-elementor' ) .
			"' : eval('element_' + element_type); #>{{{ label }}}";

		$this->add_control(
			'elements',
			[
				'label'         => __( 'Elements', 'immonex-kickstart-elementor' ),
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
				'label'       => esc_html__( 'Key(s)', 'immonex-kickstart-elementor' ),
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
		$is_edit_mode = ! empty( $_REQUEST['editor_post_id'] )
			|| \Elementor\Plugin::$instance->editor->is_edit_mode();

		if ( 'array_keys' === $this->get_settings( 'type' ) ) {
			if ( $is_edit_mode ) {
				return '[' . $this->get_settings( 'key' ) . ']';
			} else {
				return $this->get_combined_array_values( $options );
			}
		}

		$elements = $this->get_settings( 'elements' );

		if ( empty( $elements ) ) {
			return '';
		}

		$element_values = [];

		foreach ( $elements as $element ) {
			$scope = 'name' === $element['element_type'] ? $element['element_type'] : false;

			if ( ! empty( $element[ "element_{$element['element_type']}" ] ) ) {
				$element['element'] = $element[ "element_{$element['element_type']}" ];
			} else {
				continue;
			}

			if ( $is_edit_mode ) {
				$element['value'] = ! empty( $element['element_type'] ) ?
					$element['element_type'] . ': ' . $element['element'] :
					$element['element'];
				$element['value'] = "[{$element['value']}]";
				$element_data     = [ $element ];
			} else {
				$element_data = apply_filters( 'inx_get_flex_items', [], $element['element'], $scope );

				if ( empty( $element_data ) ) {
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

					$element_return['value'] = apply_filters(
						'inx_format',
						$element_return['value'],
						$format['type'],
						$format['args']
					);
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

				$element_values[] = $element_return['value'];
			}
		}

		return implode( '', $element_values );
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

		$template_data = apply_filters( 'inx_get_property_template_data', [] );
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
	 * Return the element type control select options.
	 *
	 * @since 1.0.0
	 *
	 * @return string[] Associative array: key => title.
	 */
	private function get_element_type_select_options() {
		return [
			'name'         => __( 'Name', 'immonex-kickstart-elementor' ),
			'destination'  => __( 'Destination (Custom Field)', 'immonex-kickstart-elementor' ),
			'user_defined' => __( 'User-defined/RegEx', 'immonex-kickstart-elementor' ),
		];
	} // get_element_type_select_options

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
			'inx_elementor_format_filters',
			[
				'inx_format_price'  => [
					'title' => __( 'Price', 'immonex-kickstart-elementor' ),
					'type'  => 'price',
					'args'  => [],
				],
				'inx_format_area'   => [
					'title' => __( 'Area', 'immonex-kickstart-elementor' ),
					'type'  => 'area',
					'args'  => [],
				],
				'inx_format_number' => [
					'title' => __( 'Number', 'immonex-kickstart-elementor' ),
					'type'  => 'number',
					'args'  => [],
				],
				'inx_format_link'   => [
					'title' => __( 'Link (URL/E-Mail/Phone)', 'immonex-kickstart-elementor' ),
					'type'  => 'link',
					'args'  => [],
				],
			]
		);

		return $this->format_filters;
	} // get_format_filters

} // class Kickstart_Template_Data
