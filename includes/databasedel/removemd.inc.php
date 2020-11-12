<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
session_start();

$id = 0;
if(isset($_POST['id'])){
   $id = mysqli_real_escape_string($conn,$_POST['id']);
}

if($id > 0){

  $checkRecord = mysqli_query($conn,"SELECT * FROM munkadijkoltseg WHERE munkadiijkoltseg_id=".$id);
  $totalrows = mysqli_num_rows($checkRecord);

  $row=mysqli_fetch_array($checkRecord);
  $megn=$row['munkadijkoltseg_megnevezes'];

  if($totalrows > 0){

    $stmt = $conn->prepare("DELETE FROM munkadijkoltseg WHERE munkadiijkoltseg_id = ?");
    $stmt->bind_param("i", $id);
    $successfullyCopied = $stmt->execute();

    $stmt2 = $conn->prepare("DELETE FROM munkadijkoltseg WHERE parent_id = ?");
    $stmt2->bind_param("i", $id);

    $successfullyCopied2 = $stmt2->execute();

    require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
    $szoveg = ("delete munkadijkoltseg ". $megn ."");
    naplozas($szoveg);

    if ($successfullyCopied && $successfullyCopied2) {
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
