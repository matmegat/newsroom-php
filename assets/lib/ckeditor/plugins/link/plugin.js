CKEDITOR.plugins.add('link', {
	
	hidpi: true,
	requires: 'dialog',
	icons: 'link,unlink', 
		
	onLoad: function() {
		
	},

	init: function(editor) {
		
		var allowed = 'a[!href]',
			required = 'a[href]';

		editor.addCommand('link', new CKEDITOR.dialogCommand('link', {
			allowedContent: allowed,
			requiredContent: required
		}));
		
		editor.addCommand('unlink', new CKEDITOR.unlinkCommand(), {
			requiredContent: 'a[href]'
		});
		
		editor.setKeystroke(CKEDITOR.CTRL + 76, 'link');

		if (editor.ui.addButton) {
			
			editor.ui.addButton('Link', {
				label: 'Link',
				command: 'link',
				toolbar: 'links,10'
			});
			
			editor.ui.addButton('Unlink', {
				label: 'Unlink',
				command: 'unlink',
				toolbar: 'links,20'
			});
			
		}

		CKEDITOR.dialog.add('link', this.path + 'dialogs/link.js');

		editor.on('doubleclick', function(evt) {
			var element = CKEDITOR.plugins.link.getSelectedLink(editor) || evt.data.element;
			if (!element.isReadOnly() && element.is('a')) {
				evt.data.dialog = 'link';
				editor.getSelection().selectElement(element);
			}
		});

	},

	afterInit: function(editor) { }
	
});

CKEDITOR.plugins.link = {

	getSelectedLink: function(editor) {
		var selection = editor.getSelection();
		var selectedElement = selection.getSelectedElement();
		if (selectedElement && selectedElement.is('a'))
			return selectedElement;
		var range = selection.getRanges(true)[0];
		if (range) {
			range.shrink(CKEDITOR.SHRINK_TEXT);
			return editor.elementPath(range.getCommonAncestor()).contains('a', 1);
		}
		return null;
	}
	
};

CKEDITOR.unlinkCommand = function() {};
CKEDITOR.unlinkCommand.prototype = {
	
	exec: function(editor) {
		var style = new CKEDITOR.style({ element: 'a', type: CKEDITOR.STYLE_INLINE, alwaysRemoveElement: 1 });
		editor.removeStyle(style);
	},

	refresh: function(editor, path) {
		var element = path.lastElement && path.lastElement.getAscendant('a', true);
		if (element && element.getName() == 'a' && element.getAttribute('href') && element.getChildCount())
			this.setState(CKEDITOR.TRISTATE_OFF);
		else
			this.setState(CKEDITOR.TRISTATE_DISABLED);
	},

	contextSensitive: 1,
	startDisabled: 1,
	
};