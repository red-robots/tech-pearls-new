/**
 *	Custom jQuery Scripts
 *	
 *	Developed by: Austin Crane	
 *	Designed by: Austin Crane
 */

jQuery(document).ready(function ($) {

	/*
        FAQ dropdowns
	__________________________________________
	*/
	$('.question').click(function() {
		var parent = $(this).parents('.faqrow');
		parent.toggleClass('collapse');
	   	$(this).next('.answer').slideToggle();
	});

	/*
	*
	*	Responsive iFrames
	*
	------------------------------------*/
	var $all_oembed_videos = $("iframe[src*='youtube']");
	
	$all_oembed_videos.each(function() {
	
		$(this).removeAttr('height').removeAttr('width').wrap( "<div class='embed-container'></div>" );
 	
 	});

 	/* Smooth Scrolling */
 	$('a[href*="#"]')
	  // Remove links that don't actually link to anything
	  .not('[href="#"]')
	  .not('[href="#0"]')
	  .click(function(event) {
	    // On-page links
	    if (
	      location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
	      && 
	      location.hostname == this.hostname
	    ) {
	      // Figure out element to scroll to
	      var target = $(this.hash);
	      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
	      // Does a scroll target exist?
	      if (target.length) {
	        // Only prevent default if animation is actually gonna happen
	        event.preventDefault();
	        $('html, body').animate({
	          scrollTop: target.offset().top
	        }, 1000, function() {
	          // Callback after animation
	          // Must change focus!
	          var $target = $(target);
	          if ($target.is(":focus")) { // Checking if the target was focused
	            return false;
	          } else {
	            $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
	          };
	        });
	      }
	    }
	  });

	
	/*
	*
	*	Colorbox
	*
	------------------------------------*/
	// $('a.cboxElement').colorbox({
	// 	rel:'gal',
	// 	maxWidth: '95%',
	// 	maxHeight: '95%'
	// });

	/*
	*
	*	Wow Animation
	*
	------------------------------------*/
	new WOW().init();


	$(document).on("click","#toggleMenu",function(){
		$(this).toggleClass('open');
		$('.mobile-navigation').toggleClass('open');
		$('body').toggleClass('open-mobile-menu');
		$('.site-header .logo').toggleClass('fixed');
		var parentdiv = $(".mobile-navigation").outerHeight();
		var mobile_nav_height = $(".mobile-main-nav").outerHeight();
		if(mobile_nav_height>parentdiv) {
			$('.mobile-navigation').addClass("overflow-height");
		}
	});

	$('#featuredTestimonial').cycle({ 
	    timeout:   10000,
	    fx:      'carousel',
	    slides: '> .testimonial-text',
	    speed: 900,
	    pager: '.pager-nav'
  	});   

	$("select#input_1_4_4").select2({
		placeholder: "Select a state...",
	    allowClear: true
	}).on("select2:unselecting", function(e) {
	    $(this).data('state', 'unselected');
	    $('input.allow-reset').val("");
	}).on("select2:open", function(e) {
	    if ($(this).data('state') === 'unselected') {
	        $(this).removeData('state'); 
	        var self = $(this);
	        setTimeout(function() {
	            self.select2('close');
	        }, 1);
	    }    
	});

	// $("#learndash_profile table .certificate_icon_large").each(function(){
	// 	var parent = $(this).parents('a');
	// 	var url = parent.attr('href');
	// 	var course_id = getUrlParameter('course_id',url);
	// 	var nonce = getUrlParameter('cert-nonce',url);
	// 	if(course_id && nonce) {
	// 		if( certURL ) {
	// 			var newLink = certURL + '?course_id=' + course_id + '&cert-nonce=' + nonce;
	// 			parent.attr('href',newLink);
	// 		} 
	// 	}

	// });

	// function getUrlParameter(name,url) {
	//     name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
	//     var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
	//     var results = regex.exec(url);
	//     return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
	// };

});// END #####################################    END