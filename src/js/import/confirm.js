	/* Confirm */
	function hackathonConfirm( response, data = '' ){

		var buttonConfirm = '<button type="button" class="button" data-action="' + data.dataAction + '" data-id="' + data.id + '">' + response.data.str.yes + '</button>';
		var buttonClose   = '<button type="button" class="button button-primary" data-close="confirm">' + response.data.str.no + '</button></div>';

		// Insert toast.
		var confirmHtml = '<div class="hackathon-confirm">' +
			'<div class="hackathon-confirm-body">' +
				'<div class="hackathon-confirm-header"><span class="dashicons dashicons-warning"></span> ' + response.data.message + '</div>' +
				'<div class="hackathon-confirm-footer">' + buttonConfirm + buttonClose  + '</div>' +
			'</div>' +
		'</div>';

		$('body').append(confirmHtml);
		var confirmEl = $(document).find('.hackathon-confirm');

		// Fade in confirm.
		confirmEl.hide().fadeIn();
	}

	/* Hide and remove confirm */
	function hackathonHideConfirm( action = '' ){
		var confirmEl = $(document).find('.hackathon-confirm');
		if ( confirmEl.length ) {
			confirmEl.fadeOut(function(){
				confirmEl.remove();
				if ( action ) {
					action;
				}
			});
		}
	}

	/* Hide confirm on click */
	$(document).on('click', '.hackathon-confirm', function( e ){
		if (e.target !== this && e.target !== $('[data-close="confirm"]')[0] ){
			return;
		}
		hackathonHideConfirm();
	});
