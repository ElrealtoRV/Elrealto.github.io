<?php
session_start();
require_once "./Classes/database.php";

if (isset($_SESSION["user"])) {
header("location: ./index.php");
exit();
}  
$userFound = false; 
if (isset($_POST["login"])) {
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    $statement = $connection->prepare("SELECT * FROM users WHERE users.email = ?");
    $statement->bind_param("s", $email);

    $statement->execute();

    $results = $statement->get_result();

    if ($results->num_rows > 0) {
        while ($row = $results->fetch_assoc()) {
            if (password_verify($password, $row["password"])) {
                $_SESSION["user"] = $row;
                $userFound = true;
                header("location: ./index.php");
            } else {
                echo '<p class="error-message animate__animated animate__fadeInUp a7">Invalid Password</p>';
            }
        }
    }  if (!$userFound) {
        echo '<p class="error-message animate__animated animate__fadeInUp a7">User Not Found</p>';
      
}
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        @keyframes snow {
  0% {
    opacity: 0;
    transform: translateY(0px);
  }

  20% {
    opacity: 1;
  }

  100% {
    opacity: 1;
    transform: translateY(650px);
  }
}

@keyframes astronaut {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

.box-of-star1,
.box-of-star2,
.box-of-star3,
.box-of-star4 {
  width: 100%;
  position: absolute;
  z-index: 10;
  left: 0;
  top: 0;
  transform: translateY(0px);
  height: 700px;
}

.box-of-star1 {
  animation: snow 5s linear infinite;
}

.box-of-star2 {
  animation: snow 5s -1.64s linear infinite;
}

.box-of-star3 {
  animation: snow 5s -2.30s linear infinite;
}

.box-of-star4 {
  animation: snow 5s -3.30s linear infinite;
}

.star {
  width: 3px;
  height: 3px;
  border-radius: 50%;
  background-color: #FFF;
  position: absolute;
  z-index: 10;
  opacity: 0.7;
}

.star:before {
  content: "";
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background-color: #FFF;
  position: absolute;
  z-index: 10;
  top: 80px;
  left: 70px;
  opacity: .7;
}

.star:after {
  content: "";
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: #FFF;
  position: absolute;
  z-index: 10;
  top: 8px;
  left: 170px;
  opacity: .9;
}

.star-position1 {
  top: 30px;
  left: 20px;
}

.star-position2 {
  top: 110px;
  left: 250px;
}

.star-position3 {
  top: 60px;
  left: 570px;
}

.star-position4 {
  top: 120px;
  left: 900px;
}

.star-position5 {
  top: 20px;
  left: 1120px;
}

.star-position6 {
  top: 90px;
  left: 1280px;
}

.star-position7 {
  top: 30px;
  left: 1480px;
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
body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color:black;
            
        }

        #gif-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .gif-container {
            position: absolute;
            top: 0;
            left: 400px;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .container {
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;

        }

        .center {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .right {
            flex: 1;
            background-color: #333;
        }

        .login-section {
            max-width: 400px;
            margin: auto;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            background-color: #8BC6EC;
            background-image: linear-gradient(135deg, #8BC6EC 0%, #9599E2 100%);
            animation: glowing 2s infinite;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        @keyframes glowing {
                0% {
                    border: 2px solid #ff0000;
                    box-shadow: 0 0 10px #ff0000;
                }

                50% {
                    border: 2px solid #00ff00;
                    box-shadow: 0 0 20px #00ff00;
                }

                100% {
                    border: 2px solid #0000ff;
                    box-shadow: 0 0 10px #0000ff;
             }
        }
        

        .input-field {
            width: 90%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
        }

        .input-field:focus {
            border-color: #ff00ff;
        }

        /* .arrow-icon {
            display: inline-block;
            margin-left: 5px;
            transition: transform 0.3s;
        }

        .login-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff00ff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            outline: none;
            transition: background-color 0.3s;
        }

        .login-button:hover {
            background-color: #e600e6;
        }

        .login-button:active .arrow-icon {
            transform: translateX(5px);
        } */

        .signup-link {
            color: #333;
            transition: color 0.3s;
        }

        .signup-link:hover {
            color: #ff00ff;
        }
        .login-form .error-message {
      color: red;
      margin-top: 10px;
    }
    .wrap {
  height: 20%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.button {
  min-width: 100px;
  min-height: 20px;
  font-family: 'Nunito', sans-serif;
  font-size: 20px;
  text-transform: uppercase;
  letter-spacing: 1.3px;
  font-weight: 700;
  color: #313133;
  background: #4FD1C5;
background: linear-gradient(90deg, rgba(129,230,217,1) 0%, rgba(79,209,197,1) 100%);
  border: none;
  border-radius: 2000px;
  box-shadow: 12px 12px 24px rgba(79,209,197,.64);
  transition: all 0.3s ease-in-out 0s;
  cursor: pointer;
  outline: none;
  position: relative;
  padding: 10px;
  }

button::before {
content: '';
  border-radius: 1000px;
  min-width: calc(300px + 12px);
  min-height: calc(60px + 12px);
  border: 6px solid #00FFCB;
  box-shadow: 0 0 60px rgba(0,255,203,.64);
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  opacity: 0;
  transition: all .3s ease-in-out 0s;
}

.button:hover, .button:focus {
  color: #313133;
  transform: translateY(-6px);
}

button:hover::before, button:focus::before {
  opacity: 1;
}

button::after {
  content: '';
  width: 30px; height: 30px;
  border-radius: 100%;
  border: 6px solid #00FFCB;
  position: absolute;
  z-index: -1;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  animation: ring 1.5s infinite;
}

button:hover::after, button:focus::after {
  animation: none;
  display: none;
}

@keyframes ring {
  0% {
    width: 20px;
    height: 20px;
    opacity: 1;
  }
  100% {
    width: 300px;
    height: 300px;
    opacity: 0;
  }
}

.container1 {
  font-family: 'Courier', monospace;
}
.loader {
  position: absolute;
  top: 20%;
  left: 45%;
  Right:50%;
  font-size: 20px;
  width: 20px;
  height: 50px;
  margin-left: -200px;
  margin-top: -50px;
  color:violet;
  overflow: hidden; /* Ensures the text stays within the container */
  animation: typing 3s steps(40, end)infinite;
  white-space: nowrap; /* Prevents line breaks */
  border-right: 0.1em solid #333; /* Simulates cursor */
}

@keyframes typing {
  from {
    width: 0; /* Starts with no width (hidden) */
  }
  to {
    width: 100%; /* Animates to full width (visible) */
  }
}
.satellite {
background-color: white;
height: 50px;
width: 40px;
left: 80%;
top:50%;
position: relative;
transform-origin: bottom;
animation: grow 4s linear infinite;
}
.satellite::before {
content: "";
position: absolute;
background-color: #ff6d00;
height: 40px;
width: 40px;
border-radius: 50%;
top: 55px;
}
.satellite::after {
content: "";
position: absolute;
background-color: white;
height: 50px;
width: 40px;
top: 100px;
}
@keyframes grow {
100% {
transform: rotate(360deg) scale(0.5);
}
50% {
transform: rotate(180deg) scale(1);
}
0% {
transform: rotate(0deg) scale(0.5);
}
}
    </style>
    
</head>

<body>
<div class="container1">
	<div class="loader">
  Login and Discover a World of Instant Connections
  </div>
  <div class="satellite"></div> 

</div>
<div class="container">
        <div class="center">
            <div class="login-section animate__animated animate__fadeIn">
                <header>
                    <h2 class="animate__animated animate__fadeInDown a1">Login</h2>
                    <h4 class="animate__animated animate__fadeInDown a2">
                        Please login to your account.
                    </h4>
                </header>
                <form method="post" action="./login.php" class="animate__animated animate__fadeInUp">
                    <input type="email" id="email" name="email" placeholder="Email" class="input-field a3" />
                    <input type="password" id="password" name="password" placeholder="Password" class="input-field a4" />
                    <p class="animate__animated animate__fadeInUp a5">Don't have an account? <a href="signup.php" class="signup-link">Sign up</a></p>
                    <!-- <button class="animate__animated animate__fadeInUp a6 login-button" type="submit" name="login">
                        Login <i class="arrow-icon fas fa-arrow-right"></i>
                    </button> -->
                    <div class="wrap">
                    <button class="button"type="submit" name="login">Login</button>
                  </div>
                </form>
                
            
        </div>
        </div>
        

<div class="body">
<div class="box-of-star1">
    <div class="star star-position1"></div>
    <div class="star star-position2"></div>
    <div class="star star-position3"></div>
    <div class="star star-position4"></div>
    <div class="star star-position5"></div>
    <div class="star star-position6"></div>
    <div class="star star-position7"></div>
  </div>
  <div class="box-of-star2">
    <div class="star star-position1"></div>
    <div class="star star-position2"></div>
    <div class="star star-position3"></div>
    <div class="star star-position4"></div>
    <div class="star star-position5"></div>
    <div class="star star-position6"></div>
    <div class="star star-position7"></div>
  </div>
  <div class="box-of-star3">
    
    <div class="star star-position1"></div>
    <div class="star star-position2"></div>
    <div class="star star-position3"></div>
    <div class="star star-position4"></div>
    <div class="star star-position5"></div>
    <div class="star star-position6"></div>
    <div class="star star-position7"></div>
  </div>
  <div class="box-of-star4">
    
    <div class="star star-position1"></div>
    <div class="star star-position2"></div>
    <div class="star star-position3"></div>
    <div class="star star-position4"></div>
    <div class="star star-position5"></div>
    <div class="star star-position6"></div>
    <div class="star star-position7"></div>
  </div>
  <div data-js="astro" class="astronaut">
    <div class="head"></div>
    <div class="arm arm-left"></div>
    <div class="arm arm-right"></div>
    <div class="body">
      <div class="panel"></div>
    </div>
    <div class="leg leg-left"></div>
    <div class="leg leg-right"></div>
    <div class="schoolbag"></div>
  </div>
  </div>
    <!-- <div id="gif-background">
        <div class="gif-container">
            <iframe src="https://giphy.com/embed/1BcSawJYHPjfHekFYe" width="100%" height="100%" frameborder="0" class="giphy-embed" allowfullscreen></iframe>
        </div>
    </div> -->


    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>

</html>
