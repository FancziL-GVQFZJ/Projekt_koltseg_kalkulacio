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
  $update_field.= "Megnevezes='".$input['Megnevezes']."'";
  } else if(isset($input['ME'])) {
  $update_field.= "ME='".$input['ME']."'";
  } else if(isset($input['Mennyiseg'])) {
  $update_field.= "Mennyiseg='".$input['Mennyiseg']."'";
  }
  if($update_field && $input['Id']) {
    $sql_query = "UPDATE egyebkoltseg SET $update_field WHERE Id='" . $input['Id'] . "'";
    mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));

    require_once 'naplo.inc.php';
    $update_field = str_replace('"',"",$update_field);
    $update_field = str_replace("'","",$update_field);
    $szoveg = ("update egyebkoltseg  ". $update_field ." ");
    naplozas($szoveg);
  }
}
?>
