<?php
require 'kapcsolat.inc.php';

$projektId = 0;
if(isset($_POST['pid'])){
   $projektId = mysqli_real_escape_string($conn,$_POST['pid']);
}

//a kiválasztott projektet megállítja

if($projektId > 0){
  $checkRecord = mysqli_query($conn,"SELECT * FROM projekt WHERE projekt_id = '$projektId'");
  $totalrows = mysqli_num_rows($checkRecord);

  if($totalrows > 0){
    session_start();

    unset($_SESSION['projektId']);
    unset($_SESSION['projektNeve']);
    unset($_SESSION['jogosultsag']);

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
