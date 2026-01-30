<?php
/**
 * Class Title_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Title Widget
 *
 * @since 1.0.0
 */
class Title_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const POST_TYPE         = 'inx_property';
	const WIDGET_NAME       = 'inx-e-single-property-title';
	const WIDGET_ICON       = 'eicon-post-title';
	const WIDGET_CATEGORIES = [ 'inx-single-property' ];
	const WIDGET_HELP_URL   = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/titel';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Title', 'immonex-kickstart-elementor' );
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
				[ __( 'title', 'immonex-kickstart-elementor' ) ]
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
			'html_tag',
			[
				'label'   => __( 'HTML Tag', 'immonex-kickstart-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->end_controls_section();

		$this->add_default_controls( [ 'heading_style' ] );
	} // register_controls

	/**
	 * Return widget contents and settings for frontend template rendering.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[]|bool Template data array or false if unavailable.
	 */
	protected function get_template_data() {
		$post_title = get_the_title( $this->get_post_id() );
		if ( ! $post_title ) {
			return false;
		}

		$settings = $this->get_settings_for_display();

		return array_merge( $settings, [ 'title' => $post_title ] );
	} // get_template_data

	/**
	 * Return demo contents for preview rendering.
	 *
	 * @since 1.0.0
	 *
	 * @param string[]|null $contents Source Demo content.
	 *
	 * @return string[]|null Demo contents.
	 */
	protected function get_demo_content( $contents = null ) {
		return parent::get_demo_content(
			[
				'title' => __( 'Spacious house in an excellent location', 'immonex-kickstart-elementor' ),
			]
		);
	} // get_demo_content

} // class Title_Widget
