<?php
/**
 * Class Native_Video_Gallery_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Native Video Gallery Widget
 *
 * @since 1.0.0
 */
class Native_Video_Gallery_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Gallery_Widget {

	const WIDGET_NAME     = 'inx-e-single-property-native-video-gallery';
	const WIDGET_ICON     = 'eicon-video-playlist';
	const WIDGET_HELP_URL = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/videogalerie';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Video Gallery', 'immonex-kickstart-elementor' ) . self::NATIVE_POSTFIX;
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
				'label' => __( 'General', 'immonex-kickstart-elementor' ),
			]
		);

		$this->add_main_class_control();

		$this->add_control(
			'enable_default_headline',
			[
				'label'        => __( 'Show Default Headline', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'description'  => __( 'Defaults to <em>Videos</em>.', 'immonex-kickstart-elementor' ),
				'default'      => '1',
				'label_off'    => __( 'Off', 'immonex-kickstart-elementor' ),
				'label_on'     => __( 'On', 'immonex-kickstart-elementor' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'headline',
			[
				'label'       => __( 'Custom Headline', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Leave blank to disable the headline display.', 'immonex-kickstart-elementor' ),
				'label_block' => true,
				'condition'   => [
					'enable_default_headline!' => '1',
				],
				'separator'   => 'after',
			]
		);

		$this->add_control(
			'enable_caption_display',
			[
				'label'        => __( 'Show Video Captions', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'label_off'    => __( 'Off', 'immonex-kickstart-elementor' ),
				'label_on'     => __( 'On', 'immonex-kickstart-elementor' ),
				'return_value' => '1',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'animation_style_section',
			[
				'label' => __( 'Animation', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'animation_type',
			[
				'label'       => __( 'Transition Animation', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'Defaults to <code>Scale<code>.', 'immonex-kickstart-elementor' ),
				'options'     => [
					''      => __( 'Default', 'immonex-kickstart-elementor' ),
					'slide' => 'Slide',
					'fade'  => 'Fade',
					'pull'  => 'Pull',
					'push'  => 'Push',
					'scale' => 'Scale',
				],
			]
		);

		$this->end_controls_section();

		$this->add_default_controls( [ 'heading_style' ], [], false );

		$this->add_control(
			'disable_heading_dividing_line',
			[
				'label'        => __( 'Hide Dividing Line', 'immonex-kickstart-elementor' ),
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
				'label' => __( 'Video', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'        => __( 'Autoplay', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_off'    => __( 'Off', 'immonex-kickstart-elementor' ),
				'label_on'     => __( 'On', 'immonex-kickstart-elementor' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'automute',
			[
				'label'        => __( 'Automute', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'label_off'    => __( 'Off', 'immonex-kickstart-elementor' ),
				'label_on'     => __( 'On', 'immonex-kickstart-elementor' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'youtube-nocookie',
			[
				'label'        => __( 'YouTube Nocookie', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'label_off'    => __( 'Off', 'immonex-kickstart-elementor' ),
				'label_on'     => __( 'On', 'immonex-kickstart-elementor' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'allow',
			[
				'label'       => __( 'iFrame allow Attribute', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Leave blank to use the default value.', 'immonex-kickstart-elementor' ),
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'extended_style_section',
			[
				'label' => __( 'Extended', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'extended_options_notice',
			[
				'type'        => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'content'     => __( 'The following options must only be adjusted <strong>per widget</strong> in special cases. The global defaults for all galleries can be defined in the Kickstart plugin options.', 'immonex-kickstart-elementor' ),
				'scope'       => [ 'tax_filters' ],
			]
		);

		$this->add_control(
			'gallery_image_slider_min_height',
			[
				'label'       => __( 'Slider Min. Height', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'description' => __( "Minimum height of the <strong>container element</strong> that holds the gallery's upper main slider in pixels (<code>0</code> to use the global default value).", 'immonex-kickstart-elementor' ),
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
				'label'       => __( 'Slider Background Color', 'immonex-kickstart-elementor' ),
				'description' => __( 'Background color of the upper main slider area that contains the currently selected image, video or virtual tour.', 'immonex-kickstart-elementor' ),
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

		$this->add_render_attribute( 'shortcode', 'elements', 'video_gallery' );

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'shortcode', 'is_preview', '1' );
			$this->add_render_attribute( 'shortcode', 'video_urls', implode( '|', \immonex\Kickstart\Elementor\Demo_Media::DEMO_VIDEO_URLS ) );
			$this->add_render_attribute( 'shortcode', 'videos_require_consent', '0' );
		}

		$switch_controls = [
			'enable_caption_display',
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

		return $shortcode_output ?
			[
				'settings'         => $settings,
				'shortcode_output' => $shortcode_output,
			] :
			false;
	} // get_template_data

} // class Native_Video_Gallery_Widget
