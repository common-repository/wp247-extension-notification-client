<?php
/*
	Extension Name: WP247 Extension Notification Client Notices Functions
	Version: 1.0
	Description: Provides the ability for extension developers to send notification messages to their extension users

	Tags: extension, notice, notification, message
	Author: Wes Cleveland
	Author URI: http://wp247.net/
	Uses: weDevs Settings API wrapper class from http://tareq.weDevs.com Tareq's Planet
*/

// Don't allow direct execution
defined( 'ABSPATH' ) or die( 'Forbidden' );

if ( !class_exists( 'WP247_extension_notification_client_notices' ) )
{
/* Skip namespace usage due to errors
	class WP247_extension_notification_client_settings extends \wp247sapi\WP247_settings_API
*/
	class WP247_extension_notification_client_notices
	{
		const MAX_FREQUENCY						= 31536000;		// One Year
	
		const EXTENSION_TYPES					= array( 'plugin', 'theme' );

		const EXTENSION_POLL_KEYS				= array(
													 'id'			=> ''
													,'version'		=> ''
													,'name'			=> ''
													,'type'			=> ''
													,'server_url'	=> ''
													,'frequency'	=> '1 day'
												);

		const EXTENSION_POLL_REQUIRED_KEYS		= array(
													 'id'			=> ''
													,'name'			=> ''
													,'type'			=> ''
													,'server_url'	=> ''
												);

		const NOTICE_TYPES						= array( 'info', 'success', 'warn', 'error', 'nag' );

		const NOTICE_DISMISS					= array( 'none', 'temp', 'perm' );

		const NOTICE_KEYS						= array(
													 'id'		=> ''
													,'title'	=> ''
													,'type'		=> ''
													,'dismiss'	=> ''
													,'date'		=> ''
													,'expires'	=> ''
													,'content'	=> ''
												);

		private $extension_poll				= null;
	
		private $extensions					= null;

		private $active_extension_ids		= null;

		private $active_extensions			= null;

		private $active_plugins				= null;
		
		private $active_themes				= null;
		
		private $settings					= null;

		/**
		 * Class constructor
		 *
		 * Prepare each instance for use - should only happen once.
		 *
		 * @return void
		 */
		function __construct()
		{
			$this->get_settings();
			add_action( 'wp_loaded', array( $this, 'do_action_wp_loaded' ) );
			add_action( 'admin_head', array( $this, 'do_action_admin_head' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'do_action_admin_enqueue_scripts' ) );
			if ( !$this->is_site_disabled() )
				add_action( 'admin_notices', array( $this, 'do_action_admin_notices' ) );
		}

		/**
		 * Get extension notices if required
		 *
		 * @return void
		 */
		function do_action_wp_loaded()
		{
			$this->get_active_extensions();
			$this->do_extension_notice_check();
		}

		/**
		 * Get extension notices if required
		 *
		 * @return void
		 */
		private function do_extension_notice_check()
		{
			if ( $this->is_site_disabled() ) return;

			// Check to see if any updates need to be performed
			$default_date = gmdate( 'Y-m-d' );
			$default_expire = gmdate( 'Y-m-d', strtotime( '1 day' ) );
			$now = gmdate( 'Y-m-d H:i:s' );
			$tomorrow = gmdate( 'Y-m-d H:i:s', strtotime( '1 day' ) );
			$settings_updated = false;
			$extensions_updated = false;
			foreach ( $this->extension_poll as $xid => $junk )		// Only process active extensions
			{
				$extension = $this->extensions[ $xid ];
				if ( !isset( $extension[ 'last_checked' ] ) or !isset( $extension[ 'next_check' ] ) or $extension[ 'next_check' ] <= $now )
				{
					if ( !isset( $this->settings[ 'extension-settings' ][ $xid ][ 'enabled' ] ) )
					{
						if ( !isset( $this->settings[ 'extension-settings' ] ) )
							$this->settings[ 'extension-settings' ] = array();
						if ( !isset( $this->settings[ 'extension-settings' ][ $xid ] ) )
							$this->settings[ 'extension-settings' ][ $xid ] = array();
						$this->settings[ 'extension-settings' ][ $xid ][ 'enabled' ]
							= $this->is_new_extensions_enabled() ? 'on' : 'off';
						$settings_updated = true;
						$extensions_updated = true;
					}

					if ( 'off' == $this->settings[ 'extension-settings' ][ $xid ][ 'enabled' ] )
						continue;

					$body = array(
								 'action'		=> 'wp247xns_server_inquire'
								,'id'			=> $extension[ 'id' ]
								,'version'		=> $extension[ 'version' ]
							);

					if ( isset( $extension[ 'last_checked_success' ] ) and !empty( $extension[ 'last_checked_success' ] ) )
						$body [ 'since' ] = $extension[ 'last_checked_success' ];

					$args = array(
								 'body'			=> $body
								,'timeout'		=> '5'
								,'redirection'	=> '5'
								,'httpversion'	=> '1.0'
								,'blocking'		=> true
								,'headers'		=> array()
								,'cookies'		=> array()
							);

					$resp = wp_safe_remote_post( $extension[ 'server_url' ], $args );

					$this->extensions[ $xid ][ 'next_check' ] = $tomorrow;
					$this->extensions[ $xid ][ 'last_checked' ] = $now;
					$this->extensions[ $xid ][ 'last_check_http_response_code' ] = wp_remote_retrieve_response_code( $resp );
					$this->extensions[ $xid ][ 'last_check_http_response_message' ] = wp_remote_retrieve_response_message( $resp );

					if( isset( $this->extensions[ $xid ][ 'last_check_server_response_code' ]  ) )
						unset( $this->extensions[ $xid ][ 'last_check_server_response_code' ]  );

					if( isset( $this->extensions[ $xid ][ 'last_check_server_response' ]  ) )
						unset( $this->extensions[ $xid ][ 'last_check_server_response' ]  );

					$extensions_updated = true;

					if ( !is_wp_error( $resp ) and '200' == wp_remote_retrieve_response_code( $resp ) )
					{
						$resp = json_decode( wp_remote_retrieve_body( $resp ), true );
						$this->extensions[ $xid ][ 'last_check_server_response' ] = $resp;
						if ( isset( $resp[ 'response' ] ) )
						{
							$this->extensions[ $xid ][ 'last_check_server_response_code' ] = $resp[ 'response' ];
							if ( 'OK' == $resp[ 'response' ] )
							{
								if ( isset( $resp[ 'timestamp' ] )
								 and $resp[ 'timestamp' ] == date( 'Y-m-d H:i:s', strtotime( $resp[ 'timestamp' ] ) )
								)
									$this->extensions[ $xid ][ 'last_checked_success' ]	= $resp[ 'timestamp' ];
								else $this->extensions[ $xid ][ 'last_checked_success' ] = $now;

								if ( isset( $resp[ 'server-url' ] )
								 and $resp[ 'server-url' ] == esc_url_raw( $resp[ 'server-url' ] )
								)
									$this->extensions[ $xid ][ 'server_url' ] = $resp[ 'server-url' ];

								if ( isset( $resp[ 'frequency' ] )
								 and false != strtotime( $resp[ 'frequency' ], 0 )
								 and strtotime( $resp[ 'frequency' ], 0 ) < self::MAX_FREQUENCY
								)
									$this->extensions[ $xid ][ 'frequency' ] = $resp[ 'frequency' ];

								if ( isset( $this->settings[ 'extension-settings' ][ $xid ][ 'frequency' ] )
								 and !empty( $this->settings[ 'extension-settings' ][ $xid ][ 'frequency' ] )
								)
								{
									$next = strtotime( $this->settings[ 'extension-settings' ][ $xid ][ 'frequency' ] );
								}
								else $next = strtotime( $this->extensions[ $xid ][ 'frequency' ] );

								if ( $next <= self::MAX_FREQUENCY )
									$next += strtotime( $this->extensions[ $xid ][ 'last_checked_success' ] );
								$this->extensions[ $xid ][ 'next_check' ] = date( 'Y-m-d H:i:s', $next );

								if ( isset( $resp[ 'reset' ] ) and true === $resp[ 'reset' ] )
									$this->extensions[ $xid ][ 'notices' ] = array();

								if ( isset( $resp[ 'notices' ] ) and is_array( $resp[ 'notices' ] ) )
								{
									foreach( $resp[ 'notices' ] as $notice )
									{
										if ( !isset( $notice[ 'function' ] ) ) continue;
										$function = $notice[ 'function' ];
										$notice = array_intersect_key( $notice, self::NOTICE_KEYS );
										if ( !isset( $notice[ 'id' ] ) ) continue;
										$nid = $notice[ 'id' ];
										if ( 'delete' == $function )
										{
											if ( isset( $this->extensions[ $xid ][ 'notices' ][ $nid ] ) )
												unset( $this->extensions[ $xid ][ 'notices' ][ $nid ] );
										}
										else if ( 'add' == $function )
										{
											if ( $nid != sanitize_title_with_dashes( $nid, $nid, 'db' ) ) continue;
											if ( !isset( $notice[ 'content' ] ) or empty( $notice[ 'content' ] ) ) continue;
											if ( !isset( $notice[ 'type' ] ) or empty( $notice[ 'type' ] ) )
												$notice[ 'type' ] = 'info';
											if ( !in_array( $notice[ 'type' ], self::NOTICE_TYPES ) ) continue;
											if ( !isset( $notice[ 'dismiss' ] ) or empty( $notice[ 'dismiss' ] ) )
												$notice[ 'dismiss' ] = 'perm';
											if ( !in_array( $notice[ 'dismiss' ], self::NOTICE_DISMISS ) ) continue;
											if ( !isset( $notice[ 'date' ] ) )
												$notice[ 'date' ] = $default_date;
											if ( $notice[ 'date' ] != date( 'Y-m-d', strtotime( $notice[ 'date' ] ) ) ) continue;
											if ( !isset( $notice[ 'expires' ] ) )
												$notice[ 'expires' ] = $default_expire;
											if ( $notice[ 'expires' ] != date( 'Y-m-d', strtotime( $notice[ 'expires' ] ) ) ) continue;
											$notice[ 'title' ] = sanitize_text_field( $notice[ 'title' ] );
											$notice[ 'content' ] = wp_kses_post( $notice[ 'content' ] );
											if ( !isset( $this->extensions[ $xid ][ 'notices' ] ) )
												$this->extensions[ $xid ][ 'notices' ] = array();
											$this->extensions[ $xid ][ 'notices' ][ $nid ] = $notice;
										}
									}
								}
							}
						}
					}
				}
			}
			if ( $settings_updated ) $this->update_settings();
			if ( $extensions_updated ) $this->update_extensions();
		}

		/**
		 * Output Custom scripts and styles
		 *
		 * @return void
		 */
		function do_action_admin_head()
		{
			$nonce = wp_create_nonce( 'wp247xns-client-ajax-nonce' );
?>
	<script type="text/javascript">var wp247xns_client_ajax_nonce = '<?php echo $nonce; ?>';</script>
<?php
		}

		/**
		 * Enqueue scripts and styles
		 *
		 * @return void
		 */
		function do_action_admin_enqueue_scripts()
		{
/*
			$params = array(
			  'ajaxurl' => admin_url( 'admin-ajax.php' ),
			  'ajax_nonce' => wp_create_nonce( 'wp247xns-client-ajax-nonce' ),
			);
			wp_localize_script( 'wp247xns-client-ajax-script', 'wp247xns_client_params', $params );
			wp_enqueue_script( 'wp247xns-client-ajax-script' );
*/
			wp_enqueue_style( 'wp247xns-client-style', plugins_url( 'wp247-extension-notification-client-notices.css', __FILE__ ) );
			wp_enqueue_script( 'wp247xns-client-script', plugins_url( 'wp247-extension-notification-client-notices.js', __FILE__ ), array( 'jquery' ) );
		}

		/**
		 * Show any active extension notices
		 *
		 * @return void
		 */
		function do_action_admin_notices()
		{
			$now = gmdate( 'Y-m-d H:i:s' );
			$extensions_updated = false;
			foreach ( $this->extension_poll as $xid => $extension )
			{
				$extension = $this->extensions[ $xid ];
				if ( isset( $extension[ 'notices' ] ) )
				{
					foreach ( $extension[ 'notices' ] as $nid => $notice )
					{
						if ( isset( $notice[ 'expires' ] ) and $notice[ 'expires' ] <= $now )
						{
							unset( $this->extensions[ $xid ][ 'notices' ][ $nid ] );
							$extensions_updated = true;
							continue;
						}

						if ( !isset( $this->settings[ 'extension-settings' ][ $xid ][ 'enabled' ] )
						 or 'off' == $this->settings[ 'extension-settings' ][ $xid ][ 'enabled' ]
						) continue;

						if ( isset( $this->settings[ 'extension-settings' ][ $xid ][ 'notices' ][ $nid ][ 'dismissed' ] )
						 and 'on' == $this->settings[ 'extension-settings' ][ $xid ][ 'notices' ][ $nid ][ 'dismissed' ]
						) continue;

						$class = 'wp247xns-notice notice';
						if ( 'nag' == $notice[ 'type' ] ) $class .= ' update-nag';
						else $class .= ' notice-' . $notice[ 'type' ];
						if ( 'none' != $notice['dismiss'] ) $class .= ' is-dismissible';
						$perm_dismiss = '';
						if ( 'perm' == $notice['dismiss'] ) $class .= ' wp247xns-client-is-perm-dismissible';
						else $perm_dismiss = '<a><p style="width: 100%; text-align: right;"><span class="dashicons-before dashicons-dismiss wp247xns-client-is-perm-dismissible"> '. __( 'Permenantly Dismiss' ).'</span></a></p>';

						$content =  wpautop( $notice[ 'content' ] );
?>
<div class="<?php echo $class; ?>" data-nid="<?php echo "$xid/$nid"; ?>">
	<div>
		<h3><?php echo htmlspecialchars( $notice[ 'title' ] );?></h3>
		<?php echo $content; ?>
	</div>
	<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
	<div class="clear"></div>
	<p style="width: 100%; text-align: right;"><a><span class="dashicons-before dashicons-dismiss wp247xns-client-is-perm-dismissible"><?php echo __( 'Permenantly Dismiss' ); ?></span></a></p>
</div>
<?php
					}
				}
			}
			if ( $extensions_updated ) $this->update_extensions();
		}

		/**
		 * Update extension field if set in response
		 *
		 * @return void
		 */
		function extension_set_if_not_empty( $resp, $xid, $pvar, $rvar = null )
		{
			if ( is_null( $rvar ) ) $rvar = str_replace( '_', '-', $pvar );
			if ( isset( $resp[ $rvar ] ) and !empty( $resp[ $rvar ] ) )
				$this->extensions[ $xid ][ $pvar ] = $resp[ $rvar ];
		}

		/**
		 * Get settings
		 *
		 * @return array settings
		 */
		function get_settings()
		{
			if ( is_null( $this->settings ) ) $this->settings = get_option( 'wp247xns_client_settings', array() );
			return $this->settings;

		}

		/**
		 * Update settings
		 *
		 * @return void
		 */
		function update_settings()
		{
			update_option( 'wp247xns_client_settings', $this->settings );
		}

		/**
		 * Is site disabled
		 *
		 * @return boolean
		 */
		function is_site_disabled()
		{
			$this->get_settings();
			return ( isset( $this->settings[ 'site-disabled'] )
			  and 'on' == $this->settings[ 'site-disabled']
			);
		}

		/**
		 * Is new extensions enabled
		 *
		 * @return boolean
		 */
		function is_new_extensions_enabled()
		{
			$this->get_settings();
			return ( isset( $this->settings[ 'new-extensions-enabled'] )
				and 'on' == $this->settings[ 'new-extensions-enabled']
			);
		}

		/**
		 * Is extension enabled
		 *
		 * @return boolean
		 */
		function is_extension_enabled( $xid )
		{
			$this->get_settings();
			return ( ( isset( $this->settings[ 'extension-settings' ][ $xid ][ 'enabled' ] )
					  and 'on' == $this->settings[ 'extension-settings' ][ $xid ][ 'enabled' ]
					 )
				  or ( !isset( $this->settings[ 'extension-settings' ][ $xid ][ 'enabled' ] )
					  and $this->is_new_extensions_enabled()
					 )
			);
		}

		/**
		 * Get extensions
		 *
		 * @return array extensions
		 */
		function get_extensions()
		{
			if ( is_null( $this->extensions ) ) $this->extensions = get_option( 'wp247xns_client_extensions', array() );
			return $this->extensions;

		}

		/**
		 * Update extensions
		 *
		 * @return void
		 */
		function update_extensions()
		{
			update_option( 'wp247xns_client_extensions', $this->extensions );
			$this->get_active_extensions( true );
		}

		/**
		 * Poll extensions
		 *
		 * @return array extension_poll
		 */
		function do_extension_poll()
		{
			if ( is_null( $this->extension_poll ) )
			{
				$this->get_extensions();
				$this->extension_poll = array();
				foreach ( $this->get_active_extension_ids() as $ax )
				{
					$xid = $ax[ 'id' ];

					$extension = apply_filters( 'wp247xns_client_extension_poll_'.$ax['type'].'_'.$ax['id'], array() );
					if ( empty( $extension ) ) continue;

					// Skip it if any unknown fields are present
					$ext = array_intersect_key( $extension, self::EXTENSION_POLL_KEYS );
					if ( $ext != $extension ) continue;

					// Skip it if any required fields are missing
					$req = array_intersect_key( $extension, self::EXTENSION_POLL_REQUIRED_KEYS );
					if ( !empty( array_diff_key( $req, self::EXTENSION_POLL_REQUIRED_KEYS ) ) ) continue;

					// Add in default value for optional fields
					$ext = array_merge( self::EXTENSION_POLL_KEYS, $extension );

					if ( $ext[ 'id' ] != $xid ) continue;

					if ( $ext[ 'name' ] != sanitize_text_field( $ext[ 'name' ] ) ) continue;

					if ( $ext[ 'server_url' ] != esc_url_raw( $ext[ 'server_url' ] ) ) continue;

					if ( !in_array( $ext[ 'type' ], self::EXTENSION_TYPES ) ) continue;
					if ( 'plugin' == $ext[ 'type' ] and !isset( $this->active_plugins[ $xid ] ) ) continue;
					if ( 'theme' == $ext[ 'type' ] and !isset( $this->active_themes[ $xid ] ) ) continue;

					$freq = strtotime( $ext[ 'frequency' ], 0 );
					if ( false == $freq or $freq > self::MAX_FREQUENCY ) continue;

					if ( $ext[ 'version' ] != sanitize_text_field( $ext[ 'version' ] ) ) continue;

					$this->extension_poll[ $xid ] = $ext;

					if ( !isset( $this->extensions[ $xid ] ) )
						$this->extensions[ $xid ] = $ext;
				}
			}
			return $this->extension_poll;
		}

		/**
		 * Get active extensions
		 *
		 * @return array active_extensions
		 */
		function get_active_extensions( $rebuild = false )
		{
			if ( is_null( $this->active_extensions ) or $rebuild ) {
				$this->do_extension_poll();
				$this->active_extensions = array();
				foreach ( $this->extension_poll as $xid => $extension )
				{
					$this->active_extensions[ $xid ] = $this->extensions[ $xid ];
				}
			}
			return $this->active_extensions;
		}

		/**
		 * Get active extension IDs
		 *
		 * @return array active_plugins
		 */
		function get_active_extension_ids()
		{
			if ( is_null( $this->active_extension_ids ) )
			{
				$this->active_extension_ids = array();
				foreach( $this->get_active_theme_ids() as $xid => $ext )
				{
					$this->active_extension_ids[] = array( 'id' => $xid, 'type' => 'theme' );
				}
				foreach( $this->get_active_plugin_ids() as $xid => $ext )
				{
					$this->active_extension_ids[] = array( 'id' => $xid, 'type' => 'plugin' );
				}
			}
			return $this->active_extension_ids;
		}

		/**
		 * Get active plugin IDs
		 *
		 * @return array active_plugins
		 */
		function get_active_plugin_ids()
		{
			if ( is_null( $this->active_plugins ) )
			{
				$this->active_plugins = array();
				foreach( get_option( 'active_plugins', array() ) as $plugin )
				{
					$xid = dirname( $plugin );
					$this->active_plugins[ $xid ] = $xid;
				}
			}
			return $this->active_plugins;
		}

		/**
		 * Get ID(s) for the active theme and active child theme
		 *
		 * @return boolean
		 */
		function get_active_theme_ids()
		{
			if ( is_null( $this->active_themes ) )
			{
				$this->active_themes = array();
				$tid = basename( get_template_directory() );
				$this->active_themes[ $tid ] = $tid;
				$cid = basename( get_stylesheet_directory() );
				if ( !empty( $cid ) )
					$this->active_themes[ $cid ] = $cid;
			}
			return $this->active_themes;
		}

	}

	/**
	 * Is extension enabled
	 *
	 * @return boolean
	 */
	function wp247xns_client_is_extension_enabled( $xid )
	{
		global $wp247xns_client_notices;
		return $wp247xns_client_notices->is_extension_enabled( $xid );
	}

	global $wp247xns_client_notices; $wp247xns_client_notices = new WP247_extension_notification_client_notices();
}