Module('WS.ControlTabsUsers', function(ControlTabsUsers) {
	ControlTabsUsers.fn.initialize = function(container) {
		this.form = container.find( '#your-profile' );
		this.init();
	};

	ControlTabsUsers.fn.init = function() {
		this._hideBoxIsNotEnterprisesUser();
		this._onAfterShow( this.getHashIndex() );
		this.setPluginTabs();
	};

	ControlTabsUsers.fn.setPluginTabs = function() {
		this.form.tabFormsAdmin({
			activeIndex       : this.getHashIndex(),
			afterShowCallback : this._onAfterShow.bind(this)
		});
	};

	ControlTabsUsers.fn._onAfterShow = function(index) {
		var action = this.form.attr( 'action' );
		this.form.attr('action', action.replace(/#[0-9]{1,}|$/, '#' + index));
	};

	ControlTabsUsers.fn._hideBoxIsNotEnterprisesUser = function() {
		
	};

	ControlTabsUsers.fn.getHashIndex = function() {
		return ( window.location.hash.replace('#', '') || 1 );
	};
});
