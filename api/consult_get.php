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



// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $database = new Database();
    $database->getConnection();
    $consult = new Consult($database);

    // Get parameters from the query string
    $name = isset($_GET['name']) ? $_GET['name'] : '';
    $birthday = isset($_GET['birthday']) ? $_GET['birthday'] : '';

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
            http_response_code(200);
            echo json_encode($existingData);
        } else {
            http_response_code(404);
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
?>