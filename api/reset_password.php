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

// Get token from database
$database = new Database();
$database->getConnection();
$access_token = new AccessToken($database);
$token = $access_token->get($auth_token);
$error_count_by_ip = $access_token->get_error_count_by_ip($_SERVER['REMOTE_ADDR']);
$verify_error_count_by_token = $access_token->get_verify_error_count_by_token($auth_token);

// Check if no token is provided
if ($token == null) {
    http_response_code(401);
    echo json_encode(array("message" => "Access denied."));
    die();
}

if($token['error_count'] > 3) {
    http_response_code(401);
    echo json_encode(array("message" => "Access denied."));
    die();
}

if($error_count_by_ip['error_count'] > 3) {
    http_response_code(401);
    echo json_encode(array("message" => "Access denied."));
    die();
}

if($verify_error_count_by_token > 3) {
    http_response_code(401);
    echo json_encode(array("message" => "提交失敗，請洽詢 IT 人員"));
    die();
}


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $consult = new Consult($database);

    $data = json_decode(file_get_contents('php://input'), true);

    $verify_code = isset($data['verify_code']) ? $data['verify_code'] : '';

    $verify_code_in_db = $access_token->get_verify_code_by_token($auth_token);

    if($verify_code == $verify_code_in_db['verify_code']) {
        // Get parameters from the query string
        $name = isset($data['name']) ? $data['name'] : '';
        $birthday = isset($data['birthday']) ? $data['birthday'] : '';

        $new_password = isset($data['new_password']) ? $data['new_password'] : '';
        $confirm_password = isset($data['confirm_password']) ? $data['confirm_password'] : '';

        if($new_password != $confirm_password) {
            http_response_code(400);
            echo json_encode(array("message" => "兩次輸入的密碼不一致"));
            exit();
        }

        // Validate input
        if (empty($name) || empty($birthday)) {
            http_response_code(400);
            echo json_encode(array("message" => "No data found."));
            exit();
        }

        try {
            // Fetch existing data by name and birthday
            $existingData = $consult->getByNameAndBirthday($name, $birthday);

            if (count($existingData) > 0) {
                http_response_code(200);
                $access_token->clear_error($auth_token);
                $access_token->clear_error_by_ip($_SERVER['REMOTE_ADDR']);

                $password_hash = password_hash($new_password, PASSWORD_BCRYPT);

                $consult->updatePassword($existingData[0]['id'], $password_hash);
                
                echo json_encode($existingData);
            } else {
                $access_token->update_error_count_by_token($auth_token);
                http_response_code(404);
                echo json_encode(array("message" => "No data found."));
            }
            
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(501);
            echo json_encode(array("message" => "提交失敗，請洽詢 IT 人員"));
            die();
        }
    } else {
        $access_token->update_verify_error_count_by_token($auth_token);
        http_response_code(401);
        $chance = 2 - ($verify_error_count_by_token * 1);
        if($chance <= 0) {
            echo json_encode(array("message" => "提交失敗，請洽詢 IT 人員"));
        }
        else
        {
            echo json_encode(array("message" => "驗證碼錯誤，您還有 " . $chance . " 次機會輸入正確的驗證碼"));
        }
    }


    // Close the database connection
    $database->close();
}
?>