Module( 'WS.Components.RegisterCourses', function(RegisterCourses) {

	RegisterCourses.fn.initialize = function(container) {
		this.container = container;
		this.select    = this.container.byData( 'attr-select' );
		this.excerpt   = this.container.byData( 'attr-excerpt' );
		this.ajax      = null;
		this.template  = null;
		this.init();
	};

	RegisterCourses.fn.init = function() {
		this._loadingDependencie();
		this._compileTemplate();
		this._setCustomSelect();
		this.addEventListener();
	};

	RegisterCourses.fn._setCustomSelect = function() {
		this.select.chosen({
			width : '40%'
		});
	};

	RegisterCourses.fn._loadingDependencie = function() {
		this.ajax = WS.Components.OperationsJSON();
	};

	RegisterCourses.fn._compileTemplate = function() {
		this.template = jQuery( '#template-preview-course' ).compileHandlebars();
	};

	RegisterCourses.fn.addEventListener = function() {
		this.select
			.on( 'change', this._onChangeCourse.bind( this ) )
		;

		this.container
			.on( 'click', '[data-action=register]', this._onClickRegister.bind( this ) )
		;

		this.ajax
			.on( 'before-set-courses-by-user', this._onBeforeAjax.bind( this ) )
			.on( 'done-set-courses-by-user', this._onDoneAjax.bind( this ) )
			.on( 'fail-set-courses-by-user', this._onFailAjax.bind( this ) )
		;
	};

	RegisterCourses.fn._onBeforeAjax = function() {
		this.disableButtonSend();
	};

	RegisterCourses.fn.disableButtonSend = function() {
		var button = this.excerpt.byData( 'attr-course' );

		button			
			.attr( 'disabled', 'disabled' )
			.addClass( 'load' )
		;
	};

	RegisterCourses.fn.enableButtonSend = function() {
		var button = this.excerpt.byData( 'attr-course' );

		button			
			.removeAttr( 'disabled' )
			.removeClass( 'load' )
		;
	};

	RegisterCourses.fn._onDoneAjax = function(e, response) {
		this.excerpt
			.html( '<div class="ws-message ws-updated"><p>' + response.message + '</p></div>' )
		;

		this.disableOptionSelectByCourse( response.course );	
	};

	RegisterCourses.fn.disableOptionSelectByCourse = function(course) {
		this.select
			.find( 'option[value=' + course + ']' )
				.attr( 'disabled', 'disabled' )
		;

		this.select.trigger( 'chosen:updated' );
	};

	RegisterCourses.fn._onFailAjax = function(e, response) {
		this.excerpt
			.find( '.ws-message.ws-error' )
				.remove()
			.end()	
			.append( '<div class="ws-message ws-error"><p>' + response.message + '</p></div>' )
		;

		this.enableButtonSend();
	};

	RegisterCourses.fn._onClickRegister = function(event) {
		var target = jQuery( event.currentTarget );
		var course = target.data( 'attr-course' );

		this.ajax.sendCoursesByUser( course );
	};

	RegisterCourses.fn._onChangeCourse = function() {
		var option = this.getCurrentOption();
		var item   = option.data( 'attr-item' );

		this.renderExcerptByItem( item );
	};

	RegisterCourses.fn.renderExcerptByItem = function(item) {
		this.excerpt.html( this.template( item ) );
	};

	RegisterCourses.fn.getCurrentOption = function() {
		return this.select.find( 'option:selected' );
	};
});	