Module('WS.Components.EventsManagerUser', function(EventsManagerUser) {

	EventsManagerUser.fn.initialize = function(container) {
		this.container = container;
		this.user      = container.data( 'attr-user' );
		this.modal     = null;
		this.ajax      = null;
		this.template  = null;
		this.init();
	};

	EventsManagerUser.fn.init = function() {
		this._loadingDependencies();
		this.addEventListener();
	};

	EventsManagerUser.fn.addEventListener = function() {
		this.container
			.on( 'click', this._onClickShowEvents.bind( this ) )
		;

		this.modal
			.on( 'open', this._onClickSetModalArea.bind( this ) )
		;

		this.ajax
			.on( 'before-request-events-user', this._onBeforeRequestEventsUser.bind( this ) )
			.on( 'done-request-events-user', this._onDoneRequestEventsUser.bind( this ) )
		;
	};

	EventsManagerUser.fn._onClickSetModalArea = function() {
		this.ajax.fire( 'request-events-user', [ this.user ] );
	};

	EventsManagerUser.fn._onClickShowEvents = function() {
		this.modal.init();
		this.modal.open();
	};

	EventsManagerUser.fn._onDoneRequestEventsUser = function(e, response) {
		console.log( response );
	};

	EventsManagerUser.fn._onBeforeRequestEventsUser = function() {

	};

	EventsManagerUser.fn._loadingDependencies = function() {
		this.modal = WS.Components.Modal();
		this.ajax  = WS.Components.RequestJSON();
	};
});