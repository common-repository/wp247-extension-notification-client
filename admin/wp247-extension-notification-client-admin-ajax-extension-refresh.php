<?php
/***
 * Do Extension Refresh
 */

// Don't allow direct execution
defined( 'ABSPATH' ) or die( 'Forbidden' );

check_ajax_referer( 'wp247xns-client-admin-ajax-nonce', 'security' );

if ( !is_admin() or !current_user_can( 'manage_options' ) or !isset( $_POST[ 'xid' ] ) ) wp_die();

$return = array( 'response' => 'OK' );

$xid = $_POST[ 'xid' ];

$extensions = get_option( 'wp247xns_client_extensions', array() );

if ( 'refresh-all' == $xid )
{
	$settings = get_option( 'wp247xns_client_settings', array() );
	foreach ( $extensions as $xid => $extension )
	{
		$extensions[ $xid ][ 'next_check' ] = '';
		$extensions[ $xid ][ 'last_checked_success' ] = '';
	}
	foreach ( $settings[ 'extension-settings' ] as $xid => $setting )
	{
		if ( isset( $setting[ 'notices' ] ) and !empty( $setting[ 'notices' ] ) )
		{
			$settings[ 'extension-settings' ][ $xid ][ 'notices' ] = array();
		}
	}
	update_option( 'wp247xns_client_settings', $settings );
	update_option( 'wp247xns_client_extensions', $extensions );
	$return[ 'reload' ] = true;
}
else if ( isset( $extensions[ $xid ] ) )
{
	$extensions[ $xid ][ 'next_check' ] = '';
	$extensions[ $xid ][ 'last_checked_success' ] = '';
	update_option( 'wp247xns_client_extensions', $extensions );
	$settings = get_option( 'wp247xns_client_settings', array() );
	if ( isset( $settings[ 'extension-settings' ][ $xid ][ 'notices' ] )
	 and !empty( $settings[ 'extension-settings' ][ $xid ][ 'notices' ] )
	)
	{
		$settings[ 'extension-settings' ][ $xid ][ 'notices' ] = array();
		update_option( 'wp247xns_client_settings', $settings );
	}
	$return[ 'reload' ] = true;
}

ob_clean();
wp_die( json_encode( $return ) );