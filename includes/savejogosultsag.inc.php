<?php
require 'dbh.inc.php';
session_start();
$id=$_POST["id"];
$pid=$_POST["pid"];
$jog=$_POST["jog"];

if ($jog>0) {
  $checkRecord = mysqli_query($conn,"SELECT * FROM jogosultsag WHERE user_id = '$id' AND projekt_id = '$pid'");
  $totalrows = mysqli_num_rows($checkRecord);

  if($totalrows > 0){
    if ($jog == 1) {
      $stmt = $conn->prepare("UPDATE jogosultsag SET iras='1',  olvasas='1' WHERE user_id = '$id' AND projekt_id = '$pid'");
    }
    else {
      $stmt = $conn->prepare("UPDATE jogosultsag SET iras='0',  olvasas='1' WHERE user_id = '$id' AND projekt_id = '$pid'");
    }
    $successfullyCopied = $stmt->execute();

    if ($successfullyCopied) {
      echo 1;
      exit;
    }

  }
  else {
    if ($jog == 1) {
      $stmt = $conn->prepare("INSERT INTO jogosultsag (user_id, projekt_id, iras, olvasas)
                                                VALUES ('$id','$pid', '1', '1')");
    }
    else {
      $stmt = $conn->prepare("INSERT INTO jogosultsag (user_id, projekt_id, iras, olvasas)
                                                VALUES ('$id','$pid', '0', '1')");
    }
    $successfullyCopied = $stmt->execute();

    if ($successfullyCopied) {
      echo 1;
      exit;
    }
  }
  echo 0;
  exit;
}
else {
  $stmt = $conn->prepare("DELETE FROM jogosultsag WHERE user_id = '$id' AND projekt_id = '$pid'");

  $successfullyCopied = $stmt->execute();
  if ($successfullyCopied) {
    echo 1;
    exit;
  }
  echo 0;
  exit;
}




/*if(!empty($_POST["jogosultsagok"])){
  $output = $_POST["jogosultsagok"];
  echo $output; /* PRINT THE OUTPUT YOU WANT, IT WILL BE RETURNED TO THE ORIGINAL PAGE */
  /*echo 1;
  exit;
}*/


/*$id = 0;
$pid = $_SESSION['projektId'];
if(isset($_POST['id'])){
   $id = mysqli_real_escape_string($conn,$_POST['id']);
}

if($id > 0){
  //$checkRecord = mysqli_query($conn,"SELECT * FROM kosar WHERE Id=".$id);
  $checkRecord = mysqli_query($conn,"SELECT * FROM pa_kapcsolat WHERE alkatresz_id = '$id' AND projekt_id = '$pid'");
  $totalrows = mysqli_num_rows($checkRecord);


  if($totalrows > 0){
    echo 2;
    exit;
  }else {


    $mysqli = new mysqli($servername,$dBUsername,$dBPassword,$dBname) or exit("Error connecting to database");


    $stmt = $mysqli->prepare("INSERT INTO pa_kapcsolat (projekt_id, alkatresz_id, DBszam)
                                              VALUES ('$pid', '$id', '1')");


    $successfullyCopied = $stmt->execute();

    if ($successfullyCopied) {
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
exit;*/
?>
