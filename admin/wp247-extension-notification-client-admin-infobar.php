<?php
// Don't allow direct execution
defined( 'ABSPATH' ) or die ( 'Forbidden' );

function wp247_extension_notification_client_admin_infobar( $caller ) {
	return array( ''.$caller->__('About this plugin') => '
<ul>
	<li><a href="http://wp247.net/wp247-extension-notification-system/" target="_blank">'.$caller->__('Plugin background').'</a></li>
	<li><a href="http://wordpress.org/support/plugin/wp247-extension-notification-client" target="_blank">'.$caller->__('Plugin support').'</a></li>
	<li><a href="http://wordpress.org/support/view/plugin-reviews/wp247-extension-notification-client" target="_blank">'.$caller->__('Review this plugin').'</a></li>
</ul>'
, ''.$caller->__('Enjoy this plugin?') => '
<p>'.$caller->__('If you find this plugin useful, would you consider making a donation to one or more of my favorite causes?').'</p>
<p><a class="wp247sapi-button button-primary" href="http://www.ijm.org/make-gift/" target="_blank">'.$caller->__('Help rescue the oppressed').'</a></p>
<p><a class="wp247sapi-button button-primary" href="http://www.compassion.com/donate.htm" target="_blank">'.$caller->__('Show compassion on an impoverished child').'</a></p>
<p><a class="wp247sapi-button button-primary" href="https://thelastwell.org/" target="_blank">'.$caller->__('Give someone clean and safe drinking water').'</a></p>
<p><a class="wp247sapi-button button-primary" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RM26LBV2K6NAU" target="_blank">'.$caller->__('Buy me a coffee :)').'</a></p>
' );
}
function wp247_extension_notification_client_admin_infobar_width() {
	return 15;
}
?>
