window.addEventListener( 'elementor/init', () => {
	const inxkickelExtendedSelect2 = elementor.modules.controls.Select2.extend({
		onReady() {
			this.select2Instance.select2.options.options.matcher = function(params, data) {
				// If there are no search terms, return all of the data.
				if (typeof params.term === 'undefined' || params.term.trim() === '') {
				  return data
				}

				// Do not display the item if there is no 'text' property.
				if (typeof data.text === 'undefined') {
				  return null
				}

				params.term = params.term.toLowerCase()

				let mappingName = ''
				let mappingValues = ''

				let matches = data.title.match(/^[a-zA-Z]+: ([a-zA-Z0-9äöüÄÖÜß .:!+~#-_=\>]*)/)
				if (matches) {
					mappingName = matches[1].trim()

					if ( mappingName.length + 2 < data.title.length ) {
						// Make possible mapping values searchable as well, if they exist.
						mappingValues = data.title.substring(matches[0].length + 2).trim().toLowerCase()
					}

					if (mappingName.match(/\.|-\>/)) {
						// If the mapping name is delimited by dots or arrows, consider only the last part as searchable.
						mappingName = mappingName.split(/\.|-\>/).pop()
					}
				}

				if (
					data.text.indexOf(params.term) > -1
					|| mappingName.indexOf(params.term) > -1
					|| mappingValues.indexOf(params.term) > -1
				) {
					return data
				}

				return null
			}
		}
	});

	elementor.addControlView( 'inxkickel-extended-select2', inxkickelExtendedSelect2 );
} );
