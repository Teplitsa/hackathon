	/* Modal */
	function hackathonModalShow(thisModal = ''){
		let scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
		$('body').css('margin-right', scrollbarWidth ).addClass('modal-open');
		$(thisModal).fadeIn();
	}

	function hackathonModalHide(){
		$('.hms-modal').fadeOut(function(){
			$('body').removeClass('modal-open').css('margin-right', '' );
		});
	}

	$('.hms-modal').on('click', function( e ){
		if ( e.target !== this ){
			return;
		}
		hackathonModalHide();
	});

	$('.hms-modal-close').on('click', function( e ){
		e.preventDefault();
		hackathonModalHide();
	});

	$('[data-modal]').on('click', function(e){
		e.preventDefault();
		var thisModal = $(this).data('modal');
		hackathonModalShow(thisModal);
	});
