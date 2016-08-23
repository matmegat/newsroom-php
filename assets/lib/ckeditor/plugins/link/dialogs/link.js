CKEDITOR.dialog.add("link", function(editor) {
	
	var plugin = CKEDITOR.plugins.link;
	var commonLang = editor.lang.common;
	var element;

	return {
		
		title: "Create Link",
		minWidth: 300,
		minHeight: 32,
		
		contents: [{
			
			id: "info",
			label: "Link",
			title: "Link",
			
			elements: [{
				
				type: "text",
				id: "url",
				required: true,
				
				onChange: function() {
					var _this = $(this.getInputElement().$);
					var value = _this.val();
					if (!(/^([^:]+:|\(\([^\)]+\)\)$)/.test(value)))
						value = "http://" + value;
					_this.val(value);
				},
				
				setup: function(data) {
					if (data.url) this.setValue(data.url);
				},
				
				commit: function(data) {
					this.onChange();
					data.url = this.getValue();
				}
				
			}],
			
		}],
		
		onCancel: function() {},
		
		onShow: function() {
			
			var editor = this.getParentEditor(),
				selection = editor.getSelection(),
				data = { url: null };

			// fill in all the relevant fields if there's already one link selected.
			if ((element = plugin.getSelectedLink(editor)) && element.hasAttribute("href")) {
				selection.selectElement(element);
				data.url = element.getAttribute("href");
			} else {
				element = null;
			}
			
			if (!data.url) {
				var default_url = editor.element.data("link-default-url")
				var text = $.trim(selection.getSelectedText());
				if (/^https?:\/\//.test(text)) data.url = text;
				else if (default_url) data.url = default_url;
				else data.url = "";
			}
					
			this.parts.dialog.addClass("cke_link_dialog");
			this.setupContent(data); 
			
		},
		
		onOk: function() {
			
			var data = {};
			var attrs = {};
			var _this = this;
			var editor = this.getParentEditor();			
			var selection = editor.getSelection();
			
			this.commitContent(data);	
			attrs.href = data.url;

			if (!element) {
					
				var range = selection.getRanges(1)[0];
				
				if (range.collapsed) {
					var text = data.url;
					var text_node = new CKEDITOR.dom.text(text, editor.document);
					range.insertNode(text_node);
					range.selectNodeContents(text_node);
				}
				
				var style = new CKEDITOR.style({ element: "a", attributes: attrs });
				style.type = CKEDITOR.STYLE_INLINE;
				style.applyToRange(range);
				range.select();
				
			} else {
				
				element.setAttributes(attrs);
				selection.selectElement(element);
				delete this._.selectedElement;
				
			}
			
		},
		
		onLoad: function() {},
		onFocus: function() {
			urlField = this.getContentElement("info", "url");
			urlField.select();
		},
		
		resizable: CKEDITOR.DIALOG_RESIZE_NONE
		
	};
	
});