<!--<?php 
   session_start();
?>-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/loginstyle.css">
    <title>Login</title>
</head>
<body>
    <body style="background-image: url('assets/backimg.png'); background-position: 25% 10%; "></body>
      <div class="container">
        <div class="box form-box">
            <!--<?php 
             
              include("php/config.php");
              if(isset($_POST['submit'])){
                $email = mysqli_real_escape_string($con,$_POST['email']);
                $password = mysqli_real_escape_string($con,$_POST['password']);

                $result = mysqli_query($con,"SELECT * FROM users WHERE Email='$email' AND Password='$password' ") or die("Select Error");
                $row = mysqli_fetch_assoc($result);

                if(is_array($row) && !empty($row)){
                    $_SESSION['valid'] = $row['Email'];
                    $_SESSION['username'] = $row['Username'];
                    $_SESSION['age'] = $row['Age'];
                    $_SESSION['id'] = $row['Id'];
                }else{
                  echo "console.log('Wrong email or password')";
                    echo "<div class='message'>
                      <p>Wrong Username or Password</p>
                       </div> <br>";
                   echo "<a href='index.php'><button class='btn'>Go Back</button>";
         
                }
                if(isset($_SESSION['valid'])){
                  echo '<script>alert("going to mail.php")</script>';
                    header("Location: mail.php");
                }
              }else{

                
            
            ?>-->
            <header>LOGIN</header>
            <form action="" method="post">
                    <input type="text" placeholder="Email" name="email" id="email" autocomplete="off" required class="input1">
                
                    <input type="password" placeholder="Password" name="password" id="password" autocomplete="off" required class="input1">
               
                    <input type="submit" class="btn" name="submit" value="Login" required>
               
                <div class="links">
                    Don't have account? <a href="register.php">Sign Up Now</a>
                </div>
                
            </form>
        </div>
        <!--<?php } ?>-->
      </div>
</body>
</html>