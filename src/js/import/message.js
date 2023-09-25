	/* Message */
	var msgItemAll     = $('.hackathon-insert-message [value=all]');
	var msgRole        = $('[name="role[]"]');

	msgItemAll.on('change', function(e){

		var isChecked = $(this).is(':checked');

		if ( isChecked ) {
			msgRole.prop('checked', true ).attr('checked','checked');
		} else {
			msgRole.prop('checked', false ).removeAttr('checked');
		}

	});

	msgRole.on('change', function(e){
		var msgRolesLength = $('[name="role[]"]:checked').length;

		if ( msgRolesLength == 3 ) {
			msgItemAll.prop('checked', true ).attr('checked','checked');
		} else {
			msgItemAll.prop('checked', false ).removeAttr('checked');
		}
	});
