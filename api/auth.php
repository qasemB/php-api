<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once('../core/initialize.php');
include_once('../core/auth.php');

$token = new Auth($db);

if ($token->auth()) {
    http_response_code(200);
    echo json_encode($token->auth());
}else{
    http_response_code(404);
    echo json_encode([
        "type" => "danger",
        "message" => "could not create token"
    ]);
}

?>