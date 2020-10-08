
jQuery(function($){
	var $container = $('#masonry').imagesLoaded( function() {
	//var $container = $('.masonry');
	  $container.imagesLoaded(function(){
		    $container.masonry({
		    // options
		    columnWidth: '.post-item',
		    itemSelector: '.post-item',
		    // option that allows for your website to center in the page
		    isFitWidth: true,
		    gutter: 10  
	   });
	  });
});

// Let's start the AJAX load more function.

	var canBeLoaded = true, // this param allows to initiate the AJAX call only if necessary
	    bottomOffset = 2000; // the distance (in px) from the page bottom when you want to load more posts


 
	$(window).scroll(function(){
		var data = {
			'action': 'load_more',
			'query': portfolio_loadmore_params.posts,
			'page' : portfolio_loadmore_params.current_page
		};

		if( $(document).scrollTop() > ( $(document).height() - bottomOffset ) && canBeLoaded == true ){
			$.ajax({
				url : portfolio_loadmore_params.ajaxurl,
				data:data,
				type:'POST',
				beforeSend: function( xhr ){


					// you see, the AJAX call is in process, we shouldn't run it again until complete
					//add a preloader, should upgrade to a gif
					$("#primary").append('<div id="preloader">Loading...</div>');
					canBeLoaded = false; 

					
				},

				success:function(data){

					//remove preloader
					$("#preloader").remove();

					//check if we have posts
					if( data ) {
						
						
						var $items = $( data ); // data is the HTML of loaded posts
						
						//$('#masonry').find('article:last-of-type').after( data ); 
						$('#masonry').append( $items ).masonry( 'appended', $items );

						//reload masonry;
						$container.imagesLoaded(function(){
						      $container.masonry('reloadItems');   
						      $container.masonry('layout');
						      $container.masonry({columnWidth: '.post-item'});
						});

						canBeLoaded = true; // the ajax is completed, now we can run it again
						portfolio_loadmore_params.current_page++;		

					}

				
			}
		});
	}
});
});