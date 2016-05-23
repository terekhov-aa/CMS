;(function ($) {
	"use strict";
	$.fn.firstplugin = function (arg) {
		var target = this;
		// console.log(this);
		// console.log(arg);
		// console.log('$.fn.plugincontrol');	
        $(target).empty();               
			var output = Mustache.render('<div>{{qwer}} is a  {{qwer}}</div>', arg); 
		$.each(arg,function(index, value){			
			// console.log(Mustache);
			// console.log(value);
		});
			$(target).append(output);
	}
})(jQuery);