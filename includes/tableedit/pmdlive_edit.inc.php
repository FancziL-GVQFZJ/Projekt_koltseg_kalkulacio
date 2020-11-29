<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
$input = filter_input_array(INPUT_POST);

//csak a megváltoztatott adat kerül frissítésre a projekmunkadíj táblában
if ($input['action'] == 'edit') {
  $update_field='';
  if(isset($input['MunkaFajta'])) {
  $update_field.= "projektmunkadij_munkafajta='".$input['MunkaFajta']."'";
  } else if(isset($input['Oraber'])) {
  $update_field.= "projektmunkadij_oraber='".$input['Oraber']."'";
  }
  if($update_field && $input['Id']) {
    $sql_query = "UPDATE projektmunkadij SET $update_field WHERE projektmunkadij_id='" . $input['Id'] . "'";
    mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));

    require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
    $update_field = str_replace('"',"",$update_field);
    $update_field = str_replace("'","",$update_field);
    $szoveg = ("update munkadij  ". $update_field ." ");
    naplozas($szoveg);
  }
}
?>
