<?php
/*
 * Template Name: Home page
 * Description: A Page Template For the home page.
 */
?>
<?php if ( have_posts() ): ?>
<?php while ( have_posts() ) : the_post(); 
	$args = array( 'post_type' => 'attachment',  'numberposts' => -1,  'post_status' => null, 'post_parent' => $post->ID );
    $attachments = get_posts( $args );
     if ( $attachments ) {
    	foreach ( $attachments as $attachment ) {
        $bck=$attachment->guid;
        #echo 'seats : '.$bck;
			}
     }
endwhile; 
 endif; ?>
<?php
$type = 'movie';
$args=array('post_type' => $type,'post_status' => 'publish','posts_per_page' => -1,'ignore_sticky_posts"'=> 1);
global $post;
$movie= new WP_Query($args);
if( $movie->have_posts() ) {
# getting categoryslugs
	$terms= get_terms('movie_category');
		if(!empty($terms)):
		   $cats_array=array();
		   foreach($terms as $term):
		   		$cats_array[]=$term->name;
		   		//echo "<br>".$term->name."<br>";
		   		endforeach;
		   		endif;


while ($movie->have_posts()) : $movie->the_post(); 
 $ids[]=get_the_ID();
 $name= get_the_title();	
 $movie_names[]=$name;
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
  #print_r($url);
  
  $slide_img= get_post_meta( get_the_ID(), '_cmb_slider_image', true );
  $slider[]=$slide_img;
  #print_r($slide_img);
  
 $img_a= get_post_meta( get_the_ID(), '_cmb_multiple_images', true );
    foreach($img_a as $img):
         $cmb_multiple[]=$img;
         #print_r($img);
         endforeach;
         
  $cap= get_post_meta( get_the_ID(), '_cmb_ cap_text', true );
  $caption[]=$cap;
   #print_r($cap);
 $img_b= get_post_meta( get_the_ID(), '_cmb_featured_image', true );
 $cmb_featured[]=$img_b;
 #print_r($img_b);
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

//find current date for front end
	$today= date("j M Y");
   // changing date format, and getting intial date, which is todays date
	//$new_default_date = date("j M Y", strtotime($today)); 
	$dateparts=explode(" ",$today);
	$day=$dateparts[0];
	$month=$dateparts[1];
	$year=$dateparts[2];
	$time=date("h:i");
	// intial dates and time	
	if(($day)<10){
		$day='0'.$day;
		}

?>

<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>

	
	<?php
		//echo '<ul class="test">';
			$i=0;
			$currentdate=date('Y-m-d');
			foreach( $movie_names as $key=> $mymovie){
		
				$myend=$enddate[$key];
				// all movies that are showing now and coming soon, end date is greater than current date
				//Now showing movie first show date is equal or greater than current date
				if($myend>$currentdate){
				  //number of days to first shw date
					$start=$startdate[$key];
					$current = strtotime($currentdate); 
					$start_date = strtotime($start);
					// comming soon , first show date is greater than current date
						if($start>$currentdate){
							$days_between = ceil(abs($start_date - $current) / 86400); //using cieling to round off
							//echo 'days left too show: '.$days_between;
							}else{
								//echo "already showing";
							}
							
					//getting movie src
					$src=$slider[$key];
					$src2=$cmb_featured[$key];
					//echo '<li>'.$src.'</li>';
					//echo"<li><img src=\"$src\"></li>";
					//echo"<li><img src=\"$src2\"></li>";
					//getting movie names
					$movie_name=$mymovie;
					//echo $movie_name;
					//remove spaces between movie names 
					$movie=str_replace(" ","",$mymovie);
					    //$date=$startdate[$key];
					$i++;
				}//end if
			}//end for
	//echo'</ul>';	
	
		#getting by category
		#get category names
		#pass them as arguments
		print_r($cats_array);
		
		foreach($cats_array as $slug):
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

				// The Loop
				while ( have_posts() ) : the_post();
				  
				    the_title();
				    $start= get_post_meta( get_the_ID(), 'movie_start_date', true );
					$startdate[]=$start;
					//getting end date
					$end= get_post_meta( get_the_ID(), 'movie_end_date', true );
					$enddate[]=$end;
					$cinema= get_post_meta( get_the_ID(), 'movie_select', true );
				    //echo $cinema."<br>";

				    
				endwhile;
			endforeach;
			
	?>
	
	
<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer') ); ?>

