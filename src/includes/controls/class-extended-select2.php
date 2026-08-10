<?php
/**
 * Class Extended_Select2
 *
 * @package immonex\KickstartForElementor
 */

namespace immonex\Kickstart\ForElementor\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Extended Elementor select2 control.
 *
 * A base control for creating select2 control. Displays a select box control
 * based on select2 jQuery plugin @see https://select2.github.io/ .
 * It accepts an array in which the `key` is the value and the `value` is the
 * option name. Set `multiple` to `true` to allow multiple value selection.
 *
 * @since 1.4.0
 */
class Extended_Select2 extends \Elementor\Control_Select2 {

	/**
	 * Get select2 control type.
	 *
	 * Retrieve the control type, in this case `select2`.
	 *
	 * @since 1.4.0
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'inxkickel-extended-select2';
	} // get_type

	/**
	 * Enqueue JS.
	 *
	 * @since 1.4.0
	 */
	public function enqueue(): void {
		wp_register_script(
			'inxkickel-extended-select2',
			plugins_url( '/assets/js/extended-select2.js', dirname( __DIR__ ) ),
			[],
			\immonex\Kickstart\ForElementor\Kickstart_For_Elementor::PLUGIN_VERSION,
			[ 'in_footer' => true ]
		);
		wp_enqueue_script( 'inxkickel-extended-select2' );
	} // enqueue

	/**
	 * Render select2 control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.4.0
	 */
	public function content_template() {
		?>
		<div class="elementor-control-field">
			<# if ( data.label ) {#>
			<label for="<?php $this->print_control_uid(); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper elementor-control-unit-5">
				<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<select id="<?php $this->print_control_uid(); ?>" class="elementor-select2" type="select2" {{ multiple }} data-setting="{{ data.name }}">
					<# _.each( data.options, function(optionTitle, optionValue) {
						let value = data.controlValue
						let selected = ''

						if (typeof value === 'string' && optionValue === value) {
							selected = 'selected'
						} else if (null !== value) {
							value = _.values(value)
							selected = (-1 !== value.indexOf(optionValue)) ? 'selected' : ''
						}

						let cut = 0
						// Extract substrings wrapped in brackets.
						let bracketParts = [...optionTitle.matchAll(/\((?:[^()]*|\([^()]*\))*\)/g)]

						if (bracketParts.length > 1) {
							cut = bracketParts[1].index
						} else if (bracketParts.length === 1 && bracketParts[0][0].indexOf(',') >= 0) {
							cut = bracketParts[0].index
						}

						if (!cut) {
							brackets = [...optionTitle.matchAll(/\[/g)]
							cut = brackets.length > 0 ? brackets[0].index : 0
						}

						let optionTitleDisplay = cut ? optionTitle.substring(0, cut).trim() : optionTitle

						if ('group' === optionValue.substr(0, 5)) {
							optionTitleDisplay = '🄶 ' + optionTitleDisplay
						} else if ('destination' === optionValue.substr(0, 11)) {
							optionTitleDisplay = '🄳 ' + optionTitleDisplay
						}

						optionTitle = optionTitle.substring(cut).trim().replace(/(\((.*)\) )?\[(.*)\]/, '$3\n\n🠊 $2').replace(/\n\n🠊 $/, '')
						#>
					<option {{ selected }} value="{{ _.escape( optionValue ) }}" title="{{ optionTitle }}">{{{ _.escape( optionTitleDisplay ) }}}</option>
					<# } ); #>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	} // content_template

} // class Extended_Select2
