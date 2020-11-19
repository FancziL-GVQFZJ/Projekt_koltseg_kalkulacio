<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
session_start();

$id = 0;
$pid = $_SESSION['projektId'];
if(isset($_POST['id'])){
   $id = mysqli_real_escape_string($conn,$_POST['id']);
}

if($id > 0){
  $checkRecord = mysqli_query($conn,"SELECT * FROM helyi_anyaglista WHERE helyi_anyaglista_id = '$id'");
  $totalrows = mysqli_num_rows($checkRecord);

  if($totalrows > 0){
    echo 2;
    exit;
  }else {
    $copy = $conn->prepare("INSERT INTO helyi_anyaglista (helyi_anyaglista_sapszam)
                                  SELECT sap_anyaglista_id
                                  FROM sap_anyaglista
                                  WHERE sap_anyaglista_id = '$id'");
    $successfullyCopied1 = $copy->execute();

    if ($successfullyCopied1) {
      require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
      $query = mysqli_query($conn,"SELECT * FROM sap_anyaglista WHERE sap_anyaglista_id='$id'");
      $row = mysqli_fetch_array($query);
      $szoveg = ("insert to helyi anyaglista= ". $row['sap_anyaglista_megnevezes'] ." pa_dbszam= 1");
      naplozas($szoveg);

      echo 1;
      exit;
    }else {
      echo 3;
      exit;
    }
  }
}else{
  echo 0;
  exit;
}
echo 0;
exit;
