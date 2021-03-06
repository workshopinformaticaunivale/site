Module('WS.Components.Register', function(Register) {

	Register.fn.initialize = function(container) {
		this.container    = container;
		this.form         = this.container.byData( 'attr-form' );
		this.errorMessage = this.container.byData( 'attr-error' );
		this.enrollment   = this.container.byData( 'attr-enrollment' );
		this.period       = this.container.byData( 'attr-period' );
		this.course       = this.container.byData( 'attr-course' );

		this.init();
	};

	Register.fn.init = function() {
		this.setParsleyValidation();
		this.addEventListener();
	};

	Register.fn.setParsleyValidation = function() {
		this.form.parsley();
	};

	Register.fn.isParsleyValid = function() {
		return this.form.parsley().isValid();
	};

	Register.fn.addEventListener = function() {
		this.form
			.on( 'submit', this._onSubmitForm.bind( this ) )
		;

		this.container
			.on( 'ifChecked', '[data-action=student]', this._onChangeStudent.bind( this ) )
		;
	};

	Register.fn.getListEspecialFields = function() {
		return [
			this.enrollment,
			this.period,
			this.course
		];
	};

	Register.fn.disableEspecialFields = function() {
		this.getListEspecialFields().forEach(function(field) {
			field.removeAttr( 'required' )
			     .hide()
			     .parsley()
			     .destroy()
			;
		});
	};

	Register.fn.enableEspecialFields = function() {
		this.getListEspecialFields().forEach(function(field) {
			field.attr( 'required', '' )
			     .show()
			     .parsley()
			     .reset()
			;
		});
	};

	Register.fn._onChangeStudent = function(event) {	
		if ( parseInt( event.target.value ) ) {
			this.enableEspecialFields();
			return;
		}

		this.disableEspecialFields();
	};

	Register.fn._onSubmitForm = function(event) {
		event.preventDefault();		

		if ( ! this.isParsleyValid() ) {
			return;
		}

		this._beforeRequest();
		this.sendRequest();
	};

	Register.fn.sendRequest = function() {
		var ajax = jQuery.ajax({
			url  	 : this.getUrlAjax(),
			data 	 : this.form.serialize(),
			dataType : 'json',
			type     : 'POST'
		});

		ajax.done( this._doneRequest.bind( this ) );
		ajax.fail( this._failRequest.bind( this ) );		
	};

	Register.fn._doneRequest = function(response) {
		this.container.addClass( 'success' );
	};

	Register.fn._failRequest = function(throwError, status) {
		var error = ( throwError.responseJSON || {} );

		this.errorMessage.text( ( error.message || 'Ops! Ocorreu um erro.' ) );
		this.modeDefault();
		this.container.addClass( 'error' );
	};

	Register.fn.getUrlAjax = function() {
		return ( WPVars || {} ).urlAjax;
	};

	Register.fn._beforeRequest = function() {
		this.modeWaiting();
	};

	Register.fn.modeWaiting = function() {
		this.form.find( '[type=submit]' )
			.attr( 'disabled', 'disabled' )
			.val( 'aguarde' )
		;
	};

	Register.fn.modeDefault = function() {
		this.form.find( '[type=submit]' )
			.removeAttr( 'disabled' )
			.val( 'enviar' )
		;
	};
});	