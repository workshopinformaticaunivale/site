Module('WS.Components.Speakers', function(Speakers) {

	Speakers.fn.initialize = function(container) {
		this.container     = container;
		this.modal         = this.container.byData( 'attr-modal' );
		this.wrapper       = jQuery( '.wrapper' );
		this._lastItemOpen = null;
		this.init();
	};

	Speakers.fn.init = function() {
		this.addEventListener();
	};

	Speakers.fn.addEventListener = function() {
		this.container
			.on( 'click', '[data-action=open]', this._onClickOpenInfoBox.bind( this ) )
		;

		this.modal
			.on( 'click', this._onClickModal.bind( this ) )
		;

		this._bindOnCanvas();
	};

	Speakers.fn._onClickModal = function(event) {
		event.stopPropagation();
	};

	Speakers.fn._bindOnCanvas = function() {
		var _self = this;

		jQuery( 'body' ).on( 'click.close-speakers', function() {
			_self.disableModeOverlay();
		});
	};

	Speakers.fn._onClickOpenInfoBox = function(event) {
		event.stopPropagation();
		var target = jQuery( event.currentTarget );
		var text   = target.data( 'attr-text' );
		
		this.activeModeOverlay();
		this._setTextInModal( text );
		
		target.addClass( 'active' );
		this._lastItemOpen = target;		
	};

	Speakers.fn._setTextInModal = function(text) {
		this.modal.html( '<p>' + text + '</p>' );
	};

	Speakers.fn.activeModeOverlay = function() {
		this.wrapper.addClass( 'active-overlay' );
		this.container.addClass( 'active-info' );
	};

	Speakers.fn.disableModeOverlay = function() {
		this.wrapper.removeClass( 'active-overlay' );
		this.container.removeClass( 'active-info' );
		
		if ( this._lastItemOpen ) {
			this._lastItemOpen.removeClass( 'active' );
		}
	};
});	