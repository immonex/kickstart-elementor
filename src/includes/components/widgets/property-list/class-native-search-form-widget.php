<?php
/**
 * Class Native_Search_Form_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\PropertyList;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Property List Native Search Form Widget
 *
 * @since 1.0.0
 */
class Native_Search_Form_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const WIDGET_NAME              = 'inx-e-native-search-form';
	const WIDGET_ICON              = 'eicon-search';
	const WIDGET_CATEGORIES        = [ 'inx-property-list' ];
	const WIDGET_HELP_URL          = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/suchformular';
	const ENABLE_RENDER_ON_PREVIEW = true;
	const IS_DYNAMIC_CONTENT       = true;

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Search Form', 'immonex-kickstart-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'search', 'immonex-kickstart-elementor' ),
					__( 'form', 'immonex-kickstart-elementor' ),
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
		$utils                  = apply_filters( 'inx_elementor_get_utils', [] ); // phpcs:ignore
		$search_form_elements   = apply_filters( 'inx_get_search_form_elements', [] ); // phpcs:ignore
		$pages                  = get_pages();
		$element_options        = [];
		$element_control_titles = [];
		$default_elements       = [];
		$results_page_options   = [
			'' => __( 'default', 'immonex-kickstart-elementor' ),
		];

		if ( ! empty( $search_form_elements ) ) {
			uasort(
				$search_form_elements,
				function ( $a, $b ) {
					return $a['order'] <=> $b['order'];
				}
			);

			foreach ( $search_form_elements as $key => $element ) {
				if ( ! $element['enabled'] ) {
					continue;
				}

				$title                 = ! empty( $element['description'] ) ? $element['description'] : $key;
				$element_control_title = preg_replace( '/ \(.*\)/', '', $title );

				$element_options[ $key ]        = $title;
				$element_control_titles[ $key ] = $utils['string']->get_excerpt( $element_control_title, 24, '…' );

				if ( ! $element['hidden'] ) {
					$default_elements[] = [
						'element'  => $key,
						'extended' => $element['extended'] ? '1' : '0',
					];
				}
			}
		}

		$element_control_titles_json = wp_json_encode( $element_control_titles );

		if ( ! empty( $pages ) ) {
			foreach ( $pages as $page ) {
				$results_page_options[ $page->ID ] = $page->post_title;
			}
		}

		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_main_class_control();

		$this->add_control(
			'dynamic-update',
			[
				'label'       => __( 'Dynamic Updates', 'immonex-kickstart-elementor' ),
				'description' => __( 'Enable dynamic updates of <strong>list and map elements</strong> on the same page when search options are changed.', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => [
					''  => __( 'Default', 'immonex-kickstart-elementor' ),
					'1' => __( 'Enabled', 'immonex-kickstart-elementor' ),
					'0' => __( 'Disabled', 'immonex-kickstart-elementor' ),
				],
			]
		);

		$this->add_control(
			'results-page-id',
			[
				'label'       => __( 'Alternative Results Page', 'immonex-kickstart-elementor' ),
				'description' => __( 'Defaults to the <strong>current page</strong> if a property list shortcode is included.', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => $results_page_options,
				'label_block' => true,
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'references',
			[
				'label'        => __( 'Sold/Rented Selection', 'immonex-kickstart-elementor' ),
				'description'  => __( 'Show "Sold" and "Rented" in the <strong>marketing type select box</strong> if related properties exist.', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => '1',
				'separator'    => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'elements_section',
			[
				'label' => __( 'Elements', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'use_default_elements',
			[
				'label'        => __( 'Use default elements', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'return_value' => '1',
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'element',
			[
				'label'       => __( 'Element', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => $element_options,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'extended',
			[
				'label'        => __( 'Extended Search', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '0',
				'return_value' => '1',
			]
		);

		$this->add_control(
			'form_elements',
			[
				'label'       => __( 'User-defined Elements', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => "<# const labels = {$element_control_titles_json}; const label = labels[element]; #>{{{ label }}}",
				'default'     => $default_elements,
				'condition'   => [
					'use_default_elements' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tax_select_defaults_section',
			[
				'label' => __( 'Taxonomy Selection Defaults', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'top-level-only',
			[
				'label'        => __( 'Main Categories only', 'immonex-kickstart-elementor' ),
				'description'  => __( 'Display only top-level entries in select boxes of hierarchical taxonomies (e.g. property type).', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => '1',
				'separator'    => 'after',
			]
		);

		$this->add_control(
			'tax_filter_notice',
			[
				'type'        => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'content'     => __( 'If the selectable taxonomy terms are to be limited to certain options, the corresponding <strong>top-level slugs</strong> can be stored in the following input fields as comma-separated lists (e.g. "houses, flats").', 'immonex-kickstart-elementor' ),
			]
		);

		$this->add_control(
			'force-location',
			[
				'label'       => __( 'Locations', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->add_control(
			'force-type-of-use',
			[
				'label'       => __( 'Types of Use', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->add_control(
			'force-property-type',
			[
				'label'       => __( 'Property Types', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->add_control(
			'force-marketing-type',
			[
				'label'       => __( 'Marketing Types', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->add_control(
			'force-feature',
			[
				'label'       => __( 'Features', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'distance_search_section',
			[
				'label' => __( 'Distance Search', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'autocomplete-countries',
			[
				'label'       => __( 'Autocomplete Countries', 'immonex-kickstart-elementor' ),
				'description' => __( 'Comma-separated <strong>ISO 3166-1 ALPHA-2</strong> country code list (e.g. "de,at,ch,be,nl") – leave empty to use defaults.', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->add_control(
			'autocomplete-osm-place-tags',
			[
				'label'       => __( 'Autocomplete OSM Place Tags', 'immonex-kickstart-elementor' ),
				'description' => __( 'Comma-separated <strong>OpenStreetMap Place Tag List</strong> for filtering/prioritizing autocomplete search results – defaults to "city,town,village,borough,suburb" if empty.', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'extended_style_section',
			[
				'label' => __( 'Extended', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'template',
			[
				'label'       => __( 'Custom Template', 'immonex-kickstart-elementor' ),
				'description' => wp_sprintf(
					__( 'Enter the <strong>filename</strong> if an <strong>alternative</strong> PHP template should be used for rendering the component.', 'immonex-kickstart-elementor' ) .
						/* translators: %s: plugin name */
						'(' . __( 'The file must be included in the <strong>skin folder</strong> selected in the %s plugin options.', 'immonex-kickstart-elementor' ) . ')',
					'<code>property-list</code>',
					'<strong>Kickstart</strong>'
				),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
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
		$settings = $this->get_settings_for_display();

		if ( ! $settings['use_default_elements'] && ! empty( $settings['form_elements'] ) ) {
			$elements = [];

			foreach ( $settings['form_elements'] as $element ) {
				$elements[] = $element['element'] . ( $element['extended'] ? '+' : '-' );
			}

			$this->add_render_attribute( 'shortcode', 'elements', implode( ',', $elements ) );
		}

		$template_data = [
			'settings' => $settings,
		];

		$ext_atts = [
			'template',
			'dynamic-update',
			'results-page-id',
			'references',
			'top-level-only',
			'force-location',
			'force-type-of-use',
			'force-property-type',
			'force-marketing-type',
			'force-feature',
			'autocomplete-countries',
			'autocomplete-osm-place-tags',
		];

		$this->add_extended_sc_atts( $ext_atts, $template_data );

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'shortcode', 'is_preview', '1' );
		}

		$template_data['shortcode_output'] = do_shortcode( '[inx-search-form ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $template_data['shortcode_output'] ? $template_data : false;
	} // get_template_data

} // class Native_Search_Form_Widget
