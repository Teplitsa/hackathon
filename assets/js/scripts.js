/* Scripts */
(function( $ ){
	'use strict';

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

	/* Date Time Picker */
	$('.hms-date-time-picker').each(function(){
		$(this).datetimepicker({
			firstDay: 1,
			dateFormat: hms.date_format,
			timeFormat: '- ' + hms.time_format,
			controlType: 'select',
			oneLine: true,
			altField: $(this).next(),
			altFormat: 'yy-mm-dd',
			altTimeFormat: 'HH:mm:ss',
			altFieldTimeOnly: false,
			timeText: hms.i18n.timeText,
			currentText: hms.i18n.currentText,
			closeText: hms.i18n.closeText,
			prevText: '',
			nextText: '',
			beforeShow: function(input, inst) {
				$(inst.dpDiv[0]).addClass('hms-ui-datepicker');

				var rangeMinEl = $('#' + inst.id ).data('range-min');

				if (typeof rangeMinEl !== typeof undefined && rangeMinEl !== false) {
					var inputAlt = $('[name=' + rangeMinEl + ']' ).val();
					var date     = new Date(inputAlt);
					date.setDate(date.getDate() + 1);
					$('#' + inst.id ).datetimepicker( 'option', 'minDate', date );
					$('#' + inst.id ).datepicker('setTime', date);
				}
			},
			onSelect: function(input, inst) {
				var rangeMaxEl = $('#' + inst.id ).data('range-max');

				if (typeof rangeMaxEl !== typeof undefined && rangeMaxEl !== false) {
					var inputAlt = $('[name=' + inst.id + ']' ).val();
					var date     = new Date(inputAlt);
					date.setDate(date.getDate() + 1);
					$('#' + rangeMaxEl ).datetimepicker( 'option', 'minDate', date );
					$('#' + rangeMaxEl ).datepicker('setTime', date);
				}
			},
		});

	});

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

	/* Validate Form */
	$('.hms-form-validate').each( function(e){

		var settings = {
			submitHandler: function(form) {

				if ( typeof window.tinyMCE !== 'undefined' ) {
					window.tinyMCE.triggerSave();
					tinyMCE.triggerSave();
				}

				var thisForm   = $(form);
				var spinner    = thisForm.find('.spinner');
				var thisButton = thisForm.find('[type="submit"]');
				var data       = thisForm.serialize();

				spinner.addClass('is-active');
				thisButton.attr('disabled', true);

				$.post( hms.ajaxurl, data, function(response) {

					spinner.removeClass('is-active');
					thisButton.attr('disabled', false);

					if ( response.success === true ) {
						hackathonToast( response.data.message, 'success' );
					}

					if ( response.success === false ) {
						hackathonToast( response.data.message, 'error' );
					}
				});
			}
		}

		$(this).validate( settings );
	});

	/* Disallow the symbols 'e' in the input type number */
	$('.hms-form').on('keydown', '[type=number]', function(e){
		let symbols = 'Ee+-.';
		if ( symbols.includes(e.key) ) {
			e.preventDefault();
		}
	});

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

	/* Login */
	$('[name=loginform]').on('submit', function(e){
		e.preventDefault();

		var thisForm = $(this);
		thisForm.removeClass('shake');

		var data = thisForm.serialize();

		$.post( hms.ajaxurl, data, function(response) {

			if ( response.success === true ) {
				window.location.replace( response.data.request.redirect_to );
			}

			if ( response.success === false ) {
				$('#login_error').remove();
				thisForm.before('<div id="login_error">' + response.data.message  + '</div>');
				thisForm.addClass('shake');
			}

			if ( typeof response.success === 'undefined' ) {
				window.location.replace( thisForm.attr('action') );
			}

		});
	});

	/* Lostpassword */
	$('[name=lostpasswordform]').on('submit', function(e){
		e.preventDefault();

		var thisForm = $(this);
		var redirect_to = thisForm.find('[name=redirect_to]').val();
		thisForm.removeClass('shake');

		var data = thisForm.serialize();

		$.post( hms.ajaxurl, data, function(response) {

			if ( response.success === false ) {
				$('#login_error').remove();
				thisForm.before('<div id="login_error">' + response.data  + '</div>');
				thisForm.addClass('shake');
			}

			if ( response.success === true ) {
				window.location.replace( redirect_to );
			}

		});
	});

	/* Register */
	$('[name=registerform]').on('submit', function(e){
		e.preventDefault();

		var thisForm    = $(this);
		var spinner     = thisForm.find('.spinner');
		var thisButon   = thisForm.find('[type="submit"]');
		var redirect_to = thisForm.find('[name=redirect_to]').val();

		spinner.addClass('is-active');
		thisButon.attr('disabled', true);
		thisForm.removeClass('shake form-invalid');

		var data = thisForm.serialize();

		$.post( hms.ajaxurl, data, function(response) {

			spinner.removeClass('is-active');
			thisButon.attr('disabled', false);

			if ( response.success === false ) {
				hackathonToast( response.data.message, 'error' );
				thisForm.addClass('form-invalid');
				var thisInput = $('[name="' + response.data.name + '"]');
				if ( ! thisInput.length ) {

				}
				thisInput.addClass('form-required');
				$('html,body').animate({scrollTop: thisInput.offset().top - 50},100);
				setTimeout(function(){
					thisForm.addClass('shake');
				}, 200 );
			}

			if ( response.success === true ) {
				window.location.replace( redirect_to );
			}

		});
	});

	/* On register form input click */
	$('[name=registerform] input, [name=registerform] textarea, [name=registerform] select').on('click', function(e){
		$(this).removeClass('form-required');
	});

	function hmsFieldsStatusUpdate( fields ){
		$(fields).each(function() {
			$( this ).prop( 'disabled', false );
			$( this ).parent('.hms-form-row').removeClass('hidden');
		});
	}

	/* On register form change participation_type */
	$('[name=participation_type]').on('change', function(e){
		var thisVal              = this.value;
		var defaultFields        = '[name=project_name], [name=problem_to_solve], [name=problem_solution], [name=project_stage]';
		var fullFields           = defaultFields + ', [name=about_team]';
		var authorOnlyFields     = defaultFields + ', [name=specialists_not_enough]';
		var specialistTeamFields = '[name=team_name]';
		var allFields            = fullFields  + ',' + specialistTeamFields + ', [name=specialists_not_enough]';

		// Hide by default additional fields.
		$(allFields).each(function() {
			$( this ).prop( 'disabled', true );
			$( this ).parent('.hms-form-row').addClass('hidden');
		});

		if ( thisVal === 'participation_full' ) {
			hmsFieldsStatusUpdate( fullFields );
		}

		if ( thisVal === 'participation_author_only' ) {
			hmsFieldsStatusUpdate( authorOnlyFields );
		}

		if ( thisVal === 'participation_specialist_team' ) {
			hmsFieldsStatusUpdate( specialistTeamFields );
		}

	});

	/* Dependencies */
	function hmsFormDependencies(){
		let fields = hms.form;

		$.each( fields, function(name,field) {
			if ( field.dependency ) {
				let dependency = field.dependency;
				let rule       = field.rule_field;
				let value      = field.rule_value;
				let control    = $('[name=' + rule + ']');
				let controlVal = control.val();

				if ( controlVal == value ) {
					$('[name=' + name + ']').parents('.hms-form-row').removeClass('hidden');
					$('[name=' + name + ']').removeAttr('disabled');
				} else {
					$('[name=' + name + ']').parents('.hms-form-row').addClass('hidden');
					$('[name=' + name + ']').attr('disabled',true);
				}
			}
		});
	}

	hmsFormDependencies();

	/* On chnage value in register form */
	$('[name="registerform"]').on('input', '.input, .select', function(e){
		hmsFormDependencies();
	});

	/* User Form */
	if( $('.hms-user-form').length ){

		$.validator.addMethod( "lettersonly", function( value, element ) {
			return this.optional( element ) || /^[a-z]+$/i.test( value );
		}, $.validator.messages.lettersonly );

		$('.hms-user-form').validate({
			rules: {
				phone: {
					number: true
				},
				telegram: {
					lettersonly: true
				}
			},
			submitHandler: function(form) {

				var thisForm   = $(form);
				var spinner    = thisForm.find('.spinner');
				var thisButton = thisForm.find('[type="submit"]');
				var data       = thisForm.serialize();

				spinner.addClass('is-active');
				thisButton.attr('disabled', true);

				$.post( hms.ajaxurl, data, function(response) {

					spinner.removeClass('is-active');
					thisButton.attr('disabled', false);

					if ( response.success === true ) {
						hackathonToast( response.data.message );
					}

				});

			}
		});
	}

	$('#hackathon-send-reset-link').on('click', function(e){
		e.preventDefault();

		var data = {
			'user_id': hms.user_id,
			'nonce':   hms.nonce_reset,
			'action': 'send_password_reset',
		};

		$.post( hms.ajaxurl, data, function(response) {
			if ( response.success === true ) {
				hackathonToast( response.data );
			}
		});

	});

	$('.hms-remove-team-user').on('click', function( e ){
		e.preventDefault();
		var thisBtn = $(this);
		var userId = $(this).data('user');
		var teamId = $(this).data('team');

		var data = {
			'user_id': userId,
			'team_id': teamId,
			'nonce':   hms.nonce,
			'action': 'hms_remove_team_user',
		};
		$.post( hms.ajaxurl, data, function(response) {

			if ( response.success === true ) {
				thisBtn.parents('tr, .hackathon-card, .hms-list-item, .hms-card').fadeOut( "slow", function(e) {
					location.reload();
				});

			}

		});
	});

	$('.hms-add-team-user').on('click', function( e ){
		e.preventDefault();
		var thisBtn = $(this);
		var userId = $(this).data('user');
		var teamId = $(this).data('team');

		var data = {
			'user_id': userId,
			'team_id': teamId,
			'nonce':   hms.nonce,
			'action': 'hms_add_team_user',
		};
		$.post( hms.ajaxurl, data, function(response) {

			if ( response.success === true ) {
				location.reload();
			} else {
				hackathonModalHide();
				hackathonToast( response.data.message, 'error' );
				if ( response.data.max_count === response.data.count ) {
					$('.hackathon-open-modal').parents('p').addClass('hidden');
				}
			}

		});
	});

	/* Upload image */
	$('.hackathon-upload-avatar').on( 'click', function(e){

		e.preventDefault();

		var thisBtn    = $(this);
		var dataTitle  = $(this).data('title');
		var dataButton = $(this).data('button');
		var dataInput  = $(this).data('input');

		var file_frame;

		if ( file_frame ) {
			file_frame.open();
			return;
		}

		file_frame = wp.media.frames.file_frame = wp.media({
			title: dataTitle,
			library: {
				type: 'image'
			},
			button: {
				text: dataButton,
			},
			multiple: false,
		});

		file_frame.on( 'select', function() {

			var attachment    = file_frame.state().get('selection').first().toJSON();
			var attachmentUrl = attachment.url;

			if ( attachment.sizes.medium !== undefined ) {
				attachmentUrl = attachment.sizes.medium.url;
			}

			$('[name=' + dataInput + ']').val(attachment.id);
			var img = '<img src="' + attachmentUrl + '" class="hackathon-avatar hackathon-avatar-custom">';

			thisBtn.parent('.hackathon-image-field').find('img').replaceWith(img);

			if ( $('body').hasClass( 'hackathon-page-my-profile' ) ) {
				$('.hms-sidebar-avatar .hms-avatar-image').attr('src', attachmentUrl);
			}
		});

		file_frame.open();

		file_frame.$el.parents('.media-modal').addClass('hackathon-media-modal');

		file_frame.$el.find( '.attachments-browser .media-toolbar, .media-sidebar' ).remove();

	});

	/* Remove image */
	$('.hackathon-remove-avatar').on( 'click', function(e){
		e.preventDefault();

		var thisBtn    = $(this);
		var thisParent = thisBtn.parent('.hackathon-image-field');
		var dataInput  = thisBtn.data('input');
		var avatarUrl  = hms.avatar_url;

		$('[name=' + dataInput + ']').val('');
		var img = '<img src="' + avatarUrl + '" class="hackathon-avatar">';
		thisBtn.parent('.hackathon-image-field').find('img').replaceWith(img);
		if ( $('body').hasClass( 'hackathon-page-my-profile' ) ) {
			$('.hms-sidebar-avatar .hms-avatar-image').attr('src', avatarUrl);
		}

	});

	/* Team Form */
	$('.hackathon-new-team-form').on('submit', function(e){
		e.preventDefault();

		var data = $(this).serialize();

		$.post( hms.ajaxurl, data, function(response) {

			if ( response.success === true ) {
				window.location.replace(response.data.redirect_to + '/' + response.data.team_id + '/');
			}

			if ( response.success === false ) {
				hackathonToast( response.data.message, 'error' );
			}

		});

	});

	$('.hackathon-update-team-form').on('submit', function(e){
		e.preventDefault();

		var data = $(this).serialize();

		$.post( hms.ajaxurl, data, function(response) {

			if ( response.success === true ) {
				hackathonToast( response.data.message );
			}

		});

	});

	/* Materials */
	/* Upload files */
	$('.hackathon-upload-files').on( 'click', function(e){

		e.preventDefault();

		var thisBtn    = $(this);
		var dataTitle  = $(this).data('title');
		var dataButton = $(this).data('button');
		var dataInput  = $(this).data('input');

		thisBtn.parent().find('.form-required').removeClass('form-required');

		var file_frame;

		if ( file_frame ) {
			file_frame.open();
			return;
		}

		file_frame = wp.media.frames.file_frame = wp.media({
			title: dataTitle,
			button: {
				text: dataButton,
			},
			multiple: 'add',
		});

		file_frame.on( 'select', function() {

			var attachments = file_frame.state().get('selection');

			var attachmentIds = [];

			$('.hackathon-files-list').empty();

			attachments.map( function( attachment ) {
				attachment = attachment.toJSON();

				var icon = '<div class="hackathon-card-figure"><img src="' + attachment.icon + '" alt=""></div>';
				var name = '<div class="hackathon-card-content">' + attachment.filename + '</div>';
				var action = '<div class="hackathon-card-info">' +
					'<div class="hackathon-card-actions">' +
						'<span class="hackathon-card-remove">' +
							'<span class="dashicons dashicons-trash"></span>' +
						'</span>' +
					'</div>' +
				'</div>';
				var url  = attachment.url;

				attachmentIds.push(attachment.id);

				$('.hackathon-files-list').prepend('<a href="' + url +'" class="hackathon-card" target="_blank" data-id="' + attachment.id + '">' + icon + name + action + '</a>');

			});

			attachmentIds = attachmentIds.join(',');

			$('[name=' + dataInput + ']').val(attachmentIds);
		});

		file_frame.on('open',function() {
			var selection = file_frame.state().get('selection');
			var ids_value = $('[name=' + dataInput + ']').val();

			if(ids_value.length > 0) {
				var ids = ids_value.split(',');

				ids.forEach(function(id) {
					var attachment = wp.media.attachment(id);
					attachment.fetch();
					selection.add(attachment ? [attachment] : []);
				});
			}
		});

		file_frame.open();

		file_frame.$el.parents('.media-modal').addClass('hackathon-media-modal');

		file_frame.$el.find( '.attachments-browser .media-toolbar, .media-sidebar' ).remove();

	});

	$(document).on('click', '.hackathon-card-actions', function(e){
		e.preventDefault();
	});

	$(document).on('click', '.hackathon-files-list .hackathon-card-remove', function(e){
		e.preventDefault();
		var thisBtn = $(this);
		var thisParent = thisBtn.parents('.hackathon-card');
		var parentId = thisParent.data('id');
		var inputElement = thisParent.parent().prev();
		var inputVal = inputElement.val();

		thisParent.fadeOut( function(e){

			$(this).remove();

			inputVal = inputVal.split(',');

			inputVal = $.grep( inputVal, function(value) {
				return value != parentId;
			});

			inputVal = inputVal.join(',');

			inputElement.val(inputVal);

		});
	});

	/* On form input click */
	$('.hms-form-materials input, .hms-form-materials textarea').on('click', function(e){
		$(this).removeClass('form-required');
	});

	/**
	 * Initial Presentation
	 */
	$('.hms-form-materials').on('submit', function(e){
		e.preventDefault();
		var thisForm = $(this);
		var thisBtn = thisForm.find('[type="submit"]');
		var data = $(this).serialize();
		
		thisBtn.addClass('is-loading');

		$.post( hms.ajaxurl, data, function(response) {

			if ( response.success === true ) {
				hackathonToast( response.data.message );
				thisBtn.removeClass('is-loading');
				thisForm.addClass('form-disabled')
				setTimeout( function () {
					location.reload();
				}, 3000 )
			}

			if ( response.success === false ) {
				hackathonToast( response.data.message, 'error' );
				thisBtn.removeClass('is-loading');

				thisForm.addClass('form-invalid');
				thisForm.find('[name=' + response.data.field + ']').addClass('form-required');

				var offsetTop = thisForm.find('[name=' + response.data.field + ']').offset().top - 140;
				if ( response.data.field == 'final_files' ) {
					offsetTop = thisForm.find('.hackathon-files-list').offset().top - 140;
				}
				
				$('html, body').animate({
					scrollTop: offsetTop
				}, 500);

			}

		});
	});

	/* Upload image */
	$('.hackathon-upload-image').on( 'click', function(e){

		e.preventDefault();

		var thisBtn    = $(this);
		var dataTitle  = $(this).data('title');
		var dataButton = $(this).data('button');
		var dataInput  = $(this).data('input');

		var file_frame;

		if ( file_frame ) {
			file_frame.open();
			return;
		}

		file_frame = wp.media.frames.file_frame = wp.media({
			title: dataTitle,
			library: {
				type: 'image'
			},
			button: {
				text: dataButton,
			},
			multiple: false,
		});

		file_frame.on( 'select', function() {

			var attachment    = file_frame.state().get('selection').first().toJSON();
			var attachmentUrl = attachment.url;

			if ( attachment.sizes.medium !== undefined ) {
				attachmentUrl = attachment.sizes.medium.url;
			}

			$('[name=' + dataInput + ']').val(attachment.id);
			thisBtn.prev().prev().html( '<img src="' + attachmentUrl + '">');
			thisBtn.addClass('hidden');
			thisBtn.prev().removeClass('hidden');
		});

		file_frame.open();

		file_frame.$el.parents('.media-modal').addClass('hackathon-media-modal');

		file_frame.$el.find( '.attachments-browser .media-toolbar, .media-sidebar' ).remove();

	});

	/* Remove image */
	$(document).on( 'click', '.hackathon-remove-image', function(e){
		e.preventDefault();

		var thisBtn    = $(this);
		var dataInput  = thisBtn.data('input');

		if ( $(this).hasClass('hms-set-default-image') ) {
			thisBtn.prev().html( '<img src="' + hms.default_logo_url + '">');
		} else {
			thisBtn.prev().html('');
		}

		$('[name=' + dataInput + ']').val('');
		thisBtn.addClass('hidden');
		thisBtn.next().removeClass('hidden');
	});


	/** Forms */

	/* On close create new form modal */
	$('[name="add-new-form"] .hms-modal-close').on('click', function(){
		$('[name="add-new-form"]').find('[type="text"]').val('');
	});

	/* Create new form */
	$('.hms-create-new-form').on('click', function(e){
		e.preventDefault();

		var thisBtn  = $(this);
		var thisForm = $('[name=add-new-form]');
		var data = thisForm.serialize();

		thisBtn.addClass('is-loading');

		$.post( hms.ajaxurl, data, function(response) {

			if ( response.success === true ) {
				window.location = response.data.redirect_to;
			}

			if ( response.success === false ) {
				thisBtn.removeClass('is-loading');
				hackathonToast( response.data.message, 'error' );
			}

		});

	});

	/* Delete Form */
	$('.hms-form-delete').on('click', function(e){
		e.preventDefault();

		var thisBtn    = $(this);
		var formId     = thisBtn.data('id');
		var card       = thisBtn.parents('.hms-card');
		var form       = thisBtn.parents( 'form' );
		var redirectTo = form.find('[name="redirect_to"]').val();
		var data       = {
			'action': 'hms_delete_form',
			'nonce': hms.nonce,
			'form_id': formId,
			'redirect_to': redirectTo,
		}

		$.post( hms.ajaxurl, data, function(response) {
			card.addClass('in-progress');
			thisBtn.addClass('is-loading');

			if ( response.success === true ) {
				if ( card.length ) {
					card.fadeOut( function(){
						card.remove();
						hackathonToast( response.data.message, 'success' );
					});
				} else {
					window.location = response.data.redirect_to;
				}
			}

			if ( response.success === false ) {
				card.removeClass('in-progress');
				hackathonToast( response.data.message, 'error' );
			}
		});
	});

	/* Sanitize form slug */
	$('[name="hms-form-update"] [name="slug"]').on('input', function(){
		var text = $(this).val().replace(/\s+/g, '-').toLowerCase();
		$(this).val(text);
	});

	/* Update Form */
	$('.hms-update-form').on('click', function(e){
		e.preventDefault();

		var thisBtn  = $(this);
		var thisForm = $('[name=hms-form-update]');
		var data     = thisForm.serialize();

		thisBtn.addClass('is-loading');

		$.post( hms.ajaxurl, data, function(response) {
			if ( response.success === true ) {
				location.reload();
			}

			if ( response.success === false ) {
				thisBtn.removeClass('is-loading');
				if ( response.data.validate === true ) {
					var labels = $('[data-type=label]');
					labels.each(function( index ) {
						if ( ! $( this ).val().trim() ) {
							$( this ).addClass('hms-field-required');
						}
					});
					var requiredLabel = $('[data-type=label].hms-field-required').first();
					$('html,body').animate({scrollTop: requiredLabel.offset().top - 50},100);
				}
				hackathonToast( response.data.message, 'error' );
			}
		});
	});

	/* Remove required class */
	$(document).on('click', '.hms-field-input', function(){
		$(this).removeClass('hms-field-required');
	});

	/* Remove filed */
	$(document).on('click', '.hms-field-remove', function(e){
		e.preventDefault();
		var field = $(this).parents('.hms-field-object');

		field.fadeOut(function(){
			field.remove();
			hms_update_fields_order();
		});
	});

	/* Sortable */
	function hmsFieldSortable(){
		if ( typeof $.fn.sortable === 'function' ) {
			$('.hms-field-list').sortable({
				axis: 'y',
				handle: '.hms-field-drag',
				forceHelperSize: true,
				forcePlaceholderSize: true,
				stop : function(){ 
					hms_update_fields_order();
					hms_update_fields_rule();
				}
			});
		}
	}

	function hmsSelectSortable(){
		if ( typeof $.fn.sortable === 'function' ) {
			$('.hms-select-options').sortable({
				axis: 'y',
				handle: '.hms-select-drag',
				forceHelperSize: true,
				forcePlaceholderSize: true,
				stop : function(){ 
					hms_update_fields_order();
				}
			});
		}
	}

	hmsFieldSortable();
	hmsSelectSortable();

	/* Update order */
	function hms_update_fields_order(){

		$('.hms-field-list .hms-field-object.hms-is-sortable').each(function( index ) {
			var thisField  = $( this );
			var thisInputs = thisField.find('[name]');
			var thisRuleField = thisField.find('[data-type="rule_field"]');
			var thisIndex  = index;
			thisInputs.each( function() {
				var thisInput = $( this );
				var thisType  = thisInput.data('type');
				var thisName  = 'field[custom_' + thisIndex + '][' + thisType + ']';
				if ( thisType === 'options' ) {
					thisName = thisName + '[]';
				}
				thisInput.attr('name', thisName );
				thisInput.attr('data-name', 'custom_' + thisIndex );
			});
		});

		var phoneExists = $('[name="field[phone][type]"]').length;
		if ( phoneExists ) {
			$('.hms-field-select-type option[value="phone"]').hide();
		} else {
			$('.hms-field-select-type option[value="phone"]').removeAttr('style');
		}

		var cityExists = $('[name="field[city][type]"]').length;
		if ( cityExists ) {
			$('.hms-field-select-type option[value="city"]').hide();
		} else {
			$('.hms-field-select-type option[value="city"]').removeAttr('style');
		}

		var telegramExists = $('[name="field[telegram][type]"]').length;
		if ( telegramExists ) {
			$('.hms-field-select-type option[value="telegram"]').hide();
		} else {
			$('.hms-field-select-type option[value="telegram"]').removeAttr('style');
		}

		var projectNameExists = $('[name="field[project_name][type]"]').length;
		if ( projectNameExists ) {
			$('.hms-field-select-type option[value="project_name"]').hide();
		} else {
			$('.hms-field-select-type option[value="project_name"]').removeAttr('style');
		}
	}

	$(window).on('load', function(){
		hms_update_fields_order();
	});

	/* Update rule */
	function hms_update_fields_rule(){
		var ruleOptions = {};
		$('.hms-field-list .hms-field-object.hms-is-sortable').each(function( index ) {
			var thisLabel = $(this).find('[data-type="label"]');
			var labelText = thisLabel.val();
			var labelName = thisLabel.data('name');
			ruleOptions[index] = {
				'text': labelText,
				'name': labelName,
			}
		});

		$('.hms-field-list .hms-field-object.hms-is-sortable').each(function() {
			var thisField  = $( this );
			var thisRuleField = thisField.find('[data-type="rule_field"]');
			var thisRules = thisRuleField.find('option');

			thisRules.each( function() {
				var thisOption = $( this );
				var optionText = thisOption.text();
				$.each( ruleOptions, function(index,element) {
					if( element.text == optionText ) {
						thisOption.attr( 'value', element.name );
					}
				});
			});

		});
	}

	/* Add new field */
	$('.hms-field-add-new').on('click', function(e){
		e.preventDefault();

		var thisBtn   = $(this);
		var container = $('.hms-field-list');
		var items     = container.find('.hms-field-object');
		var formId    = $('[name="hms-form-update"]').find('[name="form_id"]').val();
		var data      = {
			'action': 'hms_add_new_field',
			'nonce': hms.nonce,
			'order': items.length,
			'form_id': formId,
		}

		thisBtn.addClass('is-loading');

		$.post( hms.ajaxurl, data, function(response) {
			if ( response.success === true ) {
				thisBtn.removeClass('is-loading');
				var html = $(response.data.html).addClass('is-added');
				container.append(html);
				container.find('.is-added').find('[type=text]').first().focus();
				container.find('.is-added').removeClass('is-added');

				hms_update_fields_order();
			}
		});
	});

	/* Select field type */
	$(document).on('change', '.hms-field-select-type', function(e){
		e.preventDefault();

		var thisControl = $(this);
		var thisVal = thisControl.val();
		var parent = thisControl.parents('.hms-field-object');

		parent.addClass('is-overwrite');

		var data      = {
			'action': 'hms_replace_field',
			'nonce': hms.nonce,
			'order': parent.index(),
			'field': {
				'type': thisVal
			}
		}

		$.post( hms.ajaxurl, data, function(response) {
			if ( response.success === true ) {
				var html = $(response.data.html).addClass('is-added');
				parent.replaceWith(html);

				hms_update_fields_order();
				html.find('[type=text]').first().focus();
			}
		});
	});

	/* Add select option */
	$(document).on('click', '.hms-select-add-option', function(e){
		e.preventDefault();

		var thisBtn    = $(this);
		var thisParent = thisBtn.parents('.hms-field-object');
		var thisSelect = thisParent.find('.hms-select-options');

		var data      = {
			'action': 'hms_add_option',
			'nonce': hms.nonce,
			'index': thisParent.index(),
		}

		$.post( hms.ajaxurl, data, function(response) {
			if ( response.success === true ) {
				var html = response.data.html;
				thisSelect.append(html);
				hmsSelectSortable();
				hms_update_fields_order();
			}
		});

	});

	/* Remove select option */
	$(document).on('click', '.hms-select-option-remove', function(e){
		e.preventDefault();

		var thisBtn    = $(this);
		var thisOption = thisBtn.parents('.hms-select-option');

		thisOption.fadeOut(function(){
			thisOption.remove();
		});

	});

	/* Select placeholder */
	$(document).on('change', '.hms-field-switch-placeholder [type=checkbox]', function(e){

		var thisCheckbox = $(this);
		var thisParent = thisCheckbox.parents('.hms-field-object');
		var thisPlaceholder = thisParent.find('[data-type="placeholder_text"]');
		var thisPlaceholderField = thisPlaceholder.parent();
		var thisValue = thisCheckbox.is(':checked');

		if ( thisValue === true ) {
			thisPlaceholder.removeAttr('disabled');
			thisPlaceholderField.removeClass('hms-field-hidden');
		} else {
			thisPlaceholder.attr('disabled','disabled');
			thisPlaceholderField.addClass('hms-field-hidden');
		}

	});

	/* Select placeholder */
	$(document).on('change', '.hms-field-switch-placeholder [type=checkbox]', function(e){

		var thisCheckbox = $(this);
		var thisParent = thisCheckbox.parents('.hms-field-object');
		var thisPlaceholder = thisParent.find('[data-type="placeholder_text"]');
		var thisPlaceholderField = thisPlaceholder.parent();
		var thisValue = thisCheckbox.is(':checked');

		if ( thisValue === true ) {
			thisPlaceholder.removeAttr('disabled');
			thisPlaceholderField.removeClass('hms-field-hidden');
		} else {
			thisPlaceholder.attr('disabled','disabled');
			thisPlaceholderField.addClass('hms-field-hidden');
		}

	});

	$(document).on('change', '.hms-field-switch-conditional-logic [type=checkbox]', function(e){

		var thisCheckbox = $(this);
		var thisParent   = thisCheckbox.parents('.hms-field-object');
		var thisLogic    = thisParent.find('.hms-field-conditional-logic');
		var thisValue    = thisCheckbox.is(':checked');

		thisLogic.removeClass('hms-show');

		if ( thisValue === true ) {
			thisLogic.slideDown();
		} else {
			thisLogic.slideUp();
		}

	});


	/* Support */
	$('[name=supportform]').on('submit', function(e){
		e.preventDefault();

		var thisForm  = $(this);
		var spinner   = thisForm.find('.spinner');
		var thisButon = thisForm.find('[type="submit"]');

		spinner.addClass('is-active');
		thisButon.attr('disabled', true);

		var data = thisForm.serialize();

		$.post( hms.ajaxurl, data, function(response) {

			spinner.removeClass('is-active');
			thisButon.attr('disabled', false);

			if ( response.success === true ) {
				hackathonToast( response.data.message, 'success' );
				$('[name=message_title], [name=message_content]').val('');
			}

			if ( response.success === false ) {

				hackathonToast( response.data.message, 'error' );
			}

		});
	});

	/* Message */
	var msgItemAll     = $('.hackathon-insert-message [value=all]');
	var msgRole        = $('[name="role[]"]');

	msgItemAll.on('change', function(e){

		var isChecked = $(this).is(':checked');

		if ( isChecked ) {
			msgRole.prop('checked', true ).attr('checked','checked');
		} else {
			msgRole.prop('checked', false ).removeAttr('checked');
		}

	});

	msgRole.on('change', function(e){
		var msgRolesLength = $('[name="role[]"]:checked').length;

		if ( msgRolesLength == 3 ) {
			msgItemAll.prop('checked', true ).attr('checked','checked');
		} else {
			msgItemAll.prop('checked', false ).removeAttr('checked');
		}
	});

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

	/* Request */
	$('[name=request_status]').on('change', function(e){
		var requestId  = $(this).data('request-id');
		var requestVal = this.value;

		var data = {
			'request_id': requestId,
			'status': requestVal,
			'nonce': hms.nonce,
			'action': 'hms_request_status',
		};

		$.post( hms.ajaxurl, data, function(response) {
			if ( response.success === true ) {
				location.reload();
			}
		});

	});

	$(document).on('click', '.hms-card-status-menu .hms-card-status', function(e){

		var thisParent = $(this).parents('.hms-card-status-dropdown');
		var thisPopoper = $(this).parents('.hms-card-status-popover');
		var requestId  = $(this).data('request-id');
		var requestVal = $(this).data('request-status');
		var statusText = $(this).find('.hms-card-label').text();
		var thisCard   = $(this).parents('.hms-card');

		thisParent.find(' > .hms-card-status .hms-card-status-icon').removeClass().addClass('hms-card-status-icon hms-card-status-' + requestVal );
		thisParent.find(' > .hms-card-status .hms-card-label').text(statusText);
		thisPopoper.addClass('hidden');

		if ( thisCard.length ) {
			thisCard.removeClass().addClass('hms-card status-' + requestVal );
		}

		var data = {
			'request_id': requestId,
			'status': requestVal,
			'nonce': hms.nonce,
			'action': 'hms_request_status',
		};

		$.post( hms.ajaxurl, data, function(response) {
			thisPopoper.removeClass('hidden');

			if ( response.success === true ) {
				hackathonToast( response.data.message );
			}

		});
	});

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


	/* Login */
	$('.hms-card-inactive').on('click', '[href="#"]', function(e){
		e.preventDefault();

	
	});


})( jQuery );
