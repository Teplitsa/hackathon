	/* Register */
	$('[name=registerform]').on('submit', function(e){
		e.preventDefault();

		var thisForm    = $(this);
		var spinner     = thisForm.find('.spinner');
		var thisButon   = thisForm.find('[type="submit"]');
		var redirect_to = thisForm.find('[name=redirect_to]').val();

		spinner.addClass('is-active');
		thisButon.attr('disabled', true);
		thisForm.removeClass('shake form-invalid');

		var data = thisForm.serialize();

		$.post( hms.ajaxurl, data, function(response) {

			spinner.removeClass('is-active');
			thisButon.attr('disabled', false);

			if ( response.success === false ) {
				hackathonToast( response.data.message, 'error' );
				thisForm.addClass('form-invalid');
				var thisInput = $('[name="' + response.data.name + '"]');
				if ( ! thisInput.length ) {

				}
				thisInput.addClass('form-required');
				$('html,body').animate({scrollTop: thisInput.offset().top - 50},100);
				setTimeout(function(){
					thisForm.addClass('shake');
				}, 200 );
			}

			if ( response.success === true ) {
				window.location.replace( redirect_to );
			}

		});
	});

	/* On register form input click */
	$('[name=registerform] input, [name=registerform] textarea, [name=registerform] select').on('click', function(e){
		$(this).removeClass('form-required');
	});

	function hmsFieldsStatusUpdate( fields ){
		$(fields).each(function() {
			$( this ).prop( 'disabled', false );
			$( this ).parent('.hms-form-row').removeClass('hidden');
		});
	}

	/* On register form change participation_type */
	$('[name=participation_type]').on('change', function(e){
		var thisVal              = this.value;
		var defaultFields        = '[name=project_name], [name=problem_to_solve], [name=problem_solution], [name=project_stage]';
		var fullFields           = defaultFields + ', [name=about_team]';
		var authorOnlyFields     = defaultFields + ', [name=specialists_not_enough]';
		var specialistTeamFields = '[name=team_name]';
		var allFields            = fullFields  + ',' + specialistTeamFields + ', [name=specialists_not_enough]';

		// Hide by default additional fields.
		$(allFields).each(function() {
			$( this ).prop( 'disabled', true );
			$( this ).parent('.hms-form-row').addClass('hidden');
		});

		if ( thisVal === 'participation_full' ) {
			hmsFieldsStatusUpdate( fullFields );
		}

		if ( thisVal === 'participation_author_only' ) {
			hmsFieldsStatusUpdate( authorOnlyFields );
		}

		if ( thisVal === 'participation_specialist_team' ) {
			hmsFieldsStatusUpdate( specialistTeamFields );
		}

	});

	/* Dependencies */
	function hmsFormDependencies(){
		let fields = hms.form;

		$.each( fields, function(name,field) {
			if ( field.dependency ) {
				let dependency = field.dependency;
				let rule       = field.rule_field;
				let value      = field.rule_value;
				let control    = $('[name=' + rule + ']');
				let controlVal = control.val();

				if ( controlVal == value ) {
					$('[name=' + name + ']').parents('.hms-form-row').removeClass('hidden');
					$('[name=' + name + ']').removeAttr('disabled');
				} else {
					$('[name=' + name + ']').parents('.hms-form-row').addClass('hidden');
					$('[name=' + name + ']').attr('disabled',true);
				}
			}
		});
	}

	hmsFormDependencies();

	/* On chnage value in register form */
	$('[name="registerform"]').on('input', '.input, .select', function(e){
		hmsFormDependencies();
	});
