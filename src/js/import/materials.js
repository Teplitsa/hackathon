	/* Materials */
	/* Upload files */
	$('.hackathon-upload-files').on( 'click', function(e){

		e.preventDefault();

		var thisBtn    = $(this);
		var dataTitle  = $(this).data('title');
		var dataButton = $(this).data('button');
		var dataInput  = $(this).data('input');

		thisBtn.parent().find('.form-required').removeClass('form-required');

		var file_frame;

		if ( file_frame ) {
			file_frame.open();
			return;
		}

		file_frame = wp.media.frames.file_frame = wp.media({
			title: dataTitle,
			button: {
				text: dataButton,
			},
			multiple: 'add',
		});

		file_frame.on( 'select', function() {

			var attachments = file_frame.state().get('selection');

			var attachmentIds = [];

			$('.hackathon-files-list').empty();

			attachments.map( function( attachment ) {
				attachment = attachment.toJSON();

				var icon = '<div class="hackathon-card-figure"><img src="' + attachment.icon + '" alt=""></div>';
				var name = '<div class="hackathon-card-content">' + attachment.filename + '</div>';
				var action = '<div class="hackathon-card-info">' +
					'<div class="hackathon-card-actions">' +
						'<span class="hackathon-card-remove">' +
							'<span class="dashicons dashicons-trash"></span>' +
						'</span>' +
					'</div>' +
				'</div>';
				var url  = attachment.url;

				attachmentIds.push(attachment.id);

				$('.hackathon-files-list').prepend('<a href="' + url +'" class="hackathon-card" target="_blank" data-id="' + attachment.id + '">' + icon + name + action + '</a>');

			});

			attachmentIds = attachmentIds.join(',');

			$('[name=' + dataInput + ']').val(attachmentIds);
		});

		file_frame.on('open',function() {
			var selection = file_frame.state().get('selection');
			var ids_value = $('[name=' + dataInput + ']').val();

			if(ids_value.length > 0) {
				var ids = ids_value.split(',');

				ids.forEach(function(id) {
					var attachment = wp.media.attachment(id);
					attachment.fetch();
					selection.add(attachment ? [attachment] : []);
				});
			}
		});

		file_frame.open();

		file_frame.$el.parents('.media-modal').addClass('hackathon-media-modal');

		file_frame.$el.find( '.attachments-browser .media-toolbar, .media-sidebar' ).remove();

	});

	$(document).on('click', '.hackathon-card-actions', function(e){
		e.preventDefault();
	});

	$(document).on('click', '.hackathon-files-list .hackathon-card-remove', function(e){
		e.preventDefault();
		var thisBtn = $(this);
		var thisParent = thisBtn.parents('.hackathon-card');
		var parentId = thisParent.data('id');
		var inputElement = thisParent.parent().prev();
		var inputVal = inputElement.val();

		thisParent.fadeOut( function(e){

			$(this).remove();

			inputVal = inputVal.split(',');

			inputVal = $.grep( inputVal, function(value) {
				return value != parentId;
			});

			inputVal = inputVal.join(',');

			inputElement.val(inputVal);

		});
	});

	/* On form input click */
	$('.hms-form-materials input, .hms-form-materials textarea').on('click', function(e){
		$(this).removeClass('form-required');
	});

	/**
	 * Initial Presentation
	 */
	$('.hms-form-materials').on('submit', function(e){
		e.preventDefault();
		var thisForm = $(this);
		var thisBtn = thisForm.find('[type="submit"]');
		var data = $(this).serialize();
		
		thisBtn.addClass('is-loading');

		$.post( hms.ajaxurl, data, function(response) {

			if ( response.success === true ) {
				hackathonToast( response.data.message );
				thisBtn.removeClass('is-loading');
				thisForm.addClass('form-disabled')
				setTimeout( function () {
					location.reload();
				}, 3000 )
			}

			if ( response.success === false ) {
				hackathonToast( response.data.message, 'error' );
				thisBtn.removeClass('is-loading');

				thisForm.addClass('form-invalid');
				thisForm.find('[name=' + response.data.field + ']').addClass('form-required');

				var offsetTop = thisForm.find('[name=' + response.data.field + ']').offset().top - 140;
				if ( response.data.field == 'final_files' ) {
					offsetTop = thisForm.find('.hackathon-files-list').offset().top - 140;
				}
				
				$('html, body').animate({
					scrollTop: offsetTop
				}, 500);

			}

		});
	});
