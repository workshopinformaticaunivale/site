Module('WS.Components.RequestJSON', function(RequestJSON) {

	RequestJSON.fn.initialize = function() {
		this._createEmitter();
		this.addEventListener();
	};

	RequestJSON.fn._createEmitter = function() {
		this.emitter = jQuery({});
		this.on      = jQuery.proxy( this.emitter, 'on' );
		this.fire    = jQuery.proxy( this.emitter, 'trigger' );
	};

	RequestJSON.fn.addEventListener = function() {
		this.on( 'request-events-user', this._onStartEventsUser.bind( this ) );
	};

	RequestJSON.fn.getUrlAjax = function() {
		return ( window.WPRegisterAdminVars || {} ).ajaxUrl;
	};

	RequestJSON.fn._onStartEventsUser = function(e, userID) {
		this.ajax( 'request-data', {
			'action'  : 'get_events_by_user',
			'user_id' : userID
		});
	};

	RequestJSON.fn.ajax = function(typeFire, data, url) {
		var ajax = jQuery.ajax({
			url       : this.getUrlAjax(),
			dataType  : 'json',
			data      : data
		});

		this.fire( 'before-' +  typeFire );
		ajax.done( jQuery.proxy( this, '_done', typeFire ) );
		ajax.fail( jQuery.proxy( this, '_fail', typeFire ) );
	};

	RequestJSON.fn._done = function(typeFire, response) {
		this.fire( 'done-' + typeFire, [ response ] );
	};

	RequestJSON.fn._fail = function(typeFire, throwError, status) {
		this.fire( 'fail-' + typeFire, [ throwError.responseJSON ] );
	};
});