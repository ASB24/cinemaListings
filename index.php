<?php
    include('./db.php');
    $presentListings = false;
    session_start();
    if(!isset($_SESSION['user']) || isset($_GET['end'])){
        header("Location: login.php");
        session_destroy();
        exit();
    }

    $conn = openConn();
    $result = $conn->query("SELECT * FROM cinema_listings");
    if($result->num_rows > 0){
        $presentListings = true;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Cartelera
    </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.0-beta1/css/bootstrap.min.css" integrity="sha512-o/MhoRPVLExxZjCFVBsm17Pkztkzmh7Dp8k7/3JrtNCHh0AQ489kwpfA3dPSHzKDe8YCuEhxXq3Y71eb/o6amg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./index.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="./index.php" style="margin-left: 10px;">
            <i class="fa fa-clapperboard" aria-hidden="true"></i>
            Movie Listings
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto navbar-custom">
                <li class="nav-item active">
                    <a class="nav-link" href="./index.php">Home <span class="sr-only">(current)</span></a>
                    <a class="nav-link" href="./index.php?end=true">Log out</a>
                    <a class="nav-link" href="./index.php">Welcome, <?php echo $_SESSION['user'] ?></a>
                </li>
            </ul>
        </div>
    </nav>
    
    <div class="main_container">
        <aside>
            <h1>xd</h1>
        </aside>
        <main>
            <h1>Listings</h1>
            <?php
                if($presentListings === TRUE){
                    echo '<div class="row">';
                    while($data = $result->fetch_assoc()){
                        $id = $data['id'];
                        $title = $data['title'];
                        $image = $data['image'];
                        $synopsis = $data['synopsis'];
                        $duration = $data['duration'];
                        $genre = $data['genre'];
                        $class = $data['classification'];
                        echo ' 
                        <div class="col-sm-6 my-3">
                            <div class="card">
                                <img class="card-img top" src="./images/'.$image.'" alt="Preview Image" srcset="">
                                <div class="card-body">
                                    <h5 class="card-title">'.$title.'</h5>
                                    <p class="card-text">'.$synopsis.'</p>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><span class="navbar-brand mb-0 h2">Duration:</span> '.$duration.' Minutes </li>
                                    <li class="list-group-item"><span class="navbar-brand mb-0 h2">Genre:</span> '.$genre.' </li>
                                    <li class="list-group-item"><span class="navbar-brand mb-0 h2">Classification:</span> '.$class.' </li>
                                </ul>';

                        if($_SESSION['user'] === "admin"){
                            echo '
                            <div class="card-body">
                                <a name="edit" id="edit" class="btn btn-info" href="./listing.php?action=edit&id='.$id.'" role="button">Edit</a>
                                <a name="delete" id="delete" class="btn btn-danger" href="./listing.php?action=delete&id='.$id.'&img='.$image.'" role="button">Delete</a>
                            </div>
                            ';
                        }

                        echo '
                            </div>
                        </div>
                        ';
                    }
                    echo '</div>';
                }
            ?>
            
        </main>
        <aside>
            <?php
                if($_SESSION['user'] === "admin"){
                    echo '
                    <div class="btn-group btn-group-lg mx-auto">
                        <a name="create" id="create" class="btn btn-success" href="listing.php" role="button">Create Listing</a>
                    </div>';
                }
            ?>
        
        </aside>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.0-beta1/js/bootstrap.bundle.min.js" integrity="sha512-ndrrR94PW3ckaAvvWrAzRi5JWjF71/Pw7TlSo6judANOFCmz0d+0YE+qIGamRRSnVzSvIyGs4BTtyFMm3MT/cg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>