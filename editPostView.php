<?php
session_start();
require_once "./Classes/database.php";
include_once("./Classes/auth.php");

$post_user = htmlspecialchars($_GET["post_user"]);

if ($_SESSION["user"]["id"] != $post_user || !$_SESSION["user"]) {
    header("location: ./index.php");
    exit();
}


$post_id = htmlspecialchars($_GET["id"]);


$postView = $connection->query(
    "SELECT users.fname, users.mname, users.lname, posts.id, posts.user, posts.post, posts.created_at, posts.updated_at
    FROM users
    LEFT JOIN posts ON posts.user = users.id
    WHERE posts.id = " . $post_id . "
    ORDER BY posts.created_at DESC"
);

if ($postView->num_rows == 0) {
    $_SESSION["view_error"] = "Post not found or does not exist.";
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/fontawesome.css" integrity="sha384-jLKHWM3JRmfMU0A5x5AkjWkw/EYfGUAGagvnfryNV3F9VqM98XiIH7VBGVoxVSc7" crossorigin="anonymous" />
    <title>FastSend</title>
    <style>
             body{
            background: #0f0c29;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #24243e, #302b63, #0f0c29);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #24243e, #302b63, #0f0c29); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

        }
.card {
  border: 2px solid #FF0000; 
  /* background: #fc4a1a; 
background: -webkit-linear-gradient(to right, #f7b733, #fc4a1a);  
background: linear-gradient(to right, #f7b733, #fc4a1a); */

}
.btn{
    border: 1px solid #FF0000; 
  background: #fc4a1a;  
background: -webkit-linear-gradient(to right, #f7b733, #fc4a1a);  
background: linear-gradient(to right, #f7b733, #fc4a1a); 

}
.btn:hover{
    background: #0f0c29;  
background: -webkit-linear-gradient(to right, #24243e, #302b63, #0f0c29); 
background: linear-gradient(to right, #24243e, #302b63, #0f0c29); 
}

    </style>
</head>

<body style="background-image:url('img/gala.jpg');">
    <?php include_once("./Layout/nav.php"); ?>

    <div class="container-fluid mt-5 pt-4 pb-4" style="display: flex; width: 90%; justify-content: center; flex-direction: column; align-items: center;">

        <h3 style="color:white;">EDIT POST</h3>
        <?php
        if (isset($_SESSION["success_message"])) {
            echo '<div class="alert alert-success style="font-size: .3rem;">' . $_SESSION["success_message"] . '</div>';
            unset($_SESSION["success_message"]);
        }
        if (isset($_SESSION["error_message"])) {
            echo '<div class="alert alert-danger style="font-size: .3rem;">' . $_SESSION["error_message"] . '</div>';
            unset($_SESSION["error_message"]);
        }
        if (isset($_SESSION["view_error"])) {
            echo ('<div class="d-flex flex-column gap-3">');
            echo '<div class="alert alert-danger style="font-size: .3rem;">' . $_SESSION["view_error"] . '</div>';
            unset($_SESSION["view_error"]);
            echo ('<div class="d-flex justify-content-end"><a href="./index.php" class="btn btn-primary">Go Back</a></div>');
            echo ('</div>');
        }
        ?>
        <div class="col-md-5 col-sm-12">
            <?php
            if ($postView->num_rows > 0) {
                while ($post = $postView->fetch_assoc()) {
                    echo '
                <div class="card p-4 bg-light hoverableCard" id="' . $post["id"] . '" >
                    <div class="media d-flex flex-column gap-4">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <div style="display: flex; flex-direction: column; gap: .4rem;">
                                    <h5 class="mt-0 fw-bold" style="font-size: .7rem; margin-bottom: 0; margin-block-start: 0; margin-block-end: 0; margin-inline-start: 0px; margin-inline-end: 0px;">' . $post["fname"] . " " .  $post["lname"] . '</h5>
                                    <p style="font-size: .5rem; margin-bottom: 0; margin-block-start: 0; margin-block-end: 0; margin-inline-start: 0px; margin-inline-end: 0px;">' . date("F j, Y, g:i a", strtotime($post["updated_at"])) . '</p>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="./posts/delete.php?id=' . $post["id"] . '">Delete</a></li>
                                </ul>
                            </div>
                        </div>
                        <form class="media-body" id="formSubmit" method="POST" action="./posts/update.php?id=' . $post["id"] . '">
                            <div class="form-group mb-3">
                                <textarea name="post" id="post" class="form-control p-3" rows="3" placeholder="What\'s on your mind?" style="font-size: .7rem; min-height: 120px; max-height: 120px;" aria-label="With textarea">' . $post["post"] . '</textarea>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="./index.php?id=' . $post["id"] . '" class="btn btn-secondary btn-sm mt-3" style="font-size: .7rem;">CANCEL</a>
                                <button type="submit" class="btn btn-primary btn-sm mt-3" style="font-size: .7rem;" name="update_button">UPDATE</button>
                            </div>
                        </form>
                    </div>
                </div>
                ';
                }
            }
            ?>
        </div>
    </div>

    <script src="https://unpkg.com/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>

</body>

</html>