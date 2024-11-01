<?php

// Don't allow direct execution
defined( 'ABSPATH' ) or die( 'Forbidden' );

check_ajax_referer( 'wp247xns-client-ajax-nonce', 'security' );

if ( !current_user_can( 'manage_options' ) ) wp_die();

if ( !isset( $_POST[ 'nid' ] ) ) wp_die();

$id = explode( '/', $_POST[ 'nid' ] );
if ( count( $id ) != 2 ) wp_die();
$xid = $id[0];
$nid = $id[1];

$settings = get_option( 'wp247xns_client_settings', array() );

if ( !isset( $settings[ 'extension-settings' ][ $xid ] ) )
	$settings[ 'extension-settings' ][ $xid ] = array();

if ( !isset( $settings[ 'extension-settings' ][ $xid ][ 'notices' ] ) )
	$settings[ 'extension-settings' ][ $xid ][ 'notices' ] = array();

if ( !isset( $settings[ 'extension-settings' ][ $xid ][ 'notices' ][ $nid ] ) )
	$settings[ 'extension-settings' ][ $xid ][ 'notices' ][ $nid ] = array();

$settings[ 'extension-settings' ][ $xid ][ 'notices' ][ $nid ][ 'dismissed' ] = 'on';
update_option( 'wp247xns_client_settings', $settings );

wp_die();