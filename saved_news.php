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

    $savenews = new SaveNews();

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        if(isset($_POST['index']) && isset($_POST['title'])){
            $index = $_POST['index'];
            $title = $_POST['title'];

            $savenews->save($con, $res_id, $index, $title);

        }
        else{
            echo "No data";
        }
    }
    elseif($_SERVER['REQUEST_METHOD'] === 'GET'){

        $savenews->retrieve($con, $res_id);

    }


    class SaveNews{

        function save($con, $res_id, $index, $title){
            $query2 = "INSERT INTO saved(user_id, news_id, news_category) VALUES('$res_id', '$index', '$title')";

            if($con->query($query2) === TRUE){
                echo "Data added successfully";
            }
            else{
                echo "Error: ". $query2."<br>" . $con->error;
            }
        }

        function retrieve($con, $res_id){
            $query3 = mysqli_query($con, "SELECT saved_id, news_id, news_category FROM saved WHERE user_id = '$res_id' ");

            $data = array();
            while($row = mysqli_fetch_assoc($query3)){
                $data[] = $row;
            }
    
            echo json_encode($data);
        }
    }

?>