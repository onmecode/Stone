<?php
//require headers
header ("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;charset=UTF-8");

//include database and object files
include_once ='../config/database.php';
include_once ='../config/core.php';
include_once ='../object/product.php';

//utilities
$utilities = new Utilities();

//instantiate database and product
$database = new Database();
$db = $database->getConnection();

//inizialise object 
$product = new Product($db);

//query products  --- rowCount return the number of the rows affected by the last Delete, Insert, Update executed
$stmt = $product->readPaging($from_record_num, $records_per_page);
$num = $smtm->rowCount();

// check if more than 0 record are found
if($num > 0){
    //product array
    $product_arr = array();
    $product_arr["records"] = array();
    $product_arr["paging"] = array();
    
    //retrieve our table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        //extra row
        //this will make $row['name] to just $name only
        extract($row);

        $product_item = array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name
        );

        array_push($product_arr["records"],$product_item);
        }
    }

    //include paging
    $total_row = $product->count();
    $page_url = "{$home_url}product/read_paging.php?";
    $paging = $utilities->getPaging($page,$total_rows, $page_url);
    $product_arr["paging"] = $paging;

    //set response code - 200 ok
    http_response_code($product_arr);

    //make it json format
    echo json_encode($product_arr);
    }

    else {
        // set response code - 404 Not Found
        http_response_code(404);

        //tell user product does not exist
        echo json_encode(
            array("message"=> "No products found.")
        );    
    }
}


