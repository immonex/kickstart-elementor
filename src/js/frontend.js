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

	window.addEventListener('inxkick/search/initialized', () => {
		$('.inx-property-search.inx-dynamic-update').on('search:change', updatePropertyLoops)
	})

	function updatePropertyLoops(event, requestParams) {
		if (!requestParams.searchStateInitialized) return

		const propertyLoops = document.querySelectorAll('.elementor-widget-loop-grid .inx-real-estate-list')

		if (propertyLoops.length) {
			propertyLoops.forEach(propertyLoop => {
				const loopWidget = $(propertyLoop).parentsUntil('.elementor-widget-loop-grid').parent()
				const widgetID = loopWidget[0].dataset.id

				if (widgetID) {
					refreshLoopWidget(widgetID, requestParams)
				}
			})
		}
	} // updatePropertyLoops

	function refreshLoopWidget(widgetID, requestParams) {
		const widget = document.querySelector(`.elementor-element-${widgetID}`)
		if (!widget) return

		widget.classList.add('e-loading-overlay')
		widget.classList.remove('e-load-more-pagination-end')

		const updatedLoopWidgetMarkup = fetchUpdatedLoopWidgetMarkup(widgetID, requestParams).then(response => {
			if (!(response instanceof Response) || !response?.ok || 400 <= response?.status) {
				return {}
			}
			return response.json();
		}).catch(() => {
			return {}
		}).then(response => {
			if (!response?.data && '' !== response?.data) return

			const existingWidgetContainer = widget.querySelector('.elementor-widget-container')
			const newWidgetContainer = createElementFromHTMLString(response.data)

			widget.replaceChild(newWidgetContainer, existingWidgetContainer)

			if (ElementorProFrontendConfig.settings.lazy_load_background_images) {
				document.dispatchEvent(new Event('elementor/lazyload/observe'))
			}

			elementorFrontend.elementsHandler.runReadyTrigger(document.querySelector(`.elementor-element-${widgetID}`))
		}).finally(() => {
			widget.classList.remove('e-loading-overlay')
		})

		return updatedLoopWidgetMarkup
	} // refreshLoopWidget

	function createElementFromHTMLString(widgetContainerHTMLString) {
		const div = document.createElement('div')

		if (!widgetContainerHTMLString) {
			div.classList.add('elementor-widget-container')
			return div
		}
		div.innerHTML = widgetContainerHTMLString.trim()

		return div.firstElementChild
	} // createElementFromHTMLString

	function fetchUpdatedLoopWidgetMarkup(widgetID, requestParams) {
		const elementorRestURL = new URL(`${elementorProFrontend.config.urls.rest}elementor-pro/v1/refresh-loop`)
		const searchParams = new URLSearchParams(requestParams.paramsString);

		elementorRestURL.search = searchParams.toString()

		return fetch(elementorRestURL.toString(), getFetchArgumentsForLoopUpdate(widgetID, requestParams))
	} // fetchUpdatedLoopWidgetMarkup

	function getFetchArgumentsForLoopUpdate(widgetID, requestParams) {
	    const data = prepareLoopUpdateRequestData(widgetID, requestParams)

		return {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(data)
		}
	} // getFetchArgumentsForLoopUpdate

	function prepareLoopUpdateRequestData(widgetID, requestParams) {
		const params = new URLSearchParams();

		const data = {
			post_id: getClosestDataElementorId(document.querySelector(`.elementor-element-${widgetID}`)) || elementorFrontend.config.post.id,
			widget_filters: params,
			widget_id: widgetID,
			pagination_base_url: location.href.replace(location.search, '')
		};

		return data
	}

	function getClosestDataElementorId(element) {
		const closestParent = element?.closest('[data-elementor-id]')
		return closestParent ? closestParent.getAttribute('data-elementor-id') : null
	}

	function runElementHandlers(elements) {
		[...elements].flatMap(el => [...el.querySelectorAll('.elementor-element')]).forEach(el => elementorFrontend.elementsHandler.runReadyTrigger(el))
	}
})
