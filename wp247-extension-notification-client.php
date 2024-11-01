<?php
/*
	Plugin Name: WP247 Extension Notification Client
	Version: 1.0.1
	Description: Provides the ability for extension developers to send notification messages to their extension users - This is the client (Extension User's) side plugin
	Tags: extension, plugin, theme, notice, notification, message
	Author: wp247
	Author URI: http://wp247.net/
	Text Domain: wp247-extension-notification-client
	Uses: weDevs Settings API wrapper class from http://tareq.weDevs.com Tareq's Planet
*/

// Don't allow direct execution
defined( 'ABSPATH' ) or die( 'Forbidden' );

// Set to true to get debug array listed at the bottom of the html body
defined( 'WP247XNS_CLIENT_DEBUG' ) or define( 'WP247XNS_CLIENT_DEBUG', false );

if ( !defined( 'WP247XNS_CLIENT_VERSION' ) )
{
	define( 'WP247XNS_CLIENT_VERSION', 1.0 );
	define( 'WP247XNS_CLIENT_PLUGIN_NAME', 'WP247 Extension Notification Client' );
	define( 'WP247XNS_CLIENT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
	define( 'WP247XNS_CLIENT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	define( 'WP247XNS_CLIENT_PLUGIN_ID', basename( dirname( __FILE__ ) ) );
	define( 'WP247XNS_CLIENT_PLUGIN_TEXT_DOMAIN', WP247XNS_CLIENT_PLUGIN_ID );

	add_action( 'plugins_loaded','wp247xns_client_do_action_plugins_loaded');
	add_action( 'wp_loaded','wp247xns_client_do_action_wp_loaded');
	add_filter( 'wp247xns_client_extension_poll_plugin_'.WP247XNS_CLIENT_PLUGIN_ID, 'wp247xns_client_do_filter_wp247xns_client_extension_poll' );
	add_action( 'wp_ajax_wp247xns_client_dismiss_notice', 'wp247xns_client_do_action_wp_ajax_wp247xns_client_dismiss_notice' );
	add_action( 'wp_ajax_wp247xns_client_admin_extension_status', 'wp247xns_client_do_action_wp_ajax_wp247xns_client_admin_extension_status' );
	add_action( 'wp_ajax_wp247xns_client_admin_extension_refresh', 'wp247xns_client_do_action_wp_ajax_wp247xns_client_admin_extension_refresh' );
	add_action( 'wp_ajax_wp247xns_client_admin_extension_reset', 'wp247xns_client_do_action_wp_ajax_wp247xns_client_admin_extension_reset' );
	add_action( 'wp_ajax_wp247xns_client_admin_view_notice', 'wp247xns_client_do_action_wp_ajax_wp247xns_client_admin_view_notice' );


	/*
	 * Only load Admin Settings if this user can update extensions
	 */
	function wp247xns_client_do_action_plugins_loaded()
	{
		if ( is_admin() and current_user_can( 'manage_options' ) )
		{
			load_plugin_textdomain( WP247XNS_CLIENT_PLUGIN_TEXT_DOMAIN, false, basename( WP247XNS_CLIENT_PLUGIN_PATH ) . '/languages' );
			require_once WP247XNS_CLIENT_PLUGIN_PATH . 'include/wp247-extension-notification-client-notices.php';
			require_once WP247XNS_CLIENT_PLUGIN_PATH . 'admin/wp247xns-client-corequisite-notice/wp247xns-client-corequisite-notice.php';
		}
	}

	/*
	 * Only load Admin Settings if this user can update extensions
	 */
	function wp247xns_client_do_action_wp_loaded()
	{
		if ( is_admin() and current_user_can( 'manage_options' ) )
		{
			require_once WP247XNS_CLIENT_PLUGIN_PATH . 'admin/wp247-extension-notification-client-admin.php';
		}
	}

	/*
	 * Tell WP247 Extension Notification Client about us
	 */
	function wp247xns_client_do_filter_wp247xns_client_extension_poll( $extensions )
	{
		return array(
					 'id'			=> WP247XNS_CLIENT_PLUGIN_ID
					,'version'		=> WP247XNS_CLIENT_VERSION
					,'name'			=> 'WP247 Extension Notification Client'
					,'type'			=> 'plugin'
					,'server_url'	=> 'http://wp247.net/wp-admin/admin-ajax.php'
					,'frequency'	=> '1 day'
				);
	}

	/**
	 * Permenantly dismiss a notice
	 *
	 * @return void - dies
	 */
	function wp247xns_client_do_action_wp_ajax_wp247xns_client_dismiss_notice()
	{
		include WP247XNS_CLIENT_PLUGIN_PATH . 'include/wp247-extension-notification-client-notices-ajax-dismiss-notice.php';
	}

	/**
	 * Display Extension Status
	 *
	 * @return void - dies
	 */
	function wp247xns_client_do_action_wp_ajax_wp247xns_client_admin_extension_status()
	{
		include WP247XNS_CLIENT_PLUGIN_PATH . 'admin/wp247-extension-notification-client-admin-ajax-extension-status.php';
	}

	/**
	 * Do Extension Refresh
	 *
	 * @return void - dies
	 */
	function wp247xns_client_do_action_wp_ajax_wp247xns_client_admin_extension_refresh()
	{
		include WP247XNS_CLIENT_PLUGIN_PATH . 'admin/wp247-extension-notification-client-admin-ajax-extension-refresh.php';
	}

	/**
	 * Do Extension Reset
	 *
	 * @return void - dies
	 */
	function wp247xns_client_do_action_wp_ajax_wp247xns_client_admin_extension_reset()
	{
		include WP247XNS_CLIENT_PLUGIN_PATH . 'admin/wp247-extension-notification-client-admin-ajax-extension-reset.php';
	}

	/**
	 * View a notice
	 *
	 * @return void - dies
	 */
	function wp247xns_client_do_action_wp_ajax_wp247xns_client_admin_view_notice()
	{
		include WP247XNS_CLIENT_PLUGIN_PATH . 'admin/wp247-extension-notification-client-admin-ajax-view-notice.php';
	}

}