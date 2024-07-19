<?php 

session_start();
include("php/config.php");
if(!isset($_SESSION['valid'])){
 header("Location: index.php");
}
 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

        
$id = $_SESSION['id'];
$query = mysqli_query($con,"SELECT*FROM users WHERE Id=$id");
while($result = mysqli_fetch_assoc($query)){
    $res_Uname = $result['Username'];
    $res_Email = $result['Email'];
    $res_Age = $result['Age'];
    $res_id = $result['Id'];
}


$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  
    $mail->SMTPAuth = true;            
    $mail->Username = 'kishorekannanguna@gmail.com';  
    $mail->Password = 'znwb xgon utyd qtsd';          
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;  

    // Recipients
    $mail->setFrom('kishorekannanguna@gmail.com', 'Truescope');
    $mail->addAddress($res_Email, $res_Uname); 

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Registration Confirmation';
    $mail->Body    = 'Thank you for registering! This is your confirmation email.   -by truescope';

    // Optional: Add attachment

    // Send email
    $mail->send();
    echo '<script>alert("Registration successful. Confirmation email sent.")</script>';
    header("Location: truescope.php");
} catch (Exception $e) {
    echo "Registration failed. Mailer Error: {$mail->ErrorInfo}";
}
?>
