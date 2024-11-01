<?php
/***
 * Get Extension Status
 */

// Don't allow direct execution
defined( 'ABSPATH' ) or die( 'Forbidden' );

check_ajax_referer( 'wp247xns-client-admin-ajax-nonce', 'security' );

if ( !is_admin() or !current_user_can( 'manage_options' ) or !isset( $_POST[ 'xid' ] ) ) wp_die();

$xid = $_POST[ 'xid' ];

$extensions = get_option( 'wp247xns_client_extensions', array() );

if ( !isset( $extensions[ $xid ] ) ) wp_die();

$fields = array_merge(
			array(
				 'name'									=> ''
				,'id'									=> $xid
				,'type'									=> ''
				,'version'								=> ''
				,'server_url'							=> ''
				,'frequency'							=> ''
				,'next_check'							=> ''
				,'last_checked'							=> ''
				,'last_checked_success'					=> ''
				,'last_check_http_response_code'		=> ''
				,'last_check_http_response_message'		=> ''
				,'last_check_server_response_code'		=> ''
			)
			,$extensions[ $xid ]
		);

$content = '<div class="notice notice-info wp247xns-client-extension-status"><h3>'.$fields[ 'name' ].'</h3><div class="wp247xns-client-extension-status-table"><table>';
unset( $fields[ 'name' ] );
if ( isset( $fields[ 'notices' ] ) ) unset( $fields[ 'notices' ] );
foreach ( $fields as $key => $value )
{
	if ( is_array( $value ) ) $value = '<pre>' . htmlspecialchars( var_export( $value, true ) ) . '</pre>';
	else $value = htmlspecialchars( $value );
	$content .= '<tr><th>' . ucwords( str_replace( '_', ' ', $key ) ) . '</th><td>' . $value . '</td></tr>';
}
$content .= '</table></div></div>';

$return = array(
				 'response' => 'OK'
				,'content' => $content
			);

ob_clean();
wp_die( json_encode( $return ) );