	/* Lostpassword */
	$('[name=lostpasswordform]').on('submit', function(e){
		e.preventDefault();

		var thisForm = $(this);
		var redirect_to = thisForm.find('[name=redirect_to]').val();
		thisForm.removeClass('shake');

		var data = thisForm.serialize();

		$.post( hms.ajaxurl, data, function(response) {

			if ( response.success === false ) {
				$('#login_error').remove();
				thisForm.before('<div id="login_error">' + response.data  + '</div>');
				thisForm.addClass('shake');
			}

			if ( response.success === true ) {
				window.location.replace( redirect_to );
			}

		});
	});
