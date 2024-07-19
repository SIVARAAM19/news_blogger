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

        if(isset($_POST['saved_id'])){
            $saved_id = $_POST['saved_id'];

            $query2 = "DELETE FROM saved where saved_id = '$saved_id' ";

            if($con->query($query2) === TRUE){
                echo "Data deleted successfully";
            }
            else{
                echo "Error: ". $query2."<br>" . $con->error;
            }

        }
        else{
            echo "No data";
        }

?>