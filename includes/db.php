<?php
$host = "localhost";
$user = "root";      // sesuaikan
$pass = "Danial2103!";          // sesuaikan
$dbname = "blog";    // sesuai yg kamu buat

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
