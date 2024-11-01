<?php
// Don't allow direct execution
defined( 'ABSPATH' ) or die ( 'Forbidden' );

function wp247_extension_notification_client_admin_sections( $caller ) {
	global $wp247_mobile_detect;
	$sections = array(
		array(
			'id' => 'wp247xns_client_settings',
			'title' => $caller->__( 'Settings' ),
			'desc' => $caller->__( 'Enable or disable extension notification messages for extensions that use the <strong>WP247 Extension Notification System</strong>' )
		),
	);
	return $sections;
}
?>