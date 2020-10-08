<?php
require '../includes/dbh.inc.php';
$input = filter_input_array(INPUT_POST);
if ($input['action'] == 'edit') {
  $update_field='';
  /*if(isset($input['Megnevezes'])) {
  $update_field.= "Megnevezes='".$input['Megnevezes']."'";
  } else if(isset($input['SAPSzam'])) {
  $update_field.= "SAPSzam='".$input['SAPSzam']."'";
  } else */
  if(isset($input['Megnevezes'])) {
  // $megnevezes=$input['Megnevezes'];
  // $teszt=str_replace("&nbsp;","",$megnevezes);
  // echo "<script>
  //   console.log($teszt);
  //   console.log($megnevezes);
  // </script>";


  $update_field.= "Megnevezes='".$input['Megnevezes']."'";
  }
  else if(isset($input['ME'])) {
  $update_field.= "ME='".$input['ME']."'";
  }
  else if(isset($input['Mennyiseg'])) {
  $update_field.= "Mennyiseg='".$input['Mennyiseg']."'";
  }
  if($update_field && $input['Id']) {
    $sql_query = "UPDATE munkafajta SET $update_field WHERE Id='" . $input['Id'] . "'";
    mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));

    require_once 'naplo.inc.php';
    $szoveg = ("update munkafajta  ". $update_field ." ");
    naplozas($szoveg);
  }
}
?>
