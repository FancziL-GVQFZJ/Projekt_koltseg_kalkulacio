<?php
require 'dbh.inc.php';

session_start();

$id = 0;
$pid = $_SESSION['projektId'];
if(isset($_POST['id'])){
   $id = mysqli_real_escape_string($conn,$_POST['id']);
}
//$id=34;
if($id > 0){

  $checkRecord = mysqli_query($conn,"SELECT * FROM pa_kapcsolat WHERE alkatresz_id = '$id' AND projekt_id = '$pid'");
  $totalrows = mysqli_num_rows($checkRecord);


  if($totalrows > 0){
    echo 2;
    exit;
  }else {

    $checkRecord2 = mysqli_query($conn,"SELECT * FROM belsoalkatresz WHERE Id = '$id'");
    $totalrows2 = mysqli_num_rows($checkRecord2);

    if ($totalrows2 = 0) {
      $copy = $conn->prepare("INSERT INTO belsoalkatresz (Id, Megnevezes, SAPSzam, ME, Egysegar)
                                    SELECT id, Megnevezes, SAPSzam, ME, Egysegar
                                    FROM alkatresz
                                    WHERE id = '$id'");
      $successfullyCopied1 = $copy->execute();
    }

    $stmt = $conn->prepare("INSERT INTO pa_kapcsolat (projekt_id, alkatresz_id, DBszam)
                                              VALUES ('$pid', '$id', '1')");

    $successfullyCopied2 = $stmt->execute();

    require_once 'naplo.inc.php';
    $query = mysqli_query($conn,"SELECT * FROM belsoalkatresz WHERE Id=".$id);
    $row = mysqli_fetch_array($query);
    $szoveg = ("insert pa_kapcsolat alkatresz= ". $row['Megnevezes'] ." DBszam= 1");
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
