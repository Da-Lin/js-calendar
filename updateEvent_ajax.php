<?php
header("Content-Type: application/json");
ini_set("session.cookie_httponly", 1);
session_start();
//Connect to Database
require 'newMySQLConnection.php';

$json_str = file_get_contents('php://input');
//store the data into an associative array
$json_obj = json_decode($json_str, true);
//access variables:
$id = $json_obj['id'];
$content = $json_obj['content'];
$date = $json_obj['date'];
$tag = $json_obj['tag'];
//check CSRF token
if(!hash_equals($_SESSION['token'], $json_obj['token'])){
	die("Request forgery detected");
}

$stmt = $mysqli->prepare("update user_events set date = ?, content = ?, tag_name = ? where id = ?");

// Bind the parameter
$stmt->bind_param('sssi',$date, $content, $tag, $id);


//try to update event
if ($stmt->execute()) {

    echo json_encode(array(
        "success" => true,
    ));
    exit;
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "failed to add event",
    ));
    exit;
}
?>