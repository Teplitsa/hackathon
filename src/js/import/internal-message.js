	/* Internal Message */
	$('.hms-widget-heading-messages').on('click', function(e){
		e.preventDefault();
		var thisModal = $(this).next('.hms-modal');

		var thisForm   = thisModal.find('.hms-chat-form');
		var data       = thisForm.serialize();
		var spinner    = thisForm.find('.spinner');
		var thisButon  = thisForm.find('[type="submit"]');
		var thisMsg    = thisForm.find('[name="message"]');
		var thisChat   = thisModal.find('.hms-chat');
		var thisAjax   = thisModal.find('.hms-chat-ajax');
		var thisScroll = thisModal.find('.hms-widget-content');

		$(this).find('.hms-unread-point-messages').fadeOut();

		thisButon.addClass('is-loading');
		thisChat.addClass('is-loading');

		$.post( hms.ajaxurl, data, function(response) {

			console.log(response);

			thisButon.removeClass('is-loading');
			thisChat.removeClass('is-loading');

			if ( response.success === true ) {
				thisAjax.html(response.data.html);
				thisScroll.scrollTop(thisScroll.find('.hms-chat')[0].scrollHeight);
			}

			if ( response.success === false ) {
				console.log( 'error' );
			}
		});
	});

	// hms-chat-form

	$('.hms-chat-form').on('submit', function(e){
		e.preventDefault();

		var thisForm   = $(this);
		var data       = thisForm.serialize();
		var spinner    = thisForm.find('.spinner');
		var thisButon  = thisForm.find('[type="submit"]');
		var thisMsg    = thisForm.find('[name="message"]');
		var thisModal  = thisForm.parents('.hms-modal-chat');
		var thisAjax   = thisModal.find('.hms-chat-ajax');
		var thisScroll = thisModal.find('.hms-widget-content');
		var thisToggle = thisModal.prev('.hms-widget-heading-messages').find('span');

		if ($.trim(thisMsg.val()).length == 0){
			thisMsg.addClass('error');
		} else {
			thisMsg.removeClass('error');
			thisMsg.attr('disabled', true);
			thisButon.addClass('is-loading');

			$.post( hms.ajaxurl, data, function(response) {

				console.log(response);

				thisButon.removeClass('is-loading');
				thisMsg.val('');
				thisMsg.attr('disabled', false);
				thisMsg.focus();

				if ( response.success === true ) {
					thisAjax.html(response.data.html);
					thisScroll.scrollTop(thisScroll.find('.hms-chat')[0].scrollHeight);
					thisToggle.text(response.data.count);
				}

				if ( response.success === false ) {
					console.log( 'error' );
				}

			});
		}
	});

	$(window).on('load', function(){
		if(window.location.hash) {
			console.log(window.location.hash);
			var msgId = window.location.hash;
			if ( $(msgId).length ) {
				var modalId = $(msgId).parents('.hms-modal-chat').attr('id');
				console.log(modalId);
				hackathonModalShow('#' + modalId );
				$(msgId).addClass('focus');
			}
		}
	});
