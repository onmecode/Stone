<?php
 //required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UFT-8");
header("Access-Control-Allow-Method:POST");
header("Access-Control-Max-Age:3600");
header("Access-Control-Allow-Headers:Content-Type, Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With");

//include database and object file
include_once '../config/database.php';
include_once '../object/product.php';

//get database connection and object
$database = new Database();
$db = $database->getConnection();

//object instantiation
$product = new Product($db);

//get product id
$data = json_decode(file_get_contents("php://input"));

//set product to be deleted
$product->id = $data->id;

//delete the product
if($product->delete()){
    //set response - 200 ok
    http_response_code(200);

    //tell the user
    echo json_encode(array("message"=>"Product was deleted."));
}

//if unable to delete the product
else {
    //set response code - 503 service unavailable
    http_response_code(503);

    //tell the user
    echo json_encode(array("message"=> "Unable to delete product"));
}