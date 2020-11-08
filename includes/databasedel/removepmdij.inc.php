<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
session_start();

$id = 0;
if(isset($_POST['id'])){
   $id = mysqli_real_escape_string($conn,$_POST['id']);
}

if($id > 0){
  $pid = $_SESSION['projektId'];
  $checkRecord = mysqli_query($conn,"SELECT * FROM projektmunkadij WHERE projektmunkadij_id='$id'");
  $totalrows = mysqli_num_rows($checkRecord);

  $row=mysqli_fetch_array($checkRecord);
  $megn=$row['projektmunkadij_munkafajta'];

  if($totalrows > 0){

    $stmt = $conn->prepare("DELETE FROM projektmunkadij WHERE projektmunkadij_id = ?");
    $stmt->bind_param("i", $id);
    $successfullyCopied = $stmt->execute();

    require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
    $szoveg = ("delete projektmunkadij ". $megn ."");
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
