<?php

    include_once "../config/dbconnect.php";
    
    $id=$_POST['record'];
    $query="DELETE FROM pasien where id_pasien='$id'";

    $data=mysqli_query($conn,$query);

    if($data){
        echo"Data Pasien Terhapus";
    }
    else{
        echo"Not able to delete";
    }
    
?>