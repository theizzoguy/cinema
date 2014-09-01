jQuery(document).ready(function($) {
		$('.feature-slider').bxSlider({
		 	minSlides:1,maxSlides:1,moveSlides:1,auto: true,controls: true,speed:500,pause:10000,autoHover:true,pagerCustom: '#bx-pager'
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
     $(".tab_names" ).click(function() {
	    //mouse enter
	   
	  if($('.now-showing').hasClass('in-cinema-default')){
			  // remove it
			$('.now-showing').removeClass('in-cinema-default')
			console.log('heyy');
		  
		  }
	
	});
	/*********open external links in different tabs**********/
	  $('.social a').click(function(event){
     	  $this=$(this);
	      url = $this.attr("href");
	      window.open(url,'_blank');
	      window.open(url); 
	      event.preventDefault();
     })
       
			
});// end document ready

