<?php
/**
 * Class Desc_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Description Widget
 *
 * @since 1.0.0
 */
class Desc_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const POST_TYPE         = 'inx_property';
	const WIDGET_NAME       = 'inx-e-single-property-desc';
	const WIDGET_ICON       = 'eicon-post-content';
	const WIDGET_CATEGORIES = [ 'inx-single-property' ];
	const WIDGET_HELP_URL   = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/beschreibung';

	const DEFAULT_CTEXT_FORMATTING_STATE = 'yes';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Description', 'immonex-kickstart-elementor' );
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
				[ __( 'description', 'immonex-kickstart-elementor' ) ]
			)
		);
	} // add_keywords

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
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_main_class_control();

		$this->add_control(
			'field_name',
			[
				'label'   => __( 'Description Type', 'immonex-kickstart-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'freitexte.objektbeschreibung' => __( 'Property', 'immonex-kickstart-elementor' ),
					'freitexte.lage'               => __( 'Location', 'immonex-kickstart-elementor' ),
					'freitexte.ausstatt_beschr'    => __( 'Features', 'immonex-kickstart-elementor' ),
					'freitexte.sonstige_angaben'   => __( 'Miscellaneous', 'immonex-kickstart-elementor' ),
				],
				'default' => 'freitexte.objektbeschreibung',
			]
		);

		$this->add_control(
			'format_ctext',
			[
				'label'   => __( 'Format Continuous Text', 'immonex-kickstart-elementor' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => static::DEFAULT_CTEXT_FORMATTING_STATE,
			]
		);

		$this->add_default_controls();

		$this->end_controls_section();

		$this->add_default_controls( [ 'heading_style' ] );

		$this->start_controls_section(
			'body_text_section',
			[
				'label' => __( 'Description Text', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'body_text_align',
			[
				'label'     => __( 'Alignment', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
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
					'{{WRAPPER}} .inx-e-desc-text' => 'text-align: {{VALUE}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'body_text_color',
			[
				'label'     => __( 'Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-e-desc-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'body_text_typography',
				'selector' => '{{WRAPPER}} .inx-e-desc-text',
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
		$post_id  = $this->get_post_id();

		if ( 'freitexte.objektbeschreibung' === $settings['field_name'] ) {
			$desc = get_the_content( '', false, $post_id );
		}

		if ( empty( $desc ) ) {
			// phpcs:ignore
			$desc = apply_filters( 'inx_get_custom_field_value_by_name', '', $settings['field_name'], $post_id );
		}
		if ( ! $desc ) {
			return false;
		}

		// phpcs:ignore
		$utils = apply_filters( 'inx_elementor_get_utils', [] );
		if ( empty( $utils ) ) {
			return false;
		}

		if ( 'yes' === $settings['format_ctext'] ) {
			// phpcs:ignore
			$desc = apply_filters( 'inx_the_content', $utils['string']->convert_urls( $desc ) );
		}

		return ! empty( $desc ) ?
			[
				'settings' => $settings,
				'desc'     => $desc,
				'h_tag'    => $this->get_h_tag( $settings['heading_level'] ),
			] :
			false;
	} // get_template_data

	/**
	 * Return demo contents for preview rendering.
	 *
	 * @since 1.0.0
	 *
	 * @param string[]|null $contents Source Demo content.
	 *
	 * @return string[]|null Demo contents.
	 */
	protected function get_demo_content( $contents = null ) {
		$contents = [
			'freitexte.objektbeschreibung' => __( 'Would you like to live in a great single-family home and move in straight away - without any tiresome renovation work?', 'immonex-kickstart-elementor' ) . PHP_EOL . PHP_EOL
				. __( 'Floor-to-ceiling windows, a friendly and colorful facade design as well as many communicative common areas underline the clear orientation towards the needs of the residents.', 'immonex-kickstart-elementor' ) . PHP_EOL . PHP_EOL
				. __( 'The house convinces with a style-typical architecture that combines modern living ambience with classic industrial charm. Striking stylistic elements such as the striking clinker facade were retained in the course of the modernization and today give the building a very special, individual character.', 'immonex-kickstart-elementor' ),
			'freitexte.lage'               => __( 'The single-family house offered here surprises with an astonishingly idyllic location in the midst of the hustle and bustle of Rupert Farnsworth Alley.', 'immonex-kickstart-elementor' ) . PHP_EOL . PHP_EOL
				. __( 'There is a large supermarket in town, as well as bakeries, butchers, various bank branches, a pharmacy and various craft businesses.', 'immonex-kickstart-elementor' ),
			'freitexte.ausstatt_beschr'    => __( 'Thanks to the floor-to-ceiling windows and the southern orientation, the sun shines into the living room throughout the day. The threshold-free door takes you to both the south-facing terrace and the garden. Very practical and in good shape: there is a tool shed and a large greenhouse in the garden. The former even has electricity and fresh water connections!', 'immonex-kickstart-elementor' ),
			'freitexte.sonstige_angaben'   => __( 'We would like to point out that the property information, documents, plans, etc. come from the seller or landlord. We therefore assume no liability for the accuracy or completeness of the information.', 'immonex-kickstart-elementor' ),
		];

		return parent::get_demo_content( $contents );
	} // get_demo_content

} // class Desc_Widget
