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
endif; 
wp_reset_query();  
 ?>
<?php
$type = 'featured';
$args=array('post_type' => $type,'post_status' => 'publish','posts_per_page' => -1,'ignore_sticky_posts"'=> 1);
global $post;
$featured= new WP_Query($args);
if( $featured->have_posts() ) {
while ($featured->have_posts()) : $featured->the_post(); 
 $f_name= get_the_title();	
 $featured_name[]=$f_name;
 //getting cinema room
 $grp= get_post_meta( get_the_ID(), '_featured_repeat_group', true );
 $data= get_post_meta( get_the_ID(), 'movie_repeatable', true );

 foreach ($grp as $grp_val){
	$caption[]=$grp_val['_featured_caption'];
	$copy[]=$grp_val['_featured_copy'];
	$tab_name[]=$grp_val['tab'];
	$featured_images[]=$grp_val['image'];
	$buttons[]=$grp_val['_featured_icons'];
	#print_r($grp_val);
	}
endwhile;
}// end if
wp_reset_query(); 

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
<?php
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
							
							}else{
								//echo "already showing";
							}
					//getting movie src for movies that are valid
					$valid_src[]=$slider[$key];
					$movie_arr[]=$mymovie;
					$movie_name=$mymovie;
				  //remove spaces between movie names 
					$movie=str_replace(" ","",$mymovie);
					    //$date=$startdate[$key];
					$i++;
				}//end if
			}//end for
	//echo'</ul>';	
	
#getting by category, category slugs are got from a function above
	#get category names
	#pass them as arguments
	#print_r($cats_array);
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
		   // The Loop through categories
			while ( have_posts() ) : the_post();
			  
			    #the_title();
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
		endforeach;
#generating dates in between function

function MydateRange($first, $last) { 
	$step = '+1 day';
	$format = 'l.d.F.Y'; 
	$dates = array();
    $current = strtotime($first);
    $last = strtotime($last);
    while( $current <= $last ) { 
          $dates[] = date($format, $current);
         $current = strtotime($step, $current);
    }
    #print_r($dates);
    return $dates;
}
#get day month and date for each date
function fndate($date_array){
	foreach($date_array as $mydate):
		$date=explode('.',$mydate);
		$day=$date[0];
		$date_numeral=$date[1];
		$date_text=$date[2];
		$year=$date[3];
		
	endforeach;
	
}
#testing
$arr=MydateRange($startdate_cat[0],$enddate_cat[0]);
fndate($arr);
	?>
<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>
<div class="container">
			<ul class="header">
				<li class="menu">
					<ul>
						<li><a href="#">home</a></li>
						<li><a href="#">what's showing</a></li>
						<li><a href="#">community</a></li>
					</ul>
				</li>
				<li class="logo clearfix"><a href="#">logo</a></li>
				<li class="quick-buy">
					<a href="#">quick buy</a>
					<ul>
						<li class="what-movie"><a href="#">what movie would you like to watch?</a>
							<ul>
								<li><a href="#">Dawn of the Planet Of The Apes</a></li>
								<li><a href="#">Xmen: Days Of Future Past</a></li>
								<li><a href="#">22 Jump Street</a></li>
								<li><a href="#">Think Like a Man too</a></li>
								<li><a href="#">Step Up 3D</a></li>
							</ul>
						</li>
						<li class="when"><a href="#">when exactly?</a>
							<ul>
								<li><a href="#">13 August</a></li>
								<li><a href="#">14 August</a></li>
								<li><a href="#">15 August</a></li>
								<li><a href="#">16 August</a></li>
								<li><a href="#">17 August</a></li>
								<li><a href="#">18 August</a></li>
								<li><a href="#">19 August</a></li>
								<li><a href="#">20 August</a></li>
							</ul>
						</li>
						<li class="what-time"><a href="#">at what time?</a>
							<ul>
								<li><a href="#">11:30am</a></li>
								<li><a href="#">11:30am</a></li>
								<li><a href="#">11:30am</a></li>
								<li><a href="#">11:30am</a></li>
								<li><a href="#">11:30am</a></li>
							</ul>
						</li>
						<li class="adults"><a href="#">1 adult</a>
							<ul>
								<li><a href="#">2</a></li>
								<li><a href="#">3</a></li>
								<li><a href="#">4</a></li>
								<li><a href="#">5</a></li>
								<li><a href="#">6</a></li>
								<li><a href="#">7</a></li>
								<li><a href="#">8</a></li>
								<li><a href="#">9</a></li>
							</ul>
						</li>
						<li class="kids"><a href="#">0 kids</a>
							<ul>
								<li><a href="#">1</a></li>
								<li><a href="#">2</a></li>
								<li><a href="#">3</a></li>
								<li><a href="#">4</a></li>
								<li><a href="#">5</a></li>
								<li><a href="#">6</a></li>
								<li><a href="#">7</a></li>
								<li><a href="#">8</a></li>
								<li><a href="#">9</a></li>
							</ul>
						</li>
						<li class="buy-now"><a href="#">buy ticket</a></li>
					</ul>
				</li>
				<li class="sign-up clearfix">
					<ul>
						<li><a href="#">sign up</a></li>
						<li><a href="#" class="sign-in">sign in</a></li>
					</ul>
				</li>
				<li class="search"></li>
			</ul>
			<ul class="feature-slider">
				<?php
				foreach($featured_images as $key=>$f_image):
					
					echo "<li class='hottest'>";
					echo "<div class='gradient-overlay'><img id='bkg' src=\"$f_image\"/></div>";
					echo "<ul>
						  <li class='caption'>
							<h1>the apes take top spot in the cinema</h1>
							<h2>come find out for yourself why this is the hottest this summers' blockbusters</h2>
						</li>
						<li class='buttons'>
							<a href=''><span></span>buy tickets</a>
							<a href=''><span></span>buy tickets</a>
						</li>
					</ul>
					<div class='notification'></div>";
					echo "</li>";	 
					endforeach
				?>
			</ul>
			<div class="movie-slider">
				<div class="highlight-slider"></div>
				<div id="tabs">
					<ul>
						<li>
							<a href="#tab1">in cinema now</a>
							<ul class="filter">
								<li><a href="#"></a></li>
								<li><a href="#"></a></li>
								<li><a href="#"></a></li>
								<li><a href="#"></a></li>
								<li><a href="#"></a></li>
							</ul>
						</li><li>
							<a href="#tab2">coming soon</a>
							<ul class="filter">
								<li><a href="#"></a></li>
								<li><a href="#"></a></li>
								<li><a href="#"></a></li>
								<li><a href="#"></a></li>
								<li><a href="#"></a></li>
							</ul>
						</li>
					</ul>
					<div id="tab1">
						<ul class="now-showing">
						<?php
							#getting all images
							foreach ($valid_src as $src):
								echo"<li> <img src=\"$src\"/></li>";
								endforeach; 
							?>
						</ul>
							
					</div>
					<div id="tab2">
						<ul class="coming-soon">
							<li>
								<img>
								<button></button>
							</li>
							<li>
								<img>
								<button></button>
							</li>
							<li>
								<img>
								<button></button>
							</li>
							<li>
								<img>
								<button></button>
							</li>
							<li>
								<img>
								<button></button>
							</li>
							
						</ul>
					</div>
				</div>
				
			</div>
			<div class="community"></div>
		</div>
</body>
</html>
<?php #Starkers_Utilities::get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer') ); ?>
