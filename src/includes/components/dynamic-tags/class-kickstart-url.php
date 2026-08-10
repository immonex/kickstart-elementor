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
				'label'       => __( 'Destination Page', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '',
				'groups'      => [
					[
						'label'   => __( 'General', 'immonex-kickstart-for-elementor' ),
						'options' => [
							'' => __( 'Automatic', 'immonex-kickstart-for-elementor' )
								. ' (' . __( 'Property Details or Overview Page', 'immonex-kickstart-for-elementor' ) . ')',
						],
					],
					[
						'label'   => __( 'Property Lists', 'immonex-kickstart-for-elementor' ),
						'options' => [
							'detail' => __( 'Property Details', 'immonex-kickstart-for-elementor' ),
						],
					],
					[
						'label'   => __( 'Property Detail Pages', 'immonex-kickstart-for-elementor' ),
						'options' => [
							'backlink' => wp_sprintf( 'Backlink (%s)', __( 'Overview', 'immonex-kickstart-for-elementor' ) ),
							'first'    => __( 'First Object', 'immonex-kickstart-for-elementor' ),
							'prev'     => __( 'Previous Object', 'immonex-kickstart-for-elementor' ),
							'next'     => __( 'Next Object', 'immonex-kickstart-for-elementor' ),
							'last'     => __( 'Last Object', 'immonex-kickstart-for-elementor' ),
						],
					],
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

		if ( ! $type && 'inx_property' === get_post_type() ) {
			return get_permalink();
		}

		$template_data = apply_filters( 'inx_get_property_template_data', [], [ 'post_id' => get_the_ID() ] ); // phpcs:ignore -- Parent plugin filter hook that can't be changed (yet) for compatibility reasons.

		switch ( $type ) {
			case 'detail':
				if ( ! empty( $template_data['url'] ) ) {
					return $template_data['url'];
				}
				break;
			case 'backlink':
				if ( ! empty( $template_data['overview_url'] ) ) {
					return $template_data['overview_url'];
				}
				break;
			case 'first':
				if ( ! empty( $template_data['inter_post_nav']['first_url'] ) ) {
					return $template_data['inter_post_nav']['first_url'];
				}
				break;
			case 'prev':
				if ( ! empty( $template_data['inter_post_nav']['prev_url'] ) ) {
					return $template_data['inter_post_nav']['prev_url'];
				}
				break;
			case 'next':
				if ( ! empty( $template_data['inter_post_nav']['next_url'] ) ) {
					return $template_data['inter_post_nav']['next_url'];
				}
				break;
			case 'last':
				if ( ! empty( $template_data['inter_post_nav']['last_url'] ) ) {
					return $template_data['inter_post_nav']['last_url'];
				}
				break;
		}

		return '';
	} // get_value

} // class Kickstart_URL
