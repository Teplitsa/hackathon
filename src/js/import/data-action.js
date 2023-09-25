	/* Data Action */
	$(document).on('click', '[data-action]', function(e){
		e.preventDefault();

		if ( typeof window.tinyMCE !== 'undefined' ) {
			window.tinyMCE.triggerSave();
		}

		var thisBtn     = $(this);
		var dataAction  = thisBtn.data('action');
		var dataId      = thisBtn.data('id');
		var dataConfirm = thisBtn.data('confirm');
		var spinner     = thisBtn.next('.spinner');
		var data        = '';

		if ( 'submit' === dataAction ) {
			var dataTarget = $(this).data('target');
			var data       = $(dataTarget).serialize();
		} else {
			data = {
				'nonce': hms.nonce,
				'id': dataId,
				'action': 'hackathon_' + dataAction,
				'dataAction': dataAction,
				'confirm': dataConfirm,
			};
		}

		spinner.addClass('is-active');
		thisBtn.attr('disabled', true);

		$.post( hms.ajaxurl, data, function(response) {

			spinner.removeClass('is-active');
			thisBtn.attr('disabled', false);

			if ( response.success === true ) {
				if ( 'submit' === thisBtn.data('action') ) {
					hackathonToast( response.data.message );
					if ( typeof response.data.reload !== 'undefined' ) {
						setTimeout( function(){
							window.location.replace(response.data.redirect_to);
						}, response.data.reload );
					}
				} else {
					hackathonHideConfirm( window.location.replace(response.data.redirect_to) );
				}
			}

			if ( response.success === false ) {
				if ( response.data.confirm === true ) {
					hackathonConfirm( response, data );
				} else {
					hackathonToast( response.data.message, 'error' );
				}
			}
		});
	});

	// User action.
	$(document).on('click', '[data-user-action]', function(e){
		e.preventDefault();

		var button = $(this);
		var action = button.data('user-action');
		var userId = button.data('user-id');
		var item   = button.parents('.hms-card');

		if ( item.length ) {
			item.fadeOut( "slow", function(e) {
				$(this).remove();
			});
		}

		var data = {
			'user_id': userId,
			'nonce':   hms.nonce,
			'action': 'hackathon_' + action + '_user',
		};
		$.post( hms.ajaxurl, data, function(response) {

			if ( response.success === true ) {

				hackathonToast( response.data.message );

				if ( ! item.length ) {
					setTimeout(function(){
						window.location.replace(response.data.redirect_to)
					}, 4000 );
				}

			}

			if ( response.success === false ) {
				hackathonToast( response.data.message, 'error' );
			}

		});

	});

	/* Item Action */
	$(document).on('click', function(e) {
		if ($(e.target).is('.item-action-toggle') === false) {
			$('.item-actions').removeClass('open');
		}
	});

	$(document).on('click', '.item-action-toggle', function(e){
		e.preventDefault();

		var thisActions = $(this).parent('.item-actions');
		var thisContainer = $(this).parents('.wp-list-table');
		var allActions = thisContainer.find('.item-actions').not(thisActions);

		allActions.removeClass('open');
		thisActions.toggleClass('open');
	});
