Module( 'WS.Datepicker', function(Datepicker){
	
	Datepicker.fn.initialize = function(container) {
		this.container = container;
		//set translate pt-BR
		this.trasnlateTimepicker();
		this.trasnlateDatepicker();
		//start
		this.init();
	};

	Datepicker.fn.trasnlateTimepicker = function() {
		if ( !jQuery.timepicker ) {
			return;
		}

		jQuery.timepicker.regional['pt-BR'] = {
			timeOnlyTitle: 'Escolha a horário',
			timeText: 'Horário',
			hourText: 'Hora',
			minuteText: 'Minutos',
			secondText: 'Segundos',
			millisecText: 'Milissegundos',
			timezoneText: 'Fuso horário',
			currentText: 'Agora',
			closeText: 'Fechar',
			timeFormat: 'HH:mm',
			amNames: ['a.m.', 'AM', 'A'],
			pmNames: ['p.m.', 'PM', 'P'],
			isRTL: false
		};

		jQuery.timepicker.setDefaults(jQuery.timepicker.regional['pt-BR']);
	};

	Datepicker.fn.trasnlateDatepicker = function() {
		if ( !jQuery.datepicker ) {
			return;
		}

		jQuery.datepicker.regional['pt-BR'] = {
			closeText		: 'Fechar',
			prevText		: '&#x3c;Anterior',
			nextText		: 'Pr&oacute;ximo&#x3e;',
			currentText		: 'Hoje',
			monthNames 		: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho', 'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
			monthNamesShort : ['Jan','Fev','Mar','Abr','Mai','Jun', 'Jul','Ago','Set','Out','Nov','Dez'],
			dayNames 		: ['Domingo','Segunda-feira','Ter&ccedil;a-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sabado'],
			dayNamesShort 	: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
			dayNamesMin 	: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
			weekHeader 		: 'Sm',
			dateFormat 		: 'dd/mm/yy',
			firstDay 		: 0,
			isRTL 			: false,
			yearSuffix 		: '',
			showMonthAfterYear: false
		};

		jQuery.datepicker.setDefaults(jQuery.datepicker.regional['pt-BR']);
	};

	Datepicker.fn.init = function(container) {
		var main = ( container || this.container );

		main.find( '.datepicker:not([data-no-plugin])' ).datepicker();
		main.find( '.datetimepicker:not([data-no-plugin])' ).datetimepicker();
		main.find( '.timepicker:not([data-no-plugin])' ).timepicker();
	};
});
