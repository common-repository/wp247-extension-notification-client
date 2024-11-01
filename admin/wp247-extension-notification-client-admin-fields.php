<?php
// Don't allow direct execution
defined( 'ABSPATH' ) or die ( 'Forbidden' );

function wp247_extension_notification_client_admin_fields( $caller ) {

	global $wp247xns_client_notices;
	$settings = $wp247xns_client_notices->get_settings();
	$new_extensions_enabled = $wp247xns_client_notices->is_new_extensions_enabled();
	
	$settings_fields = array(
		'wp247xns_client_settings' => array(
			array(
				'name' => 'site-disabled',
				'label' => $caller->__( 'Site-wide Extension Notifications' ),
				'intro' => $caller->__( 'You can disable notifications site wide or at the individual extension level.<br/>This option allows you to dsable extension notifications site wide.' ) . '<br/>',
				'desc' => $caller->__( 'Disable Extension Notifications for the entire site.' ),
				'type' => 'checkbox',
			),
			array(
				'name' => 'new-extensions-enabled',
				'label' => $caller->__( 'New Extensions' ),
				'intro' => $caller->__( 'As a security measure, by default, Extension Notifications for new extensions is disabled and you must manually enable those extensions you trust. This option will allow you to automatically enable all new extensions at the time they are activated.' ) . '<br/>',
				'desc' => $caller->__( 'Automatically enable Extension Notifications for new extensions when they are activated.' ),
				'type' => 'checkbox',
				'default' => 'off',
			),
		),
	);

	$fields_set = false;
	$first_notice = true;
	foreach ( $wp247xns_client_notices->get_active_extensions() as $xid => $ext )
	{
		if ( isset( $ext[ 'name' ] ) and isset( $ext[ 'version' ] ) )
		{
			if ( !$fields_set )
			{
				$settings_fields[ 'wp247xns_client_settings' ][] = array(
						'label' => $caller->__( 'Individual Extension Notifications' ),
						'desc' => $caller->__( 'Enable extension notifications for any of the following extensions that use the <strong>WP247 Extension Notification System</strong>, or permanently dismiss individual notices, or view permanently dismissed notices that have not yet expired.' ) . '<br/>',
						'type' => 'html',
					);
				$fields_set = true;
			}
			$fields = array();
			$fields[] = array(
						'name' => 'enabled',
						'label' => '',
						'desc' => sprintf( $caller->__( 'Enable extension notifications for %1$s %2$s' ), $ext[ 'type' ], $ext[ 'name' ] ),
						'type' => 'checkbox',
						'default' => ( $new_extensions_enabled ? 'on' : 'off' ),
						'options' => array(
										 'actions' => array( 'Status' => array( 'class' => 'extension-status', 'data' => $xid )
															,'Refresh' => array( 'class' => 'extension-refresh', 'data' => $xid )
															,'Reset' => array( 'class' => 'extension-reset', 'data' => $xid )
															)
										,'actions-hidden' => true
										)
					);
			$fields[] = array(
						'name' => 'frequency',
						'label' => '',
						'intro' => $caller->__( 'Override how often to check for new notices' ),
						'desc' => sprintf( $caller->__( 'Extension default is: %s' ), $ext[ 'frequency' ] ),
						'type' => 'text',
						'size' => 'medium',
					);
			if ( isset( $ext[ 'notices' ] )
			 and is_array( $ext[ 'notices' ] )
			 and count( $ext[ 'notices' ] ) > 0
			)
			{
				if ( $first_notice )
				{
					$first_notice = false;
				}
				$fields[] = array(
							'desc' => $caller->__( 'Dismiss any of the following notices' ),
							'type' => 'html',
						);
				foreach ( $ext[ 'notices' ] as $notice )
				{
					$nid = $notice[ 'id' ];
					$fields[] = array(
								'name' => 'notices/' . $nid . '/dismissed',
								'label' => '',
								'desc' => $notice[ 'title' ],
								'type' => 'checkbox',
								'class' => 'indent',
								'default' => ( isset( $notice[ 'dismissed' ] ) and $notice[ 'dismissed' ] ) ? 'on' : 'off',
								'options' => array(
												 'actions' => array( 'View Notice' => array( 'class' => 'notice-view', 'data' => $xid.'/'.$nid ) )
												,'actions-hidden' => true
												)
							);
				}
			}
			$class = 'is-enabled';
			if ( (  isset( $settings[ 'extension-settings' ][ $xid ][ 'enabled' ] ) and 'off' == $settings[ 'extension-settings' ][ $xid ][ 'enabled' ] )
			  or ( !isset( $settings[ 'extension-settings' ][ $xid ][ 'enabled' ] ) and !$new_extensions_enabled )
			) $class = 'is-disabled';
			$settings_fields[ 'wp247xns_client_settings' ][]
				= array(
						'name' => 'extension-settings/'.$xid,
						'label' => $ext[ 'name' ],
						'desc' => '',
						'type' => 'group',
						'fields' => $fields,
						'options' => array( 'collapse' => true, 'class' => $class ),
					);
		}
	}
	if ( !$fields_set )
	{
		$settings_fields[ 'wp247xns_client_settings' ][]
			= array(
				'name' => 'extension-settings',
				'label' => $caller->__( 'Individual Extension Notifications' ),
				'desc' => $caller->__( 'There are currently no extensions using the WP247 Extension Notification System' ),
				'type' => 'html',
			);
	}
	else
	{
		$settings_fields[ 'wp247xns_client_settings' ][]
			= array(
				'name' => 'extension-settings',
				'type' => 'html',
				'desc' => '<span class="wp247sapi-action-item extension-refresh" style="margin-right: 10px;"><a class="button-primary" href="#" data="refresh-all">'.$caller->__( 'Refresh All' ).'</a></span>'
						. '<span class="wp247sapi-action-item extension-reset"><a class="button-primary" href="#" data="reset-all">'.$caller->__( 'Reset All' ).'</a></span>',
			);
	}

	return $settings_fields;
}
?>