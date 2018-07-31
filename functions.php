<?php
/**
 * thegoodartisan functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package thegoodartisan
 */


if ( ! function_exists( 'thegoodartisan_setup' ) ) :


    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support( 'post-thumbnails' );

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function thegoodartisan_setup() {

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'thegoodartisan' ),
		) );


	}
endif;
add_action( 'after_setup_theme', 'thegoodartisan_setup' );



/**
 * Enqueue scripts and styles.
 */
function thegoodartisan_scripts() {

	wp_enqueue_style( 'thegoodartisan-style', get_stylesheet_uri() );
    wp_enqueue_style( 'thegoodartisan-main-css', get_template_directory_uri() . '/build/static/css/main.9574bb42.css' );

}
add_action( 'wp_enqueue_scripts', 'thegoodartisan_scripts' );


/**
 * Enqueue scripts and styles.
 */
function thegoodartisan_react_scripts() {

	
	wp_enqueue_script( 'thegoodartisan-custom-js', get_template_directory_uri() . '/js/custom.js', array('jquery'), '20151215', true );
    
    wp_enqueue_script( 'thegoodartisan-main-js', get_template_directory_uri() . '/build/static/js/main.a803207d.js', array('thegoodartisan-custom-js'), '20151215', true );

    // wp_enqueue_script( 'thegoodartisan-sw-js', get_template_directory_uri() . '/build/service-worker.js', array('thegoodartisan-custom-js'), '20151215', true );

	//
	$siteurl = get_option('siteurl');
    $themedir = get_stylesheet_directory_uri();

	// define script
	$script = array(
		'WPDOMAIN' => $siteurl,
        'TEMPLATEURL' => $themedir
		);

	//must be added after wp_enqueue_script 'tga-script-public'
	// localize script
	//WPURL.WPDOMAIN
	wp_localize_script( 'thegoodartisan-custom-js', 'WPURL', $script );


}
add_action( 'wp_enqueue_scripts', 'thegoodartisan_react_scripts' );


//https://developer.wordpress.org/reference/functions/wp_get_nav_menu_items/
function get_menu() {
	// using register_nav_menus name 'menu-1'
	$menuLocations = get_nav_menu_locations(); // Get our nav locations (set in our theme, usually functions.php)
	                                           // This returns an array of menu locations ([LOCATION_NAME] = MENU_ID);

	$menuID = $menuLocations['menu-1']; // Get the *primary* menu 
	$primaryNav = wp_get_nav_menu_items($menuID); // Get the array of wp objects, the nav items for our queried location.
	return $primaryNav;

}

add_action( 'rest_api_init', function () {
		//https://thegoodartisan.com/wp-json/thegoodartisan/menu
        register_rest_route( 'thegoodartisan', '/menu', array(
        'methods' => 'GET',
        'callback' => 'get_menu',
    ) );
} );

//https://developer.wordpress.org/reference/functions/wp_get_nav_menu_items/
/**
 * Static Posts Page Rest Endpoint
 */

add_action( 'rest_api_init', function()
{
    \register_rest_route( 'thegoodartisan/page-post', '/id/',
        [
            'methods'   => 'GET',
            'callback'  => 'get_psot_page_id'
        ]
    );
} );

function get_psot_page_id( $request ) {
    
    $pid  = \get_option( 'page_for_posts' );

    $post = ( $pid > 0 ) ? \get_post( $pid ) : null;

    // No static frontpage is set
    if( ! is_a( $post, '\WP_Post' ) )
        return new \WP_Error( 'thegoodartisan-fallback',
           \esc_html__( 'No Static Posts page.', 'thegoodartisan' ), [ 'Go to Admin Dashboard' => 'Select Static Page for Posts' ] );

    // Response setup
    $data = [
        'ID'      => $post->ID
    ];

    return new \WP_REST_Response( $data, 200 );
}

add_action('rest_api_init', 'register_rest_images' );

function register_rest_images(){
    register_rest_field( array('post'),
        'thegoodartisan_featured_media',
        array(
            'get_callback'    => 'get_rest_featured_image',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

function get_rest_featured_image( $object, $field_name, $request ) {
    if( $object['featured_media'] ){
        $img = wp_get_attachment_image_src( $object['featured_media'], 'app-thumb' );
        return $img[0];
    }
    return false;
}


add_action('rest_api_init', 'register_rest_images_2' );
function register_rest_images_2(){
    register_rest_field( array('page'),
        'thegoodartisan_featured_media',
        array(
            'get_callback'    => 'get_rest_featured_image_2',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}
function get_rest_featured_image_2( $object, $field_name, $request ) {
    if( $object['featured_media'] ){
        $img = wp_get_attachment_image_src( $object['featured_media'], 'app-thumb' );
        return $img[0];
    }
    return false;
}
