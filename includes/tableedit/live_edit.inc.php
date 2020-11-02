<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
$input = filter_input_array(INPUT_POST);
if ($input['action'] == 'edit') {
  $update_field='';
  if(isset($input['pa_dbszam'])) {
  $update_field.= "pa_dbszam='".$input['pa_dbszam']."'";
  }
  if($update_field && $input['alkatresz_id']) {
    $sql_query = "UPDATE pa_kapcsolat SET $update_field WHERE alkatresz_id='" . $input['alkatresz_id'] . "'";
    mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));

    require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
    $query = mysqli_query($conn,"SELECT * FROM helyi_anyaglista WHERE helyi_anyaglista_id=".$input['alkatresz_id']);
    $row = mysqli_fetch_array($query);
    $update_field = str_replace('"',"",$update_field);
    $update_field = str_replace("'","",$update_field);
    $szoveg = ("update pa_kapcsolat  ".$row['helyi_anyaglista_megnevezes']." ". $update_field ." ");
    naplozas($szoveg);
  }
}
?>
