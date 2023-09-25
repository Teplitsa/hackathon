	/* Request */
	$('[name=request_status]').on('change', function(e){
		var requestId  = $(this).data('request-id');
		var requestVal = this.value;

		var data = {
			'request_id': requestId,
			'status': requestVal,
			'nonce': hms.nonce,
			'action': 'hms_request_status',
		};

		$.post( hms.ajaxurl, data, function(response) {
			if ( response.success === true ) {
				location.reload();
			}
		});

	});

	$(document).on('click', '.hms-card-status-menu .hms-card-status', function(e){

		var thisParent = $(this).parents('.hms-card-status-dropdown');
		var thisPopoper = $(this).parents('.hms-card-status-popover');
		var requestId  = $(this).data('request-id');
		var requestVal = $(this).data('request-status');
		var statusText = $(this).find('.hms-card-label').text();
		var thisCard   = $(this).parents('.hms-card');

		thisParent.find(' > .hms-card-status .hms-card-status-icon').removeClass().addClass('hms-card-status-icon hms-card-status-' + requestVal );
		thisParent.find(' > .hms-card-status .hms-card-label').text(statusText);
		thisPopoper.addClass('hidden');

		if ( thisCard.length ) {
			thisCard.removeClass().addClass('hms-card status-' + requestVal );
		}

		var data = {
			'request_id': requestId,
			'status': requestVal,
			'nonce': hms.nonce,
			'action': 'hms_request_status',
		};

		$.post( hms.ajaxurl, data, function(response) {
			thisPopoper.removeClass('hidden');

			if ( response.success === true ) {
				hackathonToast( response.data.message );
			}

		});
	});
