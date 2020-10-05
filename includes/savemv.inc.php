<?php
require 'dbh.inc.php';
session_start();
$id=$_POST["id"];
$munkadijId=$_POST["mid"];

if ($id>0 && $munkadijId>0) {

  $stmt = $conn->prepare("UPDATE munkafajta SET munkadij_id='$munkadijId' WHERE Id = '$id'");

  $successfullyCopied = $stmt->execute();

  if ($successfullyCopied) {
    echo 1;
    exit;
  }
}
echo 0;
exit;
