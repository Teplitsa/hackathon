	/* Filter */
	$('.hms-filter select').on('change', '', function(e){
		e.preventDefault();

		$('.hms-filter').submit();
	});

	$('.hms-filter-button').on('click', function(e){
		e.preventDefault();

		if ( $(this).hasClass('active') ) {
			$('.hms-filter-bottom').slideUp();
		} else {
			$('.hms-filter-bottom').slideDown();
		}
		$(this).toggleClass('active');
	});

	$('.hms-filter-order').on('click', function(e){
		e.preventDefault();

		var thisForm   = $(this).parents('.hms-filter');
		var orderInput = thisForm.find('[name=order]');
		var order      = orderInput.val();

		if ( order === 'asc' ) {
			$(this).removeClass('order-asc').addClass('order-desc');
			orderInput.val('desc');
		} else {
			$(this).removeClass('order-desc').addClass('order-asc');
			orderInput.val('asc');
		}
		thisForm.submit();
	});
