<?php
/*
Plugin Name: WP Glesys Admin
Plugin URI: http://kaska.jp/wp-glesys-admin
Description: A plugin that adds a dashboard widget with information about your Glesys server and email administration
Version: 1.0
Author: KASKA
Author URI: http://kaska.jp/
*/

// Show message upon activation
register_activation_hook(__FILE__, 'glesys_admin_activation');
function glesys_admin_activation() {
	$notices= get_option('glesys_admin_deferred_admin_notices', array());
	$notices[]= 'Don\'t forget that you have to <a href="'.admin_url().'admin.php?page=glesys-admin-server-settings-page">change your server settings</a> to use Glesys Admin.';
	update_option('glesys_admin_deferred_admin_notices', $notices);
}

add_action('admin_notices', 'glesys_admin_admin_notices');
function glesys_admin_admin_notices() {
	if ($notices = get_option('glesys_admin_deferred_admin_notices')) {
		foreach ($notices as $notice) {
			echo "<div class='error'><p>$notice</p></div>";
		}
		delete_option('glesys_admin_deferred_admin_notices');
	}
}

$dir = plugin_dir_path( __FILE__ );

require_once($dir . 'inc/conf.php');
require_once($dir . 'glesys-api/APIClient.php');

try{
	$client = new APIClient($config["username"], $config["API-key"]);
} catch(Exception $e){
	echo "Connection error: ".$e->getMessage();
	return;
}

// Add admin menu
add_action('admin_menu', 'glesys_admin_plugin_menu');
function glesys_admin_plugin_menu() {
	add_menu_page( 'Glesys Admin', 'Glesys Admin', 'hackability', 'glesys_menu', 'glesys_email_accounts' );
	add_submenu_page( 'glesys_menu', 'Email Accounts', 'Email Accounts', 'administrator', 'email-accounts', 'glesys_email_accounts' );
	add_submenu_page( 'glesys_menu', 'Email Aliases', 'Email Aliases', 'administrator', 'email-aliases', 'glesys_email_aliases' );
	add_submenu_page( 'glesys_menu', 'Server Settings', 'Server Settings', 'administrator', 'glesys-admin-server-settings-page', 'glesys_email_server_settings' );

	add_action('admin_init', 'register_glesys_server_settings');
}

// Server settings
function register_glesys_server_settings() {
	register_setting( 'glesys-server-settings-group', 'glesys_domainname' );
	register_setting( 'glesys-server-settings-group', 'glesys_username' );
	register_setting( 'glesys-server-settings-group', 'glesys_password' );
	register_setting( 'glesys-server-settings-group', 'glesys_serverid' );
	register_setting( 'glesys-server-settings-group', 'glesys_api_key' );
}

// Load plugin CSS
function glesys_admin_css() {
	wp_register_style('glesys_admin_css', plugins_url('assets/css/glesys-admin-style.css',__FILE__ ));
	wp_enqueue_style('glesys_admin_css');
}
add_action( 'admin_init','glesys_admin_css');

// Load plugin JS
function glesys_admin_js() {
	wp_enqueue_script('my-spiffy-miodal', plugins_url('assets/js/glesys-admin-email.js',__FILE__), array('jquery-ui-dialog'));
	wp_enqueue_style('wp-jquery-ui-dialog');
}
add_action( 'admin_enqueue_scripts', 'glesys_admin_js');

require_once($dir . 'inc/email-manager.php');
require_once($dir . 'inc/dashboard-widget.php');

?>