<?php
require '../includes/dbh.inc.php';


$projektId = 0;
if(isset($_POST['pid'])){
   $projektId = mysqli_real_escape_string($conn,$_POST['pid']);
}

if($projektId > 0){

  $checkRecord = mysqli_query($conn,"SELECT * FROM projekt WHERE idProjekt = '$projektId'");
  $totalrows = mysqli_num_rows($checkRecord);

  if($totalrows > 0){
    session_start();

    $row=mysqli_fetch_array($checkRecord);
    $projektNev = $row['projektNev'];
    $fid = $_SESSION['userId'];
    $fnev = $_SESSION['userUid'];

    $_SESSION['projektId'] = $projektId;
    $_SESSION['projektNeve'] = $projektNev;

    $checkRecord2 = mysqli_query($conn,"SELECT * FROM jogosultsag
              WHERE user_id = '$fid' AND projekt_id = '$projektId'");
    $totalrows2 = mysqli_num_rows($checkRecord2);

    if ($totalrows2 > 0){
      $row2=mysqli_fetch_array($checkRecord2);
      if ($row2['iras'] == 1 ) {
        $jogosultsag = "iras";
      }
      elseif ($row2['iras'] == 0 && $row2['olvasas'] == 1) {
        $jogosultsag = "olvasas";
      }

    }else{
      $jogosultsag = "admin";
    }
    $_SESSION['jogosultsag'] = $jogosultsag;

    echo 1;
    exit;

  }else {
    echo 0;
    exit;
  }
}else{
  echo 0;
  exit;
}
echo 0;
exit;


// if (isset($_POST['projektstart'])) {
//   require 'dbh.inc.php';
//
//   $projektId = $_POST['projektid'];
//   $projektNev = $_POST['projektnev'];
//
//   //session_unset($_SESSION['projektId']);
//   //session_unset($_SESSION['projektNeve']);
//
//   session_start();
//   $_SESSION['projektId'] = $projektId;
//   $_SESSION['projektNeve'] = $projektNev;
//
//
//   $fid = $_SESSION['userId'];
//   $fnev = $_SESSION['userUid'];
//
//
//   $checkRecord = mysqli_query($conn,"SELECT * FROM jogosultsag
//             WHERE user_id = '$fid' AND projekt_id = '$projektId'");
//   $totalrows = mysqli_num_rows($checkRecord);
//
//   if ($totalrows > 0){
//     $row=mysqli_fetch_array($checkRecord);
//     if ($row['iras'] == 1 ) {
//       $jogosultsag = "iras";
//     }
//     elseif ($row['iras'] == 0 && $row['olvasas'] == 1) {
//       $jogosultsag = "olvasas";
//     }
//
//   }else{
//     $jogosultsag = "admin";
//   }
//   $_SESSION['jogosultsag'] = $jogosultsag;
//
//
//   header("Location: ../index.php");
//   exit();
//}
