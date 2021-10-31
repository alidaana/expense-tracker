<?php
    session_start();

    $user_id=$_SESSION['user_id'];
    
    include "connection.php";

    $query = "SELECT e.id,u.name as category,e.amount,e.date FROM expenses e,categories u WHERE e.user_id=? AND u.id = e.category_id";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s",$user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $temp_array = [];
    while($row = $result->fetch_assoc()){
        $temp_array[] = $row;
    }

    $json = json_encode($temp_array, JSON_PRETTY_PRINT);
    echo $json;

?>