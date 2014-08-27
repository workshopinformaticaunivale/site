Module('WS.Components.UploadFile', function(UploadFile) {
	/*Image*/
	var FieldImage = function(container) {
		this.container = container;
		this.src       = this.container.data( 'attr-image-src' );
		this.position  = this.container.data( 'attr-image-position' ) ;
		this.$el       = null;
		this.create();
	};

	FieldImage.prototype.create = function() {
		if ( !this.src ) {
			return;
		}

		if ( this.isExist() ) {
			this.$el.attr( 'src', this.src );
			return;
		}

		this.$el = jQuery( '<img>', {
			class : 'image-create-component',
			src   : this.src
		});

		this._insert();
	};

	FieldImage.prototype._insert = function() {
		var args = {
			'inner'  : 'append',
			'before' : 'before',
			'after'  : 'after'
		};

		this.container[ args[ ( this.position || 'inner' ) ] ]( this.$el );
		this.container.addClass( 'created-image' );
	};

	FieldImage.prototype.isExist = function() {
		return ( this.$el );
	};

	FieldImage.prototype.reload = function(src) {
		this.src = src;
		this.create();
	};

	/*Hidden*/
	var FieldHidden = function(container) {
		this.container = container;
		this.value     = this.container.data( 'attr-hidden-value' );
		this.name      = this.container.data( 'attr-hidden-name' );
		this.$el       = null;
		this.create();
	};

	FieldHidden.prototype.create = function() {
		this.$el = jQuery( '<input>', {
			type  : 'hidden',
			class : 'hidden-create-component',
			value : this.value,
			name  : this.name
		});

		this.container.before( this.$el );
	};

	FieldHidden.prototype.val = function(value) {
		this.value = value;
		this.$el.val( value );
	};

	/*Component*/
	UploadFile.fn.initialize = function(container) {
		this.container = container;
		this.image     = null;
		this.hidden    = null;
		this.init();
	};

	UploadFile.fn.init = function() {
		this._createElements();
		this.addEventListener();
	};

	UploadFile.fn._defineButtonText = function() {
		var text = ( this.container.data( 'attr-button-text' ) || 'inserir' );

		( _wpMediaViewsL10n || {} ).insertIntoPost = text;
	};

	UploadFile.fn._createElements = function() {
		this.image  = new FieldImage( this.container );
		this.hidden = new FieldHidden( this.container );
	};

	UploadFile.fn.addEventListener = function() {
		this.container
			.on( 'click', this._onClickButtonUpload.bind( this ) )
		;
	};

	UploadFile.fn._onClickButtonUpload = function(event) {
		this._defineButtonText();		

		wp.media.editor.send.attachment = this._onAfterAttachmentAction();
    	wp.media.editor.open( this.container );
	};

	UploadFile.fn._onAfterAttachmentAction = function() {
		var _self = this;

		return function(props, attachment) {
			_self.image.reload( attachment.url );
			_self.hidden.val( attachment.id );
		};
	};
});