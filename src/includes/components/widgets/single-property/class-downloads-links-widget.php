<?php
/**
 * Class Downloads_Links_Widget
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor\Components\Widgets\SingleProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Single Property Downloads and Links Widget
 *
 * @since 1.0.0
 */
class Downloads_Links_Widget extends \immonex\Kickstart\Elementor\Components\Widgets\Icon_List_Widget {

	const WIDGET_NAME           = 'inx-e-single-property-downloads-links';
	const WIDGET_HELP_URL       = 'https://docs.immonex.de/kickstart-elementor/#/elementor-immobilien-widgets/downloads-und-links';
	const ENABLE_ICON_SELECTION = false;

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Downloads & Links', 'immonex-kickstart-elementor' );
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
					__( 'downloads', 'immonex-kickstart-elementor' ),
					__( 'links', 'immonex-kickstart-elementor' ),
				]
			)
		);
	} // add_keywords

	/**
	 * Return widget contents and settings for frontend template rendering.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[]|bool Template data array or false if unavailable.
	 */
	protected function get_template_data() {
		$template_data = parent::get_template_data();

		$files = apply_filters( 'inx_get_property_files', [], false );
		$links = apply_filters( 'inx_get_property_links', [], false );

		if ( empty( $files ) && empty( $links ) ) {
			return false;
		}

		if ( ! empty( $files ) ) {
			$doc_att_types = array(
				'pdf',
				'txt',
				'rtf',
				'msword',
				'vnd.oasis.opendocument.text',
			);

			foreach ( $files as $att ) {
				if ( in_array( $att['subtype'], $doc_att_types, true ) ) {
					$icon = [
						'value'   => 'far fa-file-pdf',
						'library' => 'fa-regular',
					];
				} elseif ( 'image' === $att['type'] ) {
					$icon = [
						'value'   => 'far fa-file-image',
						'library' => 'fa-regular',
					];
				} else {
					$icon = [
						'value'   => 'far fa-file-download',
						'library' => 'fa-regular',
					];
				}

				$template_data['items'][] = [
					'title' => $att['title'],
					'icon'  => \Elementor\Icons_Manager::try_get_icon_html( $icon, [ 'aria-hidden' => 'true' ] ),
					'url'   => $att['url'],
				];
			}
		}

		if ( ! empty( $links ) ) {
			$utils = apply_filters( 'inx_elementor_get_utils', [] );
			$icon  = \Elementor\Icons_Manager::try_get_icon_html(
				[
					'value'   => 'fas fa-external-link-alt',
					'library' => 'fa-solid',
				]
			);

			foreach ( $links as $link ) {
				$title = $link['title'];
				if ( ! $title ) {
					$title = $utils['string']->get_excerpt( trim( $link['url'] ), 32 );
				}

				$template_data['items'][] = [
					'title' => $title,
					'icon'  => $icon,
					'url'   => $link['url'],
				];
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
			'heading' => __( 'Downloads and Links', 'immonex-kickstart-elementor' ),
		];

		return ! empty( $defaults[ $control_id ] ) ? $defaults[ $control_id ] : $default_value;
	} // get_default

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
		return parent::get_demo_content(
			[
				'items' => [
					[
						'title' => __( 'Information Brochure', 'immonex-kickstart-elementor' ),
						'type'  => 'pdf',
						'icon'  => [
							'value'   => 'far fa-file-pdf',
							'library' => 'fa-regular',
						],
						'url'   => '#',
					],
					[
						'title' => 'inveris PluginShop',
						'type'  => 'link',
						'icon'  => [
							'value'   => 'fas fa-external-link-alt',
							'library' => 'fa-solid',
						],
						'url'   => 'https://plugins.inveris.de/',
					],
					[
						'title' => 'immonex.dev',
						'type'  => 'link',
						'icon'  => [
							'value'   => 'fas fa-external-link-alt',
							'library' => 'fa-solid',
						],
						'url'   => 'https://immonex.dev/',
					],
				],
			]
		);
	} // get_demo_content

} // class Downloads_Links_Widget
