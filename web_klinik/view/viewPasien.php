<?php
function decryptAES($data, $key, $iv) {
   $cipher = "aes-256-cbc"; // AES decryption algorithm and mode
    $decrypted = openssl_decrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);

    if ($decrypted === false) {
        // Error occurred during decryption
        $error = openssl_error_string();
        error_log("Decryption error: $error");
        return null; // or handle the error as required
    }

    return $decrypted;
}

function superDecrypt($data) {
    // Caesar Cipher decryption with shift value 3 (for example)
    $shift = 17;
    $caesarDecipher = '';
    $length = strlen($data);
    for ($i = 0; $i < $length; $i++) {
        $char = $data[$i];
        if (ctype_alpha($char)) {
            $asciiStart = ord(ctype_upper($char) ? 'A' : 'a');
            $caesarDecipher .= chr((26 + ord($char) - $shift - $asciiStart) % 26 + $asciiStart);
        } else {
            $caesarDecipher .= $char;
        }
    }

    // Base64 Decoding
    $base64Decoded = base64_decode($caesarDecipher);
    return $base64Decoded;
}
?>




<div >
  <h2>Data Pasien</h2>
  <table class="table ">
    <thead>
      <tr>
        <th class="text-center">Nomor</th>
        <th class="text-center">Foto Pasien</th>
        <th class="text-center">NIK</th>
        <th class="text-center">Nama Pasien</th>
        <th class="text-center">Alamat</th>
        <th class="text-center">Tanggal Lahir</th>
        <th class="text-center">Nomor HP</th>
        <th class="text-center">Keluhan</th>
        <th class="text-center">Poli</th>
        <th class="text-center" colspan="2">Action</th>
      </tr>
    </thead>
    <?php
      include_once "../config/dbconnect.php";

      $sql="SELECT * FROM pasien INNER JOIN poli ON pasien.poli_id = poli.poli_id";
      $result=$conn-> query($sql);
      $count=1;
      if ($result-> num_rows > 0){
        while ($row=$result-> fetch_assoc()) {
    ?>
    <tr>
      <td><?=$count?></td>   
      <td>            
        <?php
              // Decrypt the image
              $encryptedImagePath = $row["berkas_foto"];
              $encryptionKey = $row["encryption_key"];
              $iv = $row["iv"];
              $encryptedImage = file_get_contents($encryptedImagePath);
              $decryptedImage = decryptAES($encryptedImage, $encryptionKey, $iv);

              // Display the decrypted image (assuming it's an image file)
              $imageData = base64_encode($decryptedImage);
              echo '<img src="data:image/jpeg;base64,' . $imageData . 'style="max-width: 100px; max-height: 100px;" />';
            ?>
              
      </td>
      <?php
       $decryptedNik = superDecrypt($row["nik"]);
        $decryptedName = superDecrypt($row["nama"]);
        $decryptedAlamat = superDecrypt($row["alamat"]);
        $decryptedTanggalLahir = superDecrypt($row["tanggal_lahir"]);
        $decryptedNomor = superDecrypt($row["nomor_hp"]);
        $decryptedKeluhan = superDecrypt($row["keluhan"]);

        echo "<td>$decryptedNik</td>";
        echo "<td>$decryptedName</td>";
        echo "<td>$decryptedAlamat</td>";
        echo "<td>$decryptedTanggalLahir</td>";
        echo "<td>$decryptedNomor</td>";
        echo "<td>$decryptedKeluhan</td>";
      ?>   
        <td><?=$row["nama_poli"]?></td>  
      <td><button class="btn btn-danger" style="height:40px" onclick="itemDelete('<?=$row['id_pasien']?>')">Delete</button></td>
      </tr>
      <?php
            $count=$count+1;
          }
        }
      ?>
  </table>

  <!-- Trigger the modal with a button -->
  <button type="button" class="btn btn-secondary " style="height:40px" data-toggle="modal" data-target="#myModal">
    Tambah Data Pasien
  </button>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Data Pasien Baru</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form  enctype='multipart/form-data' action="./controller/addPasienController.php" method="POST">
            <div class="form-group">
              <label for="nik">NIK Pasien:</label>
              <input type="text" class="form-control" name="p_nik" required>
            </div>
            <div>
              <label for="nama">Nama Pasien:</label>
              <input type="text" class="form-control" name="p_nama" required>
            </div>
            <div>
              <label for="alamat">Alamat:</label>
              <input type="text" class="form-control" name="p_alamat" required>
            </div>
            <div>
              <label for="tanggal">Tanggal Lahir:</label>
              <input type="text" class="form-control" name="p_tanggal" required>
            </div>
            <div class="form-group">
              <label for="nomor">Nomor HP:</label>
              <input type="text" class="form-control" name="p_nomor" required>
            </div>
            <div class="form-group">
              <label for="keluhan">Keluhan:</label>
              <input type="text" class="form-control" name="p_keluhan" required>
            </div>
            <div class="form-group">
              <label>Poli:</label>
              <select name="poli" >
                <option disabled selected>Pilih Poli</option>
                <?php

                  $sql="SELECT * from poli";
                  $result = $conn-> query($sql);

                  if ($result-> num_rows > 0){
                    while($row = $result-> fetch_assoc()){
                      echo"<option value='".$row['poli_id']."'>".$row['nama_poli'] ."</option>";
                    }
                  }
                ?>
              </select>
            </div>
            <div class="form-group">
                <label for="file">Masukkan Gambar:</label>
                <input type="file" class="form-control-file" name="file" accept="image/*">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-secondary" name="uploadpasien" style="height:40px">Save</button>
            </div>
          </form>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px">Close</button>
        </div>
      </div>
      
    </div>
  </div>

  
</div>
   