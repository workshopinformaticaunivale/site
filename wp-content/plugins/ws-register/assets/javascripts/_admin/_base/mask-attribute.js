Module("WS.MaskAttribute", function(MaskAttribute) {
	MaskAttribute.fn.initialize = function() {
		this.init();
	};

	MaskAttribute.fn.init = function() {
		if ( !jQuery.fn.mask ) {
			return;
		}

		this.maskInputs( jQuery( '*[data-mask]' ) );
	};

	MaskAttribute.fn.maskInputs = function(inputs){
		if ( !inputs || !inputs.length ) {
			return;
		}

		 // looking for inputs with data-mask attribute
	    inputs.each(function() {
	        var input   = jQuery( this )
	          , options = {};

	        if (input.attr('data-mask-reverse') === 'true') {
	            options.reverse = true;
	        }

	        if (input.attr('data-mask-maxlength') === 'false') {
	            options.maxlength = false;
	        }

	        input.mask( input.attr('data-mask'), options );
	    });
	};
});
