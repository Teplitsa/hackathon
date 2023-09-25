	/* Toggle Sidebar */
	function hmsToggleSidebar(e){
		let status = '';
		$('.hms-sidebar').toggleClass('open');
		if ( $('.hms-sidebar').hasClass('open') ) {
			status = 'opened';
		} else {
			status = 'closed';
		}
		return status;
	}

	$('.hms-adminbar-toggle > a').on('click', function(e){
		e.preventDefault();
		let status = hmsToggleSidebar(e);

		if ( status === 'opened' ) {
			$(this).addClass('active');
			$(this).find( 'svg > use').attr( 'xlink:href', '#icon-close');
		} else {
			$(this).removeClass('active');
			$(this).find( 'svg > use').attr( 'xlink:href', '#icon-menu');
		}
	});
