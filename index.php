<?php
session_start();
require_once "./Classes/database.php";
include_once("./Classes/auth.php");

if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = null;
    header("location: ./login.php");
    exit;
}


if (isset($_POST["post_button"])) {
    $post = $_POST["post"];
    $user_id = $_SESSION["user_id"];

    $statement = $connection->prepare("INSERT INTO posts(user, post) VALUES (?, ?)");
    $statement->bind_param("is", $_SESSION["user"]["id"], $post);

    if ($statement->execute()) {
        header("location: ./index.php");
    }
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
            background: #0f0c29;  
            background: -webkit-linear-gradient(to right, #24243e, #302b63, #0f0c29); 
            background: linear-gradient(to right, #24243e, #302b63, #0f0c29); 

        }
.card {
  border: 2px solid #FF0000; 

  /* background: #fc4a1a; 
background: -webkit-linear-gradient(to right, #f7b733, #fc4a1a); 
background: linear-gradient(to right, #f7b733, #fc4a1a); */

}
/* .btn{
    border: 1px solid #FF0000; 
  background: #fc4a1a;  
background: -webkit-linear-gradient(to right, #f7b733, #fc4a1a);  
background: linear-gradient(to right, #f7b733, #fc4a1a); 

} */
.btn {
  display: flex;
  

}

.box {
    border-radius:10%;
  width: 20px;
  height: 30px;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 20px;
  font-weight: 700;
  color: #fff;
  transition: all .8s;
  cursor: pointer;
  position: relative;
  background: #fc4a1a;  
    background: -webkit-linear-gradient(to right, #f7b733, #fc4a1a);  
    background: linear-gradient(to right, #f7b733, #fc4a1a); 
  overflow: hidden;
}

.box:before {
  content: "P";
  position: absolute;
  top: 0;
  background: #0f0c29;  
background: -webkit-linear-gradient(to right, #24243e, #302b63, #0f0c29); 
background: linear-gradient(to right, #24243e, #302b63, #0f0c29); 
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  transform: translateY(100%);
  transition: transform .4s;
}

.box:nth-child(2)::before {
  transform: translateY(-100%);
  content: 'O';
}

.box:nth-child(3)::before {
  content: 'S';
}

.box:nth-child(4)::before {
  transform: translateY(-100%);
  content: 'T';
}
.btn:hover .box:before {
  transform: translateY(0);
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
.astronaut {
  width: 250px;
  height: 300px;
  position: absolute;
  z-index: 11;
  top: calc(50% - 150px);
  left: calc(50% - 125px);
  animation: astronaut 5s linear infinite;
}

.schoolbag {
  width: 100px;
  height: 150px;
  position: absolute;
  z-index: 1;
  top: calc(50% - 75px);
  left: calc(50% - 50px);
  background-color: #94b7ca;
  border-radius: 50px 50px 0 0 / 30px 30px 0 0;
}

.head {
  width: 97px;
  height: 80px;
  position: absolute;
  z-index: 3;
  background: -webkit-linear-gradient(left, #e3e8eb 0%, #e3e8eb 50%, #fbfdfa 50%, #fbfdfa 100%);
  border-radius: 50%;
  top: 34px;
  left: calc(50% - 47.5px);
}

.head:after {
  content: "";
  width: 60px;
  height: 50px;
  position: absolute;
  top: calc(50% - 25px);
  left: calc(50% - 30px);
  background: -webkit-linear-gradient(top, #15aece 0%, #15aece 50%, #0391bf 50%, #0391bf 100%);
  border-radius: 15px;
}

.head:before {
  content: "";
  width: 12px;
  height: 25px;
  position: absolute;
  top: calc(50% - 12.5px);
  left: -4px;
  background-color: #618095;
  border-radius: 5px;
  box-shadow: 92px 0px 0px #618095;
}

.body {
  width: 85px;
  height: 100px;
  position: absolute;
  z-index: 2;
  background-color: #fffbff;
  border-radius: 40px / 20px;
  top: 105px;
  left: calc(20% - 41px);
  background: -webkit-linear-gradient(left, #e3e8eb 0%, #e3e8eb 50%, #fbfdfa 50%, #fbfdfa 100%);
}

.panel {
  width: 60px;
  height: 40px;
  position: absolute;
  top: 20px;
  left: calc(50% - 30px);
  background-color: #b7cceb;
}

.panel:before {
  content: "";
  width: 30px;
  height: 5px;
  position: absolute;
  top: 9px;
  left: 7px;
  background-color: #fbfdfa;
  box-shadow: 0px 9px 0px #fbfdfa, 0px 18px 0px #fbfdfa;
}

.panel:after {
  content: "";
  width: 8px;
  height: 8px;
  position: absolute;
  top: 9px;
  right: 7px;
  background-color: #fbfdfa;
  border-radius: 50%;
  box-shadow: 0px 14px 0px 2px #fbfdfa;
}

.arm {
  width: 80px;
  height: 30px;
  position: absolute;
  top: 121px;
  z-index: 2;
}

.arm-left {
  left: 30px;
  background-color: #e3e8eb;
  border-radius: 0 0 0 39px;
}

.arm-right {
  right: 30px;
  background-color: #fbfdfa;
  border-radius: 0 0 39px 0;
}

.arm-left:before,
.arm-right:before {
  content: "";
  width: 30px;
  height: 70px;
  position: absolute;
  top: -40px;
}

.arm-left:before {
  border-radius: 50px 50px 0px 120px / 50px 50px 0 110px;
  left: 0;
  background-color: #e3e8eb;
}

.arm-right:before {
  border-radius: 50px 50px 120px 0 / 50px 50px 110px 0;
  right: 0;
  background-color: #fbfdfa;
}

.arm-left:after,
.arm-right:after {
  content: "";
  width: 30px;
  height: 10px;
  position: absolute;
  top: -24px;
}

.arm-left:after {
  background-color: #6e91a4;
  left: 0;
}

.arm-right:after {
  right: 0;
  background-color: #b6d2e0;
}

.leg {
  width: 30px;
  height: 40px;
  position: absolute;
  z-index: 2;
  bottom: 70px;
}

.leg-left {
  left: 76px;
  background-color: #e3e8eb;
  transform: rotate(20deg);
}

.leg-right {
  right: 73px;
  background-color: #fbfdfa;
  transform: rotate(-20deg);
}

.leg-left:before,
.leg-right:before {
  content: "";
  width: 50px;
  height: 25px;
  position: absolute;
  bottom: -26px;
}

.leg-left:before {
  left: -20px;
  background-color: #e3e8eb;
  border-radius: 30px 0 0 0;
  border-bottom: 10px solid #6d96ac;
}

.leg-right:before {
  right: -20px;
  background-color: #fbfdfa;
  border-radius: 0 30px 0 0;
  border-bottom: 10px solid #b0cfe4;
}

    </style>
</head>

<body id="gala" style="background-image:url('img/gala.jpg');">
    <?php
    include_once("./Layout/nav.php");
    ?>
    
    <div class="container-fluid mt-5 pt-4 pb-4" style="width: 100%;">
        <div class="row justify-content-center">
            <div class="col-md-3 d-none d-md-block">
            <div class="card mb-2">
    <div class="card-body d-flex flex-column gap-1">
        <div class="d-flex align-items-center" style="align-items: center; gap: .5rem;">
      <a href="./profile.php?id=<?php echo $_SESSION["user"]["id"] ?>" class="card-title m-0 text-wrap" style="text-decoration:none; color:black; font-size: 30px; ">
                <?php
                echo ($_SESSION["user"]["fname"] . " " . $_SESSION["user"]["lname"]);
                ?>
            </a>
            
        </div>
    </div>
</div>


            </div>
            <div class="col-md-5 col-sm-12">

                <!-- Post Input -->
                <div class="card">
                    <div class="card-body">
                        <form action="./index.php" method="post">
                            <div class="form-group mb-3">
                                <textarea required name="post" id="user_text_input" class="form-control p-3" rows="3" placeholder="What's on your mind?" style="font-size: .7rem; min-height: 120px; max-height: 120px;" aria-label="With textarea"></textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" name="post_button" class="btn">
                                <div class="box">P</div>
                                <div class="box">O</div>
                                <div class="box">S</div>
                                <div class="box">T</div>
                                 </button>
                            </div>

                        </form>
                    </div>
                </div>

                <!-- Posts -->
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="d-flex">
                            <h4 class="card-title mb-3 rounded-2 text-white bg-dark" style="padding: .3rem .7rem; font-size: .8rem;">Posts</h4>
                        </div>
                        <div class="d-flex flex-column gap-3" id="postHere">
                            <?php
                            $posts = $connection->query(
                                "SELECT users.fname, users.mname, users.lname, posts.id, posts.user, posts.post, posts.created_at, posts.updated_at
                                FROM users
                                LEFT JOIN posts ON posts.user = users.id
                                ORDER BY posts.created_at DESC
                                "
                            );


                            foreach ($posts as $post) {
                                echo ('
                                <div class="card p-4 bg-light hoverableCard" id="id">
                                    <div class="media d-flex flex-column gap-4">
                                        <div class="d-flex align-items-start justify-content-between gap-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <div style="display: flex; flex-direction: column; gap: .4rem;">
                                                    <a href="./profile.php?id=' . $post["user"] . '" class="mt-0 fw-bold" style="text-decoration:none; color:black; font-size: .7rem; margin-bottom: 0; margin-block-start: 0; margin-block-end: 0; margin-inline-start: 0px; margin-inline-end: 0px;">' . $post["fname"] . " " .  $post["lname"] . '</a>
                                                    <p style="font-size: .5rem; margin-bottom: 0; margin-block-start: 0; margin-block-end: 0; margin-inline-start: 0px; margin-inline-end: 0px;">');
                                                    if (!is_null($post["updated_at"])) {
                                                        echo date("F j, Y, g:i a", strtotime($post["updated_at"]));
                                                    } else {
                                                        echo "N/A";
                                                    }
                                                    echo ('
                                    
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
                                                    <li><a class="dropdown-item" href="#" onclick="deletePost(' . $post["id"] . ', ' . $post["user"] . ')">DELETE</a></li>

                                                    
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
                                        </div>
                                    
                                    ');
                            }

                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Discover Friends -->
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
    <script>
    function deletePost(postId, userId) {
        if (confirm("Are you sure you want to delete this post?")) {
            // Redirect to the delete endpoint with the post ID and user ID
            window.location.href = "./posts/delete.php?id=" + postId + "&post_user=" + userId;
        }
    }
    </script>

    <script src="https://unpkg.com/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>

</body>

</html>