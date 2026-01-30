<?php
/**
 * Class Property_Type_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Type Widget
 *
 * @since 1.0.0
 */
class Property_Type_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Widget_Base {

	const POST_TYPE         = 'inx_property';
	const WIDGET_NAME       = 'inx-e-single-property-type';
	const WIDGET_ICON       = 'eicon-angle-right';
	const WIDGET_CATEGORIES = [ 'inx-single-property' ];
	const WIDGET_HELP_URL   = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/objektart-pfad';

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Property Type', 'immonex-kickstart-elementor' ) .
			' (' . __( 'Trail', 'immonex-kickstart-elementor' ) . ')';
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
					__( 'type', 'immonex-kickstart-elementor' ),
					__( 'breadcrumb', 'immonex-kickstart-elementor' ),
					__( 'trail', 'immonex-kickstart-elementor' ),
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
			'general_content_section',
			[
				'label' => __( 'General', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_main_class_control();

		$this->add_control(
			'info',
			[
				'type'        => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'content'     => __( 'The property type is rendered as breadcrumb trail with (optional) links to the related category overview/archive pages.', 'immonex-kickstart-elementor' ),
			]
		);

		$this->add_control(
			'include_type_of_use',
			[
				'label'        => __( 'Include Type of Use', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'return_value' => '1',
			]
		);

		$this->add_control(
			'include_parent',
			[
				'label'        => __( 'Include Parent Type', 'immonex-kickstart-elementor' ),
				'description'  => __( 'e.g. <strong>Houses</strong> for a property types like <strong>Single-family home</strong>', 'immonex-kickstart-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => '1',
				'return_value' => '1',
			]
		);

		$this->add_control(
			'link_type',
			[
				'label'       => __( 'Links', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'The default overview page can be selected in the <strong>lists</strong> tab of the Kickstart plugin options.', 'immonex-kickstart-elementor' ),
				'options'     => [
					''        => _x( 'None', 'link destination: none', 'immonex-kickstart-elementor' ),
					'list'    => __( 'Default Overview Page', 'immonex-kickstart-elementor' ),
					'archive' => __( 'Archive Page', 'immonex-kickstart-elementor' ),
				],
				'default'     => 'list',
			]
		);

		$this->add_control(
			'icon',
			[
				'label'       => __( 'Icon', 'immonex-kickstart-elementor' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'description' => __( 'The icon is displayed as separator between the trail elements (&gt; if not selected).', 'immonex-kickstart-elementor' ),
				'default'     => $this->get_default(
					'icon',
					[
						'value'   => 'fas fa-angle-right',
						'library' => 'fa-solid',
					]
				),
				'recommended' => [
					'fa-solid'   => [
						'angle-right',
						'angle-double-right',
						'chevron-right',
						'arrow-right',
						'arrow-circle-right',
						'arrow-alt-circle-right',
						'grip-lines-vertical',
					],
					'fa-regular' => [
						'arrow-circle-right',
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'text_section',
			[
				'label' => __( 'Text/Icon', 'immonex-kickstart-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label'     => __( 'Alignment', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => __( 'Left', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'   => [
						'title' => __( 'Right', 'immonex-kickstart-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .inx-e-single-property-type__items' => 'justify-content: {{VALUE}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-e-single-property-type__items'         => 'color: {{VALUE}}',
					'{{WRAPPER}} .inx-e-single-property-type__separator svg' => 'fill: {{VALUE}}',
				],
				'global'    => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_control(
			'link_color',
			[
				'label'     => __( 'Link Color', 'immonex-kickstart-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inx-e-single-property-type__item a.inx-e-single-property-type__title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'           => 'text_typography',
				'selector'       => '{{WRAPPER}} .inx-e-single-property-type__items, {{WRAPPER}} .inx-e-single-property-type__separator',
				'fields_options' => [
					'font_size' => [
						'default' => [
							'size' => 1,
							'unit' => 'em',
						],
					],
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
		$settings   = $this->get_settings_for_display();
		$items      = [];
		$taxonomies = $settings['include_type_of_use'] ?
			[ 'inx_type_of_use', 'inx_property_type' ] :
			[ 'inx_property_type' ];

		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_the_terms( $this->get_post_id(), $taxonomy );

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$query_arg_name = str_replace( [ 'inx_', '_' ], [ 'inx-search-', '-' ], $term->taxonomy );

					if ( $settings['include_parent'] && $term->parent ) {
						$parent = get_term( $term->parent, $taxonomy );
						$url    = '';

						if ( $settings['link_type'] ) {
							$url = 'list' === $settings['link_type'] ?
								add_query_arg( $query_arg_name, $parent->slug, get_post_type_archive_link( 'inx_property' ) ) :
								get_term_link( $parent );
						}

						$items[] = [
							'title'    => $parent->name,
							'url'      => $url,
							'taxonomy' => $taxonomy,
						];
					}

					if ( $settings['link_type'] ) {
						$url = 'list' === $settings['link_type'] ?
							add_query_arg( $query_arg_name, $term->slug, get_post_type_archive_link( 'inx_property' ) ) :
							get_term_link( $term );
					}

					$items[] = [
						'title'    => $term->name,
						'url'      => $url,
						'taxonomy' => $taxonomy,
					];
				}
			}
		}

		$this->add_render_attribute( 'list_items', 'class', 'inx-e-single-property-type__items' );
		if ( $settings['link_type'] ) {
			$this->add_render_attribute( 'list_items', 'itemscope', '' );
			$this->add_render_attribute( 'list_items', 'itemtype', 'https://schema.org/BreadcrumbList' );
		}

		$this->add_render_attribute( 'list_item', 'class', 'inx-e-single-property-type__item' );
		if ( $settings['link_type'] ) {
			$this->add_render_attribute( 'list_item', 'itemscope', '' );
			$this->add_render_attribute( 'list_item', 'itemprop', 'itemListElement' );
			$this->add_render_attribute( 'list_item', 'itemtype', 'https://schema.org/ListItem' );
		}

		$font_size      = ! empty( $settings['text_typography_font_size']['size'] ) ?
			$settings['text_typography_font_size']['size'] : 1;
		$font_size_unit = ! empty( $settings['text_typography_font_size']['unit'] ) ?
			$settings['text_typography_font_size']['unit'] : 'em';
		$icon_width     = ( $font_size / 2 ) . $font_size_unit;
		$icon_html      = ! empty( $settings['icon'] ) ?
			\Elementor\Icons_Manager::try_get_icon_html(
				$settings['icon'],
				[
					'width'       => $icon_width,
					'aria-hidden' => true,
				]
			) : '';

		return ! empty( $items ) ?
			[
				'settings'        => $settings,
				'list_items_attr' => $this->get_render_attribute_string( 'list_items' ),
				'list_item_attr'  => $this->get_render_attribute_string( 'list_item' ),
				'icon_html'       => $icon_html,
				'items'           => $items,
			] :
			false;
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
		$items = [
			[
				'title' => __( 'Residential Property', 'immonex-kickstart-elementor' ),
				'url'   => '#',
				'type'  => 'type_of_use',
			],
			[
				'title' => __( 'Houses', 'immonex-kickstart-elementor' ),
				'url'   => '#',
				'type'  => 'parent',
			],
			[
				'title' => __( 'Single-family House', 'immonex-kickstart-elementor' ),
				'url'   => '#',
				'type'  => '',
			],
		];

		return parent::get_demo_content( [ 'items' => $items ] );
	} // get_demo_content

} // class Property_Type_Widget
