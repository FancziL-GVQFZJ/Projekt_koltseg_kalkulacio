<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
session_start();

$id = 0;
if(isset($_POST['id'])){
   $id = mysqli_real_escape_string($conn,$_POST['id']);
}

// a listázott anyagokból töröl

if($id > 0){
  $checkRecord = mysqli_query($conn,"SELECT * FROM pa_kapcsolat WHERE alkatresz_id=".$id);
  $totalrows = mysqli_num_rows($checkRecord);

  if($totalrows > 0){

    $stmt = $conn->prepare("DELETE FROM pa_kapcsolat WHERE alkatresz_id = ?");
    $stmt->bind_param("i", $id);
    $successfullyCopied = $stmt->execute();

    require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
    $query = mysqli_query($conn,"SELECT * FROM sap_anyaglista WHERE sap_anyaglista_id=".$id);
    $row = mysqli_fetch_array($query);
    $szoveg = ("delete from anyaglista  ". $row['helyi_anyaglista_megnevezes'] ." ");
    naplozas($szoveg);

    if ($successfullyCopied) {
      echo 1;
      exit;
    }
  }else{
    echo 0;
    exit;
  }
}
echo 2;
exit;
