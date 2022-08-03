



window.licenceStatus = function( action, id, that ) {
  if( action == 'sts' ) {
    let status = that.checked;
    jQuery.ajax( {
      url: 'admin-ajax.php',
      method: 'POST',
      // headers: {},
      // cache: false,
      // contentType: 'json',
      processData: true,
      data: {
        action: 'licence_verification_api_status_toggle',
        // _nonce: '',
        licence: id,
        status: ( status ) ? 'off' : 'on'
      },
      success: function( res ) {
        console.log( res );
      },
      error: function( err, code ) {}
    } );
  } else if( action == 'see' ) {

    var details = that.getAttribute( 'data-details' );
    details = JSON.parse( details );
    let msg = 'Client Name: ' + details.fullname + '\nProduct: ' + details.product + '\nLicence Key: ' + details.ID + '\nlabel: ' + details.label + ',\t Status: ' + details.lc_status + '\nSubscription type: ' + details.lc_type + ' version\nFired total: ' + details.counted + ' times\nCreated time: ' + details.createdon;
    if( confirm( msg ) ) {
      if( typeof copy ===  'function' && copy( msg ) ) {
        console.log( 'Details copied to clipboard' );
      } else {
        // navigator.clipboard.writeText( 'Hellow everyone' );
      }
    }
    
  } else if( action == 'del' ) {
    let pass = prompt( 'Are you sure you want ot erase this licence from your database permanenty?\nPlease make sure, because this can\'t be undone or recovered and you might face problem sooner if you\'re wrong.\nAnyway, please give your login information here to confirm deletation.' );
    if( ! pass ) {return;}
    else {
      jQuery.ajax( {
        url: 'admin-ajax.php',
        method: 'POST',
        // headers: {},
        // cache: false,
        // contentType: 'json',
        processData: true,
        data: {
          action: 'licence_verification_api_remove',
          // _nonce: '',
          licence: id,
          ps: pass
        },
        success: function( res ) {
          console.log( res );
        },
        error: function( err, code ) {}
      } );
    }
  } else {}
}












// jQuery( document ).on( 'ready', function() {
//   jQuery( "input[data-type=switch]" ).bootstrapSwitch( {
//     onSwitchChange: function( e, state ) { 
//         console.log(state);
//     }
//   } );
// } );
