<?php
function printmuszakitartalom(){
  require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
  global $pdf;
  $pid = $_SESSION['projektId'];
  $pdf->Cell(0 ,5,'4.Műszaki tartalom:',0,1,'L');//end of line

  $result = mysqli_query($conn,"SELECT * FROM muszakitartalom where projekt_id='$pid'");
  $row = mysqli_fetch_array($result);
  $str=$row['muszakitartalom_tartalom'];
  $str = str_replace(['<p>', '</p>'], ['', '<br>'], $str);

  $pdf->WriteHTML($str);
}
?>
