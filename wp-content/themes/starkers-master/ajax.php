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
	foreach ($images_cinema as $image){
		echo "<li> <img src=\"$image\"/></li>";
	}
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
	
?>