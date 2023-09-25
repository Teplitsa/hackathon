	/* Accordion */
	function hackathonAccordionToggle(e){
		var thisItem    = $(e.currentTarget);
		var thisParent  = thisItem.parent();
		var thisContent = thisParent.find('.hms-card-hidden');

		thisParent.siblings('.open').addClass('closing')
			.find('.hms-card-hidden')
			.slideUp(function(){
				$(this).parent().removeClass('open closing');
			});

		if ( thisParent.hasClass( 'open') ) {
			thisParent.addClass('closing');
			thisContent.slideUp(function(){
				thisParent.removeClass('open closing');
			});
		} else {
			thisParent.addClass('opening');
			thisContent.slideDown(function(){
				thisParent.addClass('open');
				thisParent.removeClass('opening');
			});
		}
	}

	$('.hms-cards-accordion .hms-card-content').on('click', function(e){
		e.preventDefault();
		hackathonAccordionToggle(e);
	});
