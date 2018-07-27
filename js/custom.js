/*
File: custom.js
Name: thegoodartisan
*/


console.log("URL generated from wp_localize_script - ", WPURL.WPDOMAIN);
/*
 * uncomment var wp_root_URL (remove '//')
 * to use the current ROOT URL
 * else it will invoke this ROOT URL "https://thegoodartisan.com"
 *
 * WPURL.WPDOMAIN is declared from functions.php using localize script or wp_localize_script()
 */
 
//######
	//var wp_root_URL = WPURL.WPDOMAIN || undefined;
//######

// react App.js file invoke 'var wp_root_URL' as 'window.wp_root_UR'
// const ROOTURL = window.wp_root_URL !== undefined ? window.wp_root_URL : WP_REST_ROUTE.ROOTURL;
// in REST API window.wp_root_URL = Root URL (ex: https://yourWP-site.com)
// else if undefined window.wp_root_URL = "https://thegoodartisan.com"
