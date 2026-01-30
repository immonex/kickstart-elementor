jQuery(document).ready(async function($) {
	/**
	 * Temporary fix for Elementor bug #18151
	 * https://github.com/elementor/elementor/issues/18151
	 */
	elementor.hooks.addAction( 'panel/open_editor/widget', function(panel, model, view) {
		panel.$el.on('click', function() {
			const dialog = $('.elementor-tag-settings-popup')[0]
			if (!dialog) return

			const helperFields = dialog.querySelectorAll('input[data-setting="repeater_bugfix"]')
			if (helperFields.length === 0) return

			function addInputListeners() {
				dialog.querySelectorAll('input, select').forEach((element) => {
					if (!element.getAttribute('listener')) {
						element.addEventListener('change', function() {
							window.setTimeout(function() {
								helperFields[0].dispatchEvent(new Event('input', { bubbles: true }))
							}, 100)
						})
						element.setAttribute('listener', true)
					}
				})
			} // addInputListeners

			const repeaterAddButton = dialog.getElementsByClassName('elementor-repeater-add')[0]

			if (repeaterAddButton && !repeaterAddButton.getAttribute('listener')) {
				repeaterAddButton.addEventListener('click', function() {
					window.setTimeout(function() {
						addInputListeners()
					}, 100)
				})
				repeaterAddButton.setAttribute('listener', true)
			}

			addInputListeners()
		})
	})
})