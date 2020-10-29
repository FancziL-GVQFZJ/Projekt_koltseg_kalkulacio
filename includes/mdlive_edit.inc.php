<?php
require '../includes/dbh.inc.php';
$input = filter_input_array(INPUT_POST);
if ($input['action'] == 'edit') {
  $update_field='';
  if(isset($input['Megnevezes'])) {
  $update_field.= "munkadijkoltseg_megnevezes='".$input['Megnevezes']."'";
  }
  else if(isset($input['ME'])) {
  $update_field.= "munkadijkoltseg_mertekegyseg='".$input['ME']."'";
  }
  else if(isset($input['Mennyiseg'])) {
  $update_field.= "munkadijkoltseg_mennyiseg='".$input['Mennyiseg']."'";
  }
  if($update_field && $input['Id']) {
    $sql_query = "UPDATE munkadijkoltseg SET $update_field WHERE munkadiijkoltseg_id='" . $input['Id'] . "'";
    mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));

    require_once 'naplo.inc.php';
    $update_field = str_replace('"',"",$update_field);
    $update_field = str_replace("'","",$update_field);
    $szoveg = ("update munkadijkoltseg:  ". $update_field ." ");
    naplozas($szoveg);
  }
}
?>
