<?php
/**
 * Class Feature_List_Widget
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Feature List Widget
 *
 * @since 1.0.0
 */
class Feature_List_Widget extends \immonex\Kickstart\ForElementor\Components\Widgets\Icon_List_Widget {

	const WIDGET_NAME     = 'inx-e-single-property-feature-list';
	const WIDGET_ICON     = 'eicon-checkbox';
	const WIDGET_HELP_URL = 'https://docs.immonex.de/kickstart-for-elementor/#/elementor-immobilien-widgets/ausstattungsliste';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Feature List', 'immonex-kickstart-for-elementor' );
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
				[ __( 'features', 'immonex-kickstart-for-elementor' ) ]
			)
		);
	} // add_keywords

	/**
	 * Return widget contents and settings for frontend template rendering.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[]|bool Template data array or false if unavailable.
	 */
	protected function get_template_data() {
		$template_data = parent::get_template_data();
		$features      = get_the_terms( $this->get_post_id(), 'inx_feature' ); // phpcs:ignore -- Parent plugin filter hook that can't be changed (yet) for compatibility reasons.

		if ( empty( $features ) || is_wp_error( $features ) ) {
			return false;
		}

		foreach ( $features as $feature ) {
			$template_data['items'][] = esc_html( $feature->name );
		}

		return $template_data;
	} // get_template_data

	/**
	 * Return demo contents for preview rendering.
	 *
	 * @since 1.0.0
	 *
	 * @param string[]|null $content Source Demo content.
	 *
	 * @return string|null Demo content or empty string.
	 */
	protected function get_demo_content( $content = null ) {
		return parent::get_demo_content(
			[
				'items' => [
					__( 'Air Condition', 'immonex-kickstart-for-elementor' ),
					__( 'Fireplace', 'immonex-kickstart-for-elementor' ),
					__( 'Sauna', 'immonex-kickstart-for-elementor' ),
					__( 'Guest Toilet', 'immonex-kickstart-for-elementor' ),
					__( 'Garden Use', 'immonex-kickstart-for-elementor' ),
					__( 'Pool', 'immonex-kickstart-for-elementor' ),
				],
			]
		);
	} // get_demo_content

} // class Feature_List_Widget
