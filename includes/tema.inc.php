<?php
require 'kapcsolat.inc.php';
session_start();

//a témát változtatja

$id = 0;
if(isset($_POST['id'])){
   $id = mysqli_real_escape_string($conn,$_POST['id']);
}

if($id > 0){
  if ($id == 1) {
    $_SESSION['tema'] = 1;
    echo 1;
    exit;
  }elseif ($id == 2) {
    $_SESSION['tema'] = 2;
    echo 1;
    exit;
  }else {
    echo 0;
    exit;
  }

}else{
  echo 0;
  exit;
}
echo 0;
exit;
