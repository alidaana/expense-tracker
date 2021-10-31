<?php 
include "connection.php";

    $expense_id = $_GET["id"];

    $query = "DELETE from expenses where id = ?";
    $obj = $connection->prepare($query);
    $obj->bind_param("s", $expense_id);
    $obj->execute();

    $response = [];
    $response["success"] = 1;

    $response_json = json_encode($response);
    echo $response_json;

?>