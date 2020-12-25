<?php
/**
 * PHP Common Funtions
 */

function validArray( $arrmixValues, $intCount = 1, $boolCheckForEquality = false ) {
	$boolIsValid = is_array( $arrmixValues ) ? ( $intCount <= count( $arrmixValues ) ) ? true : false : false;
	if( $boolCheckForEquality && $boolIsValid ) {
		$boolIsValid = ( $intCount == count( $arrmixValues ) ) ? true : false;
	}

	return $boolIsValid;
}

function show( $mixValue, $boolShowOnlyMethods = false ) {
	if ( false == $boolShowOnlyMethods ) {
		echo '<pre style="background-color:white; color:rgb(32, 56, 18);padding:5px; border: 1px solid black; border-radius: 4px;">', htmlentities( print_r( $mixValue, true ) ), '</pre>';
	}
	if ( validArray( get_class_methods( $mixValue ) ) ) {
		echo '<pre style="background-color:white; color:rgb(32, 56, 18);padding:5px; border: 1px solid black; border-radius: 4px;">', htmlentities( print_r( get_class_methods( $mixValue ), true ) ), '</pre>';
	} elseif( true == $boolShowOnlyMethods ) {
		echo '<pre style="background-color:white; color:rgb(32, 56, 18);padding:5px; border: 1px solid black; border-radius: 4px;">', htmlentities( 'No Class Methods Present' ), '</pre>';
	}
    exit;
}

function validInteger( $intNumber ) {
    return is_integer( $intNumber );
}

function validString( $strString ) {
    $strString = ( false == is_array( $strString ) ) ? trim( ( string ) $strString ) : NULL;
    return is_string( $strString ) ? ( '' == $strString || ' ' == $strString ) ? false : true : false;
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

function rekeyArray( $strKeyFieldName, $arrmixUnkeyedData, $boolMakeKeyLowerCase = false, $boolHasMultipleArraysWithSameKey = false, $boolExcludeNulls = false ) {
	if( false == validArray( $arrmixUnkeyedData ) ) {
		return $arrmixUnkeyedData;
	}

	$arrmixRekeyedData = [];

	if( 'index' != $strKeyFieldName ) {
		foreach( $arrmixUnkeyedData as $arrmixUnkeyedData ) {
			if( true == $boolExcludeNulls && true == is_null( $arrmixUnkeyedData[$strKeyFieldName] ) ) {
				continue;
			}
			if( true == $boolHasMultipleArraysWithSameKey ) {
				$strKey                       = ( true == $boolMakeKeyLowerCase ) ? strtolower( trim( $arrmixUnkeyedData[$strKeyFieldName] ) ) : trim( $arrmixUnkeyedData[$strKeyFieldName] );
				$arrmixRekeyedData[$strKey][] = $arrmixUnkeyedData;
			} else {

				if( false == isset ( $arrmixUnkeyedData[$strKeyFieldName] ) ) {
					$strKey = '';
				} else {
					$strKey = ( true == $boolMakeKeyLowerCase ) ? strtolower( trim( $arrmixUnkeyedData[$strKeyFieldName] ) ) : trim( $arrmixUnkeyedData[$strKeyFieldName] );
				}

				$arrmixRekeyedData[$strKey] = $arrmixUnkeyedData;
			}
		}
	} else {

		foreach( $arrmixUnkeyedData as $arrmixUnkeyedData ) {
			$arrmixRekeyedData[] = $arrmixUnkeyedData;
		}
	}

	return $arrmixRekeyedData;
}

?>