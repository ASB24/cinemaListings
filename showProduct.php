<?php

include('db.php');

$name = "";
$desc = "";

if($_SERVER['REQUEST_METHOD'] === "GET"){
    $conn = openConn();
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param('s',$id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result -> num_rows > 0){
        $data = $result->fetch_assoc();
        $name = $data['name'];
        $desc = $data['description'];
    }else{
        print_r(['Ta mal eso manito', 'ya tu sabe']);
    }
    closeConn($conn);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <div class="card" style="width:18rem;">
      <div class="card-body">
        <h5 class="card-title"><?php echo $name; ?></h5>
        <p class="card-text"><?php echo $desc; ?></p>
      </div>
    </div>
    <a class="btn btn-info btn-lg " href="./index.php" role="button">Back</a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>