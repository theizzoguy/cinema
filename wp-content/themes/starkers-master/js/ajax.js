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
/*****filters tABS and slider*******************************************************************************/	
	$( "#tabs" ).tabs();
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
		 
	config={
	 	slideWidth: 250,minSlides:1,maxSlides:maxslides,moveSlides:1,auto: true,controls: true,speed:750,pause:6000,autoHover:true,pager:false, 
			onSlideBefore: function($slideElement, oldIndex, newIndex){
				$('.slides').removeClass('activeSlide');
				$slideElement.addClass('activeSlide');
		}
	}
	var slider = $('.now-showing').bxSlider(config);
	
	var counter=0;
	var show= false
	$('.coming-soon').css('opacity',0);
	
	$('.tab_names').click(function(event){
		$this=$(this);
		
		$class=$this.attr('id');
		if($('#now-showing').hasClass('in-cinema-default')){
			
			$('#now-showing').removeClass('in-cinema-default')
			
		}
	$('.tab_names').removeClass('in-cinema-default');
	$(this).addClass('in-cinema-default');


		// toggle off cuurent slider
		
		   if($class=='now-showing'){
				arg=1;
				show =true
				$('.now').html('genre');
				
			}else{
				arg=0;
				counter++;
				$('.soon').html('genre');
			}
		getMovies(arg,$class);
		event.preventDefault();
	})
	
	function getMovies($state,$class){
		jQuery.ajax({
		 url: MyAjax.ajaxurl,
		 type:'POST',
		 dataType: 'html',
		 data: ({action : 'main_filter',state:$state}),
		 success: function(data,state) {
		 			
		 			if(counter == 1 ){
			 			//clicked first time
			 		  slider=$('.coming-soon').bxSlider(config);
			 		  console.log('data:'+data);
					   setTimeout(function(){
				 		$('.coming-soon').css('opacity',1)
			 			
			 			},500);
			 			
			 			}else{
				 								
			 				console.log('my counter:'+counter);
			 				console.log('class is: '+$class + 'arg is: '+ $state+ ' data after 1:  '+data);
				 			$parent=$('.'+$class).parent();
				 			$parent_02=$parent.parent();
				 			$parent_03=$parent_02.parent();
				 			$parent_03.html("<ul class="+$class+"></ul")
				 			$('.'+$class).html(data);
						    slider = $('.'+$class).bxSlider(config);
					 			
			 			}
		 			
				}
		 });//end ajax
		
	}

	
		
	//get category name
	$('.filter a').click(function(event){
	    $this=$(this);
		slug=$this.attr('class');
		$str=$this.attr('name');
		$class=$str.substring(1)
		console.log($class);
		filter=$this.text();
		//console.log=($filter);	  		  
		if($class=='now-showing'){
				arg=1;
				$('.now').html(filter);
			}else{
				arg=0;
				$('.soon').html($filter);
			}
		
	getCategory(arg,slug,$class);
	//$('#'+$class).click();	
	event.preventDefault();
	})
	
	function getCategory($state,$slug,$class){
		jQuery.ajax({
		 url: MyAjax.ajaxurl,
		 type:'POST',
		 dataType: 'html',
		 data: ({action : 'cat_filter',slug:$slug,state:$state}),
		 success: function(data,state) {
		 			console.log(data);
		 			$parent=$('.'+$class).parent();
		 			$parent_02=$parent.parent();
		 			$parent_03=$parent_02.parent();
		 			$parent_03.html("<ul class="+$class+"></ul")
		 			$('.'+$class).html(data);
				    $('.'+$class).bxSlider(config);
				}
		 });//end ajax
		
	}
/********Going to Individual Page ********************************************************/
/******************************Ajax get movie info***************************************/
   
		
	
	//getting movie details
	 $('#tab1').on('click','.now-showing li',function(){
	 	//go to next page
		if($('.individual').is(":visible")){
			 	// do nothing
		 	}else{
			 	$('.feature-slider').fadeOut(100);
			 	//add class to shift movie slider up
			 	$('.movie-slider').addClass('showing-movie-slider ');
			 	$('#bx-pager').fadeOut(100);
			 	$('.individual').fadeIn('fast');
			 	
		 	}
	 	//moving to selected slide
	 	 $this=$(this);
	 	 myIndex=$this.attr('index');
		 slider.goToSlide(myIndex);
		 //getting multiple images for slider
		 slide_id=$this.attr('id');
		 console.log('slide id: '+slide_id);
		 multipleSlides(slide_id);
		 //getting movie details
		 var  movie =$this.children('img').attr('class');
		 		 
		 var movie_arr=movie.split(" ");
		 var movie_str=movie_arr.join("+");
		
		 var $url="http://www.omdbapi.com/?i=&t="+movie_str+"&plot=full";
		 $.ajax({
				url:$url,
				type: "POST",
				success: function(data,state){
					console.log(data);
					var json=eval("(" + data + ")");
					$('.movie-title h1').html(json.Title);
					$('.genre h4 span').html(json.Genre)
					$('.age-rating span').html(json.Rated)
					//$('.poster').attr('src',json.Poster);x
					$('.synopsis p span').html(json.Plot);
					//$('.director').html(json.Director);
					$('.run-time h4').html(json.Runtime)
					}
			});// end ajax
		});
	 
	  function multipleSlides($id){
			//ajax function for getting slides
		jQuery.ajax({
		 url: MyAjax.ajaxurl,
		 type:'POST',
		 dataType: 'html',
		 data: ({action : 'multiple_images',id:$id}),
		 success: function(data,state) {
		 			console.log(data);
		 			$('#multiple').html("<ul class='mult'></ul");
		 			$('.mult').html(data);
		 			$('.mult').bxSlider({
					 	minSlides:1,maxSlides:1,moveSlides:1,auto: true,controls: true,speed:500,pause:10000,autoHover:true,pager:false
			  			});
				  }// end sucess
		 });//end ajax
	
			
		}	
		
	$('.trailer').click(function(event){
		 $url=$(this).attr('name');
		 event.preventDefault();
		 jQuery.ajax({
			 url: MyAjax.ajaxurl,
			 type:'POST',
			 dataType: 'html',
			 data: ({action : 'get_movie_trailer',url:$url}),
			 success: function(data,state) {
			         console.log(data);
					$('#video').html(data);
					//$('.feature-slider').html(data);
					
					}
			 });//end ajax
		
		})// end click function	
		
	//************************calender **************/
	  //getting default date
	  var availableDates=new Array();
	  var month = new Array();
		month[0] = "January";
		month[1] = "February";
		month[2] = "March";
		month[3] = "April";
		month[4] = "May";
		month[5] = "June";
		month[6] = "July";
		month[7] = "August";
		month[8] = "September";
		month[9] = "October";
		month[10] = "November";
		month[11] = "December";
		
	 //toggle on calender			
	  $('.schedule').click(function(event){
		  	  $this=$(this);
		  	  $id=$this.attr('id');
		  	  console.log('schedule id'+ $id);
			  ajax_events($id);
			event.preventDefault();	  
		  }) 
	   //getting events	
	 function ajax_events($id){
	  jQuery.ajax({
		 url: MyAjax.ajaxurl,
		 type:'POST',
		 dataType: 'json',
		 data: ({action : 'get_my_dates',id:$id}),
		 success: function(data,state) {
		       	console.log(data)
				dates=data[0];
				times=data[1];
				availableDates=Array.prototype.slice.call(dates)
				//getting calender dates
				$('#datepicker').datepicker({ 
				beforeShowDay: available, 
				onSelect: function(){ 
					var dateObject = $('#date').datepicker('getDate'); 
					//getting date
					selected_date=get_date(dateObject)
					}//end on select
				});// end date picker
			mytimes=times.toString();
			var desired = mytimes.replace(/^"/, "");
			console.log(desired);
			setTimeout(addText(dates,desired),1000);
			}// end sucess
		 });//end ajax
		
	}// end ajax_events
	
  function addText(dates,times){
	   for (i=0;i<dates.length;i++){
		  var strDate=dates[i];
		  var dateParts = strDate.split("-");
		  var date = new Date(dateParts[2], (dateParts[1] - 1), dateParts[0]);
		  var myMonth = month[date.getMonth()];
		  var date=date.getDate();
		  $(".ui-datepicker-calendar .ui-state-default").each(function () {
              //you can get the year using below code.
              var year = $(".ui-datepicker-year").first().html();
              if ($(".ui-datepicker-month").first().html() == myMonth && $(this).html() == date){
                      //add custome text to date cel
                      $(this).html("<span class='selectedDate'>"+date+"</span>"+"<span class='time'>"+times+"</span>");
                  
                   }// end if
            
              });//end for each
		}// end for

	 }// end function
	 
  function available(date) {
  		//dmy is calender dates
		  dmy = date.getDate() + "-" + (date.getMonth()+1) + "-" + date.getFullYear();
		  
		  //finding if calender dates are with in the returned dates
		  if ($.inArray(dmy, availableDates) != -1) {
		  	//console.log('dmy is '+ dmy+' avaible dates '+ availableDates)
			return [true, "Available","Available"];
				
			} else {
			return [false,"unAvailable","unAvailable"];
			console.log('dmy is '+ dmy+' avaible dates '+ availableDates)
		  }
		}
  	
	});// end jquery function
});// end document ready