<?php
include 'dbh.inc.php';
session_start();

$id = 0;
if(isset($_POST['id'])){
   $id = mysqli_real_escape_string($conn,$_POST['id']);
}
//ha törlöm az egyik alsót mi legyen a gyerekeivel
if($id > 0){

  $checkRecord = mysqli_query($conn,"SELECT * FROM munkafajta WHERE Id=".$id);
  $totalrows = mysqli_num_rows($checkRecord);

  $row=mysqli_fetch_array($checkRecord);
  $megn=$row['Megnevezes'];

  if($totalrows > 0){

    $stmt = $conn->prepare("DELETE FROM munkafajta WHERE Id = ?");
    $stmt->bind_param("i", $id);
    $successfullyCopied = $stmt->execute();

    $stmt2 = $conn->prepare("DELETE FROM munkafajta WHERE parent_id = ?");
    $stmt2->bind_param("i", $id);

    $successfullyCopied2 = $stmt2->execute();

    require_once 'naplo.inc.php';
    $szoveg = ("delete munkafajta ". $megn ."");
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
