<?php
/**
 * Class Native_Agency_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\Team;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Native Agency Widget
 *
 * @since 1.0.0
 */
class Native_Agency_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const WIDGET_NAME               = 'inx-e-native-team-agency';
	const WIDGET_ICON               = 'eicon-welcome';
	const WIDGET_CATEGORIES         = [ 'inx-team' ];
	const WIDGET_HELP_URL           = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/agentur';
	const ENABLE_RENDER_ON_PREVIEW  = true;
	const IS_DYNAMIC_CONTENT        = true;
	const PARENT_PLUGIN_NAME        = 'immonex Kickstart Team';
	const PARENT_PLUGIN_WP_REPO_URL = 'https://wordpress.org/plugins/immonex-kickstart-team/';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Agency', 'immonex-kickstart-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'contact', 'immonex-kickstart-elementor' ),
					__( 'team', 'immonex-kickstart-elementor' ),
					__( 'agency', 'immonex-kickstart-elementor' ),
					__( 'agents', 'immonex-kickstart-elementor' ),
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

		$agency_posts = get_posts(
			[
				'post_type' => 'inx_agency',
				'orderby'   => 'title',
				'order'     => 'ASC',
			]
		);
		$agencies     = [
			'' => __( 'Automatic (Property Detail Pages)', 'immonex-kickstart-elementor' ),
		];

		if ( ! empty( $agency_posts ) ) {
			foreach ( $agency_posts as $agency ) {
				$agencies[ $agency->ID ] = $agency->post_title;
			}
		}

		// phpcs:ignore
		$agency_elements = apply_filters( 'inx_team_get_agency_elements', [] );
		$element_options = [];

		if ( ! empty( $agency_elements ) ) {
			uasort(
				$agency_elements,
				function ( $a, $b ) {
					return $a['order'] <=> $b['order'];
				}
			);

			foreach ( $agency_elements as $key => $element ) {
				if ( empty( $element['selectable_for_output'] ) ) {
					continue;
				}

				$element_options[ $key ] = ! empty( $element['label'] ) ? $element['label'] : $key;

				if ( ! empty( $element['default_show'] ) && in_array( 'widget', $element['default_show'], true ) ) {
					$default_elements[] = [ 'element' => $key ];
				}
			}
		}

		$element_options_json = wp_json_encode( $element_options );

		$text_style_sections = [
			'text_general'     => [
				'label' => __( 'Text in General', 'immonex-kickstart-elementor' ),
				'class' => '.inx-team-single-agency',
			],
			'company'          => [
				'label' => __( 'Company', 'immonex-kickstart-elementor' ),
				'class' => '.inx-team-single-agency__company, .inx-team-single-agency__element--type--company .inx-team-single-agency__element-value',
			],
			'contact_elements' => [
				'label' => __( 'Contact Data Elements', 'immonex-kickstart-elementor' ),
				'class' => '.inx-team-single-agency__element-value',
			],
			'consent'          => [
				'label' => __( 'Consent Texts', 'immonex-kickstart-elementor' ),
				'class' => '.inx-team-contact-form__consent-text',
			],
		];

		$this->start_controls_section(
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_main_class_control();

		$this->add_control(
			'type',
			[
				'label'       => __( 'Display Type', 'immonex-kickstart-elementor' ),
				'description' => __( 'The widget is a compact view that usually contains the contact data incl. logo and form (see Elements below), the full view also includes lists of related agents and properties, but no form by default.', 'immonex-kickstart-elementor' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => [
					''       => __( 'Full View (Single)', 'immonex-kickstart-elementor' ),
					'widget' => __( 'Widget', 'immonex-kickstart-elementor' ),
				],
			]
		);

		$this->add_control(
			'agency_id',
			[
				'label'       => __( 'Agency', 'immonex-kickstart-elementor' ),
				'description' => __( 'Only to be selected if the agency data should <strong>not</strong> be embedded in a <strong>property detail page<strong>.', 'immonex-kickstart-elementor' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '',
				'options'     => $agencies,
			]
		);

		$default_control_args = [
			'heading'       => [
				'condition' => [
					'type' => 'widget',
				],
			],
			'heading_level' => [
				'condition' => [
					'type' => 'widget',
				],
			],
		];

		$this->add_default_controls( [ 'heading' ], $default_control_args );

		$this->add_control(
			'contact_form_scope',
			[
				'label'     => __( 'Contact Form Scope', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''         => __( 'Default', 'immonex-kickstart-elementor' ),
					'basic'    => __( 'Basic', 'immonex-kickstart-elementor' ),
					'extended' => __( 'Extended', 'immonex-kickstart-elementor' ),
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'link_type',
			[
				'label'     => __( 'Agency Link Destination', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''         => __( 'Default', 'immonex-kickstart-elementor' ),
					'internal' => __( 'Internal Profile Page', 'immonex-kickstart-elementor' ),
					'external' => __( 'External URL (if available)', 'immonex-kickstart-elementor' ),
					'none'     => _x( 'None', 'link destination: none', 'immonex-kickstart-elementor' ),
				],
				'condition' => [
					'type' => 'widget',
				],
			]
		);

		$this->add_control(
			'display_for',
			[
				'label'       => __( 'Property Status', 'immonex-kickstart-elementor' ),
				'description' => __( 'Display the agency widget only on detail pages of properties matching the selected state.', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '',
				'options'     => [
					''                      => __( 'Default', 'immonex-kickstart-elementor' ),
					'all'                   => __( 'All Properties', 'immonex-kickstart-elementor' ),
					'all_except_references' => __( 'All except References', 'immonex-kickstart-elementor' ),
					'available_only'        => __( 'Available only', 'immonex-kickstart-elementor' ),
					'unavailable_only'      => __( 'Unavailable only', 'immonex-kickstart-elementor' ),
					'references_only'       => __( 'References only', 'immonex-kickstart-elementor' ),
				],
				'condition'   => [
					'type' => 'widget',
				],
			]
		);

		$this->add_control(
			'convert_links',
			[
				'label'        => __( 'Convert Links', 'immonex-kickstart-elementor' ),
				'description'  => __( 'Convert mail addresses and phone numbers to links.', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'return_value' => '1',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'elements_section',
			[
				'label' => __( 'Contact Elements', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'use_default_elements',
			[
				'label'        => __( 'Use default elements', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'return_value' => '1',
			]
		);

		$this->add_control(
			'element_order_notice',
			[
				'type'        => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'content'     => __( 'The order of the following elements does not affect their frontend output order.', 'immonex-kickstart-elementor' ),
				'condition'   => [
					'use_default_elements' => '',
				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'element',
			[
				'label'       => __( 'Element', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => $element_options,
			]
		);

		$this->add_control(
			'form_elements',
			[
				'label'         => __( 'User-defined Elements', 'immonex-kickstart-elementor' ),
				'type'          => \Elementor\Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'title_field'   => "<# const labels = {$element_options_json}; const label = labels[element]; #>{{{ label }}}",
				'prevent_empty' => false,
				'default'       => $default_elements,
				'condition'     => [
					'use_default_elements' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'extended_content_section',
			[
				'label'     => __( 'Extended (Full View)', 'immonex-kickstart-elementor' ),
				'tab'       => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [
					'type' => '',
				],
			]
		);

		$this->add_control(
			'extended_contents_notice',
			[
				'type'        => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'content'     => __( 'The following settings can be used to override the corresponding ones in the plugin and agency options.', 'immonex-kickstart-elementor' ),
				'condition'   => [
					'use_default_elements' => '',
				],
			]
		);

		$this->add_control(
			'show_agent_list',
			[
				'label'       => __( 'Agent List', 'immonex-kickstart-elementor' ),
				'description' => __( 'Display agents associated with the agency.', 'immonex-kickstart-elementor' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '',
				'options'     => [
					''    => __( 'Default (Plugin/Agency Options)', 'immonex-kickstart-elementor' ),
					'yes' => __( 'show', 'immonex-kickstart-elementor' ),
					'no'  => __( 'hide', 'immonex-kickstart-elementor' ),
				],
			]
		);

		$this->add_control(
			'show_property_list',
			[
				'label'       => __( 'Property List', 'immonex-kickstart-elementor' ),
				'description' => __( 'Display properties associated with the agency.', 'immonex-kickstart-elementor' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '',
				'options'     => [
					''    => __( 'Default (Plugin/Agency Options)', 'immonex-kickstart-elementor' ),
					'yes' => __( 'show', 'immonex-kickstart-elementor' ),
					'no'  => __( 'hide', 'immonex-kickstart-elementor' ),
				],
			]
		);

		$this->add_control(
			'show_legal_notice',
			[
				'label'       => __( 'Legal Notice', 'immonex-kickstart-elementor' ),
				'description' => __( "Display the agency's legal notice, if available.", 'immonex-kickstart-elementor' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '',
				'options'     => [
					''    => __( 'Default (Plugin/Agency Options)', 'immonex-kickstart-elementor' ),
					'yes' => __( 'show', 'immonex-kickstart-elementor' ),
					'no'  => __( 'hide', 'immonex-kickstart-elementor' ),
				],
			]
		);

		$this->end_controls_section();

		$this->add_default_controls(
			[ 'heading_style' ],
			[
				'heading_style_section' => [
					'condition' => [
						'type!' => '',
					],
				],
				'heading_align'         => [
					'selectors' => [
						'{{WRAPPER}} .inx-team-single-agency__title' => 'text-align: {{VALUE}};',
					],
				],
				'heading_title_color'   => [
					'selectors' => [
						'{{WRAPPER}} .inx-team-single-agency__title' => 'color: {{VALUE}};',
					],
				],
				'heading_typography'    => [
					'selector' => '{{WRAPPER}} .inx-team-single-agency__title',
				],
				'heading_text_stroke'   => [
					'selector' => '{{WRAPPER}} .inx-team-single-agency__title',
				],
				'heading_text_shadow'   => [
					'selector' => '{{WRAPPER}} .inx-team-single-agency__title',
				],
				'blend_mode'            => [
					'selectors' => [
						'{{WRAPPER}} .inx-team-single-agency__title' => 'mix-blend-mode: {{VALUE}}',
					],
				],
			],
			false
		);

		$this->end_controls_section();

		foreach ( $text_style_sections as $key => $section_data ) {
			$this->start_controls_section(
				"{$key}_section",
				[
					'label' => $section_data['label'],
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				"{$key}_color",
				[
					'label'       => __( 'Text Color', 'immonex-kickstart-elementor' ),
					'type'        => \Elementor\Controls_Manager::COLOR,
					'description' => __( 'This color is <strong>not</strong> applied to links.', 'immonex-kickstart-elementor' )
						. __( 'The link (action elements) color is defined in the Kickstart plugin options.', 'immonex-kickstart-elementor' ),
					'selectors'   => [
						"{{WRAPPER}} :not(a) > {$section_data['class']}" => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => "{$key}_typography",
					'selector' => "{{WRAPPER}} {$section_data['class']}",
				]
			);

			$this->end_controls_section();
		}

		$this->start_controls_section(
			'icon_section',
			[
				'label' => __( 'Icons', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'       => __( 'Color', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'description' => __( 'This color is <strong>not</strong> applied to links.', 'immonex-kickstart-elementor' )
					. __( 'The link (action elements) color is defined in the Kickstart plugin options.', 'immonex-kickstart-elementor' ),
				'selectors'   => [
					'{{WRAPPER}} :not(a) > span > svg' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'form_section',
			[
				'label' => __( 'Form Fields', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'field_bg_color',
			[
				'label'     => __( 'Background Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-team-contact-form__input > input, {{WRAPPER}} .inx-team-contact-form__input > textarea' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'field_placeholder_color',
			[
				'label'     => __( 'Placeholder Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-team-contact-form__input > input::placeholder, {{WRAPPER}} .inx-team-contact-form__input > textarea::placeholder' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'field_border_color',
			[
				'label'     => __( 'Border Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-team-contact-form__input > input, {{WRAPPER}} .inx-team-contact-form__input > textarea' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'field_border_radius',
			[
				'label'      => __( 'Border Radius', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .inx-team-contact-form__input > input, {{WRAPPER}} .inx-team-contact-form__input > textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'after',
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-team-contact-form__input > input, {{WRAPPER}} .inx-team-contact-form__input > textarea' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'field_typography',
				'selector' => '{{WRAPPER}} .inx-team-contact-form__input > input, {{WRAPPER}} .inx-team-contact-form__input > textarea',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'submit_button_section',
			[
				'label' => __( 'Submit Button', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'submit_button_bg_color',
			[
				'label'     => __( 'Background Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-team-contact-form__submit:not(:disabled)' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_button_hover_color',
			[
				'label'     => __( 'Hover Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-team-contact-form__submit:not(:disabled):hover' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'submit_button_border_color',
			[
				'label'     => __( 'Border Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-team-contact-form__submit:not(:disabled)' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'submit_button_border_radius',
			[
				'label'      => __( 'Border Radius', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .inx-team-contact-form__submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'after',
			]
		);

		$this->add_control(
			'submit_button_text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-team-contact-form__submit:not(:disabled)' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'submit_button_typography',
				'selector' => '{{WRAPPER}} .inx-team-contact-form__submit',
			]
		);

		$this->end_controls_section();

		$this->add_default_controls(
			[ 'heading_style' ],
			[
				'heading_style'         => [
					'prefix' => 'list',
				],
				'heading_style_section' => [
					'label'     => __( 'List Headlines', 'immonex-kickstart-elementor' ),
					'condition' => [
						'type' => '',
					],
				],
				'heading_align'         => [
					'selectors' => [
						'{{WRAPPER}} .inx-team-single-agency__list-headline' => 'text-align: {{VALUE}};',
					],
				],
				'heading_title_color'   => [
					'selectors' => [
						'{{WRAPPER}} .inx-team-single-agency__list-headline' => 'color: {{VALUE}};',
					],
				],
				'heading_typography'    => [
					'selector' => '{{WRAPPER}} .inx-team-single-agency__list-headline',
				],
				'heading_text_stroke'   => [
					'selector' => '{{WRAPPER}} .inx-team-single-agency__list-headline',
				],
				'heading_text_shadow'   => [
					'selector' => '{{WRAPPER}} .inx-team-single-agency__list-headline',
				],
				'blend_mode'            => [
					'selectors' => [
						'{{WRAPPER}} .inx-team-single-agency__list-headline' => 'mix-blend-mode: {{VALUE}}',
					],
				],
			],
			false
		);

		$this->end_controls_section();

		$this->add_default_controls( [ 'list_element_style' ] );
	} // register_controls

	/**
	 * Return widget contents for frontend template rendering.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[]|bool Template data array or false if unavailable.
	 */
	protected function get_template_data() {
		if ( ! $this->parent_plugin_available ) {
			return false;
		}

		$settings = $this->get_settings_for_display();

		if ( ! $settings['use_default_elements'] && ! empty( $settings['form_elements'] ) ) {
			$elements = [];

			foreach ( $settings['form_elements'] as $element ) {
				$elements[] = $element['element'];
			}

			$this->add_render_attribute( 'shortcode', 'elements', implode( ',', $elements ) );
		}

		if ( ! empty( $settings['agency_id'] ) ) {
			$this->add_render_attribute( 'shortcode', 'id', $settings['agency_id'] );
		}

		if ( ! empty( $settings['heading'] ) ) {
			$h_tag = $this->get_h_tag( $settings['heading_level'] );

			$this->add_render_attribute( 'shortcode', 'before_title', "<{$h_tag}>" );
			$this->add_render_attribute( 'shortcode', 'title', $settings['heading'] );
			$this->add_render_attribute( 'shortcode', 'after_title', "</{$h_tag}>" );
		}

		$ext_atts = [
			'type',
			'contact_form_scope',
			'link_type',
			'display_for',
			'convert_links',
			'show_agent_list',
			'show_property_list',
			'show_legal_notice',
		];

		foreach ( $ext_atts as $att ) {
			if ( ! empty( $settings[ $att ] ) ) {
				$this->add_render_attribute( 'shortcode', $att, $settings[ $att ] );
			}
		}

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'shortcode', 'is_preview', '1' );
		}

		$shortcode_output = do_shortcode( '[inx-team-agency ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $shortcode_output ?
			[
				'settings'         => $settings,
				'shortcode_output' => $shortcode_output,
			] :
			false;
	} // get_template_data

} // class Native_Agency_Widget
