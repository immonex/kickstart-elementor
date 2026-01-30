jQuery(document).ready(async function($) {
	const elementorObserver = new MutationObserver(function(mutations, observer) {
		for (const mutation of mutations) {
			if (mutation.type !== 'childList' || typeof mutation.target.dataset.widget_type === 'undefined') {
				continue
			}

			if (mutation.target.dataset.widget_type === 'inx-e-native-search-form.default') {
				const reinitEvent = new CustomEvent('inxInitPropertySearch', {
					bubbles: true,
					cancelable: true,
					composed: false,
				})
				document.body.dispatchEvent(reinitEvent)
			}

			if (mutation.target.dataset.widget_type === 'inx-e-single-property-native-location-map.default') {
				const reinitEvent = new CustomEvent('inxInitDetails', {
					bubbles: true,
					cancelable: true,
					composed: false,
				})
				document.body.dispatchEvent(reinitEvent)
			}

			if (mutation.target.dataset.widget_type === 'inx-e-native-property-map.default') {
				const reinitEvent = new CustomEvent('inxInitPropertyMap', {
					bubbles: true,
					cancelable: true,
					composed: false,
				})
				document.body.dispatchEvent(reinitEvent)
			}
		}
	})

	elementorObserver.observe(document, {
		childList: true,
		subtree: true,
		attributes: true,
	})
})