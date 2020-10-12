<?php
require 'dbh.inc.php';
session_start();
$id=$_POST["id"];
$munkadijId=$_POST["mid"];

if ($id>0 && $munkadijId>0) {

  $stmt = $conn->prepare("UPDATE munkafajta SET munkadij_id='$munkadijId' WHERE Id = '$id'");

  $successfullyCopied = $stmt->execute();

  require_once 'naplo.inc.php';
  $query = mysqli_query($conn,"SELECT * FROM projektmunkadij WHERE Munkadij_id='$munkadijId'");
  $row = mysqli_fetch_array($query);

  $query2 = mysqli_query($conn,"SELECT * FROM munkafajta WHERE Id='$id'");
  $row2 = mysqli_fetch_array($query2);
  $szoveg = ("update munkafajta: ".$row2['Megnevezes']." to ".$row['pm_MunkaFajta']."");
  naplozas($szoveg);

  if ($successfullyCopied) {
    echo 1;
    exit;
  }
}
echo 0;
exit;
