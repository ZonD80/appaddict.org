jQuery(window).load(function() {
	jQuery('.navigationContent').hide();
});

jQuery(document).ready(function() {

	jQuery('#navigation').localScroll({
		offset: -80
	});

	jQuery('#mobile-nav').localScroll({
		offset: -47
	});

	jQuery('.action').localScroll({
		offset: -80
	});

	jQuery(function() {

	  var sections = jQuery('section');
	  var navigation_links = jQuery('nav a');
	  sections.waypoint({
	    handler: function(direction) {
	    	var active_section;
			active_section = jQuery(this);
			if (direction === "up") active_section = active_section.prev();
			var active_link = jQuery('nav a[href="#' + active_section.attr("id") + '"]');
			navigation_links.removeClass("current");
			active_link.addClass("current");
	    },
	    offset: '35%'
	  });
	});

	jQuery(window).scroll(function() {
		var ss = jQuery(window).scrollTop();

		if ( ss >= 610 ) {
			jQuery('.sidephone-small').animate({
				left : 12
			}, 1200, "easeOutCubic");
		}

		if ( ss >= 910 ) {
			jQuery('.static-phone').animate({
				bottom : 0
			}, 1200, "easeOutCubic");
		}

	});

	jQuery('.gallery-bxslider').bxSlider({
		mode: 'fade',
		touchEnabled: true,
		swipeThreshold: 50,
		oneToOneTouch: true,
		pagerSelector: '#gallery-pager',
		nextText: 'next',
		prevText: 'prev',
		tickerHover: true,
		preloadImages: 'all'
	});

	jQuery(function(jQuery) {
		jQuery('body').on('click','#subscribe',function(){jQuery.ajax({'type':'POST','success':function(data) {
									
		var error = jQuery('.notification.error');
		var success = jQuery('.notification.success');
		if(data == 1) {
			success.css('opacity', 0);
			success.slideDown(300);
			success.animate({
				opacity : 1
			}, 300);
			error.hide()
		} else {
			error.css('opacity', 0);
			error.slideDown(300);
			error.animate({
				opacity : 1
			}, 300);
			success.hide()
		} 
		},
		'url':'form.php',		  
		'cache':false,
		'data':jQuery(this).parents("form").serialize()});return false;});
	});

	jQuery(document.body).aetherNotifications();

	jQuery("#top").click(function () {
		return jQuery("body,html").stop().animate({
			scrollTop: 0
		}, 800, "easeOutCubic"), !1;
	});

	jQuery('.navigationButton').click(function() {
		if(jQuery('.navigationContent').is(':hidden')) {
			jQuery('.navigationContent').show('slow');
		}
		else {
			jQuery('.navigationContent').hide('slow');
		}
	});

	jQuery('.navigationContent a').click(function(){
		jQuery('.navigationContent').hide('slow');
	});

});