Module( 'WS.ControlDays', function(ControlDays) {

	var _datepicker, _maskAttribute;

	var Clone = function(cloned) {
		this.base = cloned;
	};

	Clone.prototype.duplicate = function() {
		return this._prepare( this.base.clone() );
	};

	Clone.prototype._prepare = function(cloned) {
		//remover data-no-plugin
		cloned.find( '[data-no-plugin]' )
			  .removeAttr( 'data-no-plugin' )
		;

		//set datepicker
		_datepicker.init( cloned );

		//set mask
		_maskAttribute.maskInputs( cloned.find( ':text' ) );

		//config cloned
		cloned.removeClass( 'blank' )
			  .addClass( 'day' )
		;

		return cloned;
	};
	//cloned

	ControlDays.fn.initialize = function(container, datepicker, maskAttribute) {
		//set privates
		_datepicker = datepicker;
		_maskAttribute = maskAttribute;

		this.container = container;
		//...
		this.init();
	};

	ControlDays.fn.init = function() {
		this.blankCloned = this.container.find( '.blank' );
		this.baseCloned = this.blankCloned.clone();

		//refactore, clear names
		this.blankCloned.find( 'input[name*="[]"]' ).attr( 'name', '' );

		this.handleEvents();
	};

	ControlDays.fn.handleEvents = function() {
		this.container.on( 'click', '.add', this.eventAdd.bind( this ) );
		this.container.on( 'click', '.remove', this.eventRemove.bind( this ) );
	};

	ControlDays.fn.eventAdd = function(event) {
		this.setNewClone( this.container );
	};

	ControlDays.fn.eventRemove = function(event) {
		var target = jQuery( event.target );
		this.removeClone( target );
	};

	ControlDays.fn.setNewClone = function(insert) {
		var clone = new Clone( this.baseCloned );
		insert.append( clone.duplicate() );
	};

	ControlDays.fn.removeClone = function(target) {
		var closest;

		if ( !this.verifyRemove( target ) ) {
			return;
		}

		closest = target.closest( '.day' );
		closest.addClass( 'hide' );

		setTimeout(function(){
			closest.remove();
		}, 200);
	};

	ControlDays.fn.verifyRemove = function(target) {
		var _parent = this.container;

		if ( !_parent ) {
			_parent = target.parent();
		}

		if ( _parent.find( '.day' ).length == 1 ) {
			this.clearInputs( target );
			return false;
		}

		return true;
	};

	ControlDays.fn.clearInputs = function(target) {
		//clear all brother elements
		target.siblings( ':text' ).val( '' );
	};
});
