<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
$input = filter_input_array(INPUT_POST);
if ($input['action'] == 'edit') {
  $update_field='';
  if(isset($input['MunkaFajta'])) {
  $update_field.= "munkadij_fajta='".$input['MunkaFajta']."'";
  } else if(isset($input['Oraber'])) {
  $update_field.= "munkadij_oraber='".$input['Oraber']."'";
  }
  if($update_field && $input['Id']) {
    $sql_query = "UPDATE munkadij SET $update_field WHERE munkadij_id='" . $input['Id'] . "'";
    mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));

    require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
    $update_field = str_replace('"',"",$update_field);
    $update_field = str_replace("'","",$update_field);
    $szoveg = ("update munkadij  ". $update_field ." ");
    naplozas($szoveg);
  }
}
?>
