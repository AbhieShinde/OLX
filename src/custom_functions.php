<?php
/**
 * PHP Common Funtions
 */

function dump( $mixValue ) {
    echo '<pre style="background-color:white; color:rgb(32, 56, 18);padding:5px; border: 1px solid black; border-radius: 4px;">', htmlentities( print_r( $mixValue, true ) ), '</pre>';
}

function show( $mixValue ) {
    var_dump( $mixValue );
    exit;
}

function dumpClassMethods( $objObject ) {
    $str = "<h4>Methods:</h4>";
    $str .= "<ol>";
    foreach ( get_class_methods( $objObject ) as $key => $value) {
        $str .= "<li>$value()</li>";
    }
    $str .= "</ol>";
    return $str;
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

function validObject( $objObject, $strClass, $strMethod = NULL, $strValue = NULL ) {

	if( false == validString( $strClass ) ) {
		trigger_error( 'Class name must be a valid object or a string', E_USER_WARNING );

		return false;
	}

	$boolIsValid = ( true == is_object( $objObject ) && true == ( $objObject instanceof $strClass ) ) ? true : false;

	if( true == $boolIsValid && NULL !== $strMethod ) {
		$boolIsValid &= ( ( true == method_exists( $objObject, 'get' . $strMethod ) && true == valStr( $objObject->{'get' . $strMethod}() ) ) ) ? true : false;
	}

	if( true == $boolIsValid && NULL !== $strValue ) {
		$boolIsValid &= ( ( string ) $strValue === ( string ) $objObject->{'get' . $strMethod}() ) ? true : false;
	}

	return $boolIsValid;
}

function encrypt( $strPassword ) {
	return hash( "sha512", $strPassword . getenv( 'salt' ) );
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