<?php
error_reporting(E_ERROR | E_PARSE);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/user.php';
include_once 'config/conf.php';
include_once 'objects/login_history.php';
$database = new Database();
$db = $database->getConnection();
$user = new User($database);
$login_history = new LoginHistory($db);
$conf = new Conf();

$phone = (isset($_POST['phone']) ?  $_POST['phone'] : "");
$password = (isset($_POST['password']) ?  $_POST['password'] : "");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {

    // Build POST request:
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_response = $_POST['recaptcha_response'];

    // Make and decode POST request:
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $conf::$recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    // Take action based on the score returned:
    if ($recaptcha->score >= 0.5) 
        $cap = 1;
} 

// set product property values
$user->phone = $phone;
$user_exists = $user->userCanLogin();

if($user_exists)
    $login_history->uid = $user->id;
else
    $login_history->uid = 0;

$login_history->ip = get_ip();

// generate json web token
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;


// check if email exists and if password is correct
//if($user_exists && password_verify($password, $user->password) && $cap == 1 && $user->status == 1){
if($user_exists && password_verify($password, $user->password_hash)  && $user->account_status == '1'){

    $issuedAt   = new DateTimeImmutable();
    $expire     = $issuedAt->modify('+24 hours')->getTimestamp(); 

    $token = array(
       "iss" => $iss,
       "aud" => $aud,
       "iat" => $iat,
       "nbf" => $nbf,
       "exp" => $expire,
       "data" => array(
           "id" => $user->id,
           "name" => $user->name,
           "phone" => $user->phone,
           "user_type" => $user->user_type,
           "username" => $user->username,
           "email" => $user->email,
       )
    );

    // write login log
    $login_history->status = "login";
    $login_history->create();
 
    // set response code
    http_response_code(200);
 
    // generate jwt
    try {
        $jwt = JWT::encode($token, $key);
    } catch (Exception $e) {
        echo json_encode(array(
            "message" => "Unable to create token.",
            "error" => $e->getMessage()
        ));
    }
    
    echo json_encode(
            array(
                "message" => "Successful login.",
                "jwt" => passport_encrypt($jwt),
                "uid" => passport_encrypt(base64_encode($user->id))
            )
        );
 
}
// login failed
else{
    if($user->account_status != '1')
    {
        if($login_history->uid !== 0)
        {
            // write login log
            $login_history->status = "not activated";
            $login_history->create();
        }
        else
        {
            // write login log
            $login_history->status = $user->username . " not existed";
            $login_history->create();
        }


        $returnArray = array('error' => 'User is not activated.');
        $jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
    } else
    {
        // write login log
        $login_history->status = "Invalid user ID or password";
        $login_history->create();

        $returnArray = array('error' => 'Invalid user ID or password.');
        $jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
    }

    echo $jsonEncodedReturnArray;
}

function get_ip(){
    $myip = "";

    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        $myip = $_SERVER['HTTP_CLIENT_IP'];
     }else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $myip = $_SERVER['HTTP_X_FORWARDED_FOR'];
     }else{
        $myip= $_SERVER['REMOTE_ADDR'];
     }
     return $myip;
}
?>