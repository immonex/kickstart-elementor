<?php
/**
 * Class Native_Contact_Form_Confirmation_Message_Widget
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor\Components\Widgets\KickstartTeam;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Native Contact Form Confirmation Message Widget
 *
 * @since 1.4.0
 */
class Native_Contact_Form_Confirmation_Message_Widget extends \immonex\Kickstart\ForElementor\Components\Widgets\Widget_Base {

	const WIDGET_NAME              = 'inx-e-native-contact-form-confirmation-message';
	const WIDGET_ICON              = 'eicon-text';
	const WIDGET_CATEGORIES        = [ 'inx-single-property', 'inx-team' ];
	const WIDGET_HELP_URL          = 'https://docs.immonex.de/kickstart-for-elementor/#/elementor-immobilien-widgets/kontaktperson-formular';
	const ENABLE_RENDER_ON_PREVIEW = true;

	/**
	 * Get widget title.
	 *
	 * @since 1.4.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Contact Form Confirmation Message', 'immonex-kickstart-for-elementor' ) . self::NATIVE_POSTFIX;
	} // get_title

	/**
	 * Add widget keywords.
	 *
	 * @since 1.4.0
	 */
	protected function add_keywords() {
		parent::add_keywords();

		$this->keywords = array_unique(
			array_merge(
				$this->keywords,
				[
					__( 'contact', 'immonex-kickstart-for-elementor' ),
					__( 'form', 'immonex-kickstart-for-elementor' ),
					__( 'confirmation', 'immonex-kickstart-for-elementor' ),
					__( 'message', 'immonex-kickstart-for-elementor' ),
				]
			)
		);
	} // add_keywords

	/**
	 * Register widget controls.
	 *
	 * @since 1.4.0
	 */
	protected function register_controls() {
		if ( ! $this->parent_plugin_available ) {
			return;
		}

		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_main_class_control();

		$this->add_control(
			'general_component_info',
			[
				'type'        => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'content'     => wp_sprintf(
					/* translators: %s = plugin options tab URL */
					__( 'This widget displays the confirmation message entered in the <a href="%s" target="_blank">contact form tab of the Team add-on plugin options</a>.', 'immonex-kickstart-for-elementor' ),
					admin_url( 'admin.php?page=immonex-kickstart_settings&tab=addon_team&section_tab=5' )
				),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'text_style_section',
			[
				'label' => __( 'Text', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label'     => __( 'Alignment', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'immonex-kickstart-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'immonex-kickstart-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'immonex-kickstart-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .inx-team-contact-form-confirmation-message' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Color', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-team-contact-form-confirmation-message' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .inx-team-contact-form-confirmation-message',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'text_shadow',
				'selector' => '{{WRAPPER}} .inx-team-contact-form-confirmation-message',
			]
		);
	} // register_controls

	/**
	 * Return widget contents for frontend template rendering.
	 *
	 * @since 1.4.0
	 *
	 * @return mixed[]|bool Template data array or false if unavailable.
	 */
	protected function get_template_data() {
		if ( ! $this->parent_plugin_available ) {
			return false;
		}

		$settings = $this->get_settings_for_display();

		$shortcode_output = do_shortcode( '[inx-team-contact-form-confirmation-message]' );

		return $shortcode_output ? [
			'settings'         => $settings,
			'shortcode_output' => $shortcode_output,
		] :
		false;
	} // get_template_data

} // class Native_Contact_Form_Confirmation_Message_Widget
