<?php
/**
 * Class Native_Agency_List_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\Team;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Native Agency List Widget
 *
 * @since 1.0.0
 */
class Native_Agency_List_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const WIDGET_NAME               = 'inx-e-native-team-agency-list';
	const WIDGET_ICON               = 'eicon-gallery-grid';
	const WIDGET_CATEGORIES         = [ 'inx-team' ];
	const WIDGET_HELP_URL           = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/agenturliste';
	const ENABLE_RENDER_ON_PREVIEW  = true;
	const IS_DYNAMIC_CONTENT        = true;
	const PARENT_PLUGIN_NAME        = 'immonex Kickstart Team';
	const PARENT_PLUGIN_WP_REPO_URL = 'https://wordpress.org/plugins/immonex-kickstart-team/';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Agency List', 'immonex-kickstart-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'team', 'immonex-kickstart-elementor' ),
					__( 'agency', 'immonex-kickstart-elementor' ),
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

		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_main_class_control();
		$this->add_default_controls(
			[
				'lists',
				'team_common',
				'authors',
			],
			[ 'authors' => [ 'separator' => 'before' ] ]
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

		$this->add_render_attribute( 'shortcode', 'ignore-pagination', '1' );

		$ext_atts = [
			'limit',
			'limit-page',
			'demo',
		];

		foreach ( $ext_atts as $att ) {
			if ( ! empty( $settings[ $att ] ) ) {
				$this->add_render_attribute( 'shortcode', $att, $settings[ $att ] );
			}
		}

		if ( ! empty( $settings['order'] ) ) {
			$order = $settings['order'] . ' ' . $settings['order_dir'];
			$this->add_render_attribute( 'shortcode', 'order', $order );
		}

		$author_query_attr_value = $this->get_author_query_sc_attr_value( $settings );
		if ( $author_query_attr_value ) {
			$this->add_render_attribute( 'shortcode', 'author', $author_query_attr_value );
		}

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'shortcode', 'is_preview', '1' );
		}

		$shortcode_output = do_shortcode( '[inx-team-agency-list ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $shortcode_output ?
			[
				'settings'         => $settings,
				'shortcode_output' => $shortcode_output,
			] :
			false;
	} // get_template_data

} // class Native_Agency_List_Widget
