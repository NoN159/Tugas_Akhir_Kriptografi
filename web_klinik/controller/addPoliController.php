<?php
    include_once "../config/dbconnect.php";
    
    if(isset($_POST['upload']))
    {
       
        $polname = $_POST['nama_poli'];
       
         $insert = mysqli_query($conn,"INSERT INTO poli
         (nama_poli) 
         VALUES ('$polname')");
 
         if(!$insert)
         {
             echo mysqli_error($conn);
             header("Location: ../dashboard.php?category=error");
         }
         else
         {
             echo "Records added successfully.";
             header("Location: ../dashboard.php?category=success");
         }
     
    }
        
?>