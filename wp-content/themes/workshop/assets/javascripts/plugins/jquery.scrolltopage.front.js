;(function($, window) {
	
	$.extend( $.easing, {
		easeOutQuad : function (x, t, b, c, d) {
			return -c *(t/=d)*(t-2) + b;
		}
	} );

	$.fn.scrollToPage = function(options) {

		options = $.extend({
			easing        : 'easeOutQuad',
			historyUpdate : true,
		}, options || {} );

		var handleEvent = function(config) {
			config.target.on( config.event, $.proxy( config.action, config.target ) );
		};

		var historyUrlHash = function(hash) {			
			if ( ! history.pushState ) {
				return;
			}

			history.pushState( {}, hash.replace(/#/, ''), hash );
		};

		var scrollTopAction = function(e) {
			e.preventDefault();

			var top
			  , href = this.attr( 'href' )
			  , page = $( href )
			;

			if ( !page.length ) {
				return true;
			}

			top = page.offset().top - ( href == '#palestrantes' ? 90 : 60 );

			if ( options.historyUpdate ) {
				historyUrlHash( href );
			}

			$( 'body, html' ).stop().animate( { scrollTop : top + 'px' }, {
				duration : 700,
				easing   : options.easing,
				complete : function() {
					$( 'body' ).trigger( 'scrolltopage-animated', [ this, href ] );
				}.bind( this )
			} );
		};

		return this.each(function(){
			handleEvent({
				target : $( this ),
				event  : 'click',
				action : scrollTopAction  
			});
		});
	};
})( jQuery, window );