<?php
/**
 * Class Map_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets;

/**
 * Extended Elementor Widget Base Class.
 */
abstract class Map_Widget extends Widget_Base {

	const WIDGET_ICON                     = 'eicon-google-maps';
	const DEFAULT_MARKER_FILL_COLOR       = '#E77906';
	const DEFAULT_MARKER_FILL_OPACITY_PCT = 80;
	const DEFAULT_MARKER_STROKE_COLOR     = '#404040';
	const DEFAULT_MARKER_STROKE_WIDTH_PX  = 3;
	const DEFAULT_MARKER_SCALE_PCT        = 75;
	const ENABLE_RENDER_ON_PREVIEW        = true;
	const IS_DYNAMIC_CONTENT              = true;

	/**
	 * Return widget contents for frontend template rendering.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[]|bool Template data array or false if unavailable.
	 */
	protected function get_template_data() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'shortcode', 'type', $settings['type'] );

		if ( 'custom' === $settings['marker_type'] && ! empty( $settings['marker_icon_url'] ) ) {
			$this->add_render_attribute( 'shortcode', 'marker_icon_url', $settings['marker_icon_url'] );
		} else {
			$this->add_render_attribute(
				'shortcode',
				'marker_fill_opacity',
				( ! empty( $settings['marker_fill_opacity']['size'] ) ? $settings['marker_fill_opacity']['size'] : self::DEFAULT_MARKER_FILL_OPACITY_PCT ) / 100
			);
			$this->add_render_attribute(
				'shortcode',
				'marker_scale',
				( ! empty( $settings['marker_scale']['size'] ) ? $settings['marker_scale']['size'] : self::DEFAULT_MARKER_SCALE_PCT ) / 100
			);

			$sc_attributes = [
				'marker_fill_color'   => self::DEFAULT_MARKER_FILL_COLOR,
				'marker_stroke_color' => self::DEFAULT_MARKER_STROKE_COLOR,
				'marker_stroke_width' => self::DEFAULT_MARKER_STROKE_WIDTH_PX,
			];

			foreach ( $sc_attributes as $attribute => $default ) {
				if ( ! empty( $settings[ $attribute ] ) ) {
					$this->add_render_attribute(
						'shortcode',
						$attribute,
						is_array( $settings[ $attribute ] ) ?
							$settings[ $attribute ]['size'] :
							$settings[ $attribute ]
					);
				} else {
					$this->add_render_attribute( 'shortcode', $attribute, $default );
				}
			}
		}

		if ( ! empty( $settings['google_api_key'] ) ) {
			$this->add_render_attribute( 'shortcode', 'google_api_key', $settings['google_api_key'] );
		}

		if ( ! empty( $settings['options'] ) ) {
			$this->add_render_attribute( 'shortcode', 'options', $settings['options'] );
		}

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->add_render_attribute( 'shortcode', 'is_preview', '1' );
		}

		return [
			'settings'         => $settings,
			'shortcode_output' => '',
		];
	} // get_template_data

} // class Widget_Base
