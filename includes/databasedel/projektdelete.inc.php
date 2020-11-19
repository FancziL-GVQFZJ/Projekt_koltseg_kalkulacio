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

  if ($totalrows > 0) {
    $stmt = $conn->prepare("DELETE FROM projekt WHERE projekt_id = ?");
    $stmt->bind_param("i", $projektId);
    $successfullyCopied = $stmt->execute();

    $stmt2 = $conn->prepare("DELETE FROM pf_kapcsolat WHERE projekt_id = ?");
    $stmt2->bind_param("i", $projektId);
    $successfullyCopied2 = $stmt2->execute();

    $stmt3 = $conn->prepare("DELETE FROM projektmunkadij WHERE projekt_id = ?");
    $stmt3->bind_param("i", $projektId);
    $successfullyCopied3 = $stmt3->execute();

    require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
    $szoveg = ("delete projekt ". $megn ."");
    naplozas($szoveg);
    if ($successfullyCopied && $successfullyCopied2 && $successfullyCopied3) {
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
