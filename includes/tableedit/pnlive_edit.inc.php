<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
$input = filter_input_array(INPUT_POST);
if ($input['action'] == 'edit') {
  $update_field='';
  if(isset($input['projekt_nev'])) {
  $update_field.= "projekt_nev='".$input['projekt_nev']."'";
  }
  if($update_field && $input['projekt_id']) {
    $sql_query = "UPDATE projekt SET $update_field WHERE projekt_id='" . $input['projekt_id'] . "'";
    mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));

    require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
    $update_field = str_replace('"',"",$update_field);
    $update_field = str_replace("'","",$update_field);
    $szoveg = ("update projekt  ". $update_field ." ");
    naplozas($szoveg);
  }
}
?>
