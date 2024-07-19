<?php
session_start();
include("php/config.php");

if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit();
}

$id = $_SESSION['id'];
$query = mysqli_query($con, "SELECT * FROM users WHERE Id = $id");
if ($result = mysqli_fetch_assoc($query)) {
    $res_Uname = $result['Username'];
    $res_Email = $result['Email'];
    $res_Age = $result['Age'];
    $res_id = $result['Id']; // Same as $id
} else {
    die("User not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /*if(isset($_POST['index']) && isset($_POST['title'])){
        $index = $_POST['index'];
        $title = $_POST['title'];
    }   */
    //$user_id = mysqli_real_escape_string($con, $_POST['user_id']);
    //$news_id = mysqli_real_escape_string($con, $_POST['news_id']);
    $comment = mysqli_real_escape_string($con, $_POST['comment']);
    $title = mysqli_real_escape_string($con, $_POST['param']);

    if (filter_var($user_id, FILTER_VALIDATE_INT) === false || 
        filter_var($news_id, FILTER_VALIDATE_INT) === false) {
        die("Invalid user_id or news_id.");
    }

    $user_check_query = mysqli_query($con, "SELECT Id FROM users WHERE Id = $user_id");
    if (mysqli_num_rows($user_check_query) == 0) {
        die("Error: User ID does not exist.");
    }

    $query = "INSERT INTO comments (user_id, news_id, comment) VALUES ('$user_id', '$news_id', '$comment')";
    if (mysqli_query($con, $query)) {
        header('Location: ' . $_SERVER['HTTP_REFERER']); 
        exit();
    } else {
        echo "Error: " . mysqli_error($con);
    }
} else {
    header('Location: truescope.php');
    exit();
}
?>
