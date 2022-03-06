<?php
/**
 * Plugin Name: Kubasovo Custom Plugin
 * Description: Небольшие кастомные доработки
 * Plugin URI:  https://github.com/snmp161/custom-wp-plugin.git
 * Author URI:  https://github.com/snmp161
 * Author:      Sergei Ovchinnikov
 * Version:     0.1
 *
 * Text Domain: ID перевода, указывается в load_plugin_textdomain()
 * Domain Path: Путь до файла перевода.
 * Requires at least: 2.5
 * Requires PHP: 5.4
 *
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Network: false    Укажите "true" для возможности активировать плагин для сети Multisite.
 * Update URI: https://site.ru/link_to_update
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/*
add_filter( 'register_url', 'my_register_page', 10, 2 );
function my_register_page( $register_url, $redirect ) {
    return str_replace("wp-login.php","register",$register_url);
}
*/
add_filter( 'login_url', 'my_login_page', 10, 2 );
function my_login_page( $login_url, $redirect ) {
    return str_replace("wp-login.php","login",$login_url);
}

add_filter( 'logout_url', 'my_logout_page', 10, 2 );
function my_logout_page( $logout_url, $redirect ) {
    return str_replace("wp-login.php","logout",$logout_url);
}

/*
add_filter( 'edit_profile_url', 'my_edit_profile_url', 10, 2 );
function my_edit_profile_url( $edit_profile_url, $redirect ) {
    return str_replace("wp-login.php","account",$edit_profile_url);
}
*/
/*
add_filter( 'get_edit_profile_url', 'my_edit_profile_url', 10, 2 );
function my_edit_profile_url( $edit_profile_url, $redirect ) {
    return str_replace("wp-login.php","account",$edit_profile_url);
}
*/

add_filter( 'get_edit_profile_url', 'my_edit_profile_url', 10, 2 );
function my_edit_profile_url( $user_id = 0, $scheme = 'admin' ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
 
    if ( is_user_admin() ) {
        $url = user_admin_url( 'account', $scheme );
    } elseif ( is_network_admin() ) {
        $url = network_admin_url( 'account', $scheme );
    } else {
        $url = get_dashboard_url( $user_id, 'account', $scheme );
    }

   return apply_filters( 'edit_profile_url', $url, $user_id, $scheme );
}

add_filter( 'lostpassword_url', 'my_lostpassword_url', 10, 2 );
function my_profile_url( $edit_profile_url, $redirect ) {
    return str_replace("wp-login.php","password-reset",$edit_profile_url);
}

add_filter( 'the_author', '__return_false' );
add_filter( 'get_the_author', '__return_false' );
add_filter( 'author_link', '__return_false' );
add_filter( 'the_date', '__return_false' );
add_filter( 'the_time', '__return_false' );
add_filter( 'the_modified_date', '__return_false' );
add_filter( 'get_the_date', '__return_false' );
add_filter( 'get_the_title', '__return_false' );
add_filter( 'get_the_time', '__return_false' );
add_filter( 'get_the_modified_date', '__return_false' );

?>
