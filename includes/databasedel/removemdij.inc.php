<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
session_start();

$id = 0;
if(isset($_POST['id'])){
   $id = mysqli_real_escape_string($conn,$_POST['id']);
}

// a munkadíjakat törli

if($id > 0){
  $checkRecord = mysqli_query($conn,"SELECT * FROM munkadij WHERE munkadij_id=".$id);
  $totalrows = mysqli_num_rows($checkRecord);

  $row=mysqli_fetch_array($checkRecord);
  $megn=$row['Megnevezes'];

  if($totalrows > 0){

    $stmt = $conn->prepare("DELETE FROM munkadij WHERE munkadij_id = ?");
    $stmt->bind_param("i", $id);
    $successfullyCopied = $stmt->execute();

    require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
    $szoveg = ("delete munkadij ". $megn ."");
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
