<?php
include 'dbh.inc.php';
session_start();

$id = 0;
if(isset($_POST['id'])){
   $id = mysqli_real_escape_string($conn,$_POST['id']);
}

if($id > 0){

  $checkRecord = mysqli_query($conn,"SELECT * FROM pa_kapcsolat WHERE alkatresz_id=".$id);
  $totalrows = mysqli_num_rows($checkRecord);

  if($totalrows > 0){

    $stmt = $conn->prepare("DELETE FROM pa_kapcsolat WHERE alkatresz_id = ?");
    $stmt->bind_param("i", $id);

    $successfullyCopied = $stmt->execute();

    require_once 'naplo.inc.php';
    $szoveg = ("delete pa_kapcsolat alkatresz_id= ". $id ." ");
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
