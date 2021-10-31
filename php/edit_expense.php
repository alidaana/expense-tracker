<?php
    include "connection.php";

    $expense_id = $_POST["id"];
    $category = $_POST['category2'];
    $amount = $_POST["amount2"];
    $date = $_POST['date2'];


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

    

    $query = "UPDATE `expenses` SET `amount`=?,`category_id`=?,`date`=? WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("iisi",$amount,$cat_id,$date,$expense_id );
    $stmt->execute();

    $expense_obj = [];
    $expense_obj["id"] = $expense_id;
    $expense_obj["category"] = $category;
    $expense_obj["amount"] = $amount;
    $expense_obj["date"] = $date;

    $json = json_encode($expense_obj);
    echo $json;

?>