	/* Toast */
	function hackathonToast( message, status = 'default' ){

		var statusClass = 'status-' + status;
		// Remove if toast exists.
		var toastEl = $(document).find('.hackathon-toast');
		if ( toastEl.length ) {
			toastEl.remove();
		}

		// Insert toast.
		var toastHtml = '<div class="hackathon-toast ' + statusClass + '"><div class="hackathon-toast-body">' + message + '</div><span class="dashicons dashicons-no-alt"></span></div>';
		$('body').append(toastHtml);
		toastEl = $(document).find('.hackathon-toast');

		// Fade in toast.
		toastEl.fadeIn();

		// Hide and remove toast after 4s.
		setTimeout( function(){
			hackathonHideToast();
		}, 4000 );
	}

	/* Hide and remove toast */
	function hackathonHideToast(){
		var toastEl = $(document).find('.hackathon-toast');
		if ( toastEl.length ) {
			toastEl.fadeOut(function(){
				toastEl.remove();
			});
		}
	}

	/* Remove toast on click close button */
	$(document).on('click', '.hackathon-toast .dashicons', function(){
		hackathonHideToast();
	})
