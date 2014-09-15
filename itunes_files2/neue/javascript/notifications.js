jQuery.noConflict();

(function($)
{
	$.fn.aetherNotifications = function(){

		var wrapper = $(this),
				notification = wrapper.find('.notification'),
				closeBtn = $('<a class="close" href="#">x</a>');

		$(document.body).find('.notification').each(function(i){
			var i = i+1;
			$(this).attr('id', 'notification_'+i);
		});
		
		notification.filter('.closeable').append(closeBtn);
		
		closeButton = notification.find('> .close');
		
		closeButton.click(function()
		{
			fadeItSlideIt( $(this).parent() );
			return false;
		});

		function slideIt(object){	
			object
			.slideUp(300);
		}
		function fadeItSlideIt(object){	
			object
			.fadeTo(300, 0, function() { slideIt(object) } );
		}		
	};
	

})(jQuery);