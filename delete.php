<?php 

require_once("./actions/connectdb.php");

$id = $_GET["id"];
if(isset($id)){
    $deleteData = "DELETE FROM users WHERE id = '$id' ";
    $result = mysqli_query($connect, $deleteData);
    if($result){
     echo "<script>alert('Data Deleted'); window.location.href='index.php';</script>";
    }else{
        echo "<script>alert('Eror, Failed to delete the data'); window.location.href='index.php';</script>";
    }
}else{
    echo "<script>alert('Eror, Failed to delete the data'); window.location.href='index.php';</script>";
}





?>