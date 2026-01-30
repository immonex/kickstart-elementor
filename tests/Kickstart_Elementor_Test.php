<?php
/**
 * Unit tests for Kickstart_Elementor class.
 *
 * @package immonex\KickstartElementor
 */

use immonex\Kickstart\Elementor\Kickstart_Elementor;

class Kickstart_Team_Test extends WP_UnitTestCase {
	private $kickstart_elementor;

	public function setUp(): void {
		$this->kickstart_elementor = new Kickstart_Elementor( 'immonex-kickstart-elementor' );
	} // setUp

	public function test_bootstrap_data() {
		$expected = array(
			'plugin_name' => 'immonex Kickstart Elementor',
			'plugin_slug' => 'immonex-kickstart-elementor',
			'plugin_prefix' => 'inx_elementor_',
			'public_prefix' => 'inx-elementor-'
		);

		$bootstrap_data = $this->kickstart_elementor->bootstrap_data;

		foreach ( $expected as $key => $expected_value ) {
			$this->assertEquals( $expected_value, $bootstrap_data[$key] );
		}
	} // test_bootstrap_data
} // class Kickstart_Team_Test
