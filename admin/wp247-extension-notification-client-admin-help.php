<?php
// Don't allow direct execution
defined( 'ABSPATH' ) or die( 'Forbidden' );

function wp247_extension_notification_client_admin_help( $caller )
{
	return array(
				array(
					 'title'	=> $caller->__( 'Overview' )
					,'id'		=> 'wp247xns_client_help_overview'
					,'content'	=> array(
										 $caller->__( 'The strength of WordPress&#174; is in it\'s ability to be customized through the use of extensions (plugins and themes).' )
										,$caller->__( 'The <strong>WP247 Extension Notification System</strong> provides a standard interface for WordPress&#174; extension developers to communicate important information about their extension to their extension users.' )
										,$caller->__( 'The <strong>Settings</strong> page provides you with complete control over which extensions the <strong>WP247 Extension Notification System</strong> will communicate with and which notices will continue to be displayed.' )
									)
				)
				,array(
					 'title'	=> $caller->__( 'Notices' )
					,'id'		=> 'wp247xns_client_help_notices'
					,'content'	=> array(
										 $caller->__( 'The <strong>WP247 Extension Notification System</strong> will display notices until they are dismissed. The extension developer assigns one of three dismissibility types to each notice they send:' )
										,'<ul>'
										,'<li>' . $caller->__( '<strong>None</strong> indicates that the notice is not dismissible. The <i>dismiss</i> button will not appear.' ) . '</li>'
										,'<li>' . $caller->__( '<strong>Temporary</strong> indicates that the notice will be temporarily dismissed when you click the <i>dismiss</i> button. Once dismissed, the notice will not appear until the next time the Admin page is refreshed.' ) . '</li>'
										,'<li>' . $caller->__( '<strong>Permanent</strong> indicates that the notice will be permanently dismissed when you click the <i>dismiss</i> button. Once dismissed, the notice will not appear again.' ) . '</li>'
										,'</ul>'
										,$caller->__( 'Regardless of the dimissibility setting chosen by the extension developer, you can permanently dismiss any notice by clicking the <i>Permanently Dismiss</i> link at the lower right of the notice or by checking the checkbox next to the notice title in the <strong>WP247 Extension Notification Client</strong>\'s <strong>Settings</strong> section.' )
									)
				)
				,array(
					 'title'	=> $caller->__( 'Settings' )
					,'id'		=> 'wp247xns_client_help_settings'
					,'content'	=> array(
										 $caller->__( 'Use the <strong>Settings</strong> page to control how the <strong>WP247 Extension Notification System</strong> operates on your site.' )
										,'<h3>' . $caller->__( 'Site-wide Extension Notifications' ) . '</h3>'
										,$caller->__( 'Checking the <strong>Disable Extension Notifications for the entire site</strong> checkbox will stop the <strong>WP247 Extension Notification System</strong> from checking for new notices and from displaying any notices that have not been dismissed.' )
										,'<h3>' . $caller->__( 'New Extensions' ) . '</h3>'
										,$caller->__( 'As a security measure, by default, new extensions are disabled from using the <strong>WP247 Extension Notification System</strong>. You may opt-in to allowing new extensions to be enabled to use the <strong>WP247 Extension Notification System</strong> at the time they are activated by checking the <strong>Automatically enable Extension Notifications for new extensions when they are activated</strong> checkbox. Otherwise, each individual extension may be enabled or disabled as you choose by checking or unchecking the <strong>Enable extension notifications for</strong>... checkbox within the individual extension\'s settings.' )
										,'<h3>' . $caller->__( 'Individual Extension Notifications' ) . '</h3>'
										,$caller->__( 'Each plugin or theme that uses the <strong>WP247 Extension Notification System</strong> is listed in the <strong>Individual Extension Notifications</strong> section. For each extension you can:' )
										,'<ul>'
										,'<li>' . $caller->__( 'Immediately begin or cease the periodic checking for new notices, and continue or discontinue displaying any notices that have not already been dismissed by checking or unchecking the <strong>Enable extension notifications for</strong>... checkbox.' ) . '</li>'
										,'<li>' . $caller->__( 'View the extension\'s status by clicking the <strong>Status</strong> link under the extension name.' ) . '</li>'
										,'<li>' . $caller->__( 'Refresh the extension\'s notices by clicking the <strong>Refresh</strong> link under the extension name. This will result in the deletion of all notices and then calling the extension\'s server to retrieve any outstanding notices if the extension is enabled to use the <strong>WP247 Extension Notification System</strong>.' ) . '</li>'
										,'<li>' . $caller->__( 'Reset the extension by clicking the <strong>Reset</strong> link under the extension name. This will result in the deletion of the extension from within the <strong>WP247 Extension Notification System</strong>\'s settings. The extension will then be re-added using default settings and, if the <strong>Automatically enable Extension Notifications for new extensions when they are activated</strong> checkbox is checked, the extension will be re-enabled to use the <strong>WP247 Extension Notification System</strong>.' ) . '</li>'
										,'<li>' . $caller->__( 'Change the frequency that periodic checking for new notices will be performed by entering a new value in the box under the <strong>Override how often to check for new notices</strong> label. You can enter values like:' ) . '</li>'
										,'<ul>'
										,'<li>' . $caller->__( '"8 hours" to check once every 8 hours' ) . '</li>'
										,'<li>' . $caller->__( '"2 days" to check once every other day' ) . '</li>'
										,'<li>' . $caller->__( '"1 week" to check once each week' ) . '</li>'
										,'</ul>'
										,'<li>' . $caller->__( 'Permanently dismiss individual notices that have not already been dismissed by checking the checkbox next to the notice title.' ) . '</li>'
										,'<li>' . $caller->__( 'View individual notices by clicking the <strong>View Notice</strong> link under the notice title.' ) . '</li>'
										,'<li>' . $caller->__( 'Refresh all extensions\' notices by clicking the <strong>Refresh All</strong> button. This will result in the deletion of all notices for all extensions and then calling all extensions\' servers to retrieve any outstanding notices for those extensions that are enabled to use the <strong>WP247 Extension Notification System</strong>.' ) . '</li>'
										,'<li>' . $caller->__( 'Reset all extensions by clicking the <strong>Reset All</strong> button. This will result in the deletion of all extensions from within the <strong>WP247 Extension Notification System</strong>\'s settings. The extensions will then be re-added using default settings and, if the <strong>Automatically enable Extension Notifications for new extensions when they are activated</strong> checkbox is checked, the extensions will be re-enabled to use the <strong>WP247 Extension Notification System</strong>.' ) . '</li>'
										,'</ul>'
									)
				)
				,array(
					 'title'	=> $caller->__( 'Privacy Policy' )
					,'id'		=> 'wp247xns_client_help_privacy_policy'
					,'content'	=> array(
										 $caller->__( 'Occasionally, the <strong>WP247 Extension Notification System</strong> will contact participating extension\'s servers in order to see if they have any new notices for your review. The only data sent to their server is the ID of their extension, and the last time we contacted their server. As an example, the <strong>WP247 Extension Notification Client</strong>\'s extension id is <strong>wp247-extension-notification-client</strong>.' )
										,$caller->__( 'Rest assured that the <strong>WP247 Extension Notification System</strong> does not capture any information about your site and does not send any information about your site when servers are polled for new notices.' )
										,$caller->__( 'In addition, you can control which extensions using the <strong>WP247 Extension Notification System</strong> may or may not be enabled to participate in issuing you notices.' )
									)
				)
			);
}