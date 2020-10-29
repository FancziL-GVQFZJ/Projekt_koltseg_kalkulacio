<?php

$servername = "localhost";
$dBUsername = "root";
$dBPassword = "mysql";
$dBname = "kalkulacio_db";

$conn = mysqli_connect($servername,$dBUsername,$dBPassword,$dBname);

if (!$conn) {
  die("Connection failed: ".mysqli_connect_error());
}
