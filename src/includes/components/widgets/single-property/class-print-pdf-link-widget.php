<?php
/**
 * Class Print_PDF_Link_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Print/PDF Link Widget
 *
 * @since 1.0.0
 */
class Print_PDF_Link_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const POST_TYPE          = 'inx_property';
	const WIDGET_NAME        = 'inx-e-single-property-print-pdf-link';
	const WIDGET_ICON        = 'eicon-document-file';
	const WIDGET_CATEGORIES  = [ 'inx-single-property' ];
	const WIDGET_HELP_URL    = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/drucken-pdf-link';
	const PARENT_PLUGIN_NAME = 'immonex Kickstart Print';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Print/PDF Link', 'immonex-kickstart-elementor' );
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
					__( 'print', 'immonex-kickstart-elementor' ),
					__( 'pdf', 'immonex-kickstart-elementor' ),
					__( 'link', 'immonex-kickstart-elementor' ),
				]
			)
		);
	} // add_keywords

	/**
	 * Register widget controls.
	 *
	 * @since 1.0.0
	 */
	protected function register_controls() {
		if ( ! $this->parent_plugin_available ) {
			return;
		}

		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_main_class_control();

		$this->add_control(
			'link_text',
			[
				'label'       => __( 'Link Text', 'immonex-kickstart-elementor' ),
				'description' => __( 'Defaults to "Print/PDF" if empty and no icon is selected.', 'immonex-kickstart-elementor' ),
				'default'     => __( 'Print/PDF', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'icon',
			[
				'label'       => __( 'Icon', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'default'     => $this->get_default(
					'icon',
					[
						'value'   => 'fas fa-print',
						'library' => 'fa-solid',
					]
				),
				'recommended' => [
					'fa-solid'   => [
						'print',
						'file-pdf',
					],
					'fa-regular' => [
						'file-pdf',
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Link Text/Icon', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .inx-e-print-pdf-link',
			]
		);

		$this->add_control(
			'color',
			[
				'label'       => __( 'Color', 'immonex-kickstart-elementor' ),
				'description' => __( 'Defaults to the standard link color.', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'selectors'   => [
					'{{WRAPPER}} .inx-e-print-pdf-link' => 'color: {{VALUE}};',
					'{{WRAPPER}} .inx-e-print-pdf-link__icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label'   => __( 'Icon Alignment', 'immonex-kickstart-elementor' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'default' => $this->get_default( 'icon_align', 'right' ),
				'options' => [
					'left'  => [
						'title' => __( 'Left', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
			]
		);
	} // register_controls

	/**
	 * Return widget contents and settings for frontend template rendering.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[]|bool Template data array or false if unavailable.
	 */
	protected function get_template_data() {
		if ( ! $this->parent_plugin_available ) {
			return false;
		}

		// phpcs:ignore
		$url = apply_filters( 'inx_print_pdf_link_url', '' );
		if ( ! $url ) {
			return false;
		}

		$settings = $this->get_settings_for_display();
		$icon     = ! empty( $settings['icon']['value'] ) ?
			\Elementor\Icons_Manager::try_get_icon_html( $settings['icon'], [ 'aria-hidden' => 'true' ] ) :
			'';

		if ( ! trim( $settings['link_text'] ) && ! $icon ) {
			$settings['link_text'] = __( 'Print/PDF', 'immonex-kickstart-elementor' );
		}

		return array_merge(
			$settings,
			[
				'url'  => $url,
				'icon' => $icon,
			]
		);
	} // get_template_data

	/**
	 * Return demo contents for preview rendering.
	 *
	 * @since 1.0.0
	 *
	 * @param string[]|null $contents Source Demo content.
	 *
	 * @return string[] Demo contents.
	 */
	protected function get_demo_content( $contents = [] ) {
		return parent::get_demo_content(
			[
				'url'               => '#',
				'default_link_text' => __( 'Print/PDF', 'immonex-kickstart-elementor' ),
			]
		);
	} // get_demo_content

} // class Print_PDF_Link_Widget
