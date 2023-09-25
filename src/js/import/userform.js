	/* User Form */
	if( $('.hms-user-form').length ){

		$.validator.addMethod( "lettersonly", function( value, element ) {
			return this.optional( element ) || /^[a-z]+$/i.test( value );
		}, $.validator.messages.lettersonly );

		$('.hms-user-form').validate({
			rules: {
				phone: {
					number: true
				},
				telegram: {
					lettersonly: true
				}
			},
			submitHandler: function(form) {

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
						hackathonToast( response.data.message );
					}

				});

			}
		});
	}

	$('#hackathon-send-reset-link').on('click', function(e){
		e.preventDefault();

		var data = {
			'user_id': hms.user_id,
			'nonce':   hms.nonce_reset,
			'action': 'send_password_reset',
		};

		$.post( hms.ajaxurl, data, function(response) {
			if ( response.success === true ) {
				hackathonToast( response.data );
			}
		});

	});

	$('.hms-remove-team-user').on('click', function( e ){
		e.preventDefault();
		var thisBtn = $(this);
		var userId = $(this).data('user');
		var teamId = $(this).data('team');

		var data = {
			'user_id': userId,
			'team_id': teamId,
			'nonce':   hms.nonce,
			'action': 'hms_remove_team_user',
		};
		$.post( hms.ajaxurl, data, function(response) {

			if ( response.success === true ) {
				thisBtn.parents('tr, .hackathon-card, .hms-list-item, .hms-card').fadeOut( "slow", function(e) {
					location.reload();
				});

			}

		});
	});

	$('.hms-add-team-user').on('click', function( e ){
		e.preventDefault();
		var thisBtn = $(this);
		var userId = $(this).data('user');
		var teamId = $(this).data('team');

		var data = {
			'user_id': userId,
			'team_id': teamId,
			'nonce':   hms.nonce,
			'action': 'hms_add_team_user',
		};
		$.post( hms.ajaxurl, data, function(response) {

			if ( response.success === true ) {
				location.reload();
			} else {
				hackathonModalHide();
				hackathonToast( response.data.message, 'error' );
				if ( response.data.max_count === response.data.count ) {
					$('.hackathon-open-modal').parents('p').addClass('hidden');
				}
			}

		});
	});

	/* Upload image */
	$('.hackathon-upload-avatar').on( 'click', function(e){

		e.preventDefault();

		var thisBtn    = $(this);
		var dataTitle  = $(this).data('title');
		var dataButton = $(this).data('button');
		var dataInput  = $(this).data('input');

		var file_frame;

		if ( file_frame ) {
			file_frame.open();
			return;
		}

		file_frame = wp.media.frames.file_frame = wp.media({
			title: dataTitle,
			library: {
				type: 'image'
			},
			button: {
				text: dataButton,
			},
			multiple: false,
		});

		file_frame.on( 'select', function() {

			var attachment    = file_frame.state().get('selection').first().toJSON();
			var attachmentUrl = attachment.url;

			if ( attachment.sizes.medium !== undefined ) {
				attachmentUrl = attachment.sizes.medium.url;
			}

			$('[name=' + dataInput + ']').val(attachment.id);
			var img = '<img src="' + attachmentUrl + '" class="hackathon-avatar hackathon-avatar-custom">';

			thisBtn.parent('.hackathon-image-field').find('img').replaceWith(img);

			if ( $('body').hasClass( 'hackathon-page-my-profile' ) ) {
				$('.hms-sidebar-avatar .hms-avatar-image').attr('src', attachmentUrl);
			}
		});

		file_frame.open();

		file_frame.$el.parents('.media-modal').addClass('hackathon-media-modal');

		file_frame.$el.find( '.attachments-browser .media-toolbar, .media-sidebar' ).remove();

	});

	/* Remove image */
	$('.hackathon-remove-avatar').on( 'click', function(e){
		e.preventDefault();

		var thisBtn    = $(this);
		var thisParent = thisBtn.parent('.hackathon-image-field');
		var dataInput  = thisBtn.data('input');
		var avatarUrl  = hms.avatar_url;

		$('[name=' + dataInput + ']').val('');
		var img = '<img src="' + avatarUrl + '" class="hackathon-avatar">';
		thisBtn.parent('.hackathon-image-field').find('img').replaceWith(img);
		if ( $('body').hasClass( 'hackathon-page-my-profile' ) ) {
			$('.hms-sidebar-avatar .hms-avatar-image').attr('src', avatarUrl);
		}

	});
