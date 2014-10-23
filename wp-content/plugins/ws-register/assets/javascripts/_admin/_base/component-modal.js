Module( 'WS.Components.Modal', function(Modal) {
	
	Modal.fn.initialize = function(target, isControlClose) {
		this.target         = target;
		this.isControlClose = isControlClose;
		this.emitter        = null;
		this.fire           = null;
		this.on             = null;
		this.container      = null;
		this.area           = null;
		this._createEmitter();
	};

	Modal.fn.init = function() {
		this._createElements();
		this.addEventListener();
	};

	Modal.fn._createEmitter = function() {
		this.emitter = jQuery({});
		this.on      = jQuery.proxy( this.emitter, 'on' );
		this.fire    = jQuery.proxy( this.emitter, 'trigger' );
	};

	Modal.fn.addEventListener = function() {
		this.container
			.on( 'click', '[data-action=close]', this._onClickClose.bind(this) )
		;

		this._onBeforeUnLoad();
	};

	Modal.fn._onBeforeUnLoad = function() {
		jQuery( window ).on( 'beforeunload.modal', function() {
        	return 'Todas as configurações serão perdidas.';
    	});
	};

	Modal.fn._offBeforeUnLoad = function() {
		jQuery( window ).off( 'beforeunload.modal' );
	};

	Modal.fn.open = function() {
		this._insertInPage();
		this.area = this.container.byData( 'attr-container' );

		this.fire( 'open', [ this.area ] );
	};

	Modal.fn.close = function() {
		this.fire( 'close' );
		this.container.remove();
		this._offBeforeUnLoad();
	};

	Modal.fn._insertInPage = function() {
		if ( this.target ) {
			this.target.append( this.container );
			return;
		}

		jQuery( 'body' ).append( this.container );
	};

	Modal.fn._onClickClose = function() {
		if ( !this.isControlClose ) {
			this.close();
			return;
		}

		this.fire( 'before-unclose' );
	};

	Modal.fn._createElements = function() {
		var html = '<div class="theme-wrap custom-modal-wrap">'
		         + '	<div class="theme-header">'
				 + '		<button type="button" data-action="close" class="close dashicons dashicons-no" alt="Fechar"></button>'
				 + '	</div>'
				 + '	<div class="theme-about" data-attr-container></div>'
				 + '</div>'
				 + '<div class="media-modal-backdrop"></div>'
		;

		this.container = jQuery( '<div class="theme-overlay"></div>' );
		this.container.html( html );
	};
});
