<?php

//létrehozza a kapcsolatot az adatbázissal
$servername = "localhost";
$dBUsername = "root";
$dBPassword = "mysql";
$dBname = "kalkulacio_db";

$conn = mysqli_connect($servername,$dBUsername,$dBPassword,$dBname);
if (!$conn) {
  die("A kapcsolatfelvétel nem sikerül: ".mysqli_connect_error());
}
?>
