<?php
require 'kapcsolat.inc.php';
session_start();
$id=$_POST["id"];
$munkadijId=$_POST["mid"];

if ($id>0 && $munkadijId>0) {

  $stmt = $conn->prepare("UPDATE egyebkoltseg SET munkadij_id='$munkadijId' WHERE egyebkoltseg_id = '$id'");
  $successfullyCopied = $stmt->execute();

  require_once 'naplo.inc.php';
  $query = mysqli_query($conn,"SELECT * FROM munkadij WHERE munkadij_id=".$munkadijId);
  $row = mysqli_fetch_array($query);
  $query2 = mysqli_query($conn,"SELECT * FROM egyebkoltseg WHERE egyebkoltseg_id=".$id);
  $row2 = mysqli_fetch_array($query2);
  $szoveg = ("update egyebkoltseg: ".$row2['egyebkoltseg_megnevezes']." to ".$row['munkadij_fajta']."");
  naplozas($szoveg);

  if ($successfullyCopied) {
    echo 1;
    exit;
  }
}
echo 0;
exit;
