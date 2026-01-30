<?php
/**
 * Class Native_Notify_Form_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\Notify;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Native Notify Form Widget
 *
 * @since 1.0.0
 */
class Native_Notify_Form_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const WIDGET_NAME              = 'inx-e-native-notify-form';
	const WIDGET_ICON              = 'eicon-mail';
	const WIDGET_CATEGORIES        = [ 'inx-marketing-acquisition' ];
	const WIDGET_HELP_URL          = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/suchagent-formular';
	const ENABLE_RENDER_ON_PREVIEW = true;
	const IS_DYNAMIC_CONTENT       = true;
	const PARENT_PLUGIN_NAME       = 'immonex Notify';
	const PARENT_PLUGIN_SHOP_URL   = 'https://plugins.inveris.de/wordpress-plugins/immonex-notify';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Search Agent Form', 'immonex-kickstart-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'notify', 'immonex-kickstart-elementor' ),
					__( 'search', 'immonex-kickstart-elementor' ),
					__( 'agent', 'immonex-kickstart-elementor' ),
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
		if ( ! $this->parent_plugin_available ) {
			return;
		}

		$form_elements      = apply_filters( 'immonex_notify_request_form_elements', [] );
		$element_options    = [];
		$default_elements   = [];
		$mandatory_elements = [];

		if ( ! empty( $form_elements ) ) {
			foreach ( $form_elements as $key => $element ) {
				$title = ! empty( $element['title'] ) ? $element['title'] : '';
				if ( ! $title ) {
					$title = ! empty( $element['placeholder'] ) ? $element['placeholder'] : $key;
				}

				$element_options[ $key ] = $title;

				if ( ! empty( $element['required'] ) ) {
					$default_elements[]   = [ 'element' => $key ];
					$mandatory_elements[] = $title;
				}
			}
		}

		$element_options_json = wp_json_encode( $element_options );

		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_main_class_control();

		$this->add_control(
			'form_scope',
			[
				'label'       => __( 'Form Scope', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => sprintf(
					/* translators: 1: Link open tag, 2: Link close tag. */
					__( 'See %1$sdetailed form description%2$s.', 'immonex-kickstart-elementor' ),
					'<a href="https://docs.immonex.de/notify/#/immobilien-suchauftraege/frontend-formular" target="_blank">',
					'</a>'
				),
				'default'     => 'compact',
				'options'     => [
					'compact'      => __( 'compact', 'immonex-kickstart-elementor' ),
					'all'          => __( 'complete', 'immonex-kickstart-elementor' ),
					'user-defined' => __( 'user-defined', 'immonex-kickstart-elementor' ),
				],
			]
		);

		$this->add_control(
			'mandatory_elements_notice',
			[
				'type'        => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'content'     => wp_sprintf(
					__( 'The following mandatory elements are always included in the form:', 'immonex-kickstart-elementor' ) . '<br>%s',
					implode( ', ', $mandatory_elements )
				),
				'condition'   => [
					'form_scope' => 'user-defined',
				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'element',
			[
				'label'       => __( 'Element', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => $element_options,
			]
		);

		$this->add_control(
			'form_elements',
			[
				'label'         => __( 'User-defined Elements', 'immonex-kickstart-elementor' ),
				'type'          => \Elementor\Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'title_field'   => "<# const labels = {$element_options_json}; const label = labels[element]; #>{{{ label }}}",
				'prevent_empty' => false,
				'default'       => $default_elements,
				'condition'     => [
					'form_scope' => 'user-defined',
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
		if ( ! $this->parent_plugin_available ) {
			return false;
		}

		$settings = $this->get_settings_for_display();

		if ( 'user-defined' === $settings['form_scope'] ) {
			$elements = [];

			foreach ( $settings['form_elements'] as $element ) {
				if ( ! in_array( $element['element'], $elements, true ) ) {
					$elements[] = $element['element'];
				}
			}

			$this->add_render_attribute( 'shortcode', 'elements', implode( ',', $elements ) );
		} else {
			$this->add_render_attribute( 'shortcode', 'elements', $settings['form_scope'] );
		}

		$shortcode_output = do_shortcode( '[immonex-notify-form ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $shortcode_output ? [
			'settings'         => $settings,
			'shortcode_output' => $shortcode_output,
		] :
		false;
	} // get_template_data

} // class Native_Notify_Form_Widget
