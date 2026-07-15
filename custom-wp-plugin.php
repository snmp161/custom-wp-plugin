<?php
/**
 * Plugin Name: Kubasovo Custom Plugin
 * Description: Небольшие кастомные доработки
 * Plugin URI:  https://github.com/snmp161/custom-wp-plugin.git
 * Author URI:  https://github.com/snmp161
 * Author:      Sergei Ovchinnikov
 * Version:     0.3
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

add_filter( 'register_url', 'change_my_register_url' );
function change_my_register_url( $url ) {
    if( is_admin() ) {
    	return $url;
    }
    return "/register/";
}

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

$css .= ".entry-meta .byline:before,
	.entry-header .entry-meta span.byline:before,
	.entry-meta .byline:after,
	.entry-header .entry-meta span.byline:after,
	.single .byline, .group-blog .byline,
	.entry-meta .byline,
	.entry-header .entry-meta > span.byline,
	.entry-meta .author.vcard  {
		content: '';
		display: none;
		margin: 0;
	}\n";

add_filter( 'the_date', '__return_false' );
add_filter( 'the_time', '__return_false' );
add_filter( 'the_modified_date', '__return_false' );
add_filter( 'get_the_date', '__return_false' );
add_filter( 'get_the_title', '__return_false' );
add_filter( 'get_the_time', '__return_false' );
add_filter( 'get_the_modified_date', '__return_false' );

$css .= ".entry-meta .posted-on:before,
	.entry-header .entry-meta > span.posted-on:before,
	.entry-meta .posted-on:after,
	.entry-header .entry-meta > span.posted-on:after,
	.entry-meta .posted-on,
	.entry-header .entry-meta > span.posted-on {
		content: '';
		display: none;
		margin: 0;
	}\n";

function kubasovo_is_email_domain_allowed( $email ) {
	$allowed_tlds = array( 'ru', 'su', 'рф', 'xn--p1ai' ); // xn--p1ai = .рф в punycode

	$at_pos = strrpos( $email, '@' );
	if ( false === $at_pos ) {
		return false;
	}

	$domain = mb_strtolower( substr( $email, $at_pos + 1 ) );
	$parts  = explode( '.', $domain );
	$tld    = end( $parts );

	return in_array( $tld, $allowed_tlds, true );
}

add_filter( 'registration_errors', 'kubasovo_email_check_register', 10, 3 );
function kubasovo_email_check_register( $errors, $sanitized_user_login, $user_email ) {
	if ( ! kubasovo_is_email_domain_allowed( $user_email ) ) {
		$errors->add(
			'email_domain_denied',
			'Регистрация возможна только с email в зонах .ru, .su, .рф'
		);
	}
	return $errors;
}

add_action( 'user_profile_update_errors', 'kubasovo_email_check_update', 10, 3 );
function kubasovo_email_check_update( $errors, $update, $user ) {
	if ( $update && ! empty( $user->user_email ) && ! kubasovo_is_email_domain_allowed( $user->user_email ) ) {
		$errors->add(
			'email_domain_denied',
			'Разрешены только email в зонах .ru, .su, .рф'
		);
	}
}

add_action( 'personal_options_update', 'kubasovo_block_email_confirmation', 1 );
function kubasovo_block_email_confirmation( $user_id ) {
	if ( empty( $_POST['email'] ) ) {
		return;
	}

	$new_email = trim( wp_unslash( $_POST['email'] ) );
	$user      = get_userdata( $user_id );

	if ( $user && $new_email !== $user->user_email && ! kubasovo_is_email_domain_allowed( $new_email ) ) {
		remove_action( 'personal_options_update', 'send_confirmation_on_profile_email' );
	}
}


?>
