	/* Date Time Picker */
	$('.hms-date-time-picker').each(function(){
		$(this).datetimepicker({
			firstDay: 1,
			dateFormat: hms.date_format,
			timeFormat: '- ' + hms.time_format,
			controlType: 'select',
			oneLine: true,
			altField: $(this).next(),
			altFormat: 'yy-mm-dd',
			altTimeFormat: 'HH:mm:ss',
			altFieldTimeOnly: false,
			timeText: hms.i18n.timeText,
			currentText: hms.i18n.currentText,
			closeText: hms.i18n.closeText,
			prevText: '',
			nextText: '',
			beforeShow: function(input, inst) {
				$(inst.dpDiv[0]).addClass('hms-ui-datepicker');

				var rangeMinEl = $('#' + inst.id ).data('range-min');

				if (typeof rangeMinEl !== typeof undefined && rangeMinEl !== false) {
					var inputAlt = $('[name=' + rangeMinEl + ']' ).val();
					var date     = new Date(inputAlt);
					date.setDate(date.getDate() + 1);
					$('#' + inst.id ).datetimepicker( 'option', 'minDate', date );
					$('#' + inst.id ).datepicker('setTime', date);
				}
			},
			onSelect: function(input, inst) {
				var rangeMaxEl = $('#' + inst.id ).data('range-max');

				if (typeof rangeMaxEl !== typeof undefined && rangeMaxEl !== false) {
					var inputAlt = $('[name=' + inst.id + ']' ).val();
					var date     = new Date(inputAlt);
					date.setDate(date.getDate() + 1);
					$('#' + rangeMaxEl ).datetimepicker( 'option', 'minDate', date );
					$('#' + rangeMaxEl ).datepicker('setTime', date);
				}
			},
		});

	});
