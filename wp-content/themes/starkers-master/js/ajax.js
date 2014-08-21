jQuery(document).ready(function($) {
$(function(){
	history.pushState("", document.title, window.location.pathname + window.location.search);
	

	$('.count').click(function(){
		var $val=$(this).text();
		var $new_val=$val +1;
		$rated_movie=$(this).attr('name');
		// remove class to disable multiple 
		$(this).removeClass('count');
		$votes=$new_val
		send_vote($rated_movie,$votes)	
		});
	
	function send_vote($rated_movie,$votes){
	  jQuery.ajax({
		 url: MyAjax.ajaxurl,
		 type:'POST',
		 dataType:'json',
		 data: ({action : 'ratings',movie:$rated_movie,rating:$votes}),
		 success: function(data,state) {
				console.log(data);
				}
		 });//end ajax
		}// end ajax_cats function	
		
	});// end jquery function
});// end document ready