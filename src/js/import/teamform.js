	/* Team Form */
	$('.hackathon-new-team-form').on('submit', function(e){
		e.preventDefault();

		var data = $(this).serialize();

		$.post( hms.ajaxurl, data, function(response) {

			if ( response.success === true ) {
				window.location.replace(response.data.redirect_to + '/' + response.data.team_id + '/');
			}

			if ( response.success === false ) {
				hackathonToast( response.data.message, 'error' );
			}

		});

	});

	$('.hackathon-update-team-form').on('submit', function(e){
		e.preventDefault();

		var data = $(this).serialize();

		$.post( hms.ajaxurl, data, function(response) {

			if ( response.success === true ) {
				hackathonToast( response.data.message );
			}

		});

	});
