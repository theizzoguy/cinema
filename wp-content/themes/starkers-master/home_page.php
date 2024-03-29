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
$args=array('post_type' => $type,'post_status' => 'publish','posts_per_page' =>1,'ignore_sticky_posts"'=> 1);
global $post;
$featured= new WP_Query($args);
if( $featured->have_posts() ) {
while ($featured->have_posts()) : $featured->the_post(); 
 $f_ids[]=get_the_ID();
 //getting cinema room
 $grp= get_post_meta( get_the_ID(), '_featured_repeat_group', true );
 #print_r($grp);
 foreach ($grp  as $grp_val){
	$caption[]=$grp_val['_featured_caption'];
	if(isset($grp_val['movie'])){
		$featured_name[]=$grp_val['movie'];
	
	}else{
		$featured_name[]=' ';
	}
	$copy[]=$grp_val['_featured_copy'];
	$tab_name[]=$grp_val['tab'];
	$featured_images[]=$grp_val['image'];
	$buttons[]=$grp_val['_featured_icons'];
	
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
				if($myend >= $currentdate){
				  //number of days to first shw date
					$start=$startdate[$key];
					$current = strtotime($currentdate); 
					$start_date = strtotime($start);
					$showtime[]=$time_array[$key];
					// comming soon , first show date is greater than current date
					if($start>$currentdate){
							$coming_soon_ids[]=$ids[$key];
							$days_between = ceil(abs($start_date - $current) / 86400); //using cieling to round off
							$coming_soon[]=$mymovie;
							$coming_soon_src[]=$slider[$key];
							$str=preg_replace('/[^A-Za-z0-9\-]/', '',$mymovie); 
							$string=strtolower($str );
							$coming_soon_clean[]=$string;
							}else{
								//echo "already showing";
							$showing_ids[]=$ids[$key];	
							$showing_movie[]=$mymovie;
							$str=preg_replace('/[^A-Za-z0-9\-]/', '',$mymovie); 
							$string=strtolower($str );
							$showing_clean[]=$string;
							$show_times[]=$time_array[$key];
							$showing_src[]=$slider[$key];
							 }
					//getting movie src for movies that are valid
					$movie_arr[]=$mymovie;
					$movie_name=$mymovie;
				   //remove spaces between movie names 
					$str=preg_replace('/[^A-Za-z0-9\-]/', '',$mymovie); 
					$string=strtolower($str );
					$valid_movies_clean[]=$string;
					    //$date=$startdate[$key];
					$i++;
				}//end if
			}//end for
	//echo'</ul>';	

#print_r($valid_movies_clean);
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
#getting movie rating /votes
function Getratings($movie_arg){
		global $wpdb;
		
		$table='Ratings';
		$query="select rating from $table where movie='$movie_arg'";
		$results = $wpdb->get_row($query ,ARRAY_A);
		if($results == null){			
			 $votes=0;
			}else{
			
			 $votes=$results['rating'];
		}
	
	return $votes;
}
#testing
$arr=MydateRange($startdate_cat[0],$enddate_cat[0]);
fndate($arr);
	?>
<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>

<div class="container">
	<ul class="header">
				<li class="menu">
					<ul>
						<li><a href="#" id='home'>home</a></li>
						<li><a href="#" id='whatsShowing'>what's showing</a></li>
						<li><a href="#" style="display:none">community</a></li>
					</ul>
				</li>
				<li class="logo clearfix"><a href="#" id='logo'>logo</a></li>
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
						<li class="buy-now">
							<a href="">buy ticket</a>
						</li>
					</ul>
				</li>
				<li class="sign-up clearfix" style="opacity:0" >
					<ul>
						<li><a href="#">sign-up</a></li>
						<li class="sign-in"><a href="#">sign in</a>
					</li></ul>
				</li>
				<li class="search" style="opacity:0" >
					<form method="post" action="/search" id="search" >
						<input name="search movies" type="text" size="40" placeholder="Search movies..."/>
					</form>
				</li>
			</ul><!-- end quick dropdown-->
		<div class='individual'>	
			<ul class="individual-movie-slider clearfix">
				<li class="screenshot-slider">
					<ul class="screenshots">
						<li>
							<div id='multiple'>
								<ul class="mult">
									<li  class="vignette"><img id="bkg" src="http://localhost/cinema/wp-content/uploads/2014/08/xmn-ss1.jpg"/></li>
								</ul>	
							</div>
						</li>
					</ul>			
				</li>
				<li class="movie-info">
					<ul>
						<li class="movie-title"><h1></h1></li>
						<li class="star-rating"><h4>Rating:<span class='stars'></span></h4></li>
						<li class="genre"><h4>genre:<span></span></h4></li>
						<li class="run-time"><h4>run time:<span></span></h4></li>
						<li class="age-rating"><span>pg-13</span></li>
						<li class="synopsis"><p><h4>synopsis</h4><span></span></p></li>
						<li class="buttons">
							<ul>
								<!--<li class="buy"><a href="#"><span></span>buy ticket</a></li>-->
								<li class="trailer"><a href="#"><span></span>trailer</a></li>
							</ul>
						</li>
					</ul>
				</li>
				
			</ul><!--end individual -->
		</div>
			<ul class="feature-slider clearfix">
				<?php
				#check if post is a movie and is currently showing
				foreach ($featured_name as $key=>$f_name){
					$str=preg_replace('/[^A-Za-z0-9\-]/', '',$f_name); 
					//echo "cleaning ...$str...";
					$featured_clean_str=strtolower($str );
					//echo "cleaned ...$featured_clean_str...";
					
				 if(in_array($featured_clean_str,$valid_movies_clean)){
						#get show times and date
						$array=array_unique($show_times);
						$times=implode(" ",$array);
						$k = array_search($featured_clean_str,$valid_movies_clean);
						//echo "..keys $k...end";
						if(!$k){
							$movie_url=null;
						    $fId[]=null;
						    $movie_url_f[]=null;
					        $featured_clean[]=null;
					        $thumb[]=null;
					   	
						}else{
							
							$movie_url=$link[$k];
							$fId[]=$ids[$k];
							$movie_url_f[]=$movie_url;
							$featured_clean[]=$featured_clean_str;
							$thumb[]=$slider[$k];

						}
											   	
					}// end if
					
				}// end for each
		//print_r($f_ids);
		#print_r($featured_name);	
		foreach($featured_images as $key=>$f_image):
					$mycopy=$copy[$key];
					$mycaption=$caption[$key];
					$mybuttons=$buttons[$key];
					$mytab_name=$tab_name[$key];
					$featured_trailer=$movie_url_f[$key];
					$mymovie_name=$featured_clean[$key];
					echo "<li class='hottest'>";
					echo "<div class='gradient-overlay'><img id='bkg' src=\"$f_image\"/></div>";
					echo "<ul>
							<li class='caption'>
							<h1>$mycaption</h1>";
							echo"<h2>$mycopy</h2>";
							echo "
							<ul>
								<li class='buttons buttons_$key'>";
							
							if(!empty($mybuttons)):
								foreach ($mybuttons as $button):
									if($button=='schedule'){
										//$currentdate=date('Y-m-d');
										echo "<a href='' id=\"$fId[$key]\" name='$featured_name[$key]' cleanName='$mymovie_name' thumb='$thumb[$key]' class=\"$button\"><span></span>$button</a>";
										
										}else if($button=='vote'){
											$rating=Getratings($mymovie_name);
											
											echo "<a href='' class=\"$button\"><span class='count pixels' name='$mymovie_name'></span></a>";
											
											}
											else if($button=='trailer'){
												echo "<a href='' class=\"$button\" name=\"$featured_trailer\"><span></span>$button</a>";

												}
												else{
													echo "<a href='' class=\"$button\"><span></span>$button</a>";
													}
										endforeach;// getting buttons 
						endif;
				echo"</li></ul></li></ul>";
			echo"</li>";// end hottest li
			endforeach;//for fetching images
				?>
			</ul><!-- feature slider-->
			<div id="bx-pager">
			<?php
			foreach ($tab_name as $key=>$tab):
			   echo "<a class='bx-pager_default' data-slide-index=\"$key\" href='#'>$tab</a>";
			endforeach;
			?>
			</div>
			<div class="movie-slider clearfix">
				<div class="highlight-slider"></div>
				<div id="tabs">
					<ul>
						<li id="in-cinema-now" >
							<a href="#tab1" class='tab_names in-cinema-default' id='now-showing'>in cinema now</a>
							<ul class="now-filter now-default">
								<li class="now-filter-genre">
									<a href="#" class="genre" name='#now-showing'>genre</a>
									<ul class="filter">
									<?php foreach($cats_array as $slug):
											echo"<li><a href='#' class='$slug' name='#now-showing'>$slug</a></li>";
											endforeach;	
									   ?>
										
									</ul>
								</li>
							</ul>
						</li>
						<li id="coming-soon">
							<a  href="#tab2" class='tab_names' id='coming-soon'>coming soon</a>
							<ul class="soon-filter">
								<li class="soon-filter-genre">
									<a  href="#" class="genre"  name='#coming-soon'>genre</a>
									<ul class="filter">
									<?php foreach($cats_array as $slug):
											echo"<li><a href='#' class='$slug' name='#coming-soon' >$slug</a></li>";
											endforeach;	
									   ?>
										
									</ul>
								</li>
							</ul>
						</li>
					</ul>
					<div id="tab1">
						<ul class="now-showing">
						<?php
							#getting all current images
							foreach ($showing_src as $key=>$src):
								echo"<li index=\"$key\" id=\"$showing_ids[$key]\" class='slides myslide$key' ><img src=\"$src\" class=\"$showing_movie[$key]\" /></li>";
								endforeach; 
							?>
						</ul>
							
					</div>
					<div id="tab2">
						<ul class="coming-soon">
						<?php
							#getting all coming soon images
							if(!empty($coming_soon_src)):
								foreach ($coming_soon_src as $key=>$src):
									if($key==0){
										echo"<li id=\"$coming_soon_ids[$key]\" class='slides myslide$key' index=\"$key\"><img src=\"$src\" class=\"$coming_soon[$key]\"  /></li>";
									}else{
										echo"<li id=\"$coming_soon_ids[$key]\" class='slides myslide$key' index=\"$key\"><img src=\"$src\" class=\"$coming_soon[$key]\"  /></li>";
									}
									
									endforeach; 
								endif;
							?>
						</ul>
					</div>
				</div>
				
			</div>
			<div class="community">
				<ul class="social">
					<h2>social media freebies</h2>
					<p>join our social media family and stand a chance to win great prizes <i>every week</i></p>
					<li class="facebook">
						<h3><a href="https://www.facebook.com/CinemaMagic"></a>facebook</h3>
						<p>like and/or comment on Cinema Magic facebook posts for free cinema tickets</p>
						<div class="like-buttons">
							<div class="fb-like" data-href="https://www.facebook.com/CinemaMagic" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
						</div>
					</li>
					<li class="twitter">
						<h3><a href="https://twitter.com/cinemamagic3D"></a>twitter</h3>
						<p>Follow us on twitter and win a ticket to the premiere of <b>Guardian's of the Galaxy</b></p>
						<div class="like-buttons">
						<a href="https://twitter.com/cinemamagic3D" class="twitter-follow-button" data-show-count="true" data-lang="en">Follow @cinemamagic3D</a>
						</div>
					</li>
					<li class="instagram" style="display:none">
						<h3><a href="https://www.facebook.com/CinemaMagic"></a>instagram</h3>
						<p>post a selfie at the cinema and stand a chance to be featured on the site</p>
						<div class="like-buttons">
							<button>follow @cinemagicUg</button>
						</div>
					</li>
				</ul>
				<ul class="the-experience clearfix" style="display:none">
					<h2>our community</h2>
					<li>
						<h2>the <span><b>cine-magic</b> experience</span></h2>
						<p>sign up to our fan community and enjoy these full benefits and more</p>
						<ol>
							<li>half price tuesday</li>
							<li>2 for 1 thursday</li>
							<li>freebie fridays</li>
						</ol>
						<p>not a member yet? what have you been waiting for?<button>sign up here</button></p>
					</li>
				</ul>
			</div>
		</div>
<div class='videoDiv' ><div id='video'></div></div>
<div class='calender' style="display:none">
	<div class="cal-movie-info">
					<ul>
						<li class="movie-thumb"></li>
						<li class="cal-movie-details">
							<ul>
								<li class="movie-title"><h1></h1></li>
								<li class="star-rating"><h4>Rating:<span class='stars'></span></h4></li>
								<li class="genre"><h4>genre:<span></span></h4></li>
								<li class="run-time"><h4>run time:<span></span></h4></li>
								<li class="age-rating"><span>pg-13</span></li>
								<li class="synopsis"><p>synopsis<span></span></p></li>
							</ul>
						</li>
					</ul>
				</div>
	<div id="datepicker"></div>
</div>
</body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</html>

<?php #Starkers_Utilities::get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer') ); ?>
