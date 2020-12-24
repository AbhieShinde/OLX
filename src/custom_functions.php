<?php
/**
 * PHP Common Funtions
 */
function show( $mixValue ) {
	echo '<pre style="background-color:white; color:rgb(32, 56, 18);padding:5px; border: 1px solid black; border-radius: 4px;">', htmlentities( print_r( $mixValue, true ) ), '</pre>';
	echo '<pre style="background-color:white; color:rgb(32, 56, 18);padding:5px; border: 1px solid black; border-radius: 4px;">', htmlentities( print_r( get_class_methods( $mixValue ), true ) ), '</pre>';
    exit;
}

function validInteger( $intNumber ) {
    return is_integer( $intNumber );
}

function validString( $strString ) {
    $strString = ( false == is_array( $strString ) ) ? trim( ( string ) $strString ) : NULL;
    return is_string( $strString ) ? ( '' == $strString || ' ' == $strString ) ? false : true : false;
}

function validArray( $arrmixValues, $intCount = 1, $boolCheckForEquality = false ) {
	$boolIsValid = is_array( $arrmixValues ) ? ( $intCount <= count( $arrmixValues ) ) ? true : false : false;
	if( $boolCheckForEquality && $boolIsValid ) {
		$boolIsValid = ( $intCount == count( $arrmixValues ) ) ? true : false;
	}

	return $boolIsValid;
}

function validIntegerArray( $arrmixInputArray ) : bool {

	if( false == valArr( $arrmixInputArray ) ) {
		return false;
	}

	return ( count( $arrmixInputArray ) == count( array_filter( filter_var_array( $arrmixInputArray, FILTER_VALIDATE_INT ) ) ) );
}

function validObject( $objObject, $strClass ) {

	if( false == validString( $strClass ) ) {
		trigger_error( 'Class name must be a valid object or a string', E_USER_WARNING );
		return false;
	}

	return ( is_object( $objObject ) && $objObject instanceof $strClass ) ? true : false;
}

function encrypt( $strPassword ) {
	return hash( "sha512", $strPassword . getenv( 'SALT' ) );
}

function is_dir_empty( $resDirectory ) {

	$resDirectory = opendir( $resDirectory );

	while( false !== ( $strEntry = readdir( $resDirectory ) ) ) {
		if( $strEntry != "." && $strEntry != ".." ) {
			closedir( $resDirectory );
			return false;
		}
	}

	closedir( $resDirectory );
	return true;
}

?>