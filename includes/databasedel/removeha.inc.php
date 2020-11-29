<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
session_start();

$id = 0;
if(isset($_POST['id'])){
   $id = mysqli_real_escape_string($conn,$_POST['id']);
}

// a helyi anyaglistából töröl

if($id > 0){
  $checkRecord = mysqli_query($conn,"SELECT * FROM helyi_anyaglista WHERE helyi_anyaglista_sapszam=".$id);
  $totalrows = mysqli_num_rows($checkRecord);

  if($totalrows > 0){

    $stmt = $conn->prepare("DELETE FROM helyi_anyaglista WHERE helyi_anyaglista_sapszam = ?");
    $stmt->bind_param("i", $id);
    $successfullyCopied = $stmt->execute();

    require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
    $row = mysqli_fetch_array($checkRecord);
    $szoveg = ("delete alkatrészlista  ". $row['helyi_anyaglista_megnevezes'] ." ");
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
