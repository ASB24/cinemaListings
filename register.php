<?php

include('db.php');

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $conn = openConn();
    $name = $_POST['name'];
    $desc = $_POST['desc'];

    $stmt = $conn->prepare("INSERT INTO products (name, description) values (?, ?)");
    $stmt->bind_param('ss', $name, $desc);
    
    if($stmt->execute()){
        header("Location: ./index.php/?r=true");
    }else{
        print_r(['Ta mal eso manito', 'ya tu sabe']);
    }
    closeConn($conn);
}
?>