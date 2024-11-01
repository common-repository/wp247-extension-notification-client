<?php
// Don't allow direct execution
defined( 'ABSPATH' ) or die ( 'Forbidden' );

function wp247_extension_notification_client_admin_menu( $caller ) {
	return array( 'page_title'		=> $caller->__( 'WP247 Extension Notification Client' )
				, 'menu_title'		=> $caller->__( 'Extension Notifications' )
				, 'capability'		=> 'manage_options'
				, 'menu_slug'		=> 'wp247xns_client_options'
				, 'page_link'		=> 'http://wp247.net/wp247-extension-notification-system'
				, 'doc_link'		=> 'http://wordpress.org/plugins/wp247-extension-notification-client'
				, 'review_link'		=> 'http://wordpress.org/support/view/plugin-reviews/wp247-extension-notification-client'
				, 'support_link'	=> 'http://wordpress.org/support/plugin/wp247-extension-notification-client'
				, 'parent_slug'		=> 'options-general'
				);
}
?>