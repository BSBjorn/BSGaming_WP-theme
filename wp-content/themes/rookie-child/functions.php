<?php
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);

function enqueue_child_theme_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}

add_filter('show_admin_bar', '__return_false');

/**
 * Display footer credit
 */
if ( ! function_exists( 'rookie_footer_credit' ) ):
  function rookie_footer_credit() {
    ?>
    <div class="site-credit">
      <?php echo apply_filters( 'rookie_footer_credit', '<a href="http://bitsector.no/">' . sprintf( __( 'Designed by %s', 'rookie' ), 'BitSector' ) . '</a>' ); ?>
    </div><!-- .site-info -->
    <?php
  }
endif;


/*==========================
 This snippet contains utility functions to create/update and pull data from the active user transient.
 Copy these contents to functions.php
 ===========================*/

//Update user online status
add_action('init', 'gearside_users_status_init');
add_action('admin_init', 'gearside_users_status_init');
function gearside_users_status_init(){
	$logged_in_users = get_transient('users_status'); //Get the active users from the transient.
	$user = wp_get_current_user(); //Get the current user's data

	//Update the user if they are not on the list, or if they have not been online in the last 900 seconds (15 minutes)
	if ( !isset($logged_in_users[$user->ID]['last']) || $logged_in_users[$user->ID]['last'] <= time()-900 ){
		$logged_in_users[$user->ID] = array(
			'id' => $user->ID,
			'username' => $user->user_login,
			'last' => time(),
		);
		set_transient('users_status', $logged_in_users, 900); //Set this transient to expire 15 minutes after it is created.
	}
}

//Check if a user has been online in the last 15 minutes
function gearside_is_user_online($id){	
	$logged_in_users = get_transient('users_status'); //Get the active users from the transient.
	
	return isset($logged_in_users[$id]['last']) && $logged_in_users[$id]['last'] > time()-900; //Return boolean if the user has been online in the last 900 seconds (15 minutes).
}

//Check when a user was last online.
function gearside_user_last_online($id){
	$logged_in_users = get_transient('users_status'); //Get the active users from the transient.
	
	//Determine if the user has ever been logged in (and return their last active date if so).
	if ( isset($logged_in_users[$id]['last']) ){
		return $logged_in_users[$id]['last'];
	} else {
		return false;
	}
}