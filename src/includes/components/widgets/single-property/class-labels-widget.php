<?php
/**
 * Class Labels_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Labels Widget
 *
 * @since 1.0.0
 */
class Labels_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const POST_TYPE         = 'inx_property';
	const WIDGET_NAME       = 'inx-e-single-property-labels';
	const WIDGET_ICON       = 'eicon-tags';
	const WIDGET_CATEGORIES = [ 'inx-single-property' ];
	const WIDGET_HELP_URL   = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/labels';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Labels', 'immonex-kickstart-elementor' );
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
					__( 'labels', 'immonex-kickstart-elementor' ),
					__( 'tags', 'immonex-kickstart-elementor' ),
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
		$this->start_controls_section(
			'tax_section',
			[
				'label' => __( 'Taxonomies', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_main_class_control();

		$this->add_control(
			'include_label_terms',
			[
				'label'        => __( 'Labels', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'return_value' => '1',
			]
		);

		$this->add_control(
			'include_marketing_type_terms',
			[
				'label'        => __( 'Marketing Type', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'return_value' => '1',
			]
		);

		$this->add_control(
			'include_type_of_use_terms',
			[
				'label'        => __( 'Type of Use', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '0',
				'return_value' => '1',
			]
		);

		$this->add_control(
			'include_property_type_terms',
			[
				'label'        => __( 'Property Type', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '0',
				'return_value' => '1',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'labels_section',
			[
				'label' => 'Labels',
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'layout',
			[
				'label'        => __( 'Layout', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::CHOOSE,
				'default'      => $this->get_default( 'item_layout', 'horizontal' ),
				'options'      => [
					'horizontal' => [
						'title' => __( 'Horizontal', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-ellipsis-h',
					],
					'vertical'   => [
						'title' => __( 'Vertical', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-editor-list-ul',
					],
				],
				'toggle'       => false,
				'prefix_class' => 'inx-e--layout--',
			]
		);

		$this->add_responsive_control(
			'item_align',
			[
				'label'        => __( 'Alignment', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::CHOOSE,
				'options'      => [
					'left'   => [
						'title' => __( 'left', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'center', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => __( 'right', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'inx-e%s--align--',
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label'      => __( 'Space Between', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default(
					'space_between',
					[
						'size' => 8,
						'unit' => 'px',
					]
				),
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px'  => [
						'max' => 64,
					],
					'em'  => [
						'max' => 16,
					],
					'rem' => [
						'max' => 16,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}.inx-e--layout--vertical .inx-e-labels__item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.inx-e--layout--horizontal .inx-e-labels__item:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label'      => __( 'Corner Radius', 'immonex-kickstart-elementor' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'default'    => $this->get_default(
					'border_radius',
					[
						'size' => 4,
						'unit' => 'px',
					]
				),
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'max' => 16,
					],
					'%'  => [
						'max' => 25,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .inx-e-labels__label' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'           => 'label_typography',
				'selector'       => '{WRAPPER}} .inx-e-labels__label',
				'fields_options' => [
					'font_size'      => [
						'default' => [
							'size' => 1,
							'unit' => 'em',
						],
					],
					'font_weight'    => [
						'default' => 'bold',
					],
					'text_transform' => [
						'default' => 'uppercase',
					],
					'line_height'    => [
						'default' => [
							'size' => 1.2,
							'unit' => 'em',
						],
					],
				],
				'separator'      => 'after',
			]
		);

		$this->add_control(
			'color_notice',
			[
				'type'        => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'content'     => __( 'The label colors can be customized in the Kickstart plugin options. <strong>Optionally</strong>, alternative uniform colors for all labels can be selected below.', 'immonex-kickstart-elementor' ),
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Text Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-e-labels__label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label'     => __( 'Background Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-e-labels__label' => 'background: {{VALUE}};',
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
		$settings   = $this->get_settings_for_display();
		$terms      = [];
		$items      = [];
		$taxonomies = [
			'label'          => 'inx_label',
			'marketing_type' => 'inx_marketing_type',
			'type_of_use'    => 'inx_type_of_use',
			'property_type'  => 'inx_property_type',
		];

		foreach ( $taxonomies as $key => $taxonomy ) {
			if ( ! $settings[ "include_{$key}_terms" ] ) {
				continue;
			}

			$tax_terms = get_the_terms( $this->get_post_id(), $taxonomy );

			if ( ! $tax_terms || is_wp_error( $tax_terms ) ) {
				continue;
			}

			$terms = array_merge( $terms, $tax_terms );
		}

		if ( empty( $terms ) ) {
			return false;
		}

		foreach ( $terms as $term ) {
			$tax_basename = str_replace( '_', '-', substr( $term->taxonomy, 4 ) );
			$items[]      = [
				'label' => $term->name,
				'class' => 'inx-e-labels__label--tax--' . $tax_basename . ' inx-e-labels__label--' . $term->slug,
			];
		}

		$this->add_render_attribute( 'list_items', 'class', 'inx-e-labels__items' );
		$this->add_render_attribute( 'list_item', 'class', 'inx-e-labels__item' );

		return ! empty( $items ) ?
			[
				'setting'         => $settings,
				'list_items_attr' => $this->get_render_attribute_string( 'list_items' ),
				'list_item_attr'  => $this->get_render_attribute_string( 'list_item' ),
				'items'           => $items,
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
		return parent::get_demo_content(
			[
				'items' => [
					'marketing_type' => [
						[
							'label' => __( 'For Sale', 'immonex-kickstart-elementor' ),
							'class' => 'inx-e-labels__label--tax--marketing-type inx-e-labels__label--for-sale',
						],
					],
					'label'          => [
						[
							'label' => __( 'Reserved', 'immonex-kickstart-elementor' ),
							'class' => 'inx-e-labels__label--tax--label inx-e-labels__label--reserved',
						],
						[
							'label' => __( 'Demo', 'immonex-kickstart-elementor' ),
							'class' => 'inx-e-labels__label--tax--label inx-e-labels__label--demo',
						],
					],
					'type_of_use'    => [
						[
							'label' => __( 'Living Property', 'immonex-kickstart-elementor' ),
							'class' => 'inx-e-labels__label--tax--type-of-use inx-e-labels__label--living-property',
						],
					],
					'property_type'  => [
						[
							'label' => __( 'Single-family House', 'immonex-kickstart-elementor' ),
							'class' => 'inx-e-labels__label--tax--property-type inx-e-labels__label--single-family-house',
						],
					],
				],
			]
		);
	} // get_demo_content

} // class Labels_Widget
