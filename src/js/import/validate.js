	/* Validate Form */
	$('.hms-form-validate').each( function(e){

		var settings = {
			submitHandler: function(form) {

				if ( typeof window.tinyMCE !== 'undefined' ) {
					window.tinyMCE.triggerSave();
					tinyMCE.triggerSave();
				}

				var thisForm   = $(form);
				var spinner    = thisForm.find('.spinner');
				var thisButton = thisForm.find('[type="submit"]');
				var data       = thisForm.serialize();

				spinner.addClass('is-active');
				thisButton.attr('disabled', true);

				$.post( hms.ajaxurl, data, function(response) {

					spinner.removeClass('is-active');
					thisButton.attr('disabled', false);

					if ( response.success === true ) {
						hackathonToast( response.data.message, 'success' );
					}

					if ( response.success === false ) {
						hackathonToast( response.data.message, 'error' );
					}
				});
			}
		}

		$(this).validate( settings );
	});

	/* Disallow the symbols 'e' in the input type number */
	$('.hms-form').on('keydown', '[type=number]', function(e){
		let symbols = 'Ee+-.';
		if ( symbols.includes(e.key) ) {
			e.preventDefault();
		}
	});
