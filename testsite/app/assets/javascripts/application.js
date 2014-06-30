
;(function ($, window, document, undefined) {

	'use strict';

	// External links handling
	$('a[rel*="external"]').click(function () {
		$(this).attr('target', '_blank');
	});

	// FastClick
	FastClick.attach(document.body);
	
	// Foundation
	$(document).foundation();

	
	// ...

})(jQuery, window, document);

