jQuery(document).ready(function($) {
		$('.feature-slider').bxSlider({
		 	minSlides:1,maxSlides:1,moveSlides:1,auto: true,controls: true,speed:500,pause:10000,autoHover:true,pagerCustom: '#bx-pager',
  			onSlideBefore: function($slideElement, oldIndex, newIndex){
  				
			}
		});
		// active state
	
		$('#bx-pager a').click(function(){
			$this=$(this);
			$('#bx-pager a').removeClass('bx-pager_active')
			$('#bx-pager a').addClass('bx-pager_default')
			$this.removeClass('bx-pager_default')
			$this.addClass('bx-pager_active')

			
			});
	 /*$('#now').click(function(){
			$this=$(this);
			//$('#tabs a').removeClass('tab_default')
			$('#tabs a').addClass('.tabs_active')
			$this.removeClass('bx-pager_default')
			$this.addClass('bx-pager_active')

			
			});*/
   
			
});// end document ready

