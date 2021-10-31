<?php
    include "connection.php";
    
    $user_id = $_GET['user_id'];
    $category = $_GET['category'];
    $amount = $_GET["amount"];
    $date = $_GET['date'];


    $array = [];
 
    $query = "SELECT * FROM categories WHERE name=?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s",$category);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while($row = $result->fetch_assoc()){
        $array[] = $row;
    }

    $cat_id = $array[0]['id'];

    $query = "INSERT INTO `expenses`(`amount`, `user_id`, `category_id`, `date`) VALUES (?,?,?,?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("iiis",$amount,$user_id,$cat_id,$date);
    $stmt->execute();
    $id = $stmt->insert_id;

    $expense_obj = [];
    $expense_obj["id"] = $id;
    $expense_obj["category"] = $category;
    $expense_obj["amount"] = $amount;
    $expense_obj["date"] = $date;

    $json = json_encode($expense_obj);
    echo $json;

?>