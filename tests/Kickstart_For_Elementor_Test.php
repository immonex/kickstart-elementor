<?php
/**
 * Unit tests for Kickstart_Elementor class.
 *
 * @package immonex\KickstartForElementor
 */

use immonex\Kickstart\ForElementor\Kickstart_For_Elementor;

class Kickstart_For_Elementor_Test extends WP_UnitTestCase {
	private $kickstart_for_elementor;

	public function setUp(): void {
		$this->kickstart_for_elementor = new Kickstart_For_Elementor( 'immonex-kickstart-for-elementor' );
	} // setUp

	public function test_bootstrap_data() {
		$expected = array(
			'plugin_name'   => 'immonex Kickstart for Elementor',
			'plugin_slug'   => 'immonex-kickstart-for-elementor',
			'plugin_prefix' => 'inxkickel_',
			'public_prefix' => 'inxkickel-'
		);

		$bootstrap_data = $this->kickstart_for_elementor->bootstrap_data;

		foreach ( $expected as $key => $expected_value ) {
			$this->assertEquals( $expected_value, $bootstrap_data[$key] );
		}
	} // test_bootstrap_data
} // class Kickstart_For_Elementor_Test
