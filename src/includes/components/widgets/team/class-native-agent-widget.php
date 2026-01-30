<?php
/**
 * Class Native_Agent_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\Team;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Native Agent Widget
 *
 * @since 1.0.0
 */
class Native_Agent_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const WIDGET_NAME               = 'inx-e-native-team-agent';
	const WIDGET_ICON               = 'eicon-person';
	const WIDGET_CATEGORIES         = [ 'inx-single-property', 'inx-team' ];
	const WIDGET_HELP_URL           = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/kontaktperson-formular';
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
		return __( 'Contact <span style="white-space:nowrap">Person/Form</span>', 'immonex-kickstart-elementor' ) . self::NATIVE_POSTFIX;
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
					__( 'agent', 'immonex-kickstart-elementor' ),
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

		$agent_posts = get_posts(
			[
				'post_type' => 'inx_agent',
				'orderby'   => 'title',
				'order'     => 'ASC',
			]
		);
		$agents      = [
			'' => __( 'Automatic (Property Detail Pages)', 'immonex-kickstart-elementor' ),
		];

		if ( ! empty( $agent_posts ) ) {
			foreach ( $agent_posts as $agent ) {
				$agents[ $agent->ID ] = $agent->post_title;
			}
		}

		// phpcs:ignore
		$agent_elements  = apply_filters( 'inx_team_get_agent_elements', [] );
		$element_options = [];

		if ( ! empty( $agent_elements ) ) {
			uasort(
				$agent_elements,
				function ( $a, $b ) {
					return $a['order'] <=> $b['order'];
				}
			);

			foreach ( $agent_elements as $key => $element ) {
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
				'class' => '.inx-team-single-agent',
			],
			'name'             => [
				'label' => __( 'Name', 'immonex-kickstart-elementor' ),
				'class' => '.inx-team-single-agent__name',
			],
			'contact_elements' => [
				'label' => __( 'Contact Data Elements', 'immonex-kickstart-elementor' ),
				'class' => '.inx-team-single-agent__element-value',
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
				'description' => __( 'The detail page section and the more compact widget view usually contain all contact information incl. photo and a form (see Elements below), the full view also includes a list of related properties and a link to the agency details.', 'immonex-kickstart-elementor' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => [
					''                                    => __( 'Full View (Single)', 'immonex-kickstart-elementor' ),
					'default_contact_element_replacement' => __( 'Contact Section (Property Detail Pages)', 'immonex-kickstart-elementor' ),
					'widget'                              => __( 'Widget', 'immonex-kickstart-elementor' ),
				],
				'default'     => 'default_contact_element_replacement',
			]
		);

		$this->add_control(
			'agent_id',
			[
				'label'       => __( 'Contact Person (Agent)', 'immonex-kickstart-elementor' ),
				'description' => __( 'Only to be selected if the agent data should <strong>not</strong> be embedded in a <strong>property detail page<strong>.', 'immonex-kickstart-elementor' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => $agents,
				'condition'   => [
					'type!' => 'default_contact_element_replacement',
				],
			]
		);

		$default_control_args = [
			'heading'       => [
				'description' => __( 'Insert "auto" for an agent\'s gender conforming standard title.', 'immonex-kickstart-elementor' ),
				'default'     => 'auto',
				'condition'   => [
					'type!' => '',
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
				'label'     => __( 'Agent Link Destination', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''         => __( 'Default', 'immonex-kickstart-elementor' ),
					'internal' => __( 'Internal Profile Page', 'immonex-kickstart-elementor' ),
					'external' => __( 'External URL (if available)', 'immonex-kickstart-elementor' ),
					'none'     => _x( 'None', 'link destination: none', 'immonex-kickstart-elementor' ),
				],
				'condition' => [
					'type!' => 'default_contact_element_replacement',
				],
			]
		);

		$this->add_control(
			'display_for',
			[
				'label'       => __( 'Property Status', 'immonex-kickstart-elementor' ),
				'description' => __( 'Display the agent widget only on detail pages of properties matching the selected state.', 'immonex-kickstart-elementor' ),
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
					'type!' => 'default_contact_element_replacement',
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
				'content'     => __( 'The following settings can be used to override the corresponding ones in the plugin and agent options.', 'immonex-kickstart-elementor' ),
				'condition'   => [
					'use_default_elements' => '',
				],
			]
		);

		$this->add_control(
			'show_property_list',
			[
				'label'       => __( 'Property List', 'immonex-kickstart-elementor' ),
				'description' => __( 'Display properties associated with the agent.', 'immonex-kickstart-elementor' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '',
				'options'     => [
					''    => __( 'Default (Plugin/Agent Options)', 'immonex-kickstart-elementor' ),
					'yes' => __( 'show', 'immonex-kickstart-elementor' ),
					'no'  => __( 'hide', 'immonex-kickstart-elementor' ),
				],
			]
		);

		$this->add_control(
			'show_agency_link',
			[
				'label'       => __( 'Footer Box (Agency Link)', 'immonex-kickstart-elementor' ),
				'description' => __( 'Includes the name/logo linked to the detail page of the related agency and a short description, if available.', 'immonex-kickstart-elementor' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '',
				'options'     => [
					''    => __( 'Default (Plugin/Agent Options)', 'immonex-kickstart-elementor' ),
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
						'{{WRAPPER}} .inx-team-single-agent__title' => 'text-align: {{VALUE}};',
					],
				],
				'heading_title_color'   => [
					'selectors' => [
						'{{WRAPPER}} .inx-team-single-agent__title' => 'color: {{VALUE}};',
					],
				],
				'heading_typography'    => [
					'selector' => '{{WRAPPER}} .inx-team-single-agent__title',
				],
				'heading_text_stroke'   => [
					'selector' => '{{WRAPPER}} .inx-team-single-agent__title',
				],
				'heading_text_shadow'   => [
					'selector' => '{{WRAPPER}} .inx-team-single-agent__title',
				],
				'blend_mode'            => [
					'selectors' => [
						'{{WRAPPER}} .inx-team-single-agent__title' => 'mix-blend-mode: {{VALUE}}',
					],
				],
			],
			false
		);

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
				'condition'    => [
					'type' => 'default_contact_element_replacement',
				],
			]
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

		$this->start_controls_section(
			'list_headline_section',
			[
				'label'     => __( 'List Headlines', 'immonex-kickstart-elementor' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'type' => '',
				],
			]
		);
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
						'{{WRAPPER}} .inx-team-single-agent__list-headline' => 'text-align: {{VALUE}};',
					],
				],
				'heading_title_color'   => [
					'selectors' => [
						'{{WRAPPER}} .inx-team-single-agent__list-headline' => 'color: {{VALUE}};',
					],
				],
				'heading_typography'    => [
					'selector' => '{{WRAPPER}} .inx-team-single-agent__list-headline',
				],
				'heading_text_stroke'   => [
					'selector' => '{{WRAPPER}} .inx-team-single-agent__list-headline',
				],
				'heading_text_shadow'   => [
					'selector' => '{{WRAPPER}} .inx-team-single-agent__list-headline',
				],
				'blend_mode'            => [
					'selectors' => [
						'{{WRAPPER}} .inx-team-single-agent__list-headline' => 'mix-blend-mode: {{VALUE}}',
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

		if ( 'default_contact_element_replacement' === $settings['type'] ) {
			$this->add_render_attribute( 'shortcode', 'template', 'single-agent/default-contact-element-replacement' );
		}

		if ( ! $settings['use_default_elements'] && ! empty( $settings['form_elements'] ) ) {
			$elements = [];

			foreach ( $settings['form_elements'] as $element ) {
				$elements[] = $element['element'];
			}

			$this->add_render_attribute( 'shortcode', 'elements', implode( ',', $elements ) );
		}

		if ( ! empty( $settings['agent_id'] ) ) {
			$this->add_render_attribute( 'shortcode', 'id', $settings['agent_id'] );
		}

		if (
			! empty( $settings['heading'] )
			|| 'default_contact_element_replacement' === $settings['type']
		) {
			$h_tag      = $this->get_h_tag( $settings['heading_level'] );
			$title_attr = 'default_contact_element_replacement' === $settings['type'] ? 'default_contact_section_title' : 'title';

			$this->add_render_attribute( 'shortcode', 'before_title', "<{$h_tag}>" );
			$this->add_render_attribute( 'shortcode', $title_attr, $settings['heading'] );
			$this->add_render_attribute( 'shortcode', 'after_title', "</{$h_tag}>" );
		}

		$ext_atts = [
			'type',
			'contact_form_scope',
			'link_type',
			'display_for',
			'convert_links',
			'show_property_list',
			'show_agency_link',
		];

		foreach ( $ext_atts as $att ) {
			if ( ! empty( $settings[ $att ] ) ) {
				$this->add_render_attribute( 'shortcode', $att, $settings[ $att ] );
			}
		}

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'shortcode', 'is_preview', '1' );
		}

		$shortcode_output = do_shortcode( '[inx-team-agent ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );

		return $shortcode_output ?
			[
				'settings'         => $settings,
				'shortcode_output' => $shortcode_output,
			] :
			false;
	} // get_template_data

} // class Native_Agent_Widget
