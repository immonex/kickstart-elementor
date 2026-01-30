<?php
/**
 * Class Basic_Gallery_Widget
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
class Basic_Gallery_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Gallery_Widget {

	const WIDGET_NAME     = 'inx-e-single-property-basic-gallery';
	const WIDGET_HELP_URL = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/basisgalerie';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Basic Gallery', 'immonex-kickstart-elementor' );
	} // get_title

	/**
	 * Register widget controls.
	 *
	 * @since 1.0.0
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'general_section',
			[
				'label' => __( 'General', 'immonex-kickstart-elementor' ),
			]
		);

		$this->add_main_class_control();

		$this->add_control(
			'gallery_image_type',
			[
				'label'       => __( 'Image Type/Source', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'gallery',
				'options'     => [
					'gallery'      => __( 'Main Gallery Images', 'immonex-kickstart-elementor' ),
					'floor_plans'  => __( 'Floor Plans', 'immonex-kickstart-elementor' ),
					'epass_images' => __( 'Energy Pass Images', 'immonex-kickstart-elementor' ),
					'custom_field' => __( 'Custom Field(s)', 'immonex-kickstart-elementor' ),
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
					'gallery_image_type' => 'custom_field',
				],
				'label_block' => true,
			]
		);

		$this->add_default_controls(
			[ 'heading' ],
			[
				'heading' => [
					'separator' => 'before',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name'    => 'thumbnail',
				'exclude' => [ 'custom' ],
			]
		);

		$gallery_columns = range( 1, 10 );
		$gallery_columns = array_combine( $gallery_columns, $gallery_columns );

		$this->add_control(
			'gallery_columns',
			[
				'label'   => __( 'Columns', 'immonex-kickstart-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 4,
				'options' => $gallery_columns,
			]
		);

		$this->add_control(
			'gallery_display_caption',
			[
				'label'     => __( 'Caption', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					'none' => __( 'None', 'immonex-kickstart-elementor' ),
					''     => __( 'Attachment Caption', 'immonex-kickstart-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .gallery-item .gallery-caption' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'open_lightbox',
			[
				'label'       => __( 'Lightbox', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => sprintf(
					/* translators: 1: Link open tag, 2: Link close tag. */
					__( 'Manage your site’s lightbox settings in the %1$sLightbox panel%2$s.', 'immonex-kickstart-elementor' ),
					'<a href="javascript: $e.run( \'panel/global/open\' ).then( () => $e.route( \'panel/global/settings-lightbox\' ) )">',
					'</a>'
				),
				'default'     => 'default',
				'options'     => [
					'default' => __( 'Default', 'immonex-kickstart-elementor' ),
					'yes'     => __( 'Yes', 'immonex-kickstart-elementor' ),
					'no'      => __( 'No', 'immonex-kickstart-elementor' ),
				],
			]
		);

		$this->add_control(
			'gallery_rand',
			[
				'label'   => __( 'Order By', 'immonex-kickstart-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''     => __( 'Default', 'immonex-kickstart-elementor' ),
					'rand' => __( 'Random', 'immonex-kickstart-elementor' ),
				],
				'default' => '',
			]
		);

		$this->end_controls_section();

		$this->add_default_controls( [ 'heading_style' ] );

		$this->start_controls_section(
			'gallery_images_section',
			[
				'label' => __( 'Images', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_spacing',
			[
				'label'        => __( 'Spacing', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'options'      => [
					''       => __( 'Default', 'immonex-kickstart-elementor' ),
					'custom' => __( 'Custom', 'immonex-kickstart-elementor' ),
				],
				'prefix_class' => 'gallery-spacing-',
				'default'      => '',
			]
		);

		$columns_margin  = is_rtl() ? '0 0 -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}};' : '0 -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}} 0;';
		$columns_padding = is_rtl() ? '0 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};' : '0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0;';

		$this->add_control(
			'image_spacing_custom',
			[
				'label'      => __( 'Custom Spacing', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px'  => [
						'max' => 100,
					],
					'em'  => [
						'max' => 10,
					],
					'rem' => [
						'max' => 10,
					],
				],
				'default'    => [
					'size' => 15,
				],
				'selectors'  => [
					'{{WRAPPER}} .gallery-item' => 'padding:' . $columns_padding,
					'{{WRAPPER}} .gallery'      => 'margin: ' . $columns_margin,
				],
				'condition'  => [
					'image_spacing' => 'custom',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .gallery-item img',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => __( 'Corner Radius', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .gallery-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'caption_section',
			[
				'label'     => __( 'Caption', 'immonex-kickstart-elementor' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'gallery_display_caption' => '',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Alignment', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'default'   => $this->get_default( 'align', 'center' ),
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
					'{{WRAPPER}} .gallery-item .gallery-caption' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'gallery_display_caption' => '',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .gallery-item .gallery-caption' => 'color: {{VALUE}};',
				],
				'condition' => [
					'gallery_display_caption' => '',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'typography',
				'global'    => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'  => '{{WRAPPER}} .gallery-item .gallery-caption',
				'condition' => [
					'gallery_display_caption' => '',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'      => 'caption_shadow',
				'selector'  => '{{WRAPPER}} .gallery-item .gallery-caption',
				'condition' => [
					'gallery_display_caption' => '',
				],
			]
		);

		$this->add_responsive_control(
			'caption_space',
			[
				'label'      => __( 'Spacing', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .gallery-item .gallery-caption' => 'margin-block-start: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'gallery_display_caption' => '',
				],
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
		$settings = $this->get_settings_for_display();
		$kit      = \Elementor\Plugin::$instance->kits_manager->get_active_kit();

		$type = 'custom_field' === $settings['gallery_image_type'] ?
			$settings['image_selection_custom_field'] :
			$settings['gallery_image_type'];

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$demo_image_ids = \immonex\Kickstart\Elementor\Demo_Media::get_demo_image_ids();
			$image_ids      = $type && isset( $demo_image_ids[ $type ] ) ?
				$demo_image_ids[ $type ] :
				$demo_image_ids['gallery'];
		} elseif ( ! $type ) {
			return false;
		} else {
			$image_ids = apply_filters(
				'inx_get_property_images',
				[],
				$this->get_post_id(),
				[
					'type'   => $type,
					'return' => 'ids',
				]
			);
		}

		if ( empty( $image_ids ) ) {
			return false;
		}

		$this->add_render_attribute( 'shortcode', 'ids', implode( ',', $image_ids ) );
		$this->add_render_attribute( 'shortcode', 'size', $settings['thumbnail_size'] );

		if ( $settings['gallery_columns'] ) {
			$this->add_render_attribute( 'shortcode', 'columns', $settings['gallery_columns'] );
		}

		if (
			'yes' === $settings['open_lightbox']
			|| (
				'default' === $settings['open_lightbox']
				&& 'yes' === $kit->get_settings( 'global_image_lightbox' )
			)
		) {
			$this->add_render_attribute( 'shortcode', 'link', 'file' );
		} else {
			$this->add_render_attribute( 'shortcode', 'link', 'none' );
		}

		if ( ! empty( $settings['gallery_rand'] ) ) {
			$this->add_render_attribute( 'shortcode', 'orderby', $settings['gallery_rand'] );
		}

		add_filter( 'wp_get_attachment_link', [ $this, 'add_lightbox_data_to_image_link' ], 10, 2 );
		$shortcode_output = do_shortcode( '[gallery ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );
		remove_filter( 'wp_get_attachment_link', [ $this, 'add_lightbox_data_to_image_link' ] );

		return $shortcode_output ?
			[
				'settings'          => $settings,
				'h_tag'             => $this->get_h_tag( $settings['heading_level'] ),
				'gallery_shortcode' => $shortcode_output,
			] :
			false;
	} // get_template_data

} // class Basic_Gallery_Widget
