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
 
     if($_SERVER['REQUEST_METHOD'] === 'POST'){

        if(isset($_POST['url']) && isset($_POST['title']) && isset($_POST['comment'])){
            $url = $_POST['url'];
            $title = $_POST['title'];
            $comment = $_POST['comment'];

            $com = new Comment_Class();
            $com->save_comment($res_id, $url, $title, $comment, $con);
        }
     } 


     

     class Comment_Class{
        public $url;
        public $title;
        public $comment;

        function save_comment($res_id, $url, $title, $comment, $con){
            $this->url = $url;
            $this->title = $title;
            $this->comment = $comment;

            $query2 = "INSERT INTO comments(user_id, news_id, comment, news_title) VALUES('$res_id', '$url', '$comment', '$title')";

            if($con->query($query2) === TRUE){
                echo "Comment added successfully";
            }
            else{
                echo "Error: ". $query2."<br>" . $con->error;
            }
        }

     }
?>
