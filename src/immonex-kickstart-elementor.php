<?php
/**
 * Plugin Name:       immonex Kickstart Elementor
 * Requires Plugins:  immonex-kickstart, immonex-kickstart-team, elementor
 * Plugin URI:        https://immonex.dev/wordpress-immobilien-plugin/immonex-kickstart-elementor
 * Description:       Add-on plugin providing 35+ Elementor widgets and dynamic tags for creating professional real estate sites with Kickstart and Elementor
 * Version:           1.0.0
 * Text Domain:       immonex-kickstart-elementor
 * Domain Path:       /languages
 * Requires at least: 6.5
 * Requires PHP:      8.3
 * Author:            inveris OHG / immonex
 * Author URI:        https://immonex.dev/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * immonex Kickstart Elementor is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or any
 * later version.
 *
 * immonex Kickstart Elementor is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this software. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 *
 * @package immonex\KickstartElementor
 */

namespace immonex\Kickstart\Elementor;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initialize autoloaders (Composer AND WP/plugin-specific).
 */
require_once __DIR__ . '/autoload.php';

/**
 * Instantiate plugin main class.
 */
$immonex_kickstart_elementor = new Kickstart_Elementor( basename( __FILE__, '.php' ) );
$immonex_kickstart_elementor->init( 20 );

// Global alias.
$inx_elementor = $immonex_kickstart_elementor; // phpcs:ignore
