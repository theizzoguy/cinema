jQuery(document).ready(function($) {
		$('.feature-slider').bxSlider({
		 	minSlides:1,maxSlides:1,moveSlides:1,auto: true,controls: true,speed:500,pause:10000,autoHover:true,pagerCustom: '#bx-pager',
  			onSlideBefore: function($slideElement, oldIndex, newIndex){
  				
			}
		});
   
			
});// end document ready

