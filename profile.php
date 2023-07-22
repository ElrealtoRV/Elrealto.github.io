<?php
session_start();
require_once "./Classes/database.php";

$user_id = htmlspecialchars($_GET["id"]);

$user = $connection->query(
    "SELECT users.id, users.fname, users.mname, users.lname, users.email, users.password, users.created_at, users.updated_at
    FROM users
    WHERE users.id = " . $user_id . "
    ORDER BY users.created_at DESC"
);

$posts = $connection->query(
    "SELECT users.fname, users.mname, users.lname, posts.id, posts.user, posts.post, posts.created_at, posts.updated_at
    FROM users
    LEFT JOIN posts ON posts.user = users.id
    WHERE posts.user = " . $user_id . "
    ORDER BY posts.created_at DESC
    "
);

if ($posts->num_rows == 0) {
    $_SESSION["profile_error"] = "No post yet.";
}
function getLikeCount($post_id)
{
    global $connection;
    $statement = $connection->prepare("SELECT COUNT(*) as count FROM likes WHERE post_id = ?");
    $statement->bind_param("i", $post_id);
    $statement->execute();
    $result = $statement->get_result();
    $row = $result->fetch_assoc();
    return $row["count"];
}
function getCommentCount($post_id)
{
    global $connection;
    $statement = $connection->prepare("SELECT COUNT(*) as count FROM comments WHERE post_id = ?");
    $statement->bind_param("i", $post_id);
    $statement->execute();
    $result = $statement->get_result();
    $row = $result->fetch_assoc();
    return $row["count"];
}
function isLiked($post_id)
{
    global $connection;
    $statement = $connection->prepare("SELECT COUNT(*) as count FROM likes WHERE post_id = ? AND user_id = ?");
    $statement->bind_param("ii", $post_id, $_SESSION["user"]["id"]);
    $statement->execute();
    $result = $statement->get_result();
    $row = $result->fetch_assoc();
    return $row["count"] > 0;
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/solid.css" integrity="sha384-Tv5i09RULyHKMwX0E8wJUqSOaXlyu3SQxORObAI08iUwIalMmN5L6AvlPX2LMoSE" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/fontawesome.css" integrity="sha384-jLKHWM3JRmfMU0A5x5AkjWkw/EYfGUAGagvnfryNV3F9VqM98XiIH7VBGVoxVSc7" crossorigin="anonymous" />
    <title>FastSend</title>

    <style>
       
        body{
            background: #0f0c29;  /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #24243e, #302b63, #0f0c29);  /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #24243e, #302b63, #0f0c29); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

                    }
            .card {
            border: 2px solid #FF0000; /* Red border */
            /* background: #fc4a1a; 
            background: -webkit-linear-gradient(to right, #f7b733, #fc4a1a);
            background: linear-gradient(to right, #f7b733, #fc4a1a);  */


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
.dropdown-menu{
    background: #0f0c29; 
background: -webkit-linear-gradient(to right, #24243e, #302b63, #0f0c29); 
background: linear-gradient(to right, #24243e, #302b63, #0f0c29); 
}
.dropdown-item{
    color:white;
}
.active-dot {
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: green;
  margin-left: 5px;
}

    </style>
</head>

<body style="background-image:url('img/gala.jpg');" >
    
    <?php
    include_once("./Layout/nav.php");
    ?>
        
    </div>
    <div class="container-fluid  pt-2 pb-4" style="width: 90%;">
        <div class="row justify-content-center">
            <div class="col-md-3 d-none d-md-block">
                <div class="card mb-2">
                    <div class="card-body d-flex flex-column gap-1">
                        <div class="d-flex align-items-center" style="align-items: center; gap: .5rem;">
                            <a href=" " class="card-title m-0 text-wrap" style="text-decoration:none; color:black; font-size: 30px; ">
                                <?php
                                if ($user->num_rows > 0) {
                                    $row = $user->fetch_assoc();
                                    echo $row["fname"] . " " . $row["lname"];
                                } else {
                                    echo "User not found.";
                                }
                                ?>
                            </a>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="col-md-5 col-sm-12">
                <!-- Posts -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <h4 class="card-title mb-3 rounded-2 text-white bg-dark" style="padding: .3rem .7rem; font-size: .8rem;">Posts</h4>
                        </div>
                        <div class="d-flex flex-column gap-3" id="postHere">
                            <?php
                            if (isset($_SESSION["profile_error"])) {
                                echo '<div class="alert alert-danger style="font-size: .3rem;">' . $_SESSION["profile_error"] . '</div>';
                                unset($_SESSION["profile_error"]);
                                // go back button
                                echo ('<div class="d-flex justify-content-end"><a href="./index.php" class="btn btn-primary">Go Back</a></div>');
                            }

                            foreach ($posts as $post) {
                                echo ('
                                    <div class="card p-4 bg-light hoverableCard" id="id">
                                        <div class="media d-flex flex-column gap-4">
                                            <div class="d-flex align-items-start justify-content-between gap-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div style="display: flex; flex-direction: column; gap: .4rem;">
                                                        <a href="./profile.php?id=' . $post["user"] . '" class="mt-0 fw-bold" style="text-decoration:none; color:black; font-size: .7rem; margin-bottom: 0; margin-block-start: 0; margin-block-end: 0; margin-inline-start: 0px; margin-inline-end: 0px;">' . $post["fname"] . " " .  $post["lname"] . '</a>
                                                        <p style="font-size: .5rem; margin-bottom: 0; margin-block-start: 0; margin-block-end: 0; margin-inline-start: 0px; margin-inline-end: 0px;">
                                                            ' .
                                    date("F j, Y, g:i a", strtotime($post["updated_at"]))
                                    . '
                                                        </p>
                                                    </div>
                                                </div>
                                ');
                                if ($post["user"] == $_SESSION["user"]["id"]) {
                                    echo ('
                                                <div class="dropdown">
                                                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li><a class="dropdown-item" href="./editPostView.php?id=' . $post["id"] . '&post_user=' . $post["user"] . '">EDIT</a></li>
                                                        <li><a class="dropdown-item" href="./posts/delete.php?id=' . $post["id"] . '">DELETE</a></li>
                                                    </ul>
                                                </div>
                                                ');
                                }
                                echo ('
                                                </div>
                                            </div>
                                            <div class="media-body pt-3 pb-3">
                                                <p class="" style="font-size: .6rem; margin-bottom: 0;">
                                                    ' . $post["post"] . '
                                                </p>

                                                </div>
                                                <div class="button-containers d-flex align-items-start justify-content-start gap-2">
                                                    <div class="like-button-content d-flex align-items-center justify-content-center gap-2">
                                                        <a href="./posts/like.php?id=' . $post["id"] . '"class="
                                                            ' .
                                    (isLiked($post["id"]) ? 'text-primary' : 'text-dark')
                                    . '
                                                        mt-0 fw-bold" style="text-decoration:none; color:black; font-size: .7rem; margin-bottom: 0; margin-block-start: 0; margin-block-end: 0; margin-inline-start: 0px; margin-inline-end: 0px; ">
                                                            <i class="like-button fas fa-thumbs-up" style="font-size: .7rem;"></i>
                                                            <p class="mt-0 fw-bold" style="text-decoration:none; color:black; font-size: .7rem; margin-bottom: 0; margin-block-start: 0; margin-block-end: 0; margin-inline-start: 0px; margin-inline-end: 0px;">' . getLikeCount($post["id"]) . '</p>
                                                        </a>
                                                    </div>
                                                    <div class="comment-button-content d-flex align-items-center justify-content-center gap-2">
                                                        <a href="./view.php?id=' . $post["id"] . '" class="mt-0 fw-bold" style="text-decoration:none; color:black; font-size: .7rem; margin-bottom: 0; margin-block-start: 0; margin-block-end: 0; margin-inline-start: 0px; margin-inline-end: 0px; ">
                                                            <i class="comment-button fas fa-comment" style="font-size: .7rem;"></i>
                                                            <p class="mt-0 fw-bold" style="text-decoration:none; color:black; font-size: .7rem; margin-bottom: 0; margin-block-start: 0; margin-block-end: 0; margin-inline-start: 0px; margin-inline-end: 0px;">' . getCommentCount($post["id"]) . '</p>
                                                        </a>
                                                    </div>
                                                </div>
                                        </div>
                                    
                                    ');
                            }

                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 d-none d-md-block">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Friend List</h5>
            <ul class="friend-list">
                
                <span class="active-dot"></span>
                    <img src="img/bill gates.jpg" style="width:40px; height:30px;"alt="Friend 1" class="friend-avatar">
                    <span class="friend-name">Bill Gates</span><br>
                    
                
             
                <span class="active-dot"></span>
                    <img src="img/elon musk.jpeg" style="width:40px; height:30px;"alt="Friend 2" class="friend-avatar">
                    <span class="friend-name">Elon Musk</span><br>
                   
               
              
                <span class="active-dot"></span>
                    <img src="img/Jeff Bezos.jpg" style="width:40px; height:30px;" alt="Friend 3" class="friend-avatar">
                    <span class="friend-name">Saitama</span><br>
                    
              
              
                <span class="active-dot"></span>
                    <img src="img/mark zuckerberg.jpg" style="width:40px; height:30px;" alt="Friend 4" class="friend-avatar">
                    <span class="friend-name">Mark Tahimik</span>
              
            </ul>
        </div>
    </div>
</div>


            </div>
        </div>
    </div>

    <script src="https://unpkg.com/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>

</body>

</html>