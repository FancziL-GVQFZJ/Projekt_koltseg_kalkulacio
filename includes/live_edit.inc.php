<?php
require 'dbh.inc.php';
$input = filter_input_array(INPUT_POST);
if ($input['action'] == 'edit') {
  $update_field='';
  /*if(isset($input['Megnevezes'])) {
  $update_field.= "Megnevezes='".$input['Megnevezes']."'";
  } else if(isset($input['SAPSzam'])) {
  $update_field.= "SAPSzam='".$input['SAPSzam']."'";
  } else if(isset($input['ME'])) {
  $update_field.= "ME='".$input['ME']."'";
  } else if(isset($input['Egysegar'])) {
  $update_field.= "Egysegar='".$input['Egysegar']."'";
  } else*/ if(isset($input['DBszam'])) {
  $update_field.= "DBszam='".$input['DBszam']."'";
  }
  if($update_field && $input['alkatresz_id']) {
    $sql_query = "UPDATE pa_kapcsolat SET $update_field WHERE alkatresz_id='" . $input['alkatresz_id'] . "'";
    mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));

    require_once 'naplo.inc.php';
    $query = mysqli_query($conn,"SELECT * FROM belsoalkatresz WHERE Id=".$input['alkatresz_id']);
    $row = mysqli_fetch_array($query);
    $update_field = str_replace('"',"",$update_field);
    $update_field = str_replace("'","",$update_field);
    $szoveg = ("update pa_kapcsolat  ".$row['Megnevezes']." ". $update_field ." ");
    naplozas($szoveg);
  }
}
?>
