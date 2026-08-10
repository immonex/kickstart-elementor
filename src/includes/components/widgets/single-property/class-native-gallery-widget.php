<?php
/**
 * Class Native_Gallery_Widget
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Native Gallery Widget
 *
 * @since 1.0.0
 */
class Native_Gallery_Widget extends \immonex\Kickstart\ForElementor\Components\Widgets\Gallery_Widget {

	const WIDGET_NAME     = 'inx-e-single-property-native-gallery';
	const WIDGET_ICON     = 'eicon-thumbnails-down';
	const WIDGET_HELP_URL = 'https://docs.immonex.de/kickstart-for-elementor/#/elementor-immobilien-widgets/galerie';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Gallery', 'immonex-kickstart-for-elementor' ) . self::NATIVE_POSTFIX;
	} // get_title

	/**
	 * Register widget controls.
	 *
	 * @since 1.0.0
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-for-elementor' ),
			]
		);

		$this->add_main_class_control();

		$this->add_control(
			'gallery_image_type',
			[
				'label'       => __( 'Image Type/Source', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'gallery',
				'options'     => [
					'gallery'      => __( 'Main Gallery', 'immonex-kickstart-for-elementor' ) . ' (' . __( 'Photos, Videos, 360°…', 'immonex-kickstart-for-elementor' ) . ')',
					'floor_plans'  => __( 'Floor Plans', 'immonex-kickstart-for-elementor' ),
					'epass_images' => __( 'Energy Pass Images', 'immonex-kickstart-for-elementor' ),
					'custom_field' => __( 'Custom Field(s)', 'immonex-kickstart-for-elementor' ),
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'image_selection_custom_field',
			[
				'label'       => __( 'Image ID Field(s)', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Comma-separated list of <strong>custom field names</strong> containing the (attachment post) IDs of the images to include in the gallery.', 'immonex-kickstart-for-elementor' ),
				'condition'   => [
					'gallery_image_type' => 'custom_field',
				],
				'label_block' => true,
				'separator'   => 'after',
			]
		);

		$this->add_control(
			'enable_video',
			[
				'label'        => __( 'Include Videos', 'immonex-kickstart-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'label_off'    => __( 'Off', 'immonex-kickstart-for-elementor' ),
				'label_on'     => __( 'On', 'immonex-kickstart-for-elementor' ),
				'return_value' => '1',
				'condition'    => [
					'gallery_image_type' => 'gallery',
				],
			]
		);

		$this->add_control(
			'enable_virtual_tour',
			[
				'label'        => __( 'Include Virtual Tour', 'immonex-kickstart-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'label_off'    => __( 'Off', 'immonex-kickstart-for-elementor' ),
				'label_on'     => __( 'On', 'immonex-kickstart-for-elementor' ),
				'return_value' => '1',
				'condition'    => [
					'gallery_image_type' => 'gallery',
				],
			]
		);

		$this->add_control(
			'enable_default_headline',
			[
				'label'        => __( 'Show Default Headline', 'immonex-kickstart-for-elementor' ),
				'description'  => __( 'Main gallery and energy pass images have no heading by default.', 'immonex-kickstart-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'label_off'    => __( 'Off', 'immonex-kickstart-for-elementor' ),
				'label_on'     => __( 'On', 'immonex-kickstart-for-elementor' ),
				'return_value' => '1',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'headline',
			[
				'label'       => __( 'Custom Headline', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Leave blank to disable the headline display.', 'immonex-kickstart-for-elementor' ),
				'condition'   => [
					'enable_default_headline!' => '1',
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'enable_caption_display',
			[
				'label'        => __( 'Show Image/Video Captions', 'immonex-kickstart-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'label_off'    => __( 'Off', 'immonex-kickstart-for-elementor' ),
				'label_on'     => __( 'On', 'immonex-kickstart-for-elementor' ),
				'return_value' => '1',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'animation_style_section',
			[
				'label' => __( 'Animation', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'animation_type',
			[
				'label'       => __( 'Transition Animation', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'Defaults to <code>Push</code> for the main gallery and <code>Scale</code> for other gallery types (floor plans, energy pass images etc.).', 'immonex-kickstart-for-elementor' ),
				'options'     => [
					''      => __( 'Default', 'immonex-kickstart-for-elementor' ),
					'slide' => 'Slide',
					'fade'  => 'Fade',
					'pull'  => 'Pull',
					'push'  => 'Push',
					'scale' => 'Scale',
				],
			]
		);

		$this->add_control(
			'enable_ken_burns_effect',
			[
				'label'        => __( 'Ken Burns Effect', 'immonex-kickstart-for-elementor' ) . ' (KBE)',
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'label_off'    => __( 'Off', 'immonex-kickstart-for-elementor' ),
				'label_on'     => __( 'On', 'immonex-kickstart-for-elementor' ),
				'return_value' => '1',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'ken_burns_effect_display_mode',
			[
				'label'       => __( 'KBE Display Mode', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'Covering the entire area of the container element usually looks better, but some images may be displayed "cropped" horizontally after the animation (depending on the different aspect ratios of the gallery images).', 'immonex-kickstart-for-elementor' ) .
					'<br><br>' .
					__( 'In return, always displaying the images in full may result in empty areas at the top and/or bottom of the container element.', 'immonex-kickstart-for-elementor' ),
				'options'     => [
					''            => __( 'Default', 'immonex-kickstart-for-elementor' ),
					'cover'       => __( 'Cover the entire container element', 'immonex-kickstart-for-elementor' ),
					'full_center' => __( 'Show full images', 'immonex-kickstart-for-elementor' ) . ' (' . __( 'centered', 'immonex-kickstart-for-elementor' ) . ')',
					'full_top'    => __( 'Show full images', 'immonex-kickstart-for-elementor' ) . ' (' . __( 'top', 'immonex-kickstart-for-elementor' ) . ')',
				],
				'condition'   => [
					'enable_ken_burns_effect' => '1',
				],
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'image_links_lightbox_style_section',
			[
				'label' => __( 'Image Links', 'immonex-kickstart-for-elementor' ) . ' / ' .
					__( 'Lightbox', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'enable_gallery_image_links',
			[
				'label'        => __( 'Image Links', 'immonex-kickstart-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'description'  => __( 'Enable full size/lightbox link for the currently displayed image.', 'immonex-kickstart-for-elementor' ),
				'default'      => '1',
				'label_off'    => __( 'Off', 'immonex-kickstart-for-elementor' ),
				'label_on'     => __( 'On', 'immonex-kickstart-for-elementor' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'lightbox_options_notice',
			[
				'type'        => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'dismissible' => true,
				'content'     => __( 'The following options apply exclusively to the <strong>Kickstart Lightbox</strong> (see plugin options) and should be left at "Standard" if the Elementor Lightbox is being used.', 'immonex-kickstart-for-elementor' ),
			]
		);

		$this->add_control(
			'lightbox_animation',
			[
				'label'     => __( 'Transition Animation', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					''      => __( 'Default', 'immonex-kickstart-for-elementor' ),
					'slide' => 'Slide',
					'fade'  => 'Fade',
					'scale' => 'Scale',
				],
				'condition' => [
					'enable_gallery_image_links' => '1',
				],
			]
		);

		$this->add_control(
			'lightbox_nav',
			[
				'label'     => __( 'Navigation', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					''         => __( 'Default', 'immonex-kickstart-for-elementor' ),
					'thumbnav' => 'Thumbnails',
					'dotnav'   => __( 'Dots', 'immonex-kickstart-for-elementor' ),
					'false'    => __( 'None', 'immonex-kickstart-for-elementor' ),
				],
				'condition' => [
					'enable_gallery_image_links' => '1',
				],
			]
		);

		$this->add_control(
			'enable_lightbox_counter',
			[
				'label'       => __( 'Counter', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'Display a counter with the current and the total number of images.', 'immonex-kickstart-for-elementor' ),
				'options'     => [
					''  => __( 'Default', 'immonex-kickstart-for-elementor' ),
					'1' => __( 'Yes', 'immonex-kickstart-for-elementor' ),
					'0' => __( 'No', 'immonex-kickstart-for-elementor' ),
				],
				'condition'   => [
					'enable_gallery_image_links' => '1',
				],
			]
		);

		$this->add_control(
			'enable_lightbox_caption',
			[
				'label'     => __( 'Image Captions', 'immonex-kickstart-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					''    => __( 'Default', 'immonex-kickstart-for-elementor' ),
					'yes' => __( 'Yes', 'immonex-kickstart-for-elementor' ),
					'no'  => __( 'No', 'immonex-kickstart-for-elementor' ),
				],
				'condition' => [
					'enable_gallery_image_links' => '1',
				],
			]
		);

		$this->end_controls_section();

		$this->add_default_controls( [ 'heading_style' ], [], false );

		$this->add_control(
			'disable_heading_dividing_line',
			[
				'label'        => __( 'Hide Dividing Line', 'immonex-kickstart-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => '0',
				'selectors'    => [
					'{{WRAPPER}} .inx-single-property__section-title.uk-heading-divider' => 'padding-bottom: {{VALUE}}; border-bottom: {{VALUE}};',
				],
				'separator'    => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'video_style_section',
			[
				'label'     => __( 'Videos', 'immonex-kickstart-for-elementor' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'gallery_image_type' => 'gallery',
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'        => __( 'Autoplay', 'immonex-kickstart-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_off'    => __( 'Off', 'immonex-kickstart-for-elementor' ),
				'label_on'     => __( 'On', 'immonex-kickstart-for-elementor' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'automute',
			[
				'label'        => __( 'Automute', 'immonex-kickstart-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'label_off'    => __( 'Off', 'immonex-kickstart-for-elementor' ),
				'label_on'     => __( 'On', 'immonex-kickstart-for-elementor' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'youtube-nocookie',
			[
				'label'        => __( 'YouTube Nocookie', 'immonex-kickstart-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'label_off'    => __( 'Off', 'immonex-kickstart-for-elementor' ),
				'label_on'     => __( 'On', 'immonex-kickstart-for-elementor' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'allow',
			[
				'label'       => __( 'iFrame allow Attribute', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Leave blank to use the default value.', 'immonex-kickstart-for-elementor' ),
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'extended_style_section',
			[
				'label' => __( 'Extended', 'immonex-kickstart-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'extended_options_notice',
			[
				'type'        => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'dismissible' => true,
				'content'     => __( 'The following options must only be adjusted <strong>per widget</strong> in special cases. The global defaults for all galleries can be defined in the Kickstart plugin options.', 'immonex-kickstart-for-elementor' ),
			]
		);

		$this->add_control(
			'gallery_image_slider_min_height',
			[
				'label'       => __( 'Slider Min. Height', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'description' => __( "Minimum height of the <strong>container element</strong> that holds the gallery's upper main slider in pixels (<code>0</code> to use the global default value).", 'immonex-kickstart-for-elementor' ),
				'placeholder' => '0',
				'min'         => 0,
				'max'         => 800,
				'step'        => 10,
				'default'     => 0,
				'selectors'   => [
					'{{WRAPPER}} .inx-gallery__images' => 'min-height: {{VALUE}}px;',
				],
			]
		);

		$this->add_control(
			'image_slider_bg_color',
			[
				'label'       => __( 'Slider Background Color', 'immonex-kickstart-for-elementor' ),
				'description' => __( 'Background color of the upper main slider area that contains the currently selected image, video or virtual tour.', 'immonex-kickstart-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'selectors'   => [
					'{{WRAPPER}} .inx-gallery__images li' => 'background-color: {{VALUE}};',
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
		$element  = 'custom_field' === $settings['gallery_image_type'] ? 'gallery' : $settings['gallery_image_type'];

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'shortcode', 'is_preview', '1' );
			$this->add_render_attribute( 'shortcode', 'videos_require_consent', '0' );
			$this->add_render_attribute( 'shortcode', 'virtual_tours_require_consent', '0' );

			if ( static::POST_TYPE !== get_post_type() ) {
				$demo_image_ids = \immonex\Kickstart\ForElementor\Demo_Media::get_demo_image_ids();
				$image_ids      = isset( $demo_image_ids[ $settings['gallery_image_type'] ] ) ?
					$demo_image_ids[ $settings['gallery_image_type'] ] :
					$demo_image_ids['gallery'];

				$virtual_tour_embed_code  = '<p style="margin:0; padding:16px; text-align:center; color:#FFF; background-color:#4C93EC">' . __( 'This is a <strong>demo</strong> of the <a href="https://www.cloudpano.com/" target="_blank">CloudPano</a> 360° tour service.', 'immonex-kickstart-for-elementor' ) . '</p>' . PHP_EOL;
				$virtual_tour_embed_code .= '<iframe width="640" height="480" src="' . \immonex\Kickstart\ForElementor\Demo_Media::DEMO_VIRTUAL_TOUR_URL . '"></iframe>';

				$this->add_render_attribute( 'shortcode', 'image_ids', implode( ',', $image_ids ) );
				$this->add_render_attribute( 'shortcode', 'video_url', \immonex\Kickstart\ForElementor\Demo_Media::DEMO_VIDEO_URLS[0] );
				$this->add_render_attribute( 'shortcode', 'virtual_tour_url', \immonex\Kickstart\ForElementor\Demo_Media::DEMO_VIRTUAL_TOUR_URL );
				$this->add_render_attribute( 'shortcode', 'virtual_tour_embed_code', $virtual_tour_embed_code );
			}
		}

		if ( ! empty( $settings['image_selection_custom_field'] ) ) {
			$this->add_render_attribute( 'shortcode', 'image_selection_custom_field', $settings['image_selection_custom_field'] );
		}

		$this->add_render_attribute( 'shortcode', 'elements', $element );

		$switch_controls = [
			'enable_caption_display',
			'enable_gallery_image_links',
			'enable_ken_burns_effect',
			'enable_video',
			'enable_virtual_tour',
			'autoplay',
			'automute',
			'youtube-nocookie',
		];

		foreach ( $switch_controls as $control ) {
			$this->add_render_attribute( 'shortcode', $control, $settings[ $control ] ? '1' : '0' );
		}

		$ext_controls = [
			'headline',
			'animation_type',
			'allow',
			'ken_burns_effect_display_mode',
			'lightbox_animation',
			'lightbox_nav',
			'enable_lightbox_counter',
			'enable_lightbox_caption',
		];

		foreach ( $ext_controls as $control ) {
			if (
				! empty( $settings[ $control ] )
				|| ( 'headline' === $control && ! $settings['enable_default_headline'] )
			) {
				$this->add_render_attribute( 'shortcode', $control, $settings[ $control ] );
			}
		}

		$shortcode_output = do_shortcode( '[inx-property-details ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $shortcode_output ? [
			'settings'         => $settings,
			'shortcode_output' => $shortcode_output,
		] :
		false;
	} // get_template_data

} // class Native_Gallery_Widget
