(function() {
	
	var placeholderReplaceRegex = /\(\([^\)]+\)\)/g;
	
	var plugin = CKEDITOR.plugins.placeholder = {
		
		createPlaceholder: function(editor, oldElement, text, isGet) {
			var element = new CKEDITOR.dom.element('span', editor.document);
			element.setAttributes({
				contentEditable: 'false',
				'data-cke-placeholder': 1,
				'class': 'cke_placeholder'
			});

			text && element.setText(text);

			if (isGet)
				return element.getOuterHtml();

			if (oldElement) {
				if (CKEDITOR.env.ie) {
					element.insertAfter(oldElement);
					// some time is required for IE 
					// before the element is removed.
					setTimeout(function() {
						oldElement.remove();
						element.focus();
					}, 10);
				} else {
					element.replace(oldElement);
				}
			} else {
				editor.insertElement(element);
			}

			return null;
		},

		getSelectedPlaceHolder: function(editor) {
			var range = editor.getSelection().getRanges()[0];
			range.shrink(CKEDITOR.SHRINK_TEXT);
			var node = range.startContainer;
			while (node && !(node.type == CKEDITOR.NODE_ELEMENT && node.data('cke-placeholder')))
				node = node.getParent();
			return node;
		}
		
	};
	
	CKEDITOR.plugins.add('placeholder', {
		
		init: function(editor) {

			editor.on('contentDom', function() {
				editor.editable().on('resizestart', function(evt) {
					if (editor.getSelection().getSelectedElement().data('cke-placeholder'))
						evt.data.preventDefault();
				});
			});
			
		},
		
		afterInit: function(editor) {
			
			var dataProcessor = editor.dataProcessor,
				dataFilter = dataProcessor && dataProcessor.dataFilter,
				htmlFilter = dataProcessor && dataProcessor.htmlFilter;

			if (dataFilter) {
				dataFilter.addRules({
					text: function(text) {
						return text.replace( placeholderReplaceRegex, function(match) {
							return plugin.createPlaceholder(editor, null, match, 1);
						});
					}
				});
			}

			if (htmlFilter) {
				htmlFilter.addRules({
					elements: {
						'span': function(element) {
							if (element.attributes && element.attributes['data-cke-placeholder'])
								delete element.name;
						}
					}
				});
			}
			
		}
		
	});
	
})();
