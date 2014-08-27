;(function($, window) {
	var Tab = function(container, attrs) {
		this.container            = container;
		this.sectionHeader        = null;
		this.sectionContent       = null
		this.wrapper              = null;
		this._currentActiveHeader = null;
		this._createContentIndex  = null;
		this.afterShowCallback    = null;
		this.tagHeader            = 'h3';
		this.tagContent           = 'table';
		this.activeIndex          = 1;

		this.assign(attrs);
	};

	Tab.prototype.init = function() {
		this.createSections();
		this.addEventListener();
	};

	Tab.prototype.addEventListener = function() {
		this.sectionHeader
			.on('click', '[data-action="change"]', this._onClickChangeTab.bind(this))
		;
	};

	Tab.prototype._onClickChangeTab = function(event) {
		var target = $(event.target);
		var index  = target.attr('href').replace('#', '');
		var wrap   = target.parent();

		this.removeCurrentActiveHeader(wrap);
		this.show(index);
		wrap.addClass('active');

		//callback active tab
		( this.afterShowCallback || $.noop ).call(null, index, target);
	};

	Tab.prototype.removeCurrentActiveHeader = function(newCurrent) {
		if ( this._currentActiveHeader ) {
			this._currentActiveHeader.removeClass('active');
		}

		this._currentActiveHeader = newCurrent;
	};

	Tab.prototype.show = function(index) {
		this.sectionContent
			.find('.tab-item')
			.addClass('hide')
			.filter('[data-attr-index="' + index + '"]')
				.removeClass('hide')
		;
	};

	Tab.prototype.insert = function() {
		this.removeOldElements();

		//insert in wrapper
		this.wrapper
			.append('<div class="contextual-tabs-bg">')
			.append(this.sectionHeader)
			.append(this.sectionContent)
		;

		this.setCurrentActiveHeader();
		this.container.prepend(this.wrapper);
	};

	Tab.prototype.setCurrentActiveHeader = function() {
		this._currentActiveHeader = this.sectionHeader.find('.active');
	};

	Tab.prototype.removeOldElements = function() {
		this.container
			.find( this.tagHeader + ', ' + this.tagContent )
			.remove()
		;
	};

	Tab.prototype.assign = function(attrs) {
		for ( var item in attrs ) {
			if ( !this.hasOwnProperty(item) || this[item] != attrs[item] ) {
				this[item] = attrs[item];
			}
		}
	};

	Tab.prototype.createSections = function() {
		this.wrapper        = $('<div class="contextual-tabs-wrap">');
		this.sectionHeader  = $('<ul class="contextual-tabs-header">');
		this.sectionContent = $('<div class="contextual-tabs-content">');
		this.generateHtml();
	};

	Tab.prototype.generateHtml = function() {
		var captions             = this.container.find(this.tagHeader);
		this._createContentIndex = 1;

		captions.each(this.createItem.bind(this));
	};

	Tab.prototype.createItem = function(i, caption) {
		caption     = $(caption);
		var content = caption.next(this.tagContent);

		if ( !content.length ) {
			return;
		}

		this.insertItemHeader(caption);
		this.insertItemContent(content);
		this._createContentIndex += 1;
	};

	Tab.prototype.insertItemContent = function(content) {
		var html = '<div class="tab-item [$1]" data-attr-index="[$2]"></div>';

		html = html.replace('[$1]', this._isActiveIndex('', 'hide'))
				   .replace('[$2]', this._createContentIndex)
		;

		html = $(html).append(content);
		this.sectionContent.append(html);
	};

	Tab.prototype.insertItemHeader = function(caption) {
		var html = '<li [$1]>'
				 + '<a data-action="change" href="#[$2]">[$3]</a>'
				 + '</li>'
		;

		html = html.replace('[$1]', this._isActiveIndex('class="active"', ''))
				   .replace('[$2]', this._createContentIndex)
				   .replace('[$3]', caption.text())
		;

		this.sectionHeader.append(html);
	};

	Tab.prototype._isActiveIndex = function(success, fail) {
		if ( this.activeIndex == this._createContentIndex ) {
			return success;
		}

		return fail;
	};

	$.fn.tabFormsAdmin = function(options) {
		var _tab = new Tab(this, options);
		_tab.init();
		_tab.insert();
	};

})(jQuery, window);