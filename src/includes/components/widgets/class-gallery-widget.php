<?php
/**
 * Class Gallery_Widget
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor\Components\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Gallery Base Widget
 *
 * @since 1.0.0
 */
class Gallery_Widget extends Widget_Base {

	const POST_TYPE                = 'inx_property';
	const WIDGET_NAME              = 'inx-e-gallery';
	const WIDGET_ICON              = 'eicon-gallery-grid';
	const WIDGET_CATEGORIES        = [ 'inx-single-property' ];
	const ENABLE_RENDER_ON_PREVIEW = true;

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Gallery', 'immonex-kickstart-for-elementor' );
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
					__( 'image', 'immonex-kickstart-for-elementor' ),
					__( 'photo', 'immonex-kickstart-for-elementor' ),
					__( 'video', 'immonex-kickstart-for-elementor' ),
					__( 'virtual', 'immonex-kickstart-for-elementor' ),
					__( 'tour', 'immonex-kickstart-for-elementor' ),
					__( 'gallery', 'immonex-kickstart-for-elementor' ),
					'360',
				]
			)
		);
	} // add_keywords

} // class Gallery_Widget
