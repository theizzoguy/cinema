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
//function to check if mobile money sms are up

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
	
	global $wpdb;
	//putting number to send confirmation to in  the db plus the reason, for use by sms gate way
	$result=$wpdb->insert( 
	'Ratings', 
	array( 
		'movie' =>$movie, 'rating' =>$rating,'IP'=>$ip
	), 
	array( 
		'%s', '%s','%s','%d') 
		);	
	if(!$result){
		echo"ERROR INSERTING VALUES IN SEND CONFIRMATION";
		}else{
			
			echo "sucesssss";
		}
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
	
	die(); 
	}
add_action("wp_ajax_ratings", "ratings");

	
	
?>