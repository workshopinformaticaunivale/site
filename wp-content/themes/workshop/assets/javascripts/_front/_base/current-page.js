Module('WS.CurrentPage', function(CurrentPage) {

	var Page = function(selector) {
		this.$el = jQuery( selector );
		this.id  = this.$el.attr( 'id' );
	};

	Page.prototype.offset = function() {
		return ( this.$el.offset().top - window.pageYOffset );
	};

	CurrentPage.fn.initialize = function(selectors, menu) {
		this.selectors = selectors;
		this.menu      = menu;
		this.pages     = [];
		this.init();
	};

	CurrentPage.fn.init = function() {
		this.transformPages();
		this.addEventListener();
	};

	CurrentPage.fn.transformPages = function() {
		this.pages = this.selectors.map(function(selector) {
			return new Page( selector );
		});
	};

	CurrentPage.fn.addEventListener = function() {
		jQuery( window ).on( 'scroll', WS.Utils.debounce( this._onScroll.bind( this ), 100 ) );
	};

	CurrentPage.fn.disabledAllLinksInMenu = function() {
		this.menu
			.find( 'li' )
			.removeClass( 'active-menu' )
		;
	};

	CurrentPage.fn.activeLinkInMenu = function(current) {
		this.menu
			.find( 'li:has([href=#'+ current.id +'])' )
			.addClass( 'active-menu' )
		;
	};

	CurrentPage.fn._onScroll = function() {
		var current = this.pages[0];
		var targets = this.pages.filter(function(page) {
			return ( page.offset() <= 60 );
		});

		this.disabledAllLinksInMenu();
		
		current = ( targets.length ) ? targets.pop() : current;
		
		this.activeLinkInMenu( current );
	};
});