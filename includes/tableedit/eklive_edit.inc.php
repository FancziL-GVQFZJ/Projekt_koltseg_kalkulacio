<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
$input = filter_input_array(INPUT_POST);

//csak a megváltoztatott adat kerül frissítésre az egyébköltség táblában
if ($input['action'] == 'edit') {
  $update_field='';
  if(isset($input['Megnevezes'])) {
  $update_field.= "egyebkoltseg_megnevezes='".$input['Megnevezes']."'";
  } else if(isset($input['ME'])) {
  $update_field.= "egyebkoltseg_mertekegyseg='".$input['ME']."'";
  } else if(isset($input['Mennyiseg'])) {
  $update_field.= "egyebkoltseg_mennyiseg='".$input['Mennyiseg']."'";
  }
  if($update_field && $input['Id']) {
    $sql_query = "UPDATE egyebkoltseg SET $update_field WHERE egyebkoltseg_id='" . $input['Id'] . "'";
    mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));

    require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
    $update_field = str_replace('"',"",$update_field);
    $update_field = str_replace("'","",$update_field);
    $szoveg = ("update egyebkoltseg  ". $update_field ." ");
    naplozas($szoveg);
  }
}
?>
