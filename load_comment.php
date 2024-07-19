<?php
    session_start();
    include("php/config.php");
    if(!isset($_SESSION['valid'])){
     header("Location: index.php");
    }
             
     $id = $_SESSION['id'];
     $query = mysqli_query($con,"SELECT*FROM users WHERE Id=$id");
     while($result = mysqli_fetch_assoc($query)){
         $res_Uname = $result['Username'];
         $res_Email = $result['Email'];
         $res_Age = $result['Age'];
         $res_id = $result['Id'];
     }

     if($_SERVER['REQUEST_METHOD'] === 'GET'){

        $query = mysqli_query($con, "SELECT comments.user_id, comments.news_id, comments.comment, users.Username FROM comments INNER JOIN users ON comments.user_id=users.Id");

        $data = array();
        while($row = mysqli_fetch_assoc($query)){
            $data[] = $row;
        }

        echo json_encode($data);
    }


?>