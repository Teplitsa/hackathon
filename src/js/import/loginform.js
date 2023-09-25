	/* Login */
	$('[name=loginform]').on('submit', function(e){
		e.preventDefault();

		var thisForm = $(this);
		thisForm.removeClass('shake');

		var data = thisForm.serialize();

		$.post( hms.ajaxurl, data, function(response) {

			if ( response.success === true ) {
				window.location.replace( response.data.request.redirect_to );
			}

			if ( response.success === false ) {
				$('#login_error').remove();
				thisForm.before('<div id="login_error">' + response.data.message  + '</div>');
				thisForm.addClass('shake');
			}

			if ( typeof response.success === 'undefined' ) {
				window.location.replace( thisForm.attr('action') );
			}

		});
	});
