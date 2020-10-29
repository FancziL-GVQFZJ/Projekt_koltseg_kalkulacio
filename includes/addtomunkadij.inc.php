<?php
require 'dbh.inc.php';
session_start();


$megn = $_POST['name'];
$ora = $_POST['oraber'];

$stmt = $conn->prepare("INSERT INTO munkadij (munkadij_fajta, munkadij_oraber)
                                          VALUES ('$megn','$ora')");
$successfullyCopied = $stmt->execute();

require_once 'naplo.inc.php';
$szoveg = ("insert munkadij ". $megn ."");
naplozas($szoveg);

if ($successfullyCopied) {
  header("Location: ../munkadijak.php");
  exit;
}
?>
