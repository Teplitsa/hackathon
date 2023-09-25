	/* Upload image */
	$('.hackathon-upload-image').on( 'click', function(e){

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
			thisBtn.prev().prev().html( '<img src="' + attachmentUrl + '">');
			thisBtn.addClass('hidden');
			thisBtn.prev().removeClass('hidden');
		});

		file_frame.open();

		file_frame.$el.parents('.media-modal').addClass('hackathon-media-modal');

		file_frame.$el.find( '.attachments-browser .media-toolbar, .media-sidebar' ).remove();

	});

	/* Remove image */
	$(document).on( 'click', '.hackathon-remove-image', function(e){
		e.preventDefault();

		var thisBtn    = $(this);
		var dataInput  = thisBtn.data('input');

		if ( $(this).hasClass('hms-set-default-image') ) {
			thisBtn.prev().html( '<img src="' + hms.default_logo_url + '">');
		} else {
			thisBtn.prev().html('');
		}

		$('[name=' + dataInput + ']').val('');
		thisBtn.addClass('hidden');
		thisBtn.next().removeClass('hidden');
	});

