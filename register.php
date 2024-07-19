<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/loginstyle.css">
    <title>Register</title>
</head>
<body>
    <body style="background-image: url('assets/backimg.png'); background-position: 25% 10%;"></body>
    <div class="background-container">
        <div class="content">
        </div>
    </div>
      <div class="container">
        <div class="box form-box">

        <?php 
         
         include("php/config.php");
         if(isset($_POST['submit'])){
            $username = $_POST['username'];
            $email = $_POST['email'];
            $age = $_POST['age'];
            $password = $_POST['password'];

         
         $verify_query = mysqli_query($con,"SELECT Email FROM users WHERE Email='$email'");

         if(mysqli_num_rows($verify_query) !=0 ){
            echo "<div class='message'>
                      <p>This email is used, Try another One Please!</p>
                  </div> <br>";
            echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
         }
         else{  

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            mysqli_query($con,"INSERT INTO users(Username,Email,Age,Password) VALUES('$username','$email','$age','$hashed_password')") or die("Erroe Occured");

           // setcookie("username", $username, time() + (86400 * 30), "/"); // 86400 = 1 day
            
            echo "<div class='message1'>
                      <p>Registration successfully!</p>
                  </div> <br>";
            echo "<a href='index.php'><button class='btn'>Login Now</button>";

         }

         }else{
         
        ?>

            <header>SIGN UP</header>
            <form action="" method="post">
                    <input type="text" placeholder="Username" name="username" id="username" autocomplete="off" required class="input1">
            
                    <input type="text" placeholder="Email" name="email" id="email" autocomplete="off" required class="input1">
               
                    <input type="number" placeholder="Age" name="age" id="age" autocomplete="off" required class="input1">
                
                    <input type="password" placeholder="Password" name="password" id="password" autocomplete="off" required class="input1">                
                    
                    <input type="submit" class="btn" name="submit" value="Register" required>
                
                <div class="links">
                    Already a member? <a href="index.php">Sign In</a>
                </div>
            </form>
        </div>
        <?php } ?>
      </div>
</body>
</html>