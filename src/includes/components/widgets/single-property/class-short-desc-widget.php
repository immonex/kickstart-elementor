<?php
/**
 * Class Short_Desc_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Short Description Widget
 *
 * @since 1.0.0
 */
class Short_Desc_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const POST_TYPE         = 'inx_property';
	const WIDGET_NAME       = 'inx-e-single-property-short-desc';
	const WIDGET_ICON       = 'eicon-post-excerpt';
	const WIDGET_CATEGORIES = [ 'inx-single-property' ];
	const WIDGET_HELP_URL   = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/kurzbeschreibung';

	const DEFAULT_MAX_LENGTH          = 256;
	const DEFAULT_CONTINUATION_POINTS = '…';
	const DEFAULT_P_WRAP_STATE        = 'yes';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Short Description', 'immonex-kickstart-elementor' );
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
					__( 'excerpt', 'immonex-kickstart-elementor' ),
					__( 'short', 'immonex-kickstart-elementor' ),
					__( 'description', 'immonex-kickstart-elementor' ),
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

		$this->add_main_class_control();

		$this->add_control(
			'max_length',
			[
				'label'       => __( 'Max. Length', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'description' => __( '16 up to 1,024 characters', 'immonex-kickstart-elementor' ),
				'placeholder' => '0',
				'min'         => 16,
				'max'         => 1024,
				'step'        => 1,
				'default'     => static::DEFAULT_MAX_LENGTH,
			]
		);

		$this->add_control(
			'continuation_points',
			[
				'label'   => __( 'Continuation Points', 'immonex-kickstart-elementor' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => static::DEFAULT_CONTINUATION_POINTS,
			]
		);

		$this->add_control(
			'p_wrap',
			[
				'label'   => __( 'Paragraph Wrap', 'immonex-kickstart-elementor' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => static::DEFAULT_P_WRAP_STATE,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'body_text_section',
			[
				'label' => __( 'Body Text', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'body_text_align',
			[
				'label'     => __( 'Alignment', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => __( 'Left', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => __( 'Right', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .inx-e-short-desc' => 'text-align: {{VALUE}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'body_text_color',
			[
				'label'     => __( 'Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-e-short-desc' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'body_text_typography',
				'selector' => '{{WRAPPER}} .inx-e-short-desc',
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
		$excerpt = get_the_excerpt( $this->get_post_id() );
		if ( ! $excerpt ) {
			return false;
		}

		// phpcs:ignore
		$utils = apply_filters( 'inx_elementor_get_utils', [] );
		if ( empty( $utils ) ) {
			return false;
		}

		$settings = $this->get_settings_for_display();
		$excerpt  = $utils['string']->get_excerpt( $excerpt, $settings['max_length'], $settings['continuation_points'] );

		return $excerpt ? array_merge( $settings, [ 'excerpt' => $excerpt ] ) : false;
	} // get_template_data

	/**
	 * Return demo contents for preview rendering.
	 *
	 * @since 1.0.0
	 *
	 * @param string[]|null $contents Source Demo content (optional).
	 *
	 * @return string[]|null Demo contents.
	 */
	protected function get_demo_content( $contents = null ) {
		return parent::get_demo_content(
			[
				'short_desc' => __( "Don't miss out on this extraordinary chance to own a stunning freestanding single-family home with captivating ocean views in the charming town of Demo. Located just an hour and a half outside the city, the potential for this home is boundless!", 'immonex-kickstart-elementor' ),
			]
		);
	} // get_demo_content

} // class Short_Desc_Widget
