<?php

// namespace Empoself\App;

error_reporting(E_ERROR | E_PARSE);
//error_reporting(0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$auth_token = (isset($_COOKIE['auth_token']) ?  $_COOKIE['auth_token'] : null);
if($auth_token == null) {
    http_response_code(404);
    echo json_encode(array("message" => "No data found."));
    die();
}

include_once 'config/core.php';

include_once 'objects/consult.php';
include_once 'config/database.php';
include_once 'objects/access_token.php';
include_once 'mail.php';

// Get token from database
$database = new Database();
$database->getConnection();
$access_token = new AccessToken($database);
$token = $access_token->get($auth_token);
$error_count_by_ip = $access_token->get_error_count_by_ip($_SERVER['REMOTE_ADDR']);

// Check if no token is provided
if ($token == null) {
    http_response_code(501);
    echo json_encode(array("message" => "Access denied."));
    die();
}

if($token['error_count'] > 3) {
    http_response_code(501);
    echo json_encode(array("message" => "Access denied."));
    die();
}

// if($error_count_by_ip['error_count'] > 3) {
//     http_response_code(501);
//     echo json_encode(array("message" => "Access denied3."));
//     die();
// }


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $consult = new Consult($database);

    $data = json_decode(file_get_contents('php://input'), true);
    // Get parameters from the query string
    $name = isset($data['name']) ? $data['name'] : '';
    $birthday = isset($data['birthday']) ? $data['birthday'] : '';

    // Validate input
    if (empty($name) || empty($birthday)) {
        http_response_code(400);
        echo json_encode(array("message" => "No data found."));
        exit();
    }

    try {
        // Fetch existing data by name and birthday
        $existingData = $consult->getByNameAndBirthday($name, $birthday);

        if ($existingData) {
            $email = $existingData[0]['email'];
            $name = $existingData[0]['name'];

            send_verify_code_email($email, $name, $auth_token, $access_token);
            echo json_encode(array("message" => $email));
        } else {
            $access_token->update_error_count_by_token($auth_token);
            http_response_code(401);
            echo json_encode(array("message" => "No data found."));
        }
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        http_response_code(501);
        echo json_encode(array("Failure at " . date("Y-m-d") . " " . date("h:i:sa") . $e->getMessage()));
        die();
    }

    // Close the database connection
    $database->close();
}

function send_verify_code_email($email, $name, $auth_token, $access_token) {
    // generate 4 digits verify code
    $verify_code = rand(100000, 999999);

    // set verify code to token
    $access_token->set_verify_code_by_token($auth_token, $verify_code);

    // send email
    if(send_veify_code($email, $name, $verify_code)) {
        //http_response_code(200);
        //echo json_encode(array("message" => "Verify code sent."));
    } else {
        //http_response_code(503);
        //echo json_encode(array("message" => "Failed to send verify code."));
    }
}

?>