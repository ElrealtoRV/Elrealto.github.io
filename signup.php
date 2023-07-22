<?php
session_start();
require_once "./Classes/database.php";

if (isset($_SESSION["user"])) {
    header("location: ./index.php");
    exit();
}

if (isset($_POST["signup_button"])) {
    $fname = $_POST["fname"];
    $mname = $_POST["mname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password_confirmation = $_POST["password_confirmation"];

    if ($password === $password_confirmation) {
        $statement = $connection->prepare("INSERT INTO users(fname, mname, lname, email, `password`) VALUES (?, ?, ?, ?, ?)");
        $statement->bind_param("sssss", $fname, $mname, $lname, $email, $hash);

        $hash = password_hash($password, PASSWORD_BCRYPT);

        if ($statement->execute()) {
            header("location: ./login.php");
            exit();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Signup | FastSend</title>
    <title>FastSend</title> 
</head>
<style>
    body {
  margin: 0;
  padding: 0;
  font-family: 'Tahoma', cursive;
  background-color:black;
}
#gif-background {
            position: absolute;
            top: 0;
            bottom:0;
            left: 300px;
            width: 100%;
            height: 100%;
            z-index: -1;
            
        }

        .gif-container {
            position: absolute;
            top: 0;
            right: 300px;
            width: 100%;
            height: 100%;
            align-items:center;
            pointer-events: none;
        }
#gif-background1 {
            position: fixed;
            top: 300px;
            bottom: 300px;
            width: 100%;
            height: 100%;
            z-index: -1;
            
        }

        .gif-container1 {
            position: absolute;
            top: 0;
            right: 100px;
            width: 100%;
            height: 100%;
            align-items:center;
            pointer-events: none;
            padding-bottom:40px;
        }

/* .container {
  display: flex;
  height: 100vh;
  align-items: center;
  justify-content: center;
} */

.left {
  flex: 1;
  display: flex;
  align-items: center;
  margin-left:600px;
}

.right {
  flex: 1;
  background-color: #333;
}

/* .login-section {
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
  border: none;
  border-radius: 5px;
  background-color: #f1f1f1;
  outline: none;
  transition: box-shadow 0.3s;
}

.input-field:focus {
  box-shadow: 0 0 5px #ff00ff;
} */

a {
  text-decoration: none;
  color: #333;
}

a:hover {
  color: #ff00ff;
}

/* button {
  width: 50%;
  padding: 10px;
  border: none;
  border-radius: 5px;
  background-color: #ff00ff;
  color: #fff;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s;
}
button.animation.a6 {
  position: relative;
  overflow: hidden;
  transition: background-color 0.3s;
}
button.animation.a6:before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 0;
  height: 0;
  border-top: 8px solid transparent;
  border-bottom: 8px solid transparent;
  border-left: 8px solid #fff;
  opacity: 0;
  transition: all 0.3s;
}
button.animation.a6:hover {
  background-color: #ff0080;
}

button.animation.a6:hover:before {
  opacity: 1;
  transform: translate(-50%, -50%) rotate(360deg);
}
 button:hover {
  background-color: #ff0080;
} */
.container {
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  font-style: italic;
  font-weight: bold;
  padding-top:50px;
  display: flex;
  margin: auto;
  aspect-ratio: 16/9;
  align-items: center;
  justify-items: center;
  justify-content: center;
  flex-wrap: wrap;
  flex-direction: column;
  gap: 1em;
  width: 70%;
}

.input-container {
  filter: drop-shadow(46px 36px 24px #4090b5) drop-shadow(-55px -40px 25px #9e30a9);
  animation: blinkShadowsFilter 8s ease-in infinite;
}

.input-content {
  display: grid;
  align-content: center;
  justify-items: center;
  align-items: center;
  text-align: center;
  padding-inline: 1em;
}

.input-content::before {
  content: "";
  position: absolute;
  width: 100%;
  height: 100%;
  filter: blur(40px);
  -webkit-clip-path: polygon(26% 0, 66% 0, 92% 0, 100% 8%, 100% 89%, 91% 100%, 7% 100%, 0 92%, 0 0);
  clip-path: polygon(26% 0, 66% 0, 92% 0, 100% 8%, 100% 89%, 91% 100%, 7% 100%, 0 92%, 0 0);
  background: rgba(122, 251, 255, 0.5568627451);
  transition: all 1s ease-in-out;
}

.input-content::after {
  content: "";
  position: absolute;
  width: 98%;
  height: 98%;
  box-shadow: inset 0px 0px 20px 20px #212121;
  background: repeating-linear-gradient(to bottom, transparent 0%, rgba(64, 144, 181, 0.6) 1px, rgb(0, 0, 0) 3px, hsl(295, 60%, 12%) 5px, #153544 4px, transparent 0.5%), repeating-linear-gradient(to left, hsl(295, 60%, 12%) 100%, hsla(295, 60%, 12%, 0.99) 100%);
  -webkit-clip-path: polygon(26% 0, 31% 5%, 61% 5%, 66% 0, 92% 0, 100% 8%, 100% 89%, 91% 100%, 7% 100%, 0 92%, 0 0);
  clip-path: polygon(26% 0, 31% 5%, 61% 5%, 66% 0, 92% 0, 100% 8%, 100% 89%, 91% 100%, 7% 100%, 0 92%, 0 0);
  animation: backglitch 50ms linear infinite;
}

.input-dist {
  z-index: 80;
  display: grid;
  align-items: center;
  text-align: center;
  width: 100%;
  padding-inline: 1em;
  padding-block: 1.2em;
  grid-template-columns: 1fr;
}

.input-type {
  display: flex;
  flex-wrap: wrap;
  flex-direction: column;
  gap: 1em;
  font-size: 1.1rem;
  background-color: transparent;
  width: 100%;
  border: none;
}

.input-is {
  color: #fff;
  font-size: 0.9rem;
  background-color: transparent;
  width: 100%;
  box-sizing: border-box;
  padding-inline: 0.5em;
  padding-block: 0.7em;
  border: none;
  transition: all 1s ease-in-out;
  border-bottom: 1px solid hsl(221, 26%, 43%);
}

.input-is:hover {
  transition: all 1s ease-in-out;
  background: linear-gradient(90deg, transparent 0%, rgba(102, 224, 255, 0.2) 27%, rgba(102, 224, 255, 0.2) 63%, transparent 100%);
}

.input-content:focus-within::before {
  transition: all 1s ease-in-out;
  background: hsla(0, 0%, 100%, 0.814);
}

.input-is:focus {
  outline: none;
  border-bottom: 1px solid hsl(192, 100%, 100%);
  color: hsl(192, 100%, 88%);
  background: linear-gradient(90deg, transparent 0%, rgba(102, 224, 255, 0.2) 27%, rgba(102, 224, 255, 0.2) 63%, transparent 100%);
}

.input-is::-moz-placeholder {
  color: hsla(192, 100%, 88%, 0.806);
}

.input-is::placeholder {
  color: hsla(192, 100%, 88%, 0.806);
}

@keyframes backglitch {
  0% {
    box-shadow: inset 0px 20px 20px 30px #212121;
  }

  50% {
    box-shadow: inset 0px -20px 20px 30px hsl(297, 42%, 10%);
  }

  to {
    box-shadow: inset 0px 20px 20px 30px #212121;
  }
}

@keyframes rotate {
  0% {
    transform: rotate(0deg) translate(-50%, 20%);
  }

  50% {
    transform: rotate(180deg) translate(40%, 10%);
  }

  to {
    transform: rotate(360deg) translate(-50%, 20%);
  }
}

@keyframes blinkShadowsFilter {
  0% {
    filter: drop-shadow(46px 36px 28px rgba(64, 144, 181, 0.3411764706)) drop-shadow(-55px -40px 28px #9e30a9);
  }

  25% {
    filter: drop-shadow(46px -36px 24px rgba(64, 144, 181, 0.8980392157)) drop-shadow(-55px 40px 24px #9e30a9);
  }

  50% {
    filter: drop-shadow(46px 36px 30px rgba(64, 144, 181, 0.8980392157)) drop-shadow(-55px 40px 30px rgba(159, 48, 169, 0.2941176471));
  }

  75% {
    filter: drop-shadow(20px -18px 25px rgba(64, 144, 181, 0.8980392157)) drop-shadow(-20px 20px 25px rgba(159, 48, 169, 0.2941176471));
  }

  to {
    filter: drop-shadow(46px 36px 28px rgba(64, 144, 181, 0.3411764706)) drop-shadow(-55px -40px 28px #9e30a9);
  }
}
header{
  color:violet;
}
p{
  color:white;
  
}
.login{
  color:white;
}
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
  left: calc(50% - 41px);
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
.container1 {
  font-family: 'Courier', monospace;
}
.loader {
  position: absolute;
  top: 50%;
  left: 19%;
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
/* .loader {
  position: absolute;
  top: 30%;
  left: 20%;
  z-index: 20;
  width: 300px;
  height: 300px;
  margin-left: -80px;
  margin-top: -50px;
  border-radius: 5px;
  color:violet;
  font-size:35px;
  font-family: "Roboto", Arial, sans-serif;
  animation: dot1_ 15s cubic-bezier(0.55,0.3,0.24,0.99) infinite;
}


@keyframes dot1_ {
  3%,97% {
    width: 200px;
    height: 50px;
    margin-top: -50px;
    margin-left: -80px;
  }

  30%,36% {
    width: 200px;
    height: 50px;
    margin-top: -60px;
    margin-left: -40px;
  }

  63%,69% {
    width: 200px;
    height: 50px;
    margin-top: -40px;
    margin-left: -20px;
  }
} */


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
</style>
<body >
<div class="container1">
	<div class="loader">
  Join the FastSend revolution and connect with 
   <br>friends in an instant!
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
            <iframe src="https://giphy.com/embed/NpATWgCGJVn9K" width="90%" height="90%" frameborder="0" class="giphy-embed" allowfullscreen></iframe>
        </div>
        
    </div> -->
<!-- <div id="gif-background">
        <div class="gif-container">
            <iframe src="https://giphy.com/embed/OK5LK5zLFfdm" width="50%" height="50%" frameborder="0" class="giphy-embed" allowfullscreen></iframe>
        </div>
    </div> -->
    
    <div class="right"></div>
<div class="left">
<div class="login-section">
            <div class="container">
      <div class="input-container">
         <div class="input-content">
           <div class="input-dist">
              <div class="input-type">
              <header>
                    <h2 class="animation a1">Sign up</h2>
                    <h4 class="animation a2">
                        Please fill in the form below to create an account.
                    </h4>
                </header>
                <form method="POST" action="./signup.php">
                <input type="text" id="fname" name="fname" placeholder="First Name" class="input-is animation a3"  required/>
                    <input type="text" id="mname" name="mname" placeholder="Middle Name" class="input-is animation a3" required/>
                    <input type="text" id="lname" name="lname" placeholder="Last Name" class="input-is animation a3" required/>
                    <input type="email" id="email" name="email" placeholder="Email" class="input-is animation a3" required/>
                    <input type="password" id="password" name="password" placeholder="Password" class="input-is animation a4" required/>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Verify Password" class="input-is animation a4" required/>
                    <p class="animation a5">Already have an account?<a href="login.php" class="login"><i>Login</i></a></p>
                    <div class="wrap">
                    <button class="button"type="submit" name="signup_button">Register</button>
                  </div>
                </form>
           </div>
          </div>
      </div>
  </div>
</div>
</div>
</div>
</body>

</html>

