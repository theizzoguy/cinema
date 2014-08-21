jQuery(document).ready(function($) {
$(function(){
	history.pushState("", document.title, window.location.pathname + window.location.search);
	
	//votes click handler
	$('.count').click(function(event){
		var $val=$(this).text();
		var $new_val=parseInt($val) +1;
		$movie=$(this).attr('name');
		//insert votes in html
		$('.count').html($new_val)
		// remove class to disable multiple 
		$(this).removeClass('count');
		send_vote($movie,$new_val)	
		event.preventDefault();
		});
	
	function send_vote($rated_movie,$votes){
	  jQuery.ajax({
		 url: MyAjax.ajaxurl,
		 type:'POST',
		 dataType:'html',
		 data: ({action : 'ratings',movie:$rated_movie,rating:$votes}),
		 success: function(data,state) {
				console.log(data[0]);
				// putting html 
				
				
				}
		 });//end ajax
		}// end send vote
	
	 $( "#tabs" ).tabs();
		
	//get category name
	$('.filter a').click(function(){
		slug=$(this).attr('class');
		$class=$(this).attr('name');
		if($class=='now-showing'){
				arg=1;
			}else{
				arg=0;
			}
		console.log(slug);
	getCategory(arg,slug,$class);
	})
	
	function getCategory($state,$slug,$class){
		jQuery.ajax({
		 url: MyAjax.ajaxurl,
		 type:'POST',
		 dataType: 'html',
		 data: ({action : 'cat_filter',slug:$slug,state:$state}),
		 success: function(data,state) {
		 			console.log(data);
				    $('.'+$class).html(data);
				}
		 });//end ajax
		
	}
		
	});// end jquery function
});// end document ready