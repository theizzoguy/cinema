jQuery(document).ready(function($) {
$(function(){
	history.pushState("", document.title, window.location.pathname + window.location.search);
	

	$('.count').click(function(event){
		alert('sdsd');
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
				console.log(data);
				}
		 });//end ajax
		}// end ajax_cats function	
		
	});// end jquery function
});// end document ready