	/* Countdown */
	$('.hms-countdown').each(function(e){
		var settings = {
			until: new Date( $(this).data('until') * 1000 ),
			compact: true,
			format: 'yodHMS',
			onExpiry: function(){
				hmsOnExpiry(this);
			},
			expiryText: hmsExpiryText(this),
			alwaysExpire: true,
		}

		$(this).countdown( settings );
	});

	/* On countdown expiry */
	function hmsOnExpiry(el){
		if ( $(el).data('expiry') ) {
			var action = $(el).data('expiry');
			if( action === 'reload' ) {
				location.reload()
			} else if ( action === 'text' ) {

			}
		}
	}

	/* Countdown expiry text */
	function hmsExpiryText(el){
		if ( $(el).data('expiry') === 'text' || $(el).data('expiry-text') ) {
			var text = $(el).data('expiry-text');
			return text;
		}
	}
