<?php
/*
Plugin Name: BuddyPress-Facebook Connect
Author: Andy Peatling, Adam Hupp
Author URI: 
Description: Enables members to log in to a BuddyPress enabled install with their Facebook account. Based on wp-facebookconnect by Adam Hupp. Requires a <a href="http://www.facebook.com/developers/">Facebook API Key</a> for use.
Version: 1.1
*/

require_once ( 'includes/wp-facebookconnect/fbconnect.php' );
require_once ( 'includes/bp-fbconnect-cssjs.php' );

function bp_fbconnect_modify_plugin_path() {
	return WP_PLUGIN_URL . '/bp-fbconnect/includes/wp-facebookconnect';
}
add_filter( 'fbconnect_plugin_path', 'bp_fbconnect_modify_plugin_path' );

function add_fb_login() {
	do_action('fbc_display_login_button');
}
add_action( 'bp_login_bar_logged_out', 'add_fb_login' );

function add_fb_logout( $logout_link ) {
	global $bp;
	
	if ( '' != get_usermeta( $bp->loggedin_user->id, 'fbuid' ) ) {
		return ' <a onclick="FBConnect.logout(); return false" href="#">Logout of Site &amp; Facebook</a>';		
	}
	
	return $logout_link;
}
add_filter( 'bp_logout_link', 'add_fb_logout' );

function bp_fbconnect_insert_data( $user_id, $usermeta ) {
	// Add the full name of the user to the xprofile table
	if ( function_exists( 'xprofile_set_field_data') )
		xprofile_set_field_data( BP_XPROFILE_FULLNAME_FIELD_NAME, $usermeta['display_name'], $user_id );
}
add_action( 'fbc_insert_user', 'bp_fbconnect_insert_data', 10, 2 );

function bp_fbconnect_modify_userdata( $userdata, $userinfo ) { 
	$userdata = array(
		'user_pass' => wp_generate_password(),
		'user_login' => $fbusername,
		'display_name' => fbc_get_displayname($userinfo),
		'user_url' => fbc_make_public_url($userinfo),
		'user_email' => $userinfo['proxied_email']
	);

	$fb_bp_user_login = strtolower( str_replace( ' ', '', fbc_get_displayname($userinfo) ) );
	
	$counter = 1;
	if ( username_exists( $fb_bp_user_login ) ) {
		do {
			$username = $fb_bp_user_login;
			$counter++;
			$username = $username . $counter;
		} while ( username_exists( $username ) );

		$userdata['user_login'] = $username;
	} else {
		$userdata['user_login'] = $fb_bp_user_login;
	}
	
	return $userdata;
}
add_filter( 'fbc_insert_user_userdata', 'bp_fbconnect_modify_userdata', 10, 2 );

function bp_fbconnect_replace_avatar( $img, $args ) {
	extract( $args );
	
	if ( $object != 'user' )
		return $img;
	
	if ( !$fbuid = fbc_get_fbuid( $item_id ) )
		return $img;
	
	$user = new stdClass;
	$user->user_id = $item_id;

	if ( !$height ) {
		if ( 'thumb' == $type )
			$height = constant( 'BP_AVATAR_THUMB_HEIGHT' );
		else
			$height = constant( 'BP_AVATAR_FULL_HEIGHT' );
	}
	
	if ( 'thumb' == $type || $height < 50 )
		$size = 'square';
	else
		$size = 'normal';

	return fbc_get_avatar( $img, $user, $height, $size, false );
}
add_filter( 'bp_core_fetch_avatar', 'bp_fbconnect_replace_avatar', 10, 2 );

?>