/*
 * wp247 Extension Notification System Client Javascript
*/
jQuery( document ).ready( function($)
{
	// Handle Perm dismissibility
	$(window).load( function()
	{
		$( '.wp247xns-client-is-perm-dismissible span.wp247xns-client-is-perm-dismissible, .wp247xns-client-is-perm-dismissible button.notice-dismiss' ).click( function () {
			var notice = $(this).closest( '.wp247xns-notice' );
			var nid = $(notice).attr( 'data-nid' );
			if ( undefined != nid )
			{
				if ( !$(this).parents( '.notice-dismiss' ).length )
				{
					$(notice).fadeOut( 'fast', function() { $(this).remove(); });
				}
				$.ajax( {
					type: 'POST',
					url: ajaxurl,
					data: {
						action: 'wp247xns_client_dismiss_notice',
						security: wp247xns_client_ajax_nonce,
						nid: nid
					},
					dataType: 'html',
					success: function ( response ) {
						var name = 'wp247xns_client_settings[extension-settings][' + nid.replace( '/', '][notices][' ) + '][dismissed]';
						$("input[type='checkbox'][name='"+name+"'][value='on']").prop('checked',true);
					},
					error: function( response ) {
					},
					async: true
				});
			}
			return false;
		});
	});

} );