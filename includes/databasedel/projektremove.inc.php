<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
session_start();

$projektId = 0;
if(isset($_POST['pid'])){
   $projektId = mysqli_real_escape_string($conn,$_POST['pid']);
}
$fid = $_SESSION['userId'];

// a megosztott projekt törlése a listából (csak a rám vonatkozó jogosultságot törli)

if ($projektId > 0) {
  $checkRecord = mysqli_query($conn,"SELECT * FROM jogosultsag WHERE felhasznalo_id = '$fid' AND projekt_id = '$projektId'");
  $totalrows = mysqli_num_rows($checkRecord);

  if ($totalrows > 0) {
    $stmt = $conn->prepare("DELETE FROM jogosultsag WHERE felhasznalo_id = ? AND projekt_id = ?");
    $stmt->bind_param("ss", $fid, $projektId);
    $successfullyCopied = $stmt->execute();

    require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
    $sor = mysqli_query($conn,"SELECT * FROM projekt WHERE projekt_id = '$projektId'");
    $row = mysqli_fetch_array($sor);
    $megn = $row['projekt_nev'];
    $szoveg = ("remove projekt jogosultság ". $megn ."");
    naplozas($szoveg);
    if ($successfullyCopied) {
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
