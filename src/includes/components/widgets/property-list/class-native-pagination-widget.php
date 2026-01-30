<?php
/**
 * Class Native_Pagination_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\PropertyList;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Native Pagination Widget
 *
 * @since 1.0.0
 */
class Native_Pagination_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const WIDGET_NAME              = 'inx-e-native-pagination';
	const WIDGET_ICON              = 'eicon-navigation-horizontal';
	const WIDGET_CATEGORIES        = [ 'inx-property-list' ];
	const WIDGET_HELP_URL          = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/seitennavigation';
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
		return __( 'Pagination', 'immonex-kickstart-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'pagination', 'immonex-kickstart-elementor' ),
					__( 'navigation', 'immonex-kickstart-elementor' ),
				]
			)
		);
	} // add_keywords

	/**
	 * Return widget contents for frontend template rendering.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[]|bool Template data array or false if unavailable.
	 */
	protected function get_template_data() {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'shortcode', 'is_preview', '1' );
		}

		$shortcode_output = do_shortcode( '[inx-pagination ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $shortcode_output ?
			[
				'settings'         => $this->get_settings_for_display(),
				'shortcode_output' => $shortcode_output,
			] :
			false;
	} // get_template_data

} // class Native_Pagination_Widget
