	/* Clipboard */
	$('.hms-clipboard').on( 'click', function(e){
		e.preventDefault();
	});

	if ( typeof ClipboardJS === 'function' ) {
		var clipboard = new ClipboardJS('.hms-clipboard', {
			text: function(trigger) {
				return trigger.innerText
			}
		});

		clipboard.on('success', function(e) {
			var msg = hms.i18n.successCopy;

			if ( e.trigger.hasAttribute('data-msg') ) {
				msg = e.trigger.getAttribute('data-msg');
			}

			hackathonToast( msg );

			e.clearSelection();
		});
	}

