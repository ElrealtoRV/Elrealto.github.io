<?php
session_start();
require_once "./Classes/database.php";

include_once("./Classes/auth.php");


if (isset($_POST["comment_button"])) {
    $post = $_POST["post"];
    $user_id = $_SESSION["user_id"];

    $statement = $connection->prepare("INSERT INTO posts(user, post) VALUES (?, ?)");
    $statement->bind_param("is", $_SESSION["user"]["id"], $post);

    if ($statement->execute()) {
        header("location: ./index.php");
    }
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
    $_SESSION["view_error"] = "Post  does not exist.";
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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/fontawesome.css" integrity="sha384-jLKHWM3JRmfMU0A5x5AkjWkw/EYfGUAGagvnfryNV3F9VqM98XiIH7VBGVoxVSc7" crossorigin="anonymous" />
    <title>FastSend</title>

    <style>
        /* CSS for profile */
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
.dropdown-menu{
    background: #0f0c29; 
background: -webkit-linear-gradient(to right, #24243e, #302b63, #0f0c29); 
background: linear-gradient(to right, #24243e, #302b63, #0f0c29); 
}
.dropdown-item{
    color:white;
}
.button {
  cursor: pointer;
  position: relative;
  padding: 10px 24px;
  font-size: 18px;
  color: rgb(193, 163, 98);
  border: 2px solid rgb(193, 163, 98);
  border-radius: 34px;
  background-color: transparent;
  font-weight: 600;
  transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
  overflow: hidden;
}

.button::before {
  content: '';
  position: absolute;
  inset: 0;
  margin: auto;
  width: 50px;
  height: 50px;
  border-radius: inherit;
  scale: 0;
  z-index: -1;
  background-color: rgb(193, 163, 98);
  transition: all 0.6s cubic-bezier(0.23, 1, 0.320, 1);
}

.btn:hover::before {
  scale: 3;
}

.btn:hover {
  color: #212121;
  scale: 1.1;
  box-shadow: 0 0px 20px rgba(193, 163, 98,0.4);
}

.btn:active {
  scale: 1;
}
    
    </style>
</head>

<body style="background-image:url('img/gala.jpg');">


    <?php
    include_once("./Layout/nav.php");
    ?>

    <div class="container-fluid mt-5 pt-4 pb-4" style="width: 90%;">
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
        <div class="row justify-content-center">
            <div class="col-md-3 d-none d-md-block">
                <div class="card mb-2">
                    <div class="card-body d-flex flex-column gap-1">
                        <div class="d-flex align-items-center" style="align-items: center; gap: .5rem;">
                            <a href=" " class="card-title m-0 text-wrap" style="text-decoration:none; color:black; font-size: .6rem;">
                                <?php
                                echo ($_SESSION["user"]["fname"] . " " . $_SESSION["user"]["lname"]);
                                ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
           
            <div class="col-md-5 col-sm-12">

                <?php

                if ($postView->num_rows > 0) {
                    while ($post = $postView->fetch_assoc()) {
                        echo ('
                            <div class="card p-4 bg-light" id="' . $post["id"] . '">
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
                            </div>
                            
                        ');
                        
                        if ($post["user"] == $_SESSION["user"]["id"]) {
                            echo ('
                                <div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="./editPostView.php?id=' . $post["id"] . '&post_user=' . $post["user"] . '">EDIT</a></li>
                                        <li><a class="dropdown-item" href="./posts/delete.php?id=' . $post["id"] . '&post_user=' . $post["user"] . '">DELETE</a></li>
                                    </ul>
                                </div>
                                ');
                        }
                        echo ('
                                    
                                </div>
                                <div class="media-body pt-3 pb-3">
                                    <p class="" style="font-size: .6rem; margin-bottom: 0;">
                                        ' . $post["post"] . '
                                    </p>

                                    </div>
                                    <div class="button-containers d-flex align-items-start justify-content-start gap-2">
                                        <div class="like-button-content d-flex align-items-center justify-content-center gap-2">
                                            <a href="./posts/like.php?id=' . $post["id"] . '" class="
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
                                    <hr style="margin: .6rem 0;"/>
                                    
                                    <form action="./posts/comment.php?id=' . $post["id"] . '" class="d-flex gap-1 mb-3" method="POST">
                                        <input type="text" id="message_input" name="comment" placeholder="Write a comment" style="font-size: .7rem; border-radius: 5rem; flex: 1 1 100%; padding: .5rem 1rem; border: 1px solid orange;"></input>
                                        <button type="submit" name="comment_button" class="btn btn-primary w-auto px-4" style="font-size: .7rem; gap: .5rem; border-radius: 5rem">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </form>
                                    <h6 style="color:white;">Comments</h6> ');
                    }
                }
                ?>

                <?php

                $query = "SELECT comments.id, comments.comment_text, comments.created_at, users.fname, users.lname, comments.user_id, comments.updated_at
                    FROM comments
                    INNER JOIN users ON comments.user_id = users.id
                    WHERE comments.post_id = $post_id
                    ORDER BY comments.created_at DESC";

                $commentsResult = $connection->query($query);

                if ($commentsResult->num_rows > 0) {
                    while ($comment = $commentsResult->fetch_assoc()) {
                        echo ('
                            <div class="card p-3 bg-light" id="id" style="margin-bottom: 5px;">
                                <div class="media d-flex flex-column gap-4">
                                    <div class="d-flex align-items-start justify-content-between gap-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="display: flex; flex-direction: column; gap: .4rem;">
                                            <a href="./profile.php?id=<?php echo $user; ?>" class="mt-0 fw-bold" style="text-decoration:none; color:black; font-size: .5rem; margin-bottom: 0; margin-block-start: 0; margin-block-end: 0; margin-inline-start: 0px; margin-inline-end: 0px;">
                                            
                                                ' . $comment["fname"] . " " .  $comment["lname"] . '
                                                </a>
                                                <p style="font-size: .45rem; margin-bottom: 0; margin-block-start: 0; margin-block-end: 0; margin-inline-start: 0px; margin-inline-end: 0px;">
                                                ' .
                            date("F j, Y, g:i a", strtotime($comment["updated_at"]))
                            . '
                                                </p>
                                            </div>
                                        </div>');
                                        if ($comment["user_id"] == $_SESSION["user"]["id"]) {
                                            echo ('
                                                <div class="dropdown">
                                                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li><a class="dropdown-item" href="./editCommentView.php?id=' . $comment["id"] . '&post_id=' . $post_id . '">EDIT</a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="deleteComment(' . $comment["id"] . ', ' . $post_id . ')">DELETE</a></li>
                                                    </ul>
                                                </div>
                                            ');
                                        }
                                        

                        echo ('
                                    </div>
                                </div>
                                <div class="media-body pt-1 pb-1">
                                    <p class="" style="font-size: .5rem; margin-bottom: 0;">
                                        ' . $comment["comment_text"] . '
                                    </p>
                                </div>
                                
                            </div>

                        ');
                    }
                }
                ?>

                <?php
                echo ('</div>'
                );
                ?>


            </div>

            <!-- Discover Friends -->
         
                   
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>

</body>

</html>