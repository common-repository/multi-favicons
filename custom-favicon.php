<?php
/*
 * Plugin Name:       Multi Favicons
 * Plugin URI:        https://customrequest.com/multi-favicons
 * Description:       This plugin creates custom post meta for favicons on post and pages. You can add a different favicon to each post or page.
 * Version:           1.2
 * Author:            David Uriarte
 * Author URI:        https://customrequest.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! class_exists( 'custom_FavIcon' ) ) {


	class custom_FavIcon {
		function __construct() {

			// Register Settings
			add_action( 'admin_init', array( &$this, 'custom_favicon_settings' ) );

			add_action( 'wp_footer', array( &$this, 'adding_icon_on_frontend' ) );

			add_action( 'admin_enqueue_scripts', array( &$this, 'adding_media_uploading_script' ) );

			/*add_action( 'wp_enqueue_scripts', array( &$this, 'adding_jquery' ) );*/
			
		}

		public function custom_favicon_settings() {

			require_once('meta-box.php');

		}	


		/*Starting frontend favicons*/
		public function adding_icon_on_frontend(){
			global $is_gecko;
			$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$postid = url_to_postid( $url );

			if ($postid != 0) {

				$value = get_post_meta($postid, '_custom_favicon', true);

				if (!empty($value)) {
					
					if ($is_gecko) {
						echo '<link rel="shortcut icon" href="'.  esc_url( $value )  .'" type="image/x-icon"/>';
					}

					else {
						echo '<link rel="shortcut icon" href="'.  esc_url( $value )  .'" type="image/x-icon"/>';
						echo '<link rel="icon" href="'.  esc_url( $value )  .'" type="image/x-icon"/>';
						echo '<link rel="apple-touch-icon-precomposed" href="'.  esc_url( $value )  .'" type="image/x-icon"/>';

						echo '
						<script>
							window.onload = function(){
								var favIcons = document.querySelectorAll("*[rel=\'icon\']");
								var favIcons1 = document.querySelectorAll("*[rel=\'shortcut icon\']");
								var favIcons2 = document.querySelectorAll("*[rel=\'apple-touch-icon-precomposed\']");

								for (var i = 0; i < favIcons.length; i++) {
						            var html = favIcons[i];
						             html.setAttribute("href", "'.  esc_url( $value )  .'");
						        }

						        favIcons1[0].setAttribute("href", "'.  esc_url( $value )  .'");
						        favIcons2[0].setAttribute("href", "'.  esc_url( $value )  .'");
								
							}
						</script>
						';
					}
				}

				
				
			}
		} 

		public function adding_jquery(){
			wp_enqueue_script('jquery');
		}

		public function adding_media_uploading_script() {
			global $pagenow;
		 	if (isset($_GET['page']) || $_GET['post'] || $pagenow == "post-new.php") {

    			wp_enqueue_style( 'thickbox' ); // Stylesheet used by Thickbox
   				wp_enqueue_script( 'thickbox' );
    			wp_enqueue_script( 'media-upload' );

		        wp_register_script('post_page_fav_icon', plugins_url( '/js/post_page_fav_icon.js' , __FILE__ ), array( 'thickbox', 'media-upload' ));
		        
		        wp_enqueue_script('post_page_fav_icon');
		    }
		} //adding_media_uploading_script


	} // End Class


	// Initiation call of plugin
	$custom_FavIcon = new custom_FavIcon();

}



?>