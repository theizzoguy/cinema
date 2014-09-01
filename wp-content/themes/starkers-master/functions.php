<?php
	/**
	 * Starkers functions and definitions
	 *
	 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
	 *
 	 * @package 	WordPress
 	 * @subpackage 	Starkers
 	 * @since 		Starkers 4.0
	 */

	/* ========================================================================================================================
	
	Required external files
	
	======================================================================================================================== */

	require_once( 'external/starkers-utilities.php' );
	require_once( 'meta_box.php');
	require'ajax.php';
	add_filter('show_admin_bar', '__return_false');
	
	//removing menu items
  function remove_admin_menu_items() {
	remove_menu_page('edit.php');
	  $remove_menu_items = array(__('Appearance'),__('Links'), __('Tools'));
	  global $menu;
	  end ($menu);
	  while (prev($menu)){
		$item = explode(' ',$menu[key($menu)][0]);
		if(in_array($item[0] != NULL?$item[0]:"" , $remove_menu_items)){
		  unset($menu[key($menu)]);}
		}
	  }
 add_action('admin_menu', 'remove_admin_menu_items');
   	/* ========================================================================================================================
	
	Theme specific settings

	Uncomment register_nav_menus to enable a single menu with the title of "Primary Navigation" in your theme
	
	======================================================================================================================== */

//	add_theme_support('post-thumbnails');
	
	// register_nav_menus(array('primary' => 'Primary Navigation'));

	/* ========================================================================================================================
	
	Actions and Filters
	
	======================================================================================================================== */

	add_action( 'wp_enqueue_scripts', 'starkers_script_enqueuer' );

	add_filter( 'body_class', array( 'Starkers_Utilities', 'add_slug_to_body_class' ) );

	/* ========================================================================================================================
	


	/**
	 * Add scripts via wp_head()
	 *
	 * @return void
	 * @author Keir Whitaker
	*/
	function modify_jquery() {
			if (!is_admin()) {
				// comment out the next two lines to load the local copy of jQuery
				wp_deregister_script('jquery');
				wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js', false, '1.8.1',false);
				wp_enqueue_script('jquery');
				wp_register_script('underscore',"http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js");
				wp_enqueue_script('underscore');

			}
		}
		
	add_action('wp_enqueue_scripts', 'modify_jquery');
	
	function starkers_script_enqueuer() {
			
		wp_register_style( 'bxcss', get_stylesheet_directory_uri().'/css/jquery.bxslider.css', '', '', 'screen' );
        wp_enqueue_style( 'bxcss' );
        	
		wp_register_style( 'calender', get_stylesheet_directory_uri().'/css/fullcalendar.css', '', '', 'screen' );
        wp_enqueue_style( 'calender' );
        
        wp_register_script( 'bxslider', get_template_directory_uri().'/js/jquery.bxslider.min.js', array('jquery') );
		wp_enqueue_script('bxslider');
	
 
        wp_register_script( 'site', get_template_directory_uri().'/js/site.js', array( 'jquery' ) );
		wp_enqueue_script('site');
		
		#wp_register_script( 'moment', get_template_directory_uri().'/js/moment.min.js', array( 'jquery' ) );
		#wp_enqueue_script( 'moment' );
		
		#wp_register_script( 'clndr', get_template_directory_uri().'/js/fullcalendar.min.js', array( 'jquery' ) );
		#wp_enqueue_script( 'clndr' );
	       
		wp_register_script( 'date', get_template_directory_uri().'/js/jquery.ui.datepicker.css', '', '', 'screen' );
		wp_enqueue_script( 'date' );
		
			
		#wp_register_style( 'slick', get_stylesheet_directory_uri().'/css/slick.css', '', '', 'screen' );
        #wp_enqueue_style( 'slick' );
        
        #wp_register_script( 'slick', get_template_directory_uri().'/js/slick.min.js', array( 'jquery' ) );
		#wp_enqueue_script( 'slick' );
		
		/*wp_register_script( 'ui', get_template_directory_uri().'/js/jquery.ui.core.js', array( 'jquery' ) );
		wp_enqueue_script( 'ui' );
		wp_register_script( 'widget', get_template_directory_uri().'/js/jquery.ui.widget.js', array( 'jquery' ) );
		wp_enqueue_script( 'widget' );
		
		wp_register_script( 'date', get_template_directory_uri().'/js/jquery.ui.datepicker.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'date' );
		
		wp_register_script( 'slick', get_template_directory_uri().'/js/slick.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'slick' );
		wp_register_script( 'countdown', get_template_directory_uri().'/js/jquery.countdown.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'countdown' );
		wp_register_script( 'validate', get_template_directory_uri().'/js/jquery.validate.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'validate' );
		
		wp_register_style( 'slick', get_stylesheet_directory_uri().'/css/slick.css', '', '', 'screen' );
        wp_enqueue_style( 'slick' );
		*/
		}	
	function my_theme_styles() {
			// replace "10" with your version number; increment as you push changes
			wp_enqueue_style('my-theme-style', get_bloginfo('template_directory') . '/style.css', false, 21);
		}
	add_action('wp_print_styles', 'my_theme_styles');
	/* ========================================================================================================================
	
	Comments
	
	======================================================================================================================== */

	/**
	 * Custom callback for outputting comments 
	 *
	 * @return void
	 * @author Keir Whitaker
	 */
	
	
	function starkers_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment; 
		?>
		<?php if ( $comment->comment_approved == '1' ): ?>	
		<li>
			<article id="comment-<?php comment_ID() ?>">
				<?php echo get_avatar( $comment ); ?>
				<h4><?php comment_author_link() ?></h4>
				<time><a href="#comment-<?php comment_ID() ?>" pubdate><?php comment_date() ?> at <?php comment_time() ?></a></time>
				<?php comment_text() ?>
			</article>
		<?php endif;
	}	
	
	 /*metaboxes for home page*/
	
	 /*========================================================================================================================
	
	     Movie Post type
	
	======================================================================================================================== */

	/**/

	 
	 
	 add_action( 'init', 'my_custom_post_movie');
	 add_action( 'init', 'my_taxonomies_movie', 0 );
	 add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
	 #meta box class
	 
	// regester the post type function
	function my_custom_post_movie() {
	//labels	
	$labels = array(
		'name'               => _x( 'Movie', 'post type general name' ),
		'singular_name'      => _x( 'Movie', 'post type singular name' ),
		'add_new'            => _x( 'Add Movie', 'movie' ),
		'add_new_item'       => __( 'Add New Movie' ),
		'edit_item'          => __( 'Edit Movie' ),
		'new_item'           => __( 'New Movie' ),
		'all_items'          => __( 'All Movies' ),
		'view_item'          => __( 'View Movie' ),
		'search_items'       => __( 'Search Movies' ),
		'not_found'          => __( 'No Movie found' ),
		'not_found_in_trash' => __( 'No Movies found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Movie'
	  );
	  //arguments
	  $args = array(
		'labels'        => $labels,
		'description'   => 'Holds movie specific data',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title'),
	
		'has_archive'   => true,
	  );
	
	register_post_type( 'movie', $args ); 
	}
	// function for creating the movie category/taxonomy
	function my_taxonomies_movie() {
		$labels = array(
		'name'              => _x( 'Genres', 'taxonomy general name' ),
		'singular_name'     => _x( 'Genres', 'taxonomy singular name' ),
		'search_items'      => __( 'Search movie Categories' ),
		'all_items'         => __( 'All  Genres' ),
		'parent_item'       => __( 'Parent Genre' ),
		'parent_item_colon' => __( 'Parent Genre:' ),
		'edit_item'         => __( 'Edit Genre' ), 
		'update_item'       => __( 'Update Genres' ),
		'add_new_item'      => __( 'Add Genre' ),
		'new_item_name'     => __( 'New Genre' ),
		'menu_name'         => __( 'Genre' ),
		
		);
		$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'show_ui' =>true,
		);
		register_taxonomy( 'movie_category', 'movie', $args );
	}
	
	add_filter( 'cmb_meta_boxes', 'cmb_movie_metaboxes' );
	function cmb_movie_metaboxes( array $meta_boxes ) {
	#movie post type meta box
		$prefix = '_cmb_';
		$meta_boxes['movie_metabox'] = array(
			'id'         => 'movie_metabox',
			'title'      => __( 'Movie datail part A', 'cmb' ),
			'pages'      => array( 'movie', ), // Post type
			'context'    => 'normal',
			'priority'   => 'high',
			'show_names' => true, // Show field names on the left
			// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
			'fields'     => array(
				
				array(
					'name' => __( 'Screen Shots', 'cmb' ),
					'desc' => __( 'Upload an image or enter a URL.', 'cmb' ),
					'id'   => $prefix . 'multiple_images',
					'description' => 'images for individuals page',
					'type' => 'file_list',
				),
				
				array(
					'name' => __( 'Thumbnail', 'cmb' ),
					'desc' => __( 'Upload an image or enter a URL.', 'cmb' ),
					'id'   => $prefix . 'slider_image',
					'type' => 'file',
				),
			
			),
		);
	
		return $meta_boxes;
	}

/* ========================================================================================================================
	
	Featured Content Post type
	
	======================================================================================================================== */

	/**/
	 add_action( 'init', 'my_custom_post_featured');
	 add_action( 'init', 'my_taxonomies_featured', 0 );
	 
	// regester the post type function
	function my_custom_post_featured() {
	//labels	
	$labels = array(
		'name'               => _x( 'Featured Content', 'post type general name' ),
		'singular_name'      => _x( 'Featured Content', 'post type singular name' ),
		'add_new'            => _x( 'Add Featured Content', 'featured' ),
		'add_new_item'       => __( 'Add New featured Content' ),
		'edit_item'          => __( 'Edit featured Content' ),
		'new_item'           => __( 'New featured Content' ),
		'all_items'          => __( 'All featured Content' ),
		'view_item'          => __( 'View featured Content' ),
		'search_items'       => __( 'Search featured Content' ),
		'not_found'          => __( 'No featured Content found' ),
		'not_found_in_trash' => __( 'No featured Conetnt found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Featured Content'
	  );
	  //arguments
	  $args = array(
		'labels'        => $labels,
		'description'   => 'Holds featured Content specific data',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title'),
	
		'has_archive'   => true,
	  );
	
	register_post_type( 'featured', $args ); 
	}
	// function for creating the featured category/taxonomy
	function my_taxonomies_featured() {
		$labels = array(
		'name'              => _x( 'Genres', 'taxonomy general name' ),
		'singular_name'     => _x( 'Genres', 'taxonomy singular name' ),
		'search_items'      => __( 'Search featured Categories' ),
		'all_items'         => __( 'All  Genres' ),
		'parent_item'       => __( 'Parent Genre' ),
		'parent_item_colon' => __( 'Parent Genre:' ),
		'edit_item'         => __( 'Edit Genre' ), 
		'update_item'       => __( 'Update Genres' ),
		'add_new_item'      => __( 'Add Genre' ),
		'new_item_name'     => __( 'New Genre' ),
		'menu_name'         => __( 'Genre' ),
		
		);
		$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'show_ui' =>true,
		);
		register_taxonomy( 'featured_category', 'featured', $args );
	}
	
	add_filter( 'cmb_meta_boxes', 'cmb_featured_metaboxes' );
	function cmb_featured_metaboxes( array $meta_boxes ) {
	#featured post type meta box
		$prefix = '_featured_';
		
	
	    $meta_boxes['field_group'] = array(
		'id'         => 'field_group',
		'title'      => __( 'Featured Content Slides', 'cmb' ),
		'pages'      => array( 'featured', ),
		'fields'     => array(
			array(
				'id'          => $prefix . 'repeat_group',
				'type'        => 'group',
				'description' => __( 'Featured Content Slidess', 'cmb' ),
				'options'     => array(
					'group_title'   => __( 'Entry {#}', 'cmb' ), // {#} gets replaced by row number
					'add_button'    => __( 'Add Another Entry', 'cmb' ),
					'remove_button' => __( 'Remove Entry', 'cmb' ),
					'sortable'      => true, // beta
				),
				// Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
				'fields'      => array(
			
					array(
					'name'    => __( 'Caption', 'cmb' ),
					'desc'    => __( '', 'cmb' ),
					'id'      => $prefix . 'caption',
					'type'    => 'text',
					#'options' => array( 'textarea_rows' => 2, ),
					),
					array(
					'name'    => __( 'Copy', 'cmb' ),
					'desc'    => __( '', 'cmb' ),
					'id'      => $prefix . 'copy',
					'type'    => 'textarea',
					#'options' => array( 'textarea_rows' => 5, ),
					),
					array(
					'name'    => __( 'Copy', 'cmb' ),
					'desc'    => __( '', 'cmb' ),
					'id'      => $prefix . 'copy',
					'type'    => 'textarea',
					#'options' => array( 'textarea_rows' => 5, ),
					),

					array(
						'name' => 'Tab Name',
						'description' => 'Tab name',
						'id'   => 'tab',
						'type' => 'text',
					),
					array(
						'name' => 'Movie Name',
						'description' => 'movie name',
						'id'   => 'movie',
						'type' => 'text',
					),
					array(
						'name' => 'Slide',
						'id'   => 'image',
						'type' => 'file',
					),
				 
				array(
					'name'    => __( 'Buttons', 'cmb' ),
					'desc'    => __( 'select buttons to appear on the slide', 'cmb' ),
					'id'      => $prefix . 'icons',
					'type'    => 'multicheck',
					'options' => array(
						'buy' => __( 'Buy', 'cmb' ),
						'trailer' => __( 'Trailer', 'cmb' ),
						'vote' => __( 'Vote', 'cmb' ),
						'schedule' => __( 'schedule', 'cmb' ),
					),
					// 'inline'  => true, // Toggles display to inline
				),
				
				
				  array(
					'name'    => __( 'Notifications', 'cmb' ),
					'desc'    => __( 'select notifications to appear on the slide', 'cmb' ),
					'id'      => $prefix . 'notificatios',
					'type'    => 'multicheck',
					'options' => array(
						'Count Down' => __('Count_down', 'cmb' ),
						'Showing Today' => __('showing_today', 'cmb' ),
						),
					),
				
				
					
				),
			),
		),
	);
	
		return $meta_boxes;
	}
	
	
	
	function cmb_initialize_cmb_meta_boxes() {
		 if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'Custom-Metaboxes-and-Fields-for-WordPress-master/init.php';

      }
    // Filter video output
add_filter('oembed_result','lc_oembed_result', 10, 3);
function lc_oembed_result($html, $url, $args) {
 
    // $args includes custom argument
 
	$newargs = $args;
	// get rid of discover=true argument
	array_pop( $newargs );
 
	$parameters = http_build_query( $newargs );
 
	// Modify video parameters
	$html = str_replace( '?feature=oembed', '?feature=oembed'.'&amp;'.$parameters, $html );
 
    return $html;
}