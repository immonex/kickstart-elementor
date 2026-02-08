<?php
/**
 * Plugin Name:       immonex Kickstart for Elementor
 * Requires Plugins:  immonex-kickstart, immonex-kickstart-team, elementor
 * Plugin URI:        https://immonex.dev/wordpress-immobilien-plugin/immonex-kickstart-for-elementor
 * Description:       35+ widgets and dynamic tags for creating professional real estate websites with immonex Kickstart and Elementor
 * Version:           1.0.0
 * Text Domain:       immonex-kickstart-for-elementor
 * Domain Path:       /languages
 * Requires at least: 6.5
 * Requires PHP:      8.3
 * Author:            inveris OHG / immonex
 * Author URI:        https://immonex.dev/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * immonex Kickstart for Elementor is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or any
 * later version.
 *
 * immonex Kickstart for Elementor is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this software. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor;

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
$immonex_kickstart_for_elementor = new Kickstart_For_Elementor( basename( __FILE__, '.php' ) );
$immonex_kickstart_for_elementor->init( 20 );
