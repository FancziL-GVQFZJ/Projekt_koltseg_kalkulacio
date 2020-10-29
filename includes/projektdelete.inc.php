<?php
require '../includes/dbh.inc.php';


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

    require_once 'naplo.inc.php';
    $szoveg = ("delete projekt ". $megn ."");
    naplozas($szoveg);
    if ($successfullyCopied && $successfullyCopied2) {
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




//   if (isset($_POST['projektdel'])){
//   require 'dbh.inc.php';
//
//   $id = mysqli_real_escape_string($conn,$_POST['projektid']);
//
//   if($id > 0)
//   {
//     // Check record exists
//     $checkRecord = mysqli_query($conn,"SELECT * FROM projekt WHERE projekt_id=".$id);
//     $totalrows = mysqli_num_rows($checkRecord);
//
//     $row=mysqli_fetch_array($checkRecord);
//     $megn=$row['projekt_nev'];
//
//     if($totalrows > 0)
//     {
//       $stmt = $conn->prepare("DELETE FROM projekt WHERE projekt_id = ?");
//       $stmt->bind_param("i", $id);
//       $successfullyCopied = $stmt->execute();
//
//       $stmt2 = $conn->prepare("DELETE FROM pf_kapcsolat WHERE projektId = ?");
//       $stmt2->bind_param("i", $id);
//
//       $successfullyCopied2 = $stmt2->execute();
//
//       require_once 'naplo.inc.php';
//       $szoveg = ("delete projekt ". $megn ."");
//       naplozas($szoveg);
//
//       if ($successfullyCopied && $successfullyCopied2)
//       {
//         header("Location: ../index.php?sikeres_felvetel");
//         exit();
//       }
//     }
//     else
//     {
//       header("Location: ../index.php?2tablasikertelenosszekapcsolasa");
//       exit();
//     }
//   }
// }
// header("Location: ../index.php?error=nemjovalami");
// exit();
