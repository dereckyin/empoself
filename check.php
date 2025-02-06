<?php
$jwt = (isset($_COOKIE['jwt']) ?  $_COOKIE['jwt'] : null);
$uid = (isset($_COOKIE['uid']) ?  $_COOKIE['uid'] : null);
if ( !isset( $jwt ) ) {
  header( 'location:login' );
}

include_once 'api/config/core.php';
include_once 'api/libs/php-jwt-master/src/BeforeValidException.php';
include_once 'api/libs/php-jwt-master/src/ExpiredException.php';
include_once 'api/libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'api/libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

try {
        //$decrypted = passport_decrypt($jwt);
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        $GLOBALS['username'] = $decoded->data->username;
        // $GLOBALS['position'] = $decoded->data->position;
        // $GLOBALS['department'] = $decoded->data->department;

        // if($decoded->data->limited_access == true 
        // && !strpos($_SERVER['REQUEST_URI'], 'meeting_calendar')
        // && !strpos($_SERVER['REQUEST_URI'], 'product_catalog_code') 
        // && !strpos($_SERVER['REQUEST_URI'], 'product_display_code') 
        // && !strpos($_SERVER['REQUEST_URI'], 'add_product_code') 
        // && !strpos($_SERVER['REQUEST_URI'], 'edit_product_code') 
        // && !strpos($_SERVER['REQUEST_URI'], 'product_spec_sheet') 
        // && !strpos($_SERVER['REQUEST_URI'], 'tag_mgt')
        // && !strpos($_SERVER['REQUEST_URI'], 'default'))
        //   header( 'location:login' );

        //if(passport_decrypt( base64_decode($uid)) !== $decoded->data->username )
        //    header( 'location:login.php' );
    }
    // if decode fails, it means jwt is invalid
    catch (Exception $e){
    
        header( 'location:login' );
    }

?>

