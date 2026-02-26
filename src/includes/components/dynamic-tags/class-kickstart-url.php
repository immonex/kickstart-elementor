<?php
/**
 * Class Kickstart_URL
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor\Components\DynamicTags;

/**
 * Kickstart URL Tag
 */
class Kickstart_URL extends \Elementor\Core\DynamicTags\Data_Tag {

	/**
	 * Get dynamic tag name.
	 *
	 * Retrieve the name of the tag.
	 *
	 * @return string Dynamic tag name.
	 */
	public function get_name() {
		return 'inx-url';
	} // get_name

	/**
	 * Get dynamic tag title.
	 *
	 * Returns the title of the tag.
	 *
	 * @return string Dynamic tag title.
	 */
	public function get_title() {
		return esc_html__( 'Kickstart URL', 'immonex-kickstart-for-elementor' );
	}

	/**
	 * Get dynamic tag groups.
	 *
	 * Retrieve the list of groups the tag belongs to.
	 *
	 * @return array Dynamic tag groups.
	 */
	public function get_group() {
		return [ 'inx' ];
	} // get_group

	/**
	 * Get dynamic tag categories.
	 *
	 * Retrieve the list of categories the tag belongs to.
	 *
	 * @return array Dynamic tag categories.
	 */
	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::URL_CATEGORY ];
	} // get_categories

	/**
	 * Register dynamic tag controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->add_control(
			'type',
			[
				'label'       => __( 'Type', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '',
				'options'     => [
					''         => __( 'Auto', 'immonex-kickstart-for-elementor' ),
					'detail'   => __( 'Property Details', 'immonex-kickstart-for-elementor' ),
					'backlink' => wp_sprintf( 'Backlink (%s)', __( 'Overview', 'immonex-kickstart-for-elementor' ) ),
				],
				'label_block' => true,
			]
		);
	} // register_controls

	/**
	 * Get dynamic tag value.
	 *
	 * @param mixed[] $options Dynamic tag options (optional).
	 *
	 * @return string Dynamic tag value.
	 */
	public function get_value( $options = [] ) {
		$type = $this->get_settings( 'type' );

		if ( ! $type && 'inx_property' === get_post_type() ) {
			$type = is_singular( 'inx_property' ) ? 'backlink' : 'detail';
		}

		if ( ! $type ) {
			return get_permalink();
		}

		$template_data = apply_filters( 'inx_get_property_template_data', [], [ 'post_id' => get_the_ID() ] ); // phpcs:ignore -- Parent plugin filter hook that can't be changed (yet) for compatibility reasons.

		if ( 'detail' === $type && ! empty( $template_data['url'] ) ) {
			return $template_data['url'];
		} elseif ( 'backlink' === $type && ! empty( $template_data['overview_url'] ) ) {
			return $template_data['overview_url'];
		}

		return get_permalink();
	} // get_value

} // class Kickstart_URL
