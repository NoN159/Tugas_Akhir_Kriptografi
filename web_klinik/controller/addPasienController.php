<?php
    include_once "../config/dbconnect.php";

function generateRandomKey($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_=+';
    $randomString = '';
    $max = strlen($characters) - 1;
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $max)];
    }
    
    return $randomString;
}

// Function to encrypt data using AES
function encryptAES($data, $key, $iv) {
    $cipher = "aes-256-cbc"; // AES encryption algorithm and mode
    $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    return $encrypted;
}

// Function to encode with Base64 and Caesar cipher
function superEncrypt($data) {
    // Base64 Encoding
    $base64Encoded = base64_encode($data);

    // Caesar Cipher with shift value 17
    $shift = 17;
    $caesarCipher = '';
    $length = strlen($base64Encoded);
    for ($i = 0; $i < $length; $i++) {
        $char = $base64Encoded[$i];
        if (ctype_alpha($char)) {
            $asciiStart = ord(ctype_upper($char) ? 'A' : 'a');
            $caesarCipher .= chr(($shift + ord($char) - $asciiStart) % 26 + $asciiStart);
        } else {
            $caesarCipher .= $char;
        }
    }
    return $caesarCipher;
}

    
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        
    $nik = $_POST['p_nik'];
    $Name = $_POST['p_nama'];
    $alamat= $_POST['p_alamat'];
    $tanggallahir= $_POST['p_tanggal'];
    $nomor= $_POST['p_nomor'];
    $keluhan= $_POST['p_keluhan'];
    $poli = $_POST['poli'];

    $enNik = superEncrypt($nik);
    $enName = superEncrypt($Name);
    $enAlamat = superEncrypt($alamat);
    $enTanggalLahir = superEncrypt($tanggallahir);
    $enNomor = superEncrypt($nomor);
    $enKeluhan = superEncrypt($keluhan);

       
            
    // AES encryption parameters
    $encryptionKey = generateRandomKey(32);
    $iv = openssl_random_pseudo_bytes(16); // Initialization Vector

    // Read the image file content
    $fileContent = file_get_contents($_FILES['file']['tmp_name']);

    // Encrypt the image content (using your encryption function)
    $encryptedImage = encryptAES($fileContent, $encryptionKey, $iv);

    $encryptedImage = $encryptedImageData['data'];
    $iv = $encryptedImageData['iv'];


    // Store the encrypted image data
    $target_dir = "../uploads/";
    $encryptedFileName = "encrypted_picture_" . $_FILES['file']['name']; // Adjust filename if needed
    $finalEncryptedImage = $target_dir . $encryptedFileName;
    file_put_contents($finalEncryptedImage, $encryptedImage);

    // Store the encryption key in the database
    $insert = mysqli_query($conn, "INSERT INTO pasien (nik, nama, alamat, tanggal_lahir, nomor_hp, keluhan, poli_id, berkas_foto, encryption_key, iv) 
        VALUES ('$enNik', '$enName', '$enAlamat', '$enTanggalLahir', '$enNomor', '$enKeluhan', '$poli', '$finalEncryptedImage', '$encryptionKey','$iv')");

    if(!$insert) {
        echo mysqli_error($conn);
             header("Location: ../dashboard.php?category=error");
    } else {
        echo "Records added successfully.";
             header("Location: ../dashboard.php?category=success");
    }
     
    }
        
?>