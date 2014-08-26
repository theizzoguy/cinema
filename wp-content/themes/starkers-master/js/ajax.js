jQuery(document).ready(function($) {
$(function(){
	history.pushState("", document.title, window.location.pathname + window.location.search);
	
	/***********votes for rating*****************/
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
/*****filters tABS*******************************************************************************/	
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
	
/******************************Ajax get movie info***************************************/
    var maxslides;
	var width= $(window).width();
	if(width>2500){
		
		maxslides=12;
		
	}else if(width<2500 && width >1500){
		
		maxslides=8
	}else if(width <1500 && width >900){
		
		maxslides = 6
		
	}else{
		maxslides=3
	}
	//toogle coming soon and now showing
	 
	config={
	 	slideWidth: 200,minSlides:1,maxSlides:maxslides,moveSlides:1,auto: true,controls: true,speed:750,pause:6000,autoHover:true,pager:false, 
			onSlideBefore: function($slideElement, oldIndex, newIndex){
				
		}
	}
	var slider=$('.now-showing').bxSlider(config);
	var $comingSoon=$('.coming-soon').bxSlider(config);
		//going to next slide on click		
	
//getting movie details
 $('#tab1').on('click','.now-showing li',function(){
 	//moving to seletcted slide
 	 $this=$(this);
 	 myIndex=$this.attr('index');
	 slider.goToSlide(myIndex);
	 //getting movie details
	 var  movie =$this.children('img').attr('class');
	 console.log(movie);
	 var movie_arr=movie.split(" ");
	 var movie_str=movie_arr.join("+");
	 var $url="http://www.omdbapi.com/?i=&t="+movie_str;
	
	 $.ajax({
			url:$url,
			type: "POST",
			success: function(data,state){
				alert(state)
				console.log(data);
				var json=eval("(" + data + ")");
				
				//$('.title').html(json.Title);
				//$('.poster').attr('src',json.Poster);
				//$('.plot').html(json.Plot);
				//$('.director').html(json.Director);
				}
		});// end ajax
});

		
	/*	$('.bxslider').on('click','.trailer', function(){
		 $trailer=$(this).children('a');
		 $url=$trailer.attr('class');
		     alert($url);
			 jQuery.ajax({
			 url: MyAjax.ajaxurl,
			 type:'POST',
			 dataType: 'html',
			 data: ({action : 'get_movie_trailer',url:$url}),
			 beforeSend: function() {
				 $('.loader').show();
			  },
			  complete: function(){
				 $('.loader').hide();
			  },
			success: function(data,state) {
					alert(state);
					$('.movie_content').show();
					$('.movie_details').html(data);
					
					}
			 });//end ajax
		
		})	*/
		
	
	

	
	});// end jquery function
});// end document ready