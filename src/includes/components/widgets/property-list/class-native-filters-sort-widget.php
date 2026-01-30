<?php
/**
 * Class Native_Filters_Sort_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\PropertyList;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Native Filters and Sort Widget
 *
 * @since 1.0.0
 */
class Native_Filters_Sort_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const WIDGET_NAME              = 'inx-e-native-filters-sort';
	const WIDGET_ICON              = 'eicon-filter';
	const WIDGET_CATEGORIES        = [ 'inx-property-list' ];
	const WIDGET_HELP_URL          = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/filter-sortierung';
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
		return __( 'Filters/Sort', 'immonex-kickstart-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'filters', 'immonex-kickstart-elementor' ),
					__( 'sort', 'immonex-kickstart-elementor' ),
					__( 'order', 'immonex-kickstart-elementor' ),
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
		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$sort_options = $this->get_property_sort_options();

		$this->add_main_class_control();

		$this->add_control(
			'elements',
			[
				'label'         => __( 'Custom Option Scope', 'immonex-kickstart-elementor' ),
				'type'          => \Elementor\Controls_Manager::REPEATER,
				'label_block'   => true,
				'fields'        => [
					[
						'name'    => 'option',
						'label'   => __( 'Option', 'immonex-kickstart-elementor' ),
						'type'    => \Elementor\Controls_Manager::SELECT,
						'options' => $sort_options['array'],
					],
				],
				'title_field'   => "<# const labels = {$sort_options['json']}; const label = typeof option !== 'undefined' ? labels[option] : ''; #>{{{ label }}}",
				'prevent_empty' => false,
			]
		);

		$this->add_control(
			'exclude',
			[
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'label'       => __( 'Exclude Options', 'immonex-kickstart-elementor' ),
				'description' => __( 'Exclude selected options instead of explicitely including them.', 'immonex-kickstart-elementor' ),
			]
		);

		$this->add_control(
			'default',
			[
				'label'       => __( 'Default Option', 'immonex-kickstart-elementor' ),
				'description' => __( 'The default sorting option only needs to be selected here if it does not correspond to the first selection option and is not defined by GET parameter or filter function.', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => $sort_options['array'],
				'separator'   => 'before',
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

		$this->add_default_controls(
			'template',
			[
				'template' => [
					'folder' => 'property-list',
					'plugin' => 'immonex Kickstart',
				],
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
		$settings      = $this->get_settings_for_display();
		$template_data = [
			'settings' => $settings,
		];

		$ext_atts = [ 'template' ];

		$this->add_extended_sc_atts( $ext_atts, $template_data, 'property-list' );

		if ( ! empty( $settings['elements'] ) ) {
			$elements = [];

			foreach ( $settings['elements'] as $element ) {
				$elements[] = $element['option'];
			}

			$this->add_render_attribute( 'shortcode', $settings['exclude'] ? 'exclude' : 'elements', implode( ',', $elements ) );
		}

		if ( $settings['default'] ) {
			$this->add_render_attribute( 'shortcode', 'default', $settings['default'] );
		}

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'shortcode', 'is_preview', '1' );
		}

		$template_data['shortcode_output'] = do_shortcode( '[inx-filters-sort ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $template_data['shortcode_output'] ? $template_data : false;
	} // get_template_data

} // class Native_Filters_Sort_Widget
