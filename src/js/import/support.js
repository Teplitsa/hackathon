	/* Support */
	$('[name=supportform]').on('submit', function(e){
		e.preventDefault();

		var thisForm  = $(this);
		var spinner   = thisForm.find('.spinner');
		var thisButon = thisForm.find('[type="submit"]');

		spinner.addClass('is-active');
		thisButon.attr('disabled', true);

		var data = thisForm.serialize();

		$.post( hms.ajaxurl, data, function(response) {

			spinner.removeClass('is-active');
			thisButon.attr('disabled', false);

			if ( response.success === true ) {
				hackathonToast( response.data.message, 'success' );
				$('[name=message_title], [name=message_content]').val('');
			}

			if ( response.success === false ) {

				hackathonToast( response.data.message, 'error' );
			}

		});
	});
