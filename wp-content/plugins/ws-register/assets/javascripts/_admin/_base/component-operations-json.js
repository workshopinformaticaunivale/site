Module( 'WS.Components.OperationsJSON', function(OperationsJSON) {

	OperationsJSON.fn.initialize = function() {
		this._createEmitter();
	};

	OperationsJSON.fn._createEmitter = function() {
		this.emitter = jQuery({});
		this.on      = jQuery.proxy( this.emitter, 'on' );
		this.fire    = jQuery.proxy( this.emitter, 'trigger' );
	};

	OperationsJSON.fn.sendCoursesByUser = function(id) {
		this.ajax({
			type : 'POST',
			data : {
				action    : 'set-courses-by-user',
				course_id : parseInt( id, 10 )		
			}
		});
	};

	OperationsJSON.fn.requestEventsByUser = function(id) {
		this.ajax({
			type : 'GET',
			data : {
				action  : 'get-events-by-user',
				user_id : parseInt( id, 10 )
			}
		});
	};

	OperationsJSON.fn.ajax = function(args) {
		args = jQuery.extend({
			url       : this.getUrlAjax(),
			dataType  : 'json',
			data      : {}
		}, args );

		var ajax       = jQuery.ajax( args )
		  , identifier = ( ( args.data || {} ).action || 'general' )
		;

		this.fire( 'before-' + identifier );
		ajax.done( jQuery.proxy( this, '_done', identifier ) );
		ajax.fail( jQuery.proxy( this, '_fail', identifier ) );
	};

	OperationsJSON.fn.getUrlAjax = function() {
		return ( window.WPRegisterAdminVars || {} ).ajaxUrl;
	};

	OperationsJSON.fn._done = function(identifier, response) {
		this.fire( 'done-' + identifier, [ response ] );
	};

	OperationsJSON.fn._fail = function(identifier, throwError, status) {
		this.fire( 'fail-' + identifier, [ throwError.responseJSON ] );
	};

});