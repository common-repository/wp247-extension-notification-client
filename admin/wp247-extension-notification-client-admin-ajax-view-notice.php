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

$extensions = get_option( 'wp247xns_client_extensions', array() );

if ( isset( $extensions[ $xid ][ 'notices' ][ $nid ] ) )
{
	$notice =$extensions[ $xid ][ 'notices' ][ $nid ];
	$title = htmlspecialchars( $notice[ 'title' ] );
	$content = $notice[ 'content' ];

	$class = 'wp247xns-notice notice';
	if ( 'nag' == $notice[ 'type' ] ) $class .= ' update-nag';
	else $class .= ' notice-' . $notice[ 'type' ];
	if ( 'none' != $notice['dismiss'] ) $class .= ' is-dismissible';
	if ( 'perm' == $notice['dismiss'] ) $class .= ' wp247xns-client-is-perm-dismissible';

	$response = array(
					 'response' => 'OK'
					,'notice' => '<div class="'.$class.'"><div class="wp247xns-client-notice-id">'.$xid.'/'.$nid.'</div><div><h3>'.$title.'</h3>'.wpautop($content).'</div><div class="clear"></div><p> </p></div>'
				);
}
else $response = array( 'response' => '404' );

echo json_encode( $response );

wp_die();