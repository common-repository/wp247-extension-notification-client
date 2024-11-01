=== WP247 Extension Notification Client ===
Contributors: wescleveland
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RM26LBV2K6NAU
Tags: extension, plugin, theme, notice, notification, message
Requires at least: 4.0
Requires PHP: 5.6.31
Tested up to: 4.9.1
Stable tag: 1.0.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides the ability for WordPress extension developers to send notification messages to their users

== Description ==

= OVERVIEW =

The strength of WordPress is in it's ability to be customized through the use of extensions (plugins and themes).

The **WP247 Extension Notification System** provides a standard interface for WordPress extension developers to communicate important information about their extension to their extension users.

The *Settings* page provides you with complete control over which extensions the **WP247 Extension Notification System** will communicate with and which notices will continue to be displayed.

= NOTICES =

The **WP247 Extension Notification System** will display notices until they are dismissed. The extension developer assigns one of three dismissibility types to each notice they send:

- **None** indicates that the notice is not dismissible. The *dismiss* button will not appear.
- **Temporary** indicates that the notice will be temporarily dismissed when you click the *dismiss* button. Once dismissed, the notice will not appear until the next time the Admin page is refreshed.
- **Permanent** indicates that the notice will be permanently dismissed when you click the *dismiss* button. Once dismissed, the notice will not appear again.

Regardless of the dimissibility setting chosen by the extension developer, you can permanently dismiss any notice by clicking the *Permanently Dismiss* link at the lower right of the notice or by checking the checkbox next to the notice title in the **WP247 Extension Notification Client**'s *Settings* section.

= SETTINGS =

Use the *Settings* page to control how the **WP247 Extension Notification System** operates on your site.

**Site-wide Extension Notifications**

Checking the *Disable Extension Notifications for the entire site* checkbox will stop the **WP247 Extension Notification System** from checking for new notices and from displaying any notices that have not been dismissed.

**New Extensions**

As a security measure, by default, new extensions are disabled from using the **WP247 Extension Notification System**. You may opt-in to allowing new extensions to be enabled to use the **WP247 Extension Notification System** at the time they are activated by checking the *Automatically enable Extension Notifications for new extensions when they are activated* checkbox. Otherwise, each individual extension may be enabled or disabled as you choose by unchecking or checking the *Disable extension notifications for*... checkbox within the individual extension's settings.

**Individual Extension Notifications**

Each plugin or theme that uses the **WP247 Extension Notification System** is listed in the *Individual Extension Notifications* section. For each extension you can:

- Immediately cease the periodic checking for new notices, and discontinue displaying any notices that have not already been dismissed by checking the *Disable extension notifications for*... checkbox.
- View the extension's status by clicking the *Status* link under the extension name.
- Refresh the extension's notices by clicking the *Refresh* link under the extension name. This will result in the deletion of all notices and then calling the extension's server to retrieve any outstanding notices if the extension is enabled to use the **WP247 Extension Notification System**.
- Reset the extension by clicking the *Reset* link under the extension name. This will result in the deletion of the extension from within the **WP247 Extension Notification System**'s settings. The extension will then be re-added using default settings and, if the *Automatically enable Extension Notifications for new extensions when they are activated* checkbox is checked, the extension will be re-enabled to use the **WP247 Extension Notification System**.
- Change the frequency that periodic checking for new notices will be performed by entering a new value in the box under the *Override how often to check for new notices* label. You can enter values like:
	- "8 hours" to check once every 8 hours
	- "2 days" to check once every other day
	- "1 week" to check once each week
- Permanently dismiss individual notices that have not already been dismissed by checking the checkbox next to the notice title.
- View individual notices by clicking the *View Notice* link under the notice title.
- Refresh all extensions' notices by clicking the *Refresh All* button. This will result in the deletion of all notices for all extensions and then calling all extensions' servers to retrieve any outstanding notices for those extensions that are enabled to use the **WP247 Extension Notification System**.
- Reset all extensions by clicking the *Reset All* button. This will result in the deletion of all extensions from within the **WP247 Extension Notification System**'s settings. The extensions will then be re-added using default settings and, if the *Automatically enable Extension Notifications for new extensions when they are activated* checkbox is checked, the extensions will be re-enabled to use the **WP247 Extension Notification System**.

= PRIVACY POLICY =

Occasionally, the **WP247 Extension Notification System** will contact participating extension's servers in order to see if they have any new notices for your review. The only data sent to their server is the ID of their extension, and the last time we contacted their server. As an example, the **WP247 Extension Notification Client**'s extension id is *wp247-extension-notification-client*.

Rest assured that the **WP247 Extension Notification System** does not capture any information about your site and does not send any information about your site when servers are polled for new notices.

In addition, you can control which extensions that use the **WP247 Extension Notification System** may or may not be enabled to participate in issuing you notices.

== Installation ==

In the WordPress backend:

- Go to Plugins->Add New
- Search for the plugin 'wp247 Extension Notification Client'
- Click the "Install" button
- Click on "Activate"

That's it. You're now ready to receive extension notification messages.

== Screenshots ==

1. Extension Notification Client Settings
2. Extension Notification Client Individual Extensions Settings
3. Extension Notification Client Help - Overview
4. Extension Notification Client Help - Notices
5. Extension Notification Client Help - Settings
6. Extension Notification Client Help - Privacy Policy

== Changelog ==

= 1.0.1 =
Fix Settings API bug

= 1.0 =
Initial release

== Upgrade Notice ==

= 1.0 =
Initial release