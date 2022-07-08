<?php
    include('./db.php');
    session_start();
    if($_SESSION['user'] !== "admin"){
        header("Location: login.php");
        session_destroy();
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['action'])){
        $conn = openConn();
        $id = $_GET['id'];
        if($_GET['action'] === "delete"){

            $img = $_GET['img'];

            $stmt = $conn->prepare("DELETE FROM `cinema_listings` WHERE `id` = ?");
            $stmt->bind_param('s',$id);
            if($stmt->execute()){
                closeConn($conn);
                unlink('./images/'.$img);
                header("Location: index.php");
                exit();
            }else{
                print_r(["Ta mal eto compai - delete"]);
            }

        }else if($_GET['action'] === "edit"){
            $edit_mode = true;
            $stmt = $conn->prepare("SELECT * FROM cinema_listings WHERE id = ?");
            $stmt->bind_param('s',$id);
            if($stmt->execute()){
                $listing = $stmt->get_result()->fetch_assoc();
            }else{
                print_r(["Ta mal eto compai - edit"]);
            }

        }
        closeConn($conn);
    }

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $conn = openConn();
        $uploadedFile = $_FILES['image']['tmp_name'];
        $name = strtolower(preg_replace('/\s+/', '', $_POST['title']));
        $filename = $name.'.'.pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        if(move_uploaded_file($uploadedFile, './images/'.$filename)){
            echo "File uploaded successfully";
        }

        if(isset($_POST['id'])){
            $stmt = $conn->prepare(" UPDATE `cinema_listings` SET `title`=?,`duration`=?,`genre`=?,`classification`=?,`synopsis`=?,`image`=? WHERE `id` = ? ");
            $stmt->bind_param('sisssss', $_POST['title'], $_POST['duration'], $_POST['genre'], $_POST['class'], $_POST['synopsis'], $filename, $_POST['id']);
        }else{
            $stmt = $conn->prepare("INSERT INTO `cinema_listings`(`title`, `duration`, `genre`, `classification`, `synopsis`, `image`) VALUES (?,?,?,?,?,?)");
            $stmt->bind_param('sissss', $_POST['title'], $_POST['duration'], $_POST['genre'], $_POST['class'], $_POST['synopsis'], $filename);
        }

        if($stmt->execute()){
            if(isset($_POST['replace_img']) && !file_exists("./images/".$_POST['replace_img'])) unlink("./images/".$_POST['replace_img']);
            closeConn($conn);
            header('Location: index.php');
            exit();
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Create, Edit or Delete Listing
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
            <img src="./assets/ad.gif" alt="ad" class="img-fluid w-100">
        </aside>
        <main class="p-1">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                  <label for="title" class="form-label">Title</label>
                  <input type="text" name="title" id="title" value="<?php if(isset($listing)) echo $listing['title'] ?>" class="form-control" placeholder="" aria-describedby="helpTitle">
                  <small id="helpTitle" class="text-muted">Title of the movie</small>
                </div>
                <div class="mb-3">
                  <label for="duration" class="form-label">Duration</label>
                  <input type="number" name="duration" id="duration" value="<?php if(isset($listing)) echo $listing['duration'] ?>" class="form-control" placeholder="" aria-describedby="helpDuration">
                  <small id="helpDuration" class="text-muted">For how many minutes does the movie run?</small>
                </div>
                <div class="mb-3">
                  <label for="genre" class="form-label">Genre</label>
                  <input type="text" name="genre" id="genre" value="<?php if(isset($listing)) echo $listing['genre'] ?>" class="form-control" placeholder="" aria-describedby="helpGenre">
                  <small id="helpGenre" class="text-muted">Genre of the movie</small>
                </div>
                <div class="mb-3">
                  <label for="class" class="form-label">Classification</label>
                  <select class="form-control" name="class" id="class">
                    <option><?php if(isset($listing)) echo $listing['classification'] ?></option>
                    <option>G</option>
                    <option>PG</option>
                    <option>PG-13</option>
                    <option>R</option>
                    <option>NC-17</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="synopsis" class="form-label">Synopsis</label>
                  <textarea class="form-control" name="synopsis" id="synopsis" rows="10"><?php if(isset($listing)) echo $listing['synopsis'] ?></textarea>
                </div>
                <div class="mb-3">
                  <label for="image" class="form-label">Poster</label>
                  <input type="file" class="form-control" name="image" id="image" placeholder="" aria-describedby="fileHelpId">
                  <div id="fileHelpId" class="form-text">Poster image of movie (any image format)</div>
                </div>
                <?php
                    if(isset($edit_mode) && isset($listing)){
                        echo '<input type="hidden" value="'.$listing['id'].'" name="id">';
                        echo '<input type="hidden" value="'.$listing['image'].'" name="replace_img">';
                    }
                ?>
                <button type="submit" class="btn btn-success">Save</button>
            </form>
        </main>
        <aside>
            
        </aside>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.0-beta1/js/bootstrap.bundle.min.js" integrity="sha512-ndrrR94PW3ckaAvvWrAzRi5JWjF71/Pw7TlSo6judANOFCmz0d+0YE+qIGamRRSnVzSvIyGs4BTtyFMm3MT/cg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>