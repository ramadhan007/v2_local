// JavaScript Document

$(document).ready(function(e){
	$('.bootstrap-tagsinput').addClass('form-control');
	
	// $('.select2').select2();
	
	(function($){
		var methods = ['addClass', 'toggleClass', 'removeClass'];
		$.each(methods,function(i,method){
			var originalMethod = $.fn[method];
			$.fn[method] = function(){
				var oldClass = $(this).attr('class');
				originalMethod.apply( this, arguments );
				var newClass = $(this).attr('class');
               	this.trigger(method, [oldClass, newClass]);
                return this;
			}
    	});
    })(jQuery);
});