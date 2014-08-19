
	jQuery(document).ready(function($) {
		var maxslides;
		var width= $(window).width();
		if(width>2500){
			
			maxSlides=12;
			
		}else if(width<2500 && width >1500){
			
			maxslides=8
		}else if(width <1500 && width >900){
			
			maxslides = 6
			
		}else{
			maxslides=3
		}
		
		$('.now-showing').bxSlider({
		 	slideWidth: 200,minSlides:1,maxSlides:maxslides,moveSlides:1,auto: true,controls: true,
  			onSlideBefore: function($slideElement, oldIndex, newIndex){
  				
			}
		});
		
	

	});// end document ready

