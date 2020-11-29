<?php
require 'kapcsolat.inc.php';
session_start();

$id = 0;
$pid = $_SESSION['projektId'];
if(isset($_POST['id'])){
   $id = mysqli_real_escape_string($conn,$_POST['id']);
}

// a munkadíjköltség hozzáadásakor kiválasztott csoport alapján kér le adatot

if($id > 0){
  $query1 = mysqli_query($conn,"SELECT * FROM munkadijkoltseg
                WHERE parent_id ='$id' AND projekt_id='$pid' AND munkadijkoltseg_mennyiseg IS NOT NULL");
  $resurt1 = mysqli_num_rows($query1);

  $query2 = mysqli_query($conn,"SELECT * FROM munkadijkoltseg
                WHERE munkadijkoltseg_id = '$id' AND parent_id IS NOT NULL AND munkadijkoltseg_mennyiseg	IS NULL");
  $resurt2 = mysqli_num_rows($query2);

  if (($resurt1 > 0)||($resurt2 > 0)) {
    echo 1;
    exit;
  }else {
    echo 0;
    exit;
  }

}elseif ($id == 0) {
  echo 2;
  exit;
}else{
  echo 0;
  exit;
}
echo 0;
exit;
