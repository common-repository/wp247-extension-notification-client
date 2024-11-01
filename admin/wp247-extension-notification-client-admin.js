/*
 * wp247 Extension Notification System Client Javascript
*/
jQuery( document ).ready( function($)
{
	// Handle Admin Extension Status request
	$('.wp247sapi-action-item.extension-status a').click( function() {
		var xid = $(this).attr('data');
		if ( undefined != xid )
		{
			$.ajax( {
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'wp247xns_client_admin_extension_status',
					security: wp247xns_client_admin_ajax_nonce,
					xid: xid
				},
				dataType: 'html',
				success: function ( response ) {
					var resp = JSON.parse( response );
					$('#wp247xns_client_content').html(resp.content);
					$('#wp247xns_client_blackout').fadeIn();
					$('#wp247xns_client_dialog').fadeIn();
				},
				error: function( response ) {
alert( 'Error: '+JSON.stringify(response) );
				},
				async: true
			});
		}
		return false;
	});

	// Handle Admin Refresh request
	$('.wp247sapi-action-item.extension-refresh a').click( function() {
		var xid = $(this).attr('data');
		if ( undefined != xid )
		{
			$.ajax( {
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'wp247xns_client_admin_extension_refresh',
					security: wp247xns_client_admin_ajax_nonce,
					xid: xid
				},
				dataType: 'html',
				success: function ( response ) {
					var resp = JSON.parse( response );
					if ( resp.reload )
					{
						window.location.reload();
					}
				},
				error: function( response ) {
alert( 'Error: '+JSON.stringify(response) );
				},
				async: true
			});
		}
		return false;
	});

	// Handle Admin Reset request
	$('.wp247sapi-action-item.extension-reset a').click( function() {
		var xid = $(this).attr('data');
		if ( undefined != xid )
		{
			$.ajax( {
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'wp247xns_client_admin_extension_reset',
					security: wp247xns_client_admin_ajax_nonce,
					xid: xid
				},
				dataType: 'html',
				success: function ( response ) {
					var resp = JSON.parse( response );
					if ( resp.reload )
					{
						window.location.reload();
					}
				},
				error: function( response ) {
alert( 'Error: '+JSON.stringify(response) );
				},
				async: true
			});
		}
		return false;
	});

	// Handle Admin View Notice request
	$('.wp247sapi-action-item.notice-view a').click( function() {
		var nid = $(this).attr('data');
		if ( undefined != nid )
		{
			$.ajax( {
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'wp247xns_client_admin_view_notice',
					security: wp247xns_client_admin_ajax_nonce,
					nid: nid
				},
				dataType: 'html',
				success: function ( response ) {
					var resp = JSON.parse( response );
					$('#wp247xns_client_content').html(resp.notice);
					$('#wp247xns_client_blackout').fadeIn();
					$('#wp247xns_client_dialog').fadeIn();
				},
				error: function( response ) {
				},
				async: true
			});
		}
		return false;
	});

	// Setup and handle blackout dialog
	$('body').append('<div id="wp247xns_client_blackout"></div>');
	$('body').append('<div id="wp247xns_client_dialog"><div id="wp247xns_client_close"><span class="dashicons dashicons-no"></span></div><div class="clear"></div><div id="wp247xns_client_content"></div></div>');
	$('body').on('click', '#wp247xns_client_close, #wp247xns_client_blackout',  function() {
		$('#wp247xns_client_dialog').fadeOut();
		$('#wp247xns_client_blackout').fadeOut();
	});

} );