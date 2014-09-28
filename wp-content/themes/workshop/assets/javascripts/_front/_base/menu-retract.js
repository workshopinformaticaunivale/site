Module('WS.MenuRetract', function(MenuRetract) {
	
	MenuRetract.fn.initialize = function(container) {
		this.container   = container.find( '.wrapper' );
		this.navigation  = container.find( '.navigation-fixed' );
		this.positionTop = this.navigation.offset().top;
		this.init();
	};

	MenuRetract.fn.init = function() {
		this._onEventFixed();
		this.addEventListener();
	};

	MenuRetract.fn.addEventListener = function() {
		jQuery( window ).on( 'scroll', this._onEventFixed.bind( this ) );
	};

	MenuRetract.fn._onEventFixed = function() {
		if ( window.pageYOffset >= this.positionTop ) {
			this.container.addClass( 'menu-fixed' );
			return;
		}
		
		this.container.removeClass( 'menu-fixed' );
	};
});