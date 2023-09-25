/* Scripts */
(function( $ ){
	'use strict';

	tinymce.PluginManager.add( 'hmsmail', function( editor ){

		if ( editor.settings.mailtags ) {

			var mailtags = editor.settings.mailtags;

			var tags = mailtags.split(',');
			var items = [];

			tinymce.each( tags, function( tag ){
				items.push({
					text: '{' + tag + '}',
					onclick: function( e ){
						var content = e.control.settings.text;
						editor.insertContent( content );
					}
				});
			});

			editor.addButton( 'hmsmailtags', {
				type: 'menubutton',
				text: hms.i18n.availableTags,
				menu: items
			});

		}

	});

})( jQuery );
