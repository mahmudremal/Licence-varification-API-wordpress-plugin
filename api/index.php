<?php
/**
 * https://api.futurewordpress.com/v2/licenses/package/licence-code/verify/json/
 * @package is for package name.
 * @licence-code is for licence code.
 * @verify means to verify.
 * @json meant agents expecting json output.
 */
$output = [];
// if( isset( $_GET[ 'product' ] ) || isset( $_GET[ 'product_id' ] ) || isset( $_GET[ 'product' ] ) || isset( $_GET[ 'product' ] ) ) {
	// $_GET = ( isset( $_GET[ 'product' ] ) || isset( $_GET[ 'product_id' ] ) ) ? $_GET : (
		// ( isset( $_GET[ 'product' ] ) || isset( $_GET[ 'product_id' ] ) ) ? $_GET : []
	// );
	
	try {
	  $conn = new PDO("mysql:host=localhost;dbname=futurewo_main", 'futurewo_licenses', '4d/b2kw;F%fg');
	  // set the PDO error mode to exception
	  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  
	  try {
		$stmt = $conn->prepare( 'SELECT API FROM licenses WHERE product = :product AND ID = :ID AND lc_status = 1;UPDATE licenses SET counted = ( counted + 1 ) WHERE product = :product AND ID = :ID AND lc_status = 1;', [ PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY ] );
		$stmt->execute( [
		  'product' => isset( $_GET[ 'product' ] ) ? $_GET[ 'product' ] : (
				isset( $_GET[ 'product_id' ] ) ? $_GET[ 'product_id' ] : (
					isset( $_GET[ 'product_permalink' ] ) ? $_GET[ 'product_permalink' ] : ''
				)
		  ),
		  'ID' => isset( $_GET[ 'license_key' ] ) ? $_GET[ 'license_key' ] : (
					isset( $_GET[ 'license' ] ) ? $_GET[ 'license' ] : (
						isset( $_GET[ 'key' ] ) ? $_GET[ 'key' ] : ''
					)
				)
		] );
		$stmt = $stmt->fetch( PDO::FETCH_ASSOC );
		$output[ isset( $stmt[ 'API' ] ) ? $stmt[ 'API' ] : 'failed' ] = true;
	  }catch(PDOException $e) {
		$output[ 'falied' ] = $e->getMessage();
	  }
	}catch(PDOException $e) {
	  $output[ 'falied' ] = 'Connection failed: ' . $e->getMessage();
	}


	header( 'Content-type: application/json;' );
	echo json_encode( $output );
	exit;


	// if( isset( $_REQUEST[ 'json' ] ) || isset( $_GET[ 'json' ] ) || isset( $_GET[ 'json' ] ) ) {
	  
	// } else {
	//   // echo isset( $output[ 'success' ] ) ? $output[ 'success' ] : (
	//   //   isset( $output[ 'failed' ] ) ? $output[ 'failed' ] : ''
	//   // );
	// }
// } else {
	// echo json_encode( [ 'error' => 403 ] );
	// exit;
// }
?>