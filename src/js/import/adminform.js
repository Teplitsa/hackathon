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

