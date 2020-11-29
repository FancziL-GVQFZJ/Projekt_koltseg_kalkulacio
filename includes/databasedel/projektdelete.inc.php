<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';

$projektId = 0;
if(isset($_POST['pid'])){
   $projektId = mysqli_real_escape_string($conn,$_POST['pid']);
}

if ($projektId > 0) {
  $checkRecord = mysqli_query($conn,"SELECT * FROM projekt WHERE projekt_id = '$projektId'");
  $totalrows = mysqli_num_rows($checkRecord);

  $row=mysqli_fetch_array($checkRecord);
  $megn=$row['projekt_nev'];

  // minden táblábaól az adott projekt adatait törölni

  if ($totalrows > 0) {
    $stmt = $conn->prepare("DELETE FROM projekt WHERE projekt_id = ?");
    $stmt->bind_param("i", $projektId);
    $successfullyCopied = $stmt->execute();

    $stmt2 = $conn->prepare("DELETE FROM pa_kapcsolat WHERE projekt_id = ?");
    $stmt2->bind_param("i", $projektId);
    $successfullyCopied1 = $stmt2->execute();

    $stmt2 = $conn->prepare("DELETE FROM pf_kapcsolat WHERE projekt_id = ?");
    $stmt2->bind_param("i", $projektId);
    $successfullyCopied2 = $stmt2->execute();

    $stmt3 = $conn->prepare("DELETE FROM projektmunkadij WHERE projekt_id = ?");
    $stmt3->bind_param("i", $projektId);
    $successfullyCopied3 = $stmt3->execute();

    $stmt = $conn->prepare("DELETE FROM anyagkoltseg WHERE projekt_id = ?");
    $stmt->bind_param("i", $projektId);
    $successfullyCopied4 = $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM munkadijkoltseg WHERE projekt_id = ?");
    $stmt->bind_param("i", $projektId);
    $successfullyCopied5 = $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM egyebkoltseg WHERE projekt_id = ?");
    $stmt->bind_param("i", $projektId);
    $successfullyCopied6 = $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM muszakitartalom WHERE projekt_id = ?");
    $stmt->bind_param("i", $projektId);
    $successfullyCopied7 = $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM jogosultsag WHERE projekt_id = ?");
    $stmt->bind_param("i", $projektId);
    $successfullyCopied8 = $stmt->execute();

    require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
    $szoveg = ("delete projekt ". $megn ."");
    naplozas($szoveg);
    if ($successfullyCopied && $successfullyCopied1 && $successfullyCopied2 && $successfullyCopied3
        && $successfullyCopied4 && $successfullyCopied5 && $successfullyCopied6 && $successfullyCopied7
        && $successfullyCopied8) {
      echo 1;
      exit;
    }else {
      echo 0;
      exit;
    }
  }else {
    echo 0;
    exit;
  }
}else {
  echo 0;
  exit;
}

echo 0;
exit;
