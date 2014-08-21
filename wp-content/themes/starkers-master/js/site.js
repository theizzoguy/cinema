jQuery(document).ready(function($) {
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
		 
		 $( "#tabs" ).tabs();
		
		$('.now-showing').bxSlider({
		 	slideWidth: 200,minSlides:1,maxSlides:maxslides,moveslides:1,auto: true,controls: true,speed:500,pause:5000,autoHover:true, 
  			onSlideBefore: function($slideElement, oldIndex, newIndex){
  				
			}
		});
		
		$('.coming-soon').bxSlider({
		 	slideWidth: 200,minSlides:1,maxSlides:maxslides,moveslides:1,auto: true,controls: true,speed:500,pause:5000,autoHover:true, 
  			onSlideBefore: function($slideElement, oldIndex, newIndex){
  				
			}
		});
		
		$('.feature-slider').bxSlider({
		 	minSlides:1,maxSlides:1,moveSlides:1,auto: true,controls: true,speed:500,pause:10000,autoHover:true,pagerCustom: '#bx-pager',
  			onSlideBefore: function($slideElement, oldIndex, newIndex){
  				
			}
		});

	});// end document ready

