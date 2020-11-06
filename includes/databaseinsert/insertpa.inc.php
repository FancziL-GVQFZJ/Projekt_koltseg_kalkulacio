<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
session_start();

$id = 0;
$pid = $_SESSION['projektId'];
if(isset($_POST['id'])){
   $id = mysqli_real_escape_string($conn,$_POST['id']);
}

if($id > 0){
  $checkRecord = mysqli_query($conn,"SELECT * FROM pa_kapcsolat WHERE alkatresz_id = '$id' AND projekt_id = '$pid'");
  $totalrows = mysqli_num_rows($checkRecord);

  if($totalrows > 0){
    echo 2;
    exit;
  }else {

    $checkRecord2 = mysqli_query($conn,"SELECT * FROM helyi_anyaglista WHERE helyi_anyaglista_id = '$id'");
    $totalrows2 = mysqli_num_rows($checkRecord2);

    if ($totalrows2 < 1) {
      $copy = $conn->prepare("INSERT INTO helyi_anyaglista (helyi_anyaglista_id, helyi_anyaglista_megnevezes,
                            helyi_anyaglista_sapszam, helyi_anyaglista_mertekegyseg, helyi_anyaglista_egysegar)
                                    SELECT sap_anyaglista_id, sap_anyaglista_megnevezes, sap_anyaglista_sapszam,
                                    sap_anyaglista_mertekegyseg, sap_anyaglista_egysegar
                                    FROM sap_anyaglista
                                    WHERE sap_anyaglista_id = '$id'");
      $successfullyCopied1 = $copy->execute();
    }

    $stmt = $conn->prepare("INSERT INTO pa_kapcsolat (projekt_id, alkatresz_id, pa_dbszam)
                                              VALUES ('$pid', '$id', '1')");
    $successfullyCopied2 = $stmt->execute();

    require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
    $query = mysqli_query($conn,"SELECT * FROM helyi_anyaglista WHERE helyi_anyaglista_id=".$id);
    $row = mysqli_fetch_array($query);
    $szoveg = ("insert to anyaglista= ". $row['helyi_anyaglista_megnevezes'] ." pa_dbszam= 1");
    naplozas($szoveg);

    if ($successfullyCopied2) {
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
