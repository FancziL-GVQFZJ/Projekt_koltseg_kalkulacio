<?php
require 'dbh.inc.php';
session_start();
$id=$_POST["id"];
$munkadijId=$_POST["mid"];

if ($id>0 && $munkadijId>0) {

  $stmt = $conn->prepare("UPDATE munkadijkoltseg SET munkadij_id='$munkadijId' WHERE munkadiijkoltseg_id = '$id'");

  $successfullyCopied = $stmt->execute();

  require_once 'naplo.inc.php';
  $query = mysqli_query($conn,"SELECT * FROM projektmunkadij WHERE munkadij_id='$munkadijId'");
  $row = mysqli_fetch_array($query);

  $query2 = mysqli_query($conn,"SELECT * FROM munkadijkoltseg WHERE munkadiijkoltseg_id='$id'");
  $row2 = mysqli_fetch_array($query2);
  $szoveg = ("update munkadijkoltseg: ".$row2['munkadijkoltseg_megnevezes']." to ".$row['pm_MunkaFajta']."");
  naplozas($szoveg);

  if ($successfullyCopied) {
    echo 1;
    exit;
  }
}
echo 0;
exit;
