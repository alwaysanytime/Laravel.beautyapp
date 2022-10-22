'use strict';
var $ = jQuery.noConflict();

(function ($) {
	'use strict';

	//Menu active
	var href = location.href;
	$('.left-main-menu li a').parent().removeClass('active');
	$('.left-main-menu li a[href="' + href + '"]').parent().addClass('active');

	$('#sidebarCollapse').on('click', function () {
		$('.be-header, .left-sidebar, .main-body').toggleClass('active');
	});
	
	$(".left-sidebar").mCustomScrollbar({
		theme: "minimal"
	});

}(jQuery));

var config = {
  '.chosen-select'           : {},
  '.chosen-select-deselect'  : { allow_single_deselect: true },
  '.chosen-select-no-single' : { disable_search_threshold: 10 },
  '.chosen-select-no-results': { no_results_text: 'Oops, nothing found!' },
  '.chosen-select-rtl'       : { rtl: true },
  '.chosen-select-width'     : { width: '95%' }
}
for (var selector in config) {
  $(selector).chosen(config[selector]);
}
