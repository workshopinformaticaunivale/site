Module( 'WS.Components.ListCoursesUser', function(ListCoursesUser) {

	ListCoursesUser.fn.initialize = function(table, ajax) {
		this.table           = table;
		this.body            = this.table.byData( 'attr-body' );
		this.userId          = this.table.data( 'attr-user-id' );
		this.ajax            = ajax;
		this._lastButtonSend = jQuery({});
		this.template        = null;
		this.init();
	};

	ListCoursesUser.fn.init = function() {
		this._loadingDependencie();
		this._compileTemplate();
		this._registerHelper();
		this.addEventListener();
		
		//force result ajax in init
		this.getResults();
	};

	ListCoursesUser.fn.getResults = function() {
		this.ajax.requestCoursesByUsers( this.userId );
	};

	ListCoursesUser.fn.addEventListener = function() {
		this.table
			.on( 'click', '[data-action=delete]', this._onClickDelete.bind( this ) )
		;	

		this.ajax
			.on( 'before-get-courses-by-user', this._onBeforeGetCoursesAjax.bind( this ) )
			.on( 'done-get-courses-by-user', this._onDoneGetCoursesAjax.bind( this ) )
			.on( 'fail-get-courses-by-user', this._onFailGetCoursesAjax.bind( this ) )
			.on( 'before-unset-courses-by-user', this._onBeforeUnsetCoursesAjax.bind( this ) )
			.on( 'done-unset-courses-by-user', this._onDoneUnsetCoursesAjax.bind( this ) )
			.on( 'fail-unset-courses-by-user', this._onFailUnsetCoursesAjax.bind( this ) )
		;
	};

	ListCoursesUser.fn._onClickDelete = function(event) {
		var target = jQuery( event.currentTarget );
		var course = target.data( 'attr-course' );

		this._lastButtonSend = target;
		this.ajax.removeCoursesByUser( course );
	};

	ListCoursesUser.fn.disableButtonSend = function() {
		this._lastButtonSend
			.attr( 'disabled', 'disabled' )
			.addClass( 'load' )
		;
	};

	ListCoursesUser.fn.enableButtonSend = function() {
		this._lastButtonSend
			.removeAttr( 'disabled' )
			.removeClass( 'load' )
		;
	};

	ListCoursesUser.fn._onDoneUnsetCoursesAjax = function(e, response) {
		this.table
			.find( 'tr:has([data-attr-course=' + response.course + '])' )
			.fadeOutRemove( 200 )
		;

		setTimeout(function() {
			( this.isEmpty() && this.setMessageTable( 'Todos os minicursos foram excluídos.' ) );
		}.bind( this ), 210 );
	};

	ListCoursesUser.fn.isEmpty = function() {
		return ( ! this.body.find( 'tr' ).length );
	};

	ListCoursesUser.fn._onBeforeUnsetCoursesAjax = function() {
		this.disableButtonSend();
	};

	ListCoursesUser.fn._onFailUnsetCoursesAjax = function(e, response) {		
		this.table
			.next( '.ws-message.ws-error' )
				.remove()
			.end()
			.after( '<div class="ws-message ws-error"><p>' + response.message + '</p></div>' )
		;
		
		this.enableButtonSend();
	};

	ListCoursesUser.fn._onDoneGetCoursesAjax = function(e, response) {
		this.body.html( this.template( response ) );
	};

	ListCoursesUser.fn._onBeforeGetCoursesAjax = function() {
		this.setMessageTable( 'Aguarde...' );
	};

	ListCoursesUser.fn._onFailGetCoursesAjax = function(e, response) {
		this.setMessageTable( response.message );
	};

	ListCoursesUser.fn.setMessageTable = function(message) {
		var html = '<tr class="left-column-list">'
			     + ' 	<td colspan="5">' + message + '</td>'
				 + '</tr>'
		;

		this.body.html( html );
	};

	ListCoursesUser.fn._loadingDependencie = function() {
		this.ajax = ( this.ajax || WS.Components.OperationsJSON() );
	};

	ListCoursesUser.fn._compileTemplate = function() {
		this.template = jQuery( '#template-list-courses-user' ).compileHandlebars();
	};

	ListCoursesUser.fn._registerHelper = function() {
		Handlebars.registerHelper( 'column_class', function(index) {
			return ( index % 2 ) ? 'normal' : 'alternate'; 
		});
	};
});	