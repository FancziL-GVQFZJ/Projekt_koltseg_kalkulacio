<?php
$servername = "localhost";
$dBUsername = "root";
$dBPassword = "root";
$dBname = "kalkulacio_db";

$conn = mysqli_connect($servername,$dBUsername,$dBPassword,$dBname);
if (!$conn) {
  die("A kapcsolatfelvétel nem sikerül: ".mysqli_connect_error());
}
?>
