<?php
/**
 * Class Native_Lead_Forms_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\LeadGenerator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Native Lead Generation Forms Widget
 *
 * @since 1.0.0
 */
class Native_Lead_Forms_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const WIDGET_NAME              = 'inx-e-native-lead-forms';
	const WIDGET_ICON              = 'eicon-price-table';
	const WIDGET_CATEGORIES        = [ 'inx-marketing-acquisition' ];
	const WIDGET_HELP_URL          = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/lead-generator';
	const ENABLE_RENDER_ON_PREVIEW = true;
	const IS_DYNAMIC_CONTENT       = true;
	const PARENT_PLUGIN_NAME       = 'immonex Lead Generator';
	const PARENT_PLUGIN_SHOP_URL   = 'https://plugins.inveris.de/wordpress-plugins/immonex-lead-generator';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Lead Generator', 'immonex-kickstart-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'lead', 'immonex-kickstart-elementor' ),
					__( 'forms', 'immonex-kickstart-elementor' ),
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
		if ( ! $this->parent_plugin_available ) {
			return;
		}

		$lead_gen_options     = apply_filters( 'immonex_options', [], 'lead_gen' );
		$additional_form_sets = ! empty( $lead_gen_options['additional_form_sets'] ) ?
			(int) $lead_gen_options['additional_form_sets'] : 0;

		if ( ! empty( $lead_gen_options['property_type_form_data'] ) ) {
			$ff_options = [
				'' => __( 'Default', 'immonex-kickstart-elementor' ),
			];

			foreach ( $lead_gen_options['property_type_form_data'] as $i => $option ) {
				$ff_options[ (string) $i ] = $option['form_title'];
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

		if ( $additional_form_sets > 0 ) {
			$options = [
				'' => __( 'Default Form', 'immonex-kickstart-elementor' ),
			];

			for ( $i = 1; $i <= $additional_form_sets; $i++ ) {
				$options[ (string) $i ] = (string) $i;
			}

			$this->add_control(
				'form_set_id',
				[
					'label'       => __( 'Form Set ID', 'immonex-kickstart-elementor' ),
					'type'        => \Elementor\Controls_Manager::SELECT,
					'description' => sprintf(
						/* translators: 1: Link open tag, 2: Link close tag. */
						__( 'If an %1$salternative set of property type forms%2$s shall be embedded, select its ID here.', 'immonex-kickstart-elementor' ),
						'<a href="https://docs.immonex.de/lead-generator/#/installation-einrichtung/einbindung?id=formularset" target="_blank">',
						'</a>'
					),
					'options'     => $options,
				]
			);
		}

		$this->add_control(
			'fast-forward',
			[
				'label'       => __( 'Fast Forward', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => sprintf(
					/* translators: 1: Link open tag, 2: Link close tag. */
					__( 'A %1$sspecific property type form%2$s to start with can be selected here.', 'immonex-kickstart-elementor' ),
					'<a href="https://docs.immonex.de/lead-generator/#/installation-einrichtung/einbindung?id=fast-forward" target="_blank">',
					'</a>'
				),
				'options'     => $ff_options,
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
		if ( ! $this->parent_plugin_available ) {
			return false;
		}

		$template_data = [
			'settings' => $this->get_settings_for_display(),
		];

		$ext_atts = [ 'form_set_id', 'fast-forward' ];

		$this->add_extended_sc_atts( $ext_atts, $template_data );

		$template_data['shortcode_output'] = do_shortcode( '[immonex-lead-generator ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $template_data['shortcode_output'] ? $template_data : false;
	} // get_template_data

} // class Native_Lead_Forms_Widget
