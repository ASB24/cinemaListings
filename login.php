<?php
    include('./db.php');
    $registered = false;

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        if(isset($_POST['register'])){
            $conn = openConn();
            $user = $_POST['user'];
            $pass = $_POST['password'];

            $stmt = $conn->prepare("INSERT INTO cinema_users (username, password) values (?, ?)");
            $stmt->bind_param('ss', $user, $pass);
            
            if($stmt->execute()){
                $registered = true;
            }else{
                print_r('The user could not be registered, please try again');
            }
            closeConn($conn);
        }else if(isset($_POST['login'])){
            $conn = openConn();
            $user = $_POST['user'];
            $pass = $_POST['password'];

            $stmt = $conn->prepare("SELECT * FROM cinema_users WHERE username = ? AND password = ?");
            $stmt->bind_param('ss',$user,$pass);
            if($stmt->execute() && $stmt->get_result()->num_rows > 0){
                session_start();
                $_SESSION['user'] = $user;
                header("Location: ./index.php");
            }else{
                print_r('The credentials provided are not correct, please try again.');
            }
            closeConn($conn);
        }

        
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.0-beta1/css/bootstrap.min.css" integrity="sha512-o/MhoRPVLExxZjCFVBsm17Pkztkzmh7Dp8k7/3JrtNCHh0AQ489kwpfA3dPSHzKDe8YCuEhxXq3Y71eb/o6amg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./index.css">
</head>
<body>
    
    <?php
        if($registered){
            echo `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                
                    <strong>Done!</strong> User created, please login.
                </div>
            `;
        }
    ?>
    
    <div class="card mx-auto my-5" style="width:18rem;">
        <h1 id="loginTitle" >Login to the site</h1>
        <div class="card-body">
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="mb-3">
                  <label for="user" class="form-label">ID</label>
                  <input type="text" required class="form-control" name="user" id="user" aria-describedby="helpId" placeholder="">
                  <small id="helpId" class="form-text text-muted">User ID</small>
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" required class="form-control" name="password" id="password" aria-describedby="helpPass" placeholder="">
                  <small id="helpPass" class="form-text text-muted">User Password</small>
                </div>
                <button type="submit" name="register" class="btn btn-primary">Register</button>
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.0-beta1/js/bootstrap.min.js" integrity="sha512-Hqe3s+yLpqaBbXM6VA0cnj/T56ii5YjNrMT9v+us11Q81L0wzUG0jEMNECtugqNu2Uq5MSttCg0p4KK0kCPVaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>