<?php
/**
 * Class Main_Image_Widget
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
class Main_Image_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const POST_TYPE              = 'inx_property';
	const WIDGET_NAME            = 'inx-e-single-property-main-image';
	const WIDGET_ICON            = 'eicon-image';
	const WIDGET_CATEGORIES      = [ 'inx-single-property' ];
	const WIDGET_HELP_URL        = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/hauptbild';
	const DEFAULT_CONTROL_SCOPES = [];
	const DEMO_IMAGE_FILENAME    = 'main-lycs-architecture-744233-unsplash.jpg';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Main Image', 'immonex-kickstart-elementor' );
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
					__( 'image', 'immonex-kickstart-elementor' ),
					__( 'photo', 'immonex-kickstart-elementor' ),
				]
			)
		);
	} // add_keywords

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the widget requires.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[] Widget style dependencies.
	 */
	public function get_style_depends(): array {
		return [ 'widget-image' ];
	} // get_style_depends

	/**
	 * Register widget controls.
	 *
	 * @since 1.0.0
	 */
	protected function register_controls() {
		$demo_image = $this->get_demo_image();

		$this->start_controls_section(
			'image_section',
			[
				'label' => __( 'Image', 'immonex-kickstart-elementor' ),
			]
		);

		$this->add_main_class_control();

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				// phpcs:ignore
				'name'    => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default' => 'large',
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

		$this->end_controls_section();

		$this->start_controls_section(
			'style_image_section',
			[
				'label' => __( 'Image', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Alignment', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label'      => __( 'Width', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default( 'width', [ 'unit' => '%' ] ),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'      => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'space',
			[
				'label'      => __( 'Max Width', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default( 'space', [ 'unit' => '%' ] ),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'      => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label'      => __( 'Height', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'object-fit',
			[
				'label'     => __( 'Object Fit', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'condition' => [
					'height[size]!' => '',
				],
				'options'   => [
					''           => __( 'Default', 'immonex-kickstart-elementor' ),
					'fill'       => __( 'Fill', 'immonex-kickstart-elementor' ),
					'cover'      => __( 'Cover', 'immonex-kickstart-elementor' ),
					'contain'    => __( 'Contain', 'immonex-kickstart-elementor' ),
					'scale-down' => __( 'Scale Down', 'immonex-kickstart-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} img' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'object-position',
			[
				'label'     => __( 'Object Position', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => $this->get_default( 'object-position', 'center center' ),
				'options'   => [
					'center center' => __( 'Center Center', 'immonex-kickstart-elementor' ),
					'center left'   => __( 'Center Left', 'immonex-kickstart-elementor' ),
					'center right'  => __( 'Center Right', 'immonex-kickstart-elementor' ),
					'top center'    => __( 'Top Center', 'immonex-kickstart-elementor' ),
					'top left'      => __( 'Top Left', 'immonex-kickstart-elementor' ),
					'top right'     => __( 'Top Right', 'immonex-kickstart-elementor' ),
					'bottom center' => __( 'Bottom Center', 'immonex-kickstart-elementor' ),
					'bottom left'   => __( 'Bottom Left', 'immonex-kickstart-elementor' ),
					'bottom right'  => __( 'Bottom Right', 'immonex-kickstart-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} img' => 'object-position: {{VALUE}};',
				],
				'condition' => [
					'height[size]!' => '',
					'object-fit'    => [ 'cover', 'contain', 'scale-down' ],
				],
			]
		);

		$this->add_control(
			'separator_panel_style',
			[
				'type'  => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab(
			'normal',
			[
				'label' => __( 'Normal', 'immonex-kickstart-elementor' ),
			]
		);

		$this->add_control(
			'opacity',
			[
				'label'     => __( 'Opacity', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			[
				'label' => __( 'Hover', 'immonex-kickstart-elementor' ),
			]
		);

		$this->add_control(
			'opacity_hover',
			[
				'label'     => __( 'Opacity', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:hover img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'css_filters_hover',
				'selector' => '{{WRAPPER}}:hover img',
			]
		);

		$this->add_control(
			'background_hover_transition',
			[
				'label'     => __( 'Transition Duration', 'immonex-kickstart-elementor' ) . ' (s)',
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} img' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'immonex-kickstart-elementor' ),
				'type'  => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} img',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => __( 'Corner Radius', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'image_box_shadow',
				'exclude'  => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} img',
			]
		);
	} // register_controls

	/**
	 * Return widget contents for frontend template rendering.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[]|false Template data array or false if unavailable.
	 */
	protected function get_template_data() {
		$settings = $this->get_settings_for_display();
		$post_id  = $this->get_post_id();
		$image_id = get_post_thumbnail_id( $post_id );
		$link_tag = '';

		if ( ! $image_id ) {
			return false;
		}

		if ( 'no' !== $settings['open_lightbox'] ) {
			$full_image_url = wp_get_attachment_image_url( $image_id, 'full' );
			$this->add_link_attributes( 'link', [ 'url' => $full_image_url ] );
			$this->add_lightbox_data_attributes( 'link', $image_id, $settings['open_lightbox'] );
			$link_tag = '<a ' . $this->get_render_attribute_string( 'link' ) . '>';
		}

		return [
			'settings'  => $settings,
			'link_tag'  => $link_tag,
			'image_tag' => wp_get_attachment_image( $image_id, $settings['image_size'] ),
		];
	} // get_template_data

	/**
	 * Return demo contents for preview rendering.
	 *
	 * @since 1.0.0
	 *
	 * @param string[]|null $contents Source Demo content (optional).
	 *
	 * @return string[]|null Demo contents.
	 */
	protected function get_demo_content( $contents = null ) {
		$demo_image = $this->get_demo_image();

		$contents = [
			'image' => $demo_image,
		];

		return parent::get_demo_content( $contents );
	} // get_demo_content

	/**
	 * Determine the demo image data for control registration and preview rendering.
	 *
	 * @return mixed[] Attachment or placeholder ID + main and size URLs.
	 */
	private function get_demo_image() {
		$args  = [
			'post_type'  => 'attachment',
			'fields'     => 'ids',
			'meta_query' => [
				[
					'key'   => '_inx_elementor_demo_content',
					'value' => 'main_image',
				],
			],
		];
		$image = get_posts( $args );

		$image_data = empty( $image ) ?
			$this->create_demo_image() :
			[
				'id'  => $image[0],
				'url' => wp_get_attachment_image_url( $image[0], 'full' ),
			];

		return \immonex\Kickstart\Elementor\Media_Utils::add_image_sizes( $image_data );
	} // get_demo_image

	/**
	 * Add the demo image to the WP media library and return its data.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[] Attachment or placeholder ID and URL.
	 */
	private function create_demo_image() {
		$plugin_dir   = apply_filters( 'inx_elementor_get_plugin_dir', '' );
		$source_image = trailingslashit( $plugin_dir ) . 'assets/demo-images/' . self::DEMO_IMAGE_FILENAME;

		$meta = [
			'post_title'                  => __( 'Main Image', 'immonex-kickstart-elementor' ),
			'_inx_elementor_demo_content' => 'main_image',
			'_wp_attachment_image_alt'    => 'immonex Kickstart Elementor ' . __( 'Demo Image', 'immonex-kickstart-elementor' ),
		];

		return \immonex\Kickstart\Elementor\Media_Utils::add_image_to_media_lib( $source_image, $meta );
	} // create_demo_image

} // class Main_Image_Widget
