<?php

// namespace Empoself\App;

error_reporting(E_ERROR | E_PARSE);
//error_reporting(0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$jwt = (isset($_COOKIE['jwt']) ?  $_COOKIE['jwt'] : null);
include_once 'config/core.php';

include_once 'objects/consult.php';
include_once 'config/database.php';


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $database->getConnection();
    $consult = new Consult($database);

    // Prepare data for insertion
    $data = [
        'name' => $_POST['name'],
        'gender' => $_POST['gender'],
        'birthday' => $_POST['birthday'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
        'address' => $_POST['address'],
        'emergency_contact' => $_POST['emergency_contact'],
        'emergency_contact_phone' => $_POST['emergency_contact_phone'],
        'referral_source' => $_POST['referral_source'],
        'referral_source_other' => $_POST['referral_source_other'],
        'health_condition' => $_POST['health_condition'],
        'health_condition_other' => $_POST['health_condition_other'],
        'account_status' => "",
        'profile_photo_url' => "",
        'active_branch' => "",
        'id_number' => "",
        'password_hash' => "",
        'contact_time' => "",
        'referrer_name' => "",
        'fitness_goals' => "",
        'referrer_id' => "",
        'height' => "",
        'weight' => "",
        'default_invoice_type' => "",
        'mobile_barcode' => "",
        'company_tax_id' => "",
        'company_name' => ""

        // 'account_status' => $_POST['account_status'],
        // 'profile_photo_url' => $_POST['profile_photo_url'],
        // 'active_branch' => $_POST['active_branch'],
        // 'id_number' => $_POST['id_number'],
        // 'password_hash' => password_hash($_POST['password'], PASSWORD_DEFAULT), // Hashing the password
        // 'contact_time' => $_POST['contact_time'],
        // 'fitness_goals' => json_encode($_POST['fitness_goals']), // Assuming fitness_goals is an array
        // 'referrer_id' => $_POST['referrer_id'],
        // 'height' => $_POST['height'],
        // 'weight' => $_POST['weight'],
        // 'default_invoice_type' => $_POST['default_invoice_type'],
        // 'mobile_barcode' => $_POST['mobile_barcode'],
        // 'company_tax_id' => $_POST['company_tax_id'],
        // 'company_name' => $_POST['company_name']
    ];

    try {
        $result = $consult->insert($data);
        if($result == ""){
            http_response_code(200);
            echo json_encode(array("message" => "已加入諮詢"));
        } else {
            http_response_code(503);
            echo json_encode(array("Failure at " . date("Y-m-d") . " " . date("h:i:sa") . $result));
        }
        
    } catch (Exception $e){
        error_log($e->getMessage());
        http_response_code(501);
        echo json_encode(array("Failure at " . date("Y-m-d") . " " . date("h:i:sa") . $e->getMessage()));
        die();
    }
    // Close the database connection
    $database->close();
}
?>