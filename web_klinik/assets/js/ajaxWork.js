
function showDataPasien(){  
    $.ajax({
        url:"./view/viewPasien.php",
        method:"post",
        data:{record:1},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}
function showPoli(){  
    $.ajax({
        url:"./view/viewPoli.php",
        method:"post",
        data:{record:1},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}




//delete product data
function itemDelete(id){
    $.ajax({
        url:"./controller/deletePasienController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('Successfully deleted');
            $('form').trigger('reset');
            showDataPasien();
        }
    });
}

//delete category data
function poliDelete(id){
    $.ajax({
        url:"./controller/deletePoliController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('Successfully deleted');
            $('form').trigger('reset');
            showPoli();
        }
    });
}



