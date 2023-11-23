<?php

    include_once "../config/dbconnect.php";
    
    $poli_id=$_POST['record'];
    $query="DELETE FROM poli where poli_id='$poli_id'";

    $data=mysqli_query($conn,$query);

    if($data){
        echo"Item Deleted";
    }
    else{
        echo"Not able to delete";
    }
    
?>