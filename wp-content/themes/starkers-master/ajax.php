<?php
//getting the admin-ajax script that handles ajax staf
date_default_timezone_set('Africa/Nairobi');
require("phpqrcode/phpqrcode.php");
require("PHPMailer-master/class.phpmailer.php"); 

function my_ajax_function(){
		wp_enqueue_script( 'jquery' );	
		wp_enqueue_script( 'script-name', get_template_directory_uri() .'/js/ajax.js', array('jquery'));
 	 	wp_localize_script( 'script-name', 'MyAjax', array('ajaxurl' => admin_url( 'admin-ajax.php' )));
 	}
add_action( 'wp_enqueue_scripts', 'my_ajax_function' );
//function for voting / rating
function ratings(){
  $movie=$_POST['movie'];
  $rating=$_POST['rating'];
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		    $ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		    $ip = $_SERVER['REMOTE_ADDR'];
		}
	$table='Ratings';
	global $wpdb;
	//check if movie exist in table
	$query="select ID from $table where movie='$movie'";
	$check= $wpdb->get_row($query ,ARRAY_A);
	if($check!=null){
		#update that entry
		$results=$wpdb->update( 
						$table, 
						array( 
							'movie' =>$movie,	// string
							'rating' =>$rating,	//
							'IP'=>$ip  
						), 
						array('movie'=>$movie),
						array( '%s','%d','%s' )
						);
		
	}else{
		#insert movie entry
		$result=$wpdb->insert( 
		$table, 
		array( 
			'movie' =>$movie, 'rating' =>$rating,'IP'=>$ip
		), 
		array( 
			'%s','%d','%s') 
			);	
		if(!$result){
			echo"ERROR INSERTING VALUES ratings table";
			}else{
				
				echo "sucesssss";
			}
	}//end else
 die(); 
}
add_action("wp_ajax_ratings", "ratings");
/**********************multiple images***********************/
function multiple_images(){
	$mult_id=$_POST['id'];
	$img_a= get_post_meta($mult_id, '_cmb_multiple_images', true );
	//getting youtube url movie_text
    $url= get_post_meta($mult_id, 'movie_text', true );
    if(!empty($img_a)):
	    foreach($img_a as $img):
	         echo"<li class='vignette' ><img id='bkg' src=\"$img\" name=\"$url\"/></li>";
	         endforeach;
	 endif;
  die();
	}
add_action("wp_ajax_multiple_images", "multiple_images");


/******************coming soon and now filters**************/

//function for fetching content by category
function main_filter(){
	$state=$_POST['state'];
	$type = 'movie';
	$args=array('post_type' => $type,'post_status' => 'publish','posts_per_page' => -1,'ignore_sticky_posts"'=> 1);
	global $post;
	$movie= new WP_Query($args);
	if( $movie->have_posts() ) {
		while ($movie->have_posts()) : $movie->the_post(); 
			 $ids[]=get_the_ID();
			 $name= get_the_title();	
			 $names_arr[]=$name;
			 //getting cinema room
			 $cinema= get_post_meta( get_the_ID(), 'movie_select', true );
			 $cinema_arr[]=$cinema;
			//getting movie start date
			 $start= get_post_meta( get_the_ID(), 'movie_start_date', true );
			 $startdate[]=$start;
			 //getting end date
			 $end= get_post_meta( get_the_ID(), 'movie_end_date', true );
			 $enddate[]=$end;
			 //getting youtube url movie_text
			 $url= get_post_meta( get_the_ID(), 'movie_text', true );
			 $link[]=$url;
			 $slide_img= get_post_meta( get_the_ID(), '_cmb_slider_image', true );
			 $slider_imgs[]=$slide_img;
			 //getting movie times
			  $data= get_post_meta( get_the_ID(), 'movie_repeatable', true );
				foreach ($data as $val){
						foreach($val as $val2){
							$time_array[] = $val2;
						 }
					}
		endwhile;
		}// end if
		wp_reset_query();  
		$array=now_comingSoon($names_arr,$enddate,$startdate,$slider_imgs);
			if($state==1){
				#in cinema_now
				$images_cinema=$array[0];
			}else{
				#in cinema later
				$images_cinema=$array[1];
			}
		#loop through the images
		foreach ($images_cinema as $key=>$image){
			//echo "<li> <img src=\"$image\"/></li>";
			echo"<li index=\"$key\" id=\"$ids[$key]\" class='slides myslide$key' ><img  src=\"$image\" class=\" $names_arr[$key]\"  /></li>";
		}
	die();
	}
add_action("wp_ajax_main_filter", "main_filter");

//function for fetching content by category
function cat_filter(){
	$slug=$_POST['slug'];
	$state=$_POST['state'];
	$args = array(
		    'post_type' => 'movie',
		    'posts_per_page' => -1,
		    'tax_query' => array(
		        array(
		            'taxonomy' => 'movie_category',
		            'field' => 'slug',
		            'terms' => $slug,
			        )
			     )
			);
		  query_posts( $args );
		   // Loop through categories
		  while ( have_posts() ) : the_post();
		  		$ids[]=get_the_ID();
			  	$name= get_the_title();
			  	$names[]=$name;
			    $start= get_post_meta( get_the_ID(), 'movie_start_date', true );
				$startdate_cat[]=$start;
				//getting end date
				$end= get_post_meta( get_the_ID(), 'movie_end_date', true );
				$enddate_cat[]=$end;
				$cinema= get_post_meta( get_the_ID(), 'movie_select', true );
			    //echo $cinema."<br>";
			    $slide_img= get_post_meta( get_the_ID(), '_cmb_slider_image', true );
				$slider_cat[]=$slide_img;
			    #echo"<img src=\"$slide_img\">";
			endwhile;
			
		$arr=now_comingSoon($names,$enddate_cat,$startdate_cat,$slider_cat);
		if($state==1){
			#in cinema_now
			$images_cinema=$arr[0];
		}else{
			$images_cinema=$arr[1];
		}
	#loop through the images
	foreach ($images_cinema as $key=>$image){
		echo"<li index=\"$key\" id=\"$ids[$key]\" class='slides myslide$key' ><img  src=\"$image\" class=\"$names[$key]\"  /></li>";

		//echo "<li> <img src=\"$image\"/></li>";
	}
	wp_reset_query();  
	die();
	}
add_action("wp_ajax_cat_filter", "cat_filter");

function now_comingSoon($movie_names,$end_arr,$start_arr,$slider){
	    $currentdate=date('Y-m-d');
	    $showing_src=array();
	    $coming_soon_src=array();
	    foreach( $movie_names as $key=> $mymovie){
		        $myend=$end_arr[$key];
				// all movies that are showing now and coming soon, end date is greater than current date
				//Now showing movie first show date is equal or greater than current date
				if($myend >= $currentdate){
				  //number of days to first shw date
				    $start=$start_arr[$key];
					$current = strtotime($currentdate); 
					$start_date = strtotime($start);
					#$showtime[]=$time_array[$key];
					// comming soon , first show date is greater than current date
					if($start>$currentdate){
							$days_between = ceil(abs($start_date - $current) / 86400); //using cieling to round off
							$coming_soon[]=$mymovie;
							$coming_soon_src[]=$slider[$key];
							}else{
								//echo "already showing";
							$showing_movie[]=$mymovie;
							#$show_times[]=$time_array[$key];
							$showing_src[]=$slider[$key];
							 }
					//getting movie src for movies that are valid
					$movie_arr[]=$mymovie;
					$movie_name=$mymovie;
				  //remove spaces between movie names 
					$movie=str_replace(" ","",$mymovie);
					    //$date=$startdate[$key];
					
				}//end if
			}//end for
	$array=array($showing_src,$coming_soon_src);
	return $array;
	
}

/**************Movie trailer ***************/
function get_movie_trailer(){
	//get trailer url
	$url=$_POST['url'];
	if(!empty($url)){
		$embed_code = wp_oembed_get($url,array('width'=>500)); 	
		echo $embed_code;
		
		}
		
	die();
	}		
add_action("wp_ajax_get_movie_trailer", "get_movie_trailer");
/********Movie time date/ event ***************************/
function get_my_dates(){
		$id=$_POST['id'];
		//echo "id from php: $id";
		movie_event($id);
		 
		die(); 
		}
	//add_action("wp_ajax_nopriv_get_my_dates", "get_my_dates");
	add_action("wp_ajax_get_my_dates", "get_my_dates");
// function for getting dates and time of a particular movie
function movie_event($id){
		$cinema= get_post_meta( $id, 'movie_select', true );
		
 		//getting movie start date
 		$start= get_post_meta( $id, 'movie_start_date', true ); //2014 07 10  , 2014 07 15
 		//echo 'start date is '.$start;
 		//getting end date
 		$end= get_post_meta( $id, 'movie_end_date', true );
 		//echo 'end date is'.$end;
		// getting in between dates
		$dates_arr=dateRange($start,$end);
		//getting movie times
		$times=movie_times($id);
		$events=array($dates_arr,$times);
		echo json_encode($events);
		
	}// end function
// date range function
function dateRange($first, $last, $step = '+1 day', $format = 'j-n-Y' ) { 
	$dates = array();
    $current = strtotime($first);
    $last = strtotime($last);
	while( $current <= $last ) { 
		$dates[] = date($format, $current);
    	 $current = strtotime($step, $current);
    }
	//eliminating dates that are past todays date
	$today=date('Y-m-d');
	foreach($dates as $mydate){
		$date= date('Y-m-d',strtotime($mydate));
		if($today <= $date){
			$new_dates[]=$mydate;
			}
	}// end for
 	return $new_dates;
		
}	
 function movie_times($id){
	//getting movie times
	$data= get_post_meta( $id, 'movie_repeatable', true );
	foreach ($data as $val){
	    foreach($val as $val2){
						$time_array[] = $val2;
						 }//end inner for loop
				}// end outer for loop
       return $time_array;
		}// end function	
		
	
?>