<?php
/*
	Extension Name: WP247 Extension Notification Client Admin Functions
	Version: 1.0
	Description: Provides the ability for extension developers to send notification messages to their extension users

	Tags: extension, notice, notification, message
	Author: Wes Cleveland
	Author URI: http://wp247.net/
	Uses: weDevs Settings API wrapper class from http://tareq.weDevs.com Tareq's Planet
*/

// Don't allow direct execution
defined( 'ABSPATH' ) or die( 'Forbidden' );

if ( !class_exists( 'WP247_extension_notification_client_settings' ) )
{
	define( 'WP247XNS_CLIENT_PLUGIN_ADMIN_PATH', plugin_dir_path( __FILE__ ) );
	require_once WP247XNS_CLIENT_PLUGIN_ADMIN_PATH . '/wp247-settings-api/wp247-settings-api.php';

/* Skip namespace usage due to errors
	class WP247_extension_notification_client_settings extends \wp247sapi\WP247_settings_API
*/
	class WP247_extension_notification_client_settings extends WP247_settings_API_2
	{
		/**
		 * Extensions using WP247XNS
		 */
		private $extensions = null;

		/**
		 * WP247XNS Corequisite Notice
		 */
		private $wp247xns_client_corequisite_notice;

		/**
		 * Class Constructor
		 *
		 * @return void
		 */
		function __construct()
		{
			if ( is_admin() and current_user_can( 'manage_options' ) )
			{
				require_once WP247XNS_CLIENT_PLUGIN_ADMIN_PATH . 'wp247xns-client-corequisite-notice/wp247xns-client-corequisite-notice.php';
				$this->wp247xns_client_corequisite_notice = new WP247XNS_Client_Corequisite_Notice( WP247XNS_CLIENT_PLUGIN_NAME, '7 days' );
			}
			parent::__construct();
		}

		/**
		 * Returns the Admin Menu
		 *
		 * @return void
		 */
		function get_settings_admin_menu()
		{
			require_once WP247XNS_CLIENT_PLUGIN_ADMIN_PATH . 'wp247-extension-notification-client-admin-menu.php';
			return wp247_extension_notification_client_admin_menu( $this );
		}

		/**
		 * Returns the Admin Help
		 *
		 * @return void
		 */
		function get_settings_admin_help()
		{
			require_once WP247XNS_CLIENT_PLUGIN_ADMIN_PATH . 'wp247-extension-notification-client-admin-help.php';
			return wp247_extension_notification_client_admin_help( $this );
		}

		/**
		 * Returns the Admin Help Sidebar
		 *
		 * @return void
		 */
		function get_settings_admin_help_sidebar()
		{
			require_once WP247XNS_CLIENT_PLUGIN_ADMIN_PATH . 'wp247-extension-notification-client-admin-help-sidebar.php';
			return wp247_extension_notification_client_admin_help_sidebar( $this );
		}

		/**
		 * Returns all the settings sections
		 *
		 * @return array settings sections
		 */
		function get_settings_sections()
		{
			require_once WP247XNS_CLIENT_PLUGIN_ADMIN_PATH . 'wp247-extension-notification-client-admin-sections.php';
			return wp247_extension_notification_client_admin_sections( $this );
		}

		/**
		 * Returns all the settings fields
		 *
		 * @return array settings fields
		 */
		function get_settings_fields()
		{
			require_once WP247XNS_CLIENT_PLUGIN_ADMIN_PATH . 'wp247-extension-notification-client-admin-fields.php';
			return wp247_extension_notification_client_admin_fields( $this );
		}

		/**
		 * Returns all the settings infobar
		 *
		 * @return array settings infobar
		 */
		function get_settings_infobar()
		{
			require_once WP247XNS_CLIENT_PLUGIN_ADMIN_PATH . 'wp247-extension-notification-client-admin-infobar.php';
			return wp247_extension_notification_client_admin_infobar( $this );
		}

		/**
		 * Returns the infobar width
		 *
		 * @return integer infobar width
		 */
		function get_infobar_width()
		{
			require_once WP247XNS_CLIENT_PLUGIN_ADMIN_PATH . 'wp247-extension-notification-client-admin-infobar.php';
			return wp247_extension_notification_client_admin_infobar_width();
		}

		/**
		 * Admin Head
		 */
		function admin_head()
		{
			$nonce = wp_create_nonce( 'wp247xns-client-admin-ajax-nonce' );
?>
	<script type="text/javascript">var wp247xns_client_admin_ajax_nonce = '<?php echo $nonce; ?>';</script>
<?php
		}

		/**
		 * Enqueue scripts and styles
		 */
		function enqueue_scripts()
		{
			wp_enqueue_style( 'wp247xns-client-admin-styles', plugins_url( 'wp247-extension-notification-client-admin.css', __FILE__ ) );
			wp_enqueue_script( 'wp247xns-client-admin-script', plugins_url( 'wp247-extension-notification-client-admin.js', __FILE__ ), array( 'jquery' ) );
		}

		/**
		 * Returns the head scripts and styles
		 *
		 * @return string head scripts and styles
		 * @return array  head scripts and styles
		 */
		function get_head_scripts()
		{
			return array( '<style> .wp247sapi-form input.indent { margin-left: 32px; } .wp247sapi-actions.indent { margin-left: 58px; } .wp247sapi-group .control-section.is-enabled .accordion-section-title { background: #b3e6b3;} .wp247sapi-group .control-section.is-disabled .accordion-section-title { background: #ffb3b3;}</style>' );
		}

		/**
		 * Get list of WP247XNS_Client extensions
		 *
		 * @return array $this->extensions
		 */
		function get_extensions() {
			global $wp247xns_client_notices;
			if ( is_null( $this->extensions ) )
			{
				$this->extensions = $wp247xns_client_notices->get_active_extensions();
			}
			return $this->extensions;
		}

		/**
		 * Return localized string
		 *
		 * @return string
		 */
		function __( $string )
		{
			return __( $string, WP247XNS_CLIENT_PLUGIN_TEXT_DOMAIN );
		}

		/**
		 * Output localized string
		 *
		 * @return void
		 */
		function _e( $string )
		{
			_e( $string, WP247XNS_CLIENT_PLUGIN_TEXT_DOMAIN );
		}

	}

	global $wp247xns_client_settings; $wp247xns_client_settings = new WP247_extension_notification_client_settings();
}