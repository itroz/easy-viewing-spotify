wp.blocks.registerBlockType('easyspotify-for-wordpress/play-button', {
	title: 'Easy Spotify for WordPress',
	description: 'Copy the Spotify URI from a song, playlist or album from Spotify and paste it in the Spotify URI field to embed it.',
	icon: 'controls-play',
	category: 'media',
	attributes: {
		spotifyUri: {type: 'string'},
		size: {type: 'string'},
		sizetype: {type: 'string'},
		link: {type: 'string'}
	},
	edit: function(props) {
		function updateSpotifyUri(event) {
			props.setAttributes({spotifyUri: event.target.value})
		}

		function updateSize(event) {
			props.setAttributes({size: event.target.value})
		}

		function updateSizeType(event) {
			props.setAttributes({sizetype: event.target.value})
		}

		function updateLink(event) {
			props.setAttributes({link: event.target.value})
		}

		return wp.element.createElement(
			"div", { 
				class: "components-placeholder is-large spotify-play-button-for-wordpress" 
			},
			wp.element.createElement(
				"div", { 
					class: "components-placeholder__label" 
				},
				'Sp*tify Play Button for WordPress'
			),
			wp.element.createElement(
				"input", { 
					type: "text", 
					value: props.attributes.spotifyUri, 
					onChange: updateSpotifyUri, 
					placeholder: "Spotify URL or URI", 
					style: {width: "100%"}
				}
			),
			wp.element.createElement(
				"div", {
					class: "blocks-table__placeholder-form" 
				},
				wp.element.createElement(
					"div", {
						class: "components-base-control blocks-table__placeholder-input" 
					},
					wp.element.createElement(
						"label", {
							class: "components-base-control__label",
						},
						'Size'
					),
					wp.element.createElement(
						"div", {
							class: "components-base-control__field" 
						},
						wp.element.createElement(
							"input", { 
								type: "number", 
								value: props.attributes.size, 
								onChange: updateSize, 
								placeholder: "Default"
							}
						),
					),
				),
				wp.element.createElement(
					"div", {
						class: "components-base-control blocks-table__placeholder-input" 
					},
					wp.element.createElement(
						"div", {
							class: "components-base-control__field" 
						},
						wp.element.createElement(
							"label", {
								class: "components-base-control__label",
							},
							'Size type'
						),
						wp.element.createElement(
							"select", { 
								value: props.attributes.sizetype, 
								onChange: updateSizeType
							},
							wp.element.createElement(
								"option", {
									value: ''
								},
								'Default'
							),
							wp.element.createElement(
								"option", {
									value: 'big'
								},
								'Big'
							),
							wp.element.createElement(
								"option", {
									value: 'compact'
								},
								'Compact'
							)
						),
					),
				),
				wp.element.createElement(
					"div", {
						class: "components-base-control blocks-table__placeholder-input" 
					},
					wp.element.createElement(
						"div", {
							class: "components-base-control__field" 
						},
						wp.element.createElement(
							"label", {
								class: "components-base-control__label",
							},
							'Link'
						),
						wp.element.createElement(
							"select", { 
								value: props.attributes.link, 
								onChange: updateLink
							},
							wp.element.createElement(
								"option", {
									value: ''
								},
								'Default'
							),
							wp.element.createElement(
								"option", {
									value: 'yes'
								},
								'Yes'
							),
							wp.element.createElement(
								"option", {
									value: 'no'
								},
								'No'
							)
						),
					),
				),
			),
			wp.element.createElement(
				"a", {
					href: "options-general.php?page=easyspotify_settings",
					class: 'default-settings'
				},
				'Change default settings here'
			),
			
		);
	},
	save: function(props) {
		return null;
	}
})
