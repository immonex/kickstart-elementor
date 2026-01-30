<?php
/**
 * Class Core_Details_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Core Details Widget
 *
 * @since 1.0.0
 */
class Core_Details_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Key_Value_List_Widget {

	const WIDGET_NAME            = 'inx-e-single-property-core-details';
	const WIDGET_HELP_URL        = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/kerndaten';
	const DEFAULT_CONTROL_SCOPES = [];
	const DISABLE_SOURCE_NOTICE  = true;

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Core Details', 'immonex-kickstart-elementor' );
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
					__( 'core', 'immonex-kickstart-elementor' ),
					__( 'details', 'immonex-kickstart-elementor' ),
				]
			)
		);
	} // add_keywords

	/**
	 * Return widget contents and settings for frontend template rendering.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[] Template data array or false if not available.
	 */
	protected function get_template_data() {
		$template_data = parent::get_template_data();

		if ( empty( $template_data ) ) {
			return false;
		}

		$primary_area    = 0;
		$primary_rooms   = 0;
		$living_bedrooms = 0;

		foreach ( $template_data['items'] as $i => $item ) {
			if ( 'primaerflaeche' === $item['element'] ) {
				$primary_area = (int) $item['value'];
				continue;
			}

			if ( 'primaeranzahl_zimmer' === $item['element'] ) {
				$primary_rooms = (int) $item['value'];
				continue;
			}

			if ( 'anzahl_wohn_schlafzimmer' === $item['element'] ) {
				$living_bedrooms = (int) $item['value'];
			}
		}

		foreach ( $template_data['items'] as $i => $item ) {
			if (
				'grundstuecksflaeche' === $item['element']
				&& (int) $item['value'] === $primary_area
			) {
				unset( $template_data['items'][ $i ] );
				continue;
			}

			if (
				'anzahl_wohn_schlafzimmer' === $item['element']
				&& (int) $item['value'] === $primary_rooms
			) {
				unset( $template_data['items'][ $i ] );
				continue;
			}

			if (
				'anzahl_schlafzimmer' === $item['element']
				&& (
					(int) $item['value'] === $primary_rooms
					|| (int) $item['value'] === $living_bedrooms
				)
			) {
				unset( $template_data['items'][ $i ] );
			}
		}

		return ! empty( $template_data['items'] ) ? $template_data : false;
	} // get_template_data

	/**
	 * Return default value for the given control.
	 *
	 * @since 1.0.0
	 *
	 * @param string        $control_id    Control ID.
	 * @param mixed|mixed[] $default_value Default value if not specified otherwise.
	 * @param string        $breakpoint    Elementor breakpoint key (optional).
	 *
	 * @return mixed[] Defaul data.
	 */
	protected function get_default( $control_id, $default_value = '', $breakpoint = 'default' ) {
		$defaults = [
			'layout'              => 'inline',
			'item_order'          => 'icon-value',
			'item_layout'         => 'horizontal',
			'item_vertical_align' => 'baseline',
			'global_value_color'  => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_SECONDARY,
			'icon_size'           => [ 'size' => 24 ],
			'icon_gap'            => [ 'size' => 8 ],
		];

		return ! empty( $defaults[ $control_id ] ) ? $defaults[ $control_id ] : $default_value;
	} // get_default

	/**
	 * Return default elements.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[] Element data.
	 */
	protected function get_default_elements() {
		$element_keys = [
			'verwaltung_techn.objektnr_extern',
			'baujahr',
			'primaerflaeche',
			'grundstuecksflaeche',
			'primaeranzahl_zimmer',
			'anzahl_wohn_schlafzimmer',
			'anzahl_schlafzimmer',
			'anzahl_badezimmer',
		];

		$predefined_elements = $this->get_predefined_elements();
		$elements            = [];

		foreach ( $element_keys as $key ) {
			if ( ! isset( $predefined_elements[ $key ] ) ) {
				continue;
			}

			$elements[] = array_merge(
				[ 'predefined_element' => $key ],
				$predefined_elements[ $key ]
			);
		}

		return $elements;
	} // get_default_elements

	/**
	 * Return predefined elements.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[] Element data.
	 */
	protected function get_predefined_elements() {
		if ( ! empty( $this->predefined_elements ) ) {
			return $this->predefined_elements;
		}

		$this->predefined_elements = [
			'verwaltung_techn.objektnr_extern' => [
				'element_type' => 'name',
				'title'        => __( 'Property #', 'immonex-kickstart-elementor' ),
				'icon'         => [
					'value'   => 'fas fa-hashtag',
					'library' => 'fa-solid',
				],
				'value'        => '123',
			],
			'geo.ort'                          => [
				'element_type' => 'name',
				'title'        => __( 'Locality', 'immonex-kickstart-elementor' ),
				'icon'         => [
					'value'   => 'fas fa-map-marked-alt',
					'library' => 'fa-solid',
				],
				'value'        => 'North Kilttown',
			],
			'baujahr'                          => [
				'element_type' => 'name',
				'title'        => __( 'Build Year', 'immonex-kickstart-elementor' ),
				'icon'         => [
					'value'   => 'fas fa-calendar-alt',
					'library' => 'fa-solid',
				],
				'value'        => '1982',
			],
			'primaerflaeche'                   => [
				'element_type' => 'name',
				'title'        => __( 'Area', 'immonex-kickstart-elementor' ),
				'format'       => 'inx_format_area',
				'icon'         => [
					'value'   => 'fas fa-ruler-combined',
					'library' => 'fa-solid',
				],
				'value'        => '140 m²',
			],
			'grundstuecksflaeche'              => [
				'element_type' => 'name',
				'title'        => __( 'Plot Area', 'immonex-kickstart-elementor' ),
				'format'       => 'inx_format_area',
				'icon'         => [
					'value'   => 'far fa-object-group',
					'library' => 'fa-regular',
				],
				'value'        => '820 m²',
			],
			'primaeranzahl_zimmer'             => [
				'element_type' => 'name',
				'title'        => __( 'Rooms', 'immonex-kickstart-elementor' ),
				'format'       => 'inx_format_number',
				'icon'         => [
					'value'   => 'fas fa-door-open',
					'library' => 'fa-solid',
				],
			],
			'anzahl_wohn_schlafzimmer'         => [
				'element_type' => 'name',
				'title'        => __( 'Living Rooms/Bedrooms', 'immonex-kickstart-elementor' ),
				'format'       => 'inx_format_number',
				'icon'         => [
					'value'   => 'fas fa-bed',
					'library' => 'fa-solid',
				],
			],
			'anzahl_schlafzimmer'              => [
				'element_type' => 'name',
				'title'        => __( 'Bedrooms', 'immonex-kickstart-elementor' ),
				'format'       => 'inx_format_number',
				'icon'         => [
					'value'   => 'fas fa-bed',
					'library' => 'fa-solid',
				],
				'value'        => '3',
			],
			'anzahl_badezimmer'                => [
				'element_type' => 'name',
				'title'        => __( 'Bathrooms', 'immonex-kickstart-elementor' ),
				'format'       => 'inx_format_number',
				'icon'         => [
					'value'   => 'fas fa-bath',
					'library' => 'fa-solid',
				],
				'value'        => '2',
			],
			'primaerpreis'                     => [
				'element_type' => 'name',
				'title'        => __( 'Primary Price', 'immonex-kickstart-elementor' ),
				'format'       => 'inx_format_price',
				'icon'         => [
					'value'   => 'fas fa-euro-sign',
					'library' => 'fa-solid',
				],
				'value'        => '320.000 €',
			],
		];

		return parent::get_predefined_elements();
	} // get_predefined_elements

	/**
	 * Return demo contents for preview rendering.
	 *
	 * @since 1.0.0
	 *
	 * @param string[]|null $content Source Demo content.
	 *
	 * @return string|null Demo content or empty string.
	 */
	protected function get_demo_content( $content = null ) {
		$predefined_elements = $this->get_predefined_elements();

		return parent::get_demo_content(
			[
				'predefined_elements' => $predefined_elements,
			]
		);
	} // get_demo_content

} // class Core_Details_Widget
