<?php
    session_start();

    include "connection.php";

    $temp_array = [];

    $num = $_SESSION['user_id'];
    
    // FOR SOME REASON IT KEEPS DOUBLING THE AMOUNT HERE 
    $query = "SELECT SUM(e.amount) as amount,e.date,c.name FROM expenses e,categories c,users u WHERE e.category_id = c.id AND e.user_id=?  GROUP BY e.category_id";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s",$num);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()){
        $temp_array[] = $row;

    }

    
    $json = json_encode($temp_array);
    echo $json;


?>