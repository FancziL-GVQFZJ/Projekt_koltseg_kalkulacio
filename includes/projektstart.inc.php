<?php
require '../includes/dbh.inc.php';


$projektId = 0;
if(isset($_POST['pid'])){
   $projektId = mysqli_real_escape_string($conn,$_POST['pid']);
}

if($projektId > 0){

  $checkRecord = mysqli_query($conn,"SELECT * FROM projekt WHERE projekt_id = '$projektId'");
  $totalrows = mysqli_num_rows($checkRecord);

  if($totalrows > 0){
    session_start();

    $row=mysqli_fetch_array($checkRecord);
    $projekt_nev = $row['projekt_nev'];
    $fid = $_SESSION['userId'];
    $fnev = $_SESSION['userUid'];

    $_SESSION['projektId'] = $projektId;
    $_SESSION['projektNeve'] = $projekt_nev;

    $checkRecord2 = mysqli_query($conn,"SELECT * FROM jogosultsag
              WHERE felhasznalo_id = '$fid' AND projekt_id = '$projektId'");
    $totalrows2 = mysqli_num_rows($checkRecord2);

    if ($totalrows2 > 0){
      $row2=mysqli_fetch_array($checkRecord2);
      if ($row2['jogosultsag_iras'] == 1 ) {
        $jogosultsag = "iras";
      }
      elseif ($row2['jogosultsag_iras'] == 0 && $row2['jogosultsag_olvasas'] == 1) {
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
