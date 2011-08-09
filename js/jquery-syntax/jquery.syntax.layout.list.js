//	This file is part of the "jQuery.Syntax" project, and is licensed under the GNU AGPLv3.
//	Copyright 2010 Samuel Williams. All rights reserved.
//	See <jquery.syntax.js> for licensing details.

Syntax.layouts.list = function(options, code, container) {
	var listTag = options.listTag || 'ol';

	var list = jQuery('<' + listTag + ' class="syntax highlighted">test'), line = 1, space = /^\s*$/;
	var toolbar = jQuery('<div class="toolbar">');
	
	var rawCode = container.clone();
	rawCode.addClass("raw syntax highlighted");
	
	// Source code
	code.children().each(function() {
		var li = document.createElement('li');
		li.className = "line ln" + line
		
		if (line % 2) {
			li.className += " alt";
		}
		
		if (line == 1) {
			li.className += " first"
		}
		
		var div = document.createElement('div'); 
		div.className = "source "  + this.className;
		
		div.appendChild(this);
		
		li.appendChild(div);
		list[0].appendChild(li);
		
		line = line + 1;
	});
	

	
	return jQuery('<div class="syntax-container">').append(toolbar).append(list);
};
