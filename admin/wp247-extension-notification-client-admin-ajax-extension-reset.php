<?php
/***
 * Do Extension Reset
 */

// Don't allow direct execution
defined( 'ABSPATH' ) or die( 'Forbidden' );

check_ajax_referer( 'wp247xns-client-admin-ajax-nonce', 'security' );

if ( !is_admin() or !current_user_can( 'manage_options' ) or !isset( $_POST[ 'xid' ] ) ) wp_die();

$return = array( 'response' => 'OK' );

$xid = $_POST[ 'xid' ];

$extensions = get_option( 'wp247xns_client_extensions', array() );

if ( 'reset-all' == $xid )
{
	$settings = get_option( 'wp247xns_client_settings', array() );
	unset( $settings[ 'extension-settings' ] );
	update_option( 'wp247xns_client_settings', $settings );
	update_option( 'wp247xns_client_extensions', array() );
	$return[ 'reload' ] = true;
}
else if ( isset( $extensions[ $xid ] ) )
{
	$settings = get_option( 'wp247xns_client_settings', array() );
	unset( $extensions[ $xid ] );
	if ( isset( $settings[ 'extension-settings' ][ $xid ] ) )
	{
		unset( $settings[ 'extension-settings' ][ $xid ] );
		update_option( 'wp247xns_client_settings', $settings );
	}
	update_option( 'wp247xns_client_extensions', $extensions );
	$return[ 'reload' ] = true;
}

ob_clean();
wp_die( json_encode( $return ) );