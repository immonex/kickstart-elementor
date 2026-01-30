<?php
/**
 * Class Native_Property_Carousel_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\PropertyList;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Native Property List Widget
 *
 * @since 1.0.0
 */
class Native_Property_Carousel_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\PropertyList\Native_Property_List_Widget {

	const WIDGET_NAME       = 'inx-e-native-property-carousel';
	const WIDGET_ICON       = 'eicon-posts-carousel';
	const WIDGET_CATEGORIES = [ 'inx-property-list' ];
	const WIDGET_HELP_URL   = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/karussell';
	const TEMPLATE          = 'slider';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Carousel', 'immonex-kickstart-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'carousel', 'immonex-kickstart-elementor' ),
				]
			)
		);
	} // add_keywords

} // class Native_Property_Carousel_Widget
