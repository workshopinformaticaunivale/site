Module('WS.Components.GoogleMaps', function(GoogleMaps) {
	
	GoogleMaps.fn.initialize = function(container) {
		this.container = container;
		this.lat       = this.container.data( 'attr-lat' );
		this.lng       = this.container.data( 'attr-lng' );
		this.title     = this.container.data( 'attr-title' );
		this.icon      = this.container.data( 'attr-icon' );
		this.init();
	};

	GoogleMaps.fn.init = function() {
		this.getScript();
	};

	GoogleMaps.fn.getScript = function() {
		var url = 'https://maps.googleapis.com/maps/api/js?v3.exp&callback=WS.Components.GoogleMaps.onFinallyScript&key=AIzaSyDUX1SdMjfsjl30ycd9-yqmrMaa-FqPWgw';

		//create public function for callback
		GoogleMaps.onFinallyScript = (function() {
			this.render();
		}).bind(this);

		//request file
		jQuery.getScript( url );
	};

	GoogleMaps.fn.render = function() {
		var marker, map, options;
		
		options = {
	    	zoom					: 16,
	    	scrollwheel             : false,
	    	center					: new google.maps.LatLng( this.lat, this.lng ),
		    mapTypeId 				: google.maps.MapTypeId.ROADMAP,
		    mapTypeControlOptions	: {
		    	mapTypeIds: [ 
		    		google.maps.MapTypeId.ROADMAP,
		    		google.maps.MapTypeId.SATELLITE
		    	]	
		    }
		};
	
		map    = new google.maps.Map( this.container.get(0), options );
		marker = new google.maps.Marker({
		    position : options.center,
		    map      : map,
		    icon     : this.icon,
		    title    : this.title
		});
	};
});  