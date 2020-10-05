<?php
require 'dbh.inc.php';
$input = filter_input_array(INPUT_POST);
if ($input['action'] == 'edit') {
  $update_field='';
  /*if(isset($input['Megnevezes'])) {
  $update_field.= "Megnevezes='".$input['Megnevezes']."'";
  } else if(isset($input['SAPSzam'])) {
  $update_field.= "SAPSzam='".$input['SAPSzam']."'";
  } else if(isset($input['Megnevezes'])) {
  $update_field.= "Megnevezes='".$input['Megnevezes']."'";
  } else*/ if(isset($input['MunkaFajta'])) {
  $update_field.= "MunkaFajta='".$input['MunkaFajta']."'";
  } else if(isset($input['Oraber'])) {
  $update_field.= "Oraber='".$input['Oraber']."'";
  }
  if($update_field && $input['Id']) {
    $sql_query = "UPDATE munkadij SET $update_field WHERE Id='" . $input['Id'] . "'";
    mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));
  }
}
?>
