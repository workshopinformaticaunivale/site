Module( 'WS.ValidationForms', function(ValidationForms) {

	ValidationForms.fn.initialize = function(fields, form) {
		this.fields = ( fields || [] );
		this.form   = form;		

		this.init();
	};

	ValidationForms.fn.init = function() {
		this.getElementsInFields();
		this.addEventListener();
	};

	ValidationForms.fn.addEventListener = function() {
		this.form
			.on( 'submit', this._onSubmit.bind( this ) )
		;
	};

	ValidationForms.fn._onSubmit = function(event) {
		var fields = this.getValidateFields();
	
		if ( !fields.length ) {			
			return true;
		}

		this._removeSpinnerWP();
		this._resetButtonSubmit();
		this._openPointerInField( fields.shift() );
		return false;
	};

	ValidationForms.fn._openPointerInField = function(field) {
		var message = {
			content  : '<h3>Notificação</h3><p>Esse campo é obrigatório</p>',
			position : ( field.position || 'top' )
		};

		field.$pointer
		      .pointer( message )
		      .pointer( 'open' )
		;
	};

	ValidationForms.fn._closePointerInField = function(field) {
		field.$pointer
		     .pointer({})
		     .pointer( 'close' )
		;
	};

	ValidationForms.fn._removeSpinnerWP = function() {
		this.form
			.find( '#publishing-action .spinner' )
			.hide()
		;
	};

	ValidationForms.fn._resetButtonSubmit = function() {
		this.form
			.find( '[type=submit]' )
			.removeClass( 'button-primary-disabled' )
		;
	};

	ValidationForms.fn.getValidateFields = function() {
		return this.fields.filter(function(field) {
			return ( !this._fieldIsValid( field ) );
		}, this );
	};

	ValidationForms.fn._fieldIsValid = function(field) {
		//remove all pointers
		this._closePointerInField( field );

		if ( field.$el.is( ':checkbox, :radio' ) ) {
			return field.$el.is( ':checked' );
		}

		//wp editor case
		if ( field.$el.is( '.wp-editor-area' ) ) {
			return tinymce.activeEditor.getContent();
		}

		//valid textarea and input text|email|number
		return jQuery.trim( field.$el.val() ).length;
	};

	ValidationForms.fn.getElementsInFields = function() {
		this.fields = this.fields.map( this._transformFields.bind( this ) );
		//filter empty fields
		this.fields = this.fields.filter(function(field) {
			return field
		});
	};

	ValidationForms.fn._transformFields = function(field) {
		var element = this.form.find( field.selector );

		if ( !element.length )
			return false;

		field.$el      = element;
		field.$pointer = this._getPointerByField( field.pointer, field.$el );

		return field;
	};

	ValidationForms.fn._getPointerByField = function(selector, elementDefault) {
		if ( selector ) {
			return this.form.find( selector );
		}

		return elementDefault;		
	};
});