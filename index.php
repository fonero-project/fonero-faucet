<?php

require_once ( dirname ( __FILE__ ) . '/framework/framework.php' );

$_REQUEST [ 'app' ] = ( isset ( $_REQUEST [ 'app' ] ) ) ? $_REQUEST [ 'app' ] : 'faucet';
$_REQUEST [ 'mod' ] = ( isset ( $_REQUEST [ 'mod' ] ) ) ? $_REQUEST [ 'mod' ] : 'index';

$application = FRAMEWORK . '/apps/' . $_REQUEST [ 'app' ] . '/' . $_REQUEST [ 'mod' ] . '.php';

if ( file_exists ( $application ) ) {

	require_once ( $application );
	exit ( );

} else {

	exit ( '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL ' . $_SERVER [ 'REQUEST_URI' ] . ' was not found on this server.</p></body></html>' );

}

?>