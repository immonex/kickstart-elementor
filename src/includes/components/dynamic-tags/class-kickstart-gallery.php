<?php
/**
 * Class Kickstart_Gallery
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\DynamicTags;

/**
 * Kickstart Gallery Dynamic Data Tag
 */
class Kickstart_Gallery extends \Elementor\Core\DynamicTags\Data_Tag {

	/**
	 * Get dynamic tag name.
	 *
	 * Retrieve the name of the tag.
	 *
	 * @return string Dynamic tag name.
	 */
	public function get_name() {
		return 'inx-gallery';
	} // get_name

	/**
	 * Get dynamic tag title.
	 *
	 * Returns the title of the tag.
	 *
	 * @return string Dynamic tag title.
	 */
	public function get_title() {
		return __( 'Kickstart Gallery', 'immonex-kickstart-elementor' );
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
		return [ \Elementor\Modules\DynamicTags\Module::GALLERY_CATEGORY ];
	} // get_categories

	/**
	 * Register dynamic tag controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		// Keep control key and custom field names as option keys for compatibility with the previous version.
		$this->add_control(
			'gallery_type',
			[
				'label'       => __( 'Image Type/Source', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '_inx_gallery_images',
				'options'     => [
					'_inx_gallery_images' => __( 'Main Gallery Images', 'immonex-kickstart-elementor' ),
					'_inx_floor_plans'    => __( 'Floor Plans', 'immonex-kickstart-elementor' ),
					'_inx_epass_images'   => __( 'Energy Pass Images', 'immonex-kickstart-elementor' ),
					'custom_field'        => __( 'Custom Field(s)', 'immonex-kickstart-elementor' ),
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'image_selection_custom_field',
			[
				'label'       => __( 'Image ID Field(s)', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Comma-separated list of <strong>custom field names</strong> containing the (attachment post) IDs of the images to include in the gallery.', 'immonex-kickstart-elementor' ),
				'condition'   => [
					'gallery_type' => 'custom_field',
				],
				'label_block' => true,
			]
		);
	} // register_controls

	/**
	 * Get dynamic tag value (images).
	 *
	 * @param mixed[] $options Dynamic tag options (optional).
	 *
	 * @return mixed[] Dynamic tag value (image IDs in associative sub-arrays).
	 */
	public function get_value( $options = [] ) {
		$type = $this->get_settings( 'gallery_type' );

		if ( 'custom_field' === $type ) {
			$type = $this->get_settings( 'image_selection_custom_field' );
		} else {
			$type_mapping = [
				'_inx_gallery_images' => 'gallery',
				'_inx_floor_plans'    => 'floor_plans',
				'_inx_epass_images'   => 'epass_images',
			];

			$type = $type_mapping[ $type ];
		}

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$demo_image_ids = \immonex\Kickstart\Elementor\Demo_Media::get_demo_image_ids();
			$image_ids      = $type && isset( $demo_image_ids[ $type ] ) ?
				$demo_image_ids[ $type ] :
				$demo_image_ids['gallery'];
		} elseif ( ! $type ) {
			return [];
		} else {
			$property_id = apply_filters( 'inx_current_property_post_id', get_the_id() );
			$image_ids   = apply_filters(
				'inx_get_property_images',
				[],
				$property_id,
				[
					'type'   => $type,
					'return' => 'ids',
				]
			);
		}

		if ( empty( $image_ids ) || ! is_array( $image_ids ) ) {
			return [];
		}

		$images = [];

		foreach ( $image_ids as $id ) {
			$images[] = [
				'id' => $id,
			];
		}

		return $images;
	} // get_value

} // class Kickstart_Gallery
