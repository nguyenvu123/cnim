function axiom_googlemap_init(dom_obj, coords) {
	"use strict";
	if (typeof AXIOM_GLOBALS['googlemap_init_obj'] == 'undefined') axiom_googlemap_init_styles();

	try {
		var id = dom_obj.id;
		AXIOM_GLOBALS['googlemap_init_obj'][id] = {
			dom: dom_obj,
			point: coords.point,
			description: coords.description,
			title: coords.title,
			opt: {
				zoom: coords.zoom,
				center: null,
				scrollwheel: false,
				scaleControl: false,
				disableDefaultUI: false,
				panControl: true,
				zoomControl: true, //zoom
				mapTypeControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				styles: AXIOM_GLOBALS['googlemap_styles'][coords.style ? coords.style : 'default'],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		};

		if (coords.latlng=='') {
			var custom_map = new google.maps.Geocoder();
			custom_map.geocode({address: coords.address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0].geometry.location.lat && results[0].geometry.location.lng) {
                        coords.latlng = '' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
                    } else {
                        coords.latlng = results[0].geometry.location.toString();
                        coords.latlng = coords.latlng.replace(/\(\)/g, '');
                    }
                    axiom_googlemap_create(id, coords.latlng);
				} else
					alert(AXIOM_GLOBALS['strings']['geocode_error'] + ' ' + status);
			});
		} else
			axiom_googlemap_create(id, coords.latlng);

	} catch (e) {
		alert(AXIOM_GLOBALS['strings']['googlemap_not_avail']);
	};
}

function axiom_googlemap_create(id) {
	"use strict";
	var latlng = arguments[1] ? arguments[1] : '';
	if (latlng) {
		var latlngStr = latlng.split(',');
		AXIOM_GLOBALS['googlemap_init_obj'][id].opt.center = new google.maps.LatLng(latlngStr[0], latlngStr[1]);
	}
	AXIOM_GLOBALS['googlemap_init_obj'][id].map = new google.maps.Map(AXIOM_GLOBALS['googlemap_init_obj'][id].dom, AXIOM_GLOBALS['googlemap_init_obj'][id].opt);
	//AXIOM_GLOBALS['googlemap_init_obj'][id].map.setCenter(AXIOM_GLOBALS['googlemap_init_obj'][id].opt.center);
	var markerInit = {
		map: AXIOM_GLOBALS['googlemap_init_obj'][id].map,
		icon: '/wp-content/themes/proparty/skins/default/images/map_marker.png',
		position: AXIOM_GLOBALS['googlemap_init_obj'][id].opt.center	//AXIOM_GLOBALS['googlemap_init_obj'][id].map.getCenter()
	};
	if (AXIOM_GLOBALS['googlemap_init_obj'][id].point) markerInit.icon = AXIOM_GLOBALS['googlemap_init_obj'][id].point;
	if (AXIOM_GLOBALS['googlemap_init_obj'][id].title) markerInit.title = AXIOM_GLOBALS['googlemap_init_obj'][id].title;
	var marker = new google.maps.Marker(markerInit);
	var infowindow = new google.maps.InfoWindow({
		content: AXIOM_GLOBALS['googlemap_init_obj'][id].description
	});
	google.maps.event.addListener(marker, "click", function() {
		infowindow.open(AXIOM_GLOBALS['googlemap_init_obj'][id].map, marker);
	});
	jQuery(window).resize(function() {
		if (AXIOM_GLOBALS['googlemap_init_obj'][id].map)
			AXIOM_GLOBALS['googlemap_init_obj'][id].map.setCenter(AXIOM_GLOBALS['googlemap_init_obj'][id].opt.center);
	});
}

function axiom_googlemap_refresh() {
	"use strict";
	for (id in AXIOM_GLOBALS['googlemap_init_obj']) {
		axiom_googlemap_create(id);
	}
}

function axiom_googlemap_init_styles() {
	// Init Google map
	AXIOM_GLOBALS['googlemap_init_obj'] = {};
	AXIOM_GLOBALS['googlemap_styles'] = {
		'default': [],
		'invert': [ { "stylers": [ { "invert_lightness": true }, { "visibility": "on" } ] } ],
		'dark': [{"featureType":"landscape","stylers":[{ "invert_lightness": true },{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}],
		'simple': [
				{
				  stylers: [
					{ hue: "#00ffe6" },
					{ saturation: -20 }
				  ]
				},{
				  featureType: "road",
				  elementType: "geometry",
				  stylers: [
					{ lightness: 100 },
					{ visibility: "simplified" }
				  ]
				},{
				  featureType: "road",
				  elementType: "labels",
				  stylers: [
					{ visibility: "off" }
				  ]
				}
			  ],
	'greyscale': [
					{
						"stylers": [
							{ "saturation": -100 }
						]
					}
				],
	'greyscale2': [
				{
				 "featureType": "landscape",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": 20.4705882352941 },
				  { "gamma": 1 }
				 ]
				},
				{
				 "featureType": "road.highway",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": 25.59999999999998 },
				  { "gamma": 1 }
				 ]
				},
				{
				 "featureType": "road.arterial",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": -22 },
				  { "gamma": 1 }
				 ]
				},
				{
				 "featureType": "road.local",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": 21.411764705882348 },
				  { "gamma": 1 }
				 ]
				},
				{
				 "featureType": "water",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": 21.411764705882348 },
				  { "gamma": 1 }
				 ]
				},
				{
				 "featureType": "poi",
				 "stylers": [
				  { "hue": "#FF0300" },
				  { "saturation": -100 },
				  { "lightness": 4.941176470588232 },
				  { "gamma": 1 }
				 ]
				}
			   ],
	'style1': [{
					"featureType": "landscape",
					"stylers": [
						{ "hue": "#FF0300"	},
						{ "saturation": -100 },
						{ "lightness": 20.4705882352941 },
						{ "gamma": 1 }
					]
				},
				{
					"featureType": "road.highway",
					"stylers": [
						{ "hue": "#FF0300" },
						{ "saturation": -100 },
						{ "lightness": 25.59999999999998 },
						{ "gamma": 1 }
					]
				},
				{
					"featureType": "road.arterial",
					"stylers": [
						{ "hue": "#FF0300" },
						{ "saturation": -100 },
						{ "lightness": -22 },
						{ "gamma": 1 }
					]
				},
				{
					"featureType": "road.local",
					"stylers": [
						{ "hue": "#FF0300" },
						{ "saturation": -100 },
						{ "lightness": 21.411764705882348 },
						{ "gamma": 1 }
					]
				},
				{
					"featureType": "water",
					"stylers": [
						{ "hue": "#FF0300" },
						{ "saturation": -100 },
						{ "lightness": 21.411764705882348 },
						{ "gamma": 1 }
					]
				},
				{
					"featureType": "poi",
					"stylers": [
						{ "hue": "#FF0300" },
						{ "saturation": -100 },
						{ "lightness": 4.941176470588232 },
						{ "gamma": 1 }
					]
				}
			],
	'style2': [
		{
			"featureType": "water",
			"elementType": "geometry",
			"stylers": [
				{
					"visibility": "on"
				},
				{
					"color": "#aee2e0"
				}
			]
		},
		{
			"featureType": "landscape",
			"elementType": "geometry.fill",
			"stylers": [
				{
					"color": "#abce83"
				}
			]
		},
		{
			"featureType": "poi",
			"elementType": "geometry.fill",
			"stylers": [
				{
					"color": "#769E72"
				}
			]
		},
		{
			"featureType": "poi",
			"elementType": "labels.text.fill",
			"stylers": [
				{
					"color": "#7B8758"
				}
			]
		},
		{
			"featureType": "poi",
			"elementType": "labels.text.stroke",
			"stylers": [
				{
					"color": "#EBF4A4"
				}
			]
		},
		{
			"featureType": "poi.park",
			"elementType": "geometry",
			"stylers": [
				{
					"visibility": "simplified"
				},
				{
					"color": "#8dab68"
				}
			]
		},
		{
			"featureType": "road",
			"elementType": "geometry.fill",
			"stylers": [
				{
					"visibility": "simplified"
				}
			]
		},
		{
			"featureType": "road",
			"elementType": "labels.text.fill",
			"stylers": [
				{
					"color": "#5B5B3F"
				}
			]
		},
		{
			"featureType": "road",
			"elementType": "labels.text.stroke",
			"stylers": [
				{
					"color": "#ABCE83"
				}
			]
		},
		{
			"featureType": "road",
			"elementType": "labels.icon",
			"stylers": [
				{
					"visibility": "off"
				}
			]
		},
		{
			"featureType": "road.local",
			"elementType": "geometry",
			"stylers": [
				{
					"color": "#A4C67D"
				}
			]
		},
		{
			"featureType": "road.arterial",
			"elementType": "geometry",
			"stylers": [
				{
					"color": "#9BBF72"
				}
			]
		},
		{
			"featureType": "road.highway",
			"elementType": "geometry",
			"stylers": [
				{
					"color": "#EBF4A4"
				}
			]
		},
		{
			"featureType": "transit",
			"stylers": [
				{
					"visibility": "off"
				}
			]
		},
		{
			"featureType": "administrative",
			"elementType": "geometry.stroke",
			"stylers": [
				{
					"visibility": "on"
				},
				{
					"color": "#87ae79"
				}
			]
		},
		{
			"featureType": "administrative",
			"elementType": "geometry.fill",
			"stylers": [
				{
					"color": "#7f2200"
				},
				{
					"visibility": "off"
				}
			]
		},
		{
			"featureType": "administrative",
			"elementType": "labels.text.stroke",
			"stylers": [
				{
					"color": "#ffffff"
				},
				{
					"visibility": "on"
				},
				{
					"weight": 4.1
				}
			]
		},
		{
			"featureType": "administrative",
			"elementType": "labels.text.fill",
			"stylers": [
				{
					"color": "#495421"
				}
			]
		},
		{
			"featureType": "administrative.neighborhood",
			"elementType": "labels",
			"stylers": [
				{
					"visibility": "off"
				}
			]
		}
	],
	'style3':  [
 {
  "featureType": "landscape",
  "stylers": [
   {
    "hue": "#FFA800"
   },
   {
    "saturation": 17.799999999999997
   },
   {
    "lightness": 152.20000000000002
   },
   {
    "gamma": 1
   }
  ]
 },
 {
  "featureType": "road.highway",
  "stylers": [
   {
    "hue": "#007FFF"
   },
   {
    "saturation": -77.41935483870967
   },
   {
    "lightness": 47.19999999999999
   },
   {
    "gamma": 1
   }
  ]
 },
 {
  "featureType": "road.arterial",
  "stylers": [
   {
    "hue": "#FBFF00"
   },
   {
    "saturation": -78
   },
   {
    "lightness": 39.19999999999999
   },
   {
    "gamma": 1
   }
  ]
 },
 {
  "featureType": "road.local",
  "stylers": [
   {
    "hue": "#00FFFD"
   },
   {
    "saturation": 0
   },
   {
    "lightness": 0
   },
   {
    "gamma": 1
   }
  ]
 },
 {
  "featureType": "water",
  "stylers": [
   {
    "hue": "#007FFF"
   },
   {
    "saturation": -77.41935483870967
   },
   {
    "lightness": -14.599999999999994
   },
   {
    "gamma": 1
   }
  ]
 },
 {
  "featureType": "poi",
  "stylers": [
   {
    "hue": "#007FFF"
   },
   {
    "saturation": -77.41935483870967
   },
   {
    "lightness": 42.79999999999998
   },
   {
    "gamma": 1
   }
  ]
 }
],
	'style4': [{"featureType":"water","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]},{"featureType":"landscape","stylers":[{"color":"#f2e5d4"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"}]},{"featureType":"administrative","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"road"},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{},{"featureType":"road","stylers":[{"lightness":20}]}]
}
}