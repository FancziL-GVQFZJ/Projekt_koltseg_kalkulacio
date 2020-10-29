<?php
require 'dbh.inc.php';
session_start();
$id=$_POST["id"];
$pid=$_POST["pid"];
$jog=$_POST["jog"];

if ($jog>0) {
  $checkRecord = mysqli_query($conn,"SELECT * FROM jogosultsag WHERE felhasznalo_id = '$id' AND projekt_id = '$pid'");
  $totalrows = mysqli_num_rows($checkRecord);

  if($totalrows > 0){
    if ($jog == 1) {
      $stmt = $conn->prepare("UPDATE jogosultsag SET jogosultsag_iras='1',  jogosultsag_olvasas='1'
                              WHERE felhasznalo_id = '$id' AND projekt_id = '$pid'");
    }
    else {
      $stmt = $conn->prepare("UPDATE jogosultsag SET jogosultsag_iras='0',  jogosultsag_olvasas='1'
                              WHERE felhasznalo_id = '$id' AND projekt_id = '$pid'");
    }
    $successfullyCopied = $stmt->execute();

    if ($successfullyCopied) {
      echo 1;
      exit;
    }

  }
  else {
    if ($jog == 1) {
      $stmt = $conn->prepare("INSERT INTO jogosultsag (felhasznalo_id, projekt_id, jogosultsag_iras, jogosultsag_olvasas)
                                                VALUES ('$id','$pid', '1', '1')");
    }
    else {
      $stmt = $conn->prepare("INSERT INTO jogosultsag (felhasznalo_id, projekt_id, jogosultsag_iras, jogosultsag_olvasas)
                                                VALUES ('$id','$pid', '0', '1')");
    }
    $successfullyCopied = $stmt->execute();

    if ($successfullyCopied) {
      echo 1;
      exit;
    }
  }
  echo 0;
  exit;
}
else {
  $stmt = $conn->prepare("DELETE FROM jogosultsag WHERE felhasznalo_id = '$id' AND projekt_id = '$pid'");

  $successfullyCopied = $stmt->execute();
  if ($successfullyCopied) {
    echo 1;
    exit;
  }
  echo 0;
  exit;
}
?>
