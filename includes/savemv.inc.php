<?php
require 'kapcsolat.inc.php';
session_start();
$id=$_POST["id"];
$munkadijId=$_POST["mid"];

//munkadíjköltség táblázatban állítja be a munkavégzőt

if ($id>0 && $munkadijId>0) {
  $stmt = $conn->prepare("UPDATE munkadijkoltseg SET munkadij_id='$munkadijId' WHERE munkadijkoltseg_id = '$id'");
  $successfullyCopied = $stmt->execute();

  require_once 'naplo.inc.php';
  $pid = $_SESSION['projektId'];
  $query = mysqli_query($conn,"SELECT * FROM projektmunkadij WHERE munkadij_id='$munkadijId' AND projekt_id='$pid'");
  $row = mysqli_fetch_array($query);

  $query2 = mysqli_query($conn,"SELECT * FROM munkadijkoltseg WHERE munkadijkoltseg_id='$id'");
  $row2 = mysqli_fetch_array($query2);
  $szoveg = ("update munkadijkoltseg: ".$row2['munkadijkoltseg_megnevezes']." to ".$row['projektmunkadij_munkafajta']."");
  naplozas($szoveg);

  if ($successfullyCopied) {
    echo 1;
    exit;
  }
}
echo 0;
exit;
