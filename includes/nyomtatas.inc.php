<?php
require 'dbh.inc.php';
//require ('../fpdf182/fpdf.php');
require ('../fpdf182/writehtml.php');

include 'anyaglistanyomtatas.inc.php';
include 'munkadijkoltsegnyomtatas.inc.php';
include 'egyebkoltsegnyomtatas.inc.php';
include 'muszakitartalomnyomtatas.inc.php';

//A4 width : 219mm
//default margin : 10mm each side
//writable horizontal : 219-(10*2)=189mm

class PDF extends PDF_HTML
{
  function Cell( $w, $h = 0, $t = '', $b = 0, $l = 0, $a = '', $f = false, $y = '' ) {

    parent::Cell( $w, $h, iconv( 'UTF-8', 'ISO-8859-2', $t ), $b, $l, $a, $f, $y );
  }
}

session_start();


$focim = 'Nyersvas keverő átépítés műszerszerelési és folyír. Munkái ';

//create pdf object
$pdf = new PDF('P', 'mm', 'A4');
//add new page
$pdf->AddPage();

//set font to arial, bold, 14pt
$pdf->SetFont('Arial','B',14);

//Cell(width , height , text , border , end line , [align] )
$pdf->Cell(0,5,'KALKULÁCIÓS ADATLAP',0,1,'C');

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,5,'',0,1);//end of line

//set font to arial, regular, 12pt
$pdf->SetFont('Arial','',10);

$pdf->Cell(0 ,5,'Tárgy:',0,1,'L');//end of line

$pdf->SetFont('Arial','',12);

$pdf->Cell(0 ,5,$focim,0,1,'C');//end of line

$pdf->SetFont('Arial','',10);

$pdf->Cell(189 ,5,'',0,1);//end of line

$pdf->Cell(0 ,5,'Vállalkozó: IT Ig. Műszerszerelő és Mérlegkarbantartó üzem:',0,1,'L');//end of line

$pdf->SetFont('Arial','',10);

if (isset($_POST['Anyaglista'])) {
  printanyaglista();
  $pdf->Cell(189 ,5,'',0,1);//end of line
}

if (isset($_POST['Munkadíj'])) {
  printmunkadijkoltseg();
  $pdf->Cell(189 ,5,'',0,1);//end of line
}

if (isset($_POST['Egyéb'])) {
  printegyebkoltseg();
  $pdf->Cell(189 ,5,'',0,1);//end of line
}
$pdf->SetFont('Arial','',10);

if (isset($_POST['Műszaki'])) {
  printmuszakitartalom();
}
$pdf->Cell(189 ,5,'',0,1);//end of line
$ma = date("Y.m.d");
$pdf->Cell(0 ,5,$ma,0,1,'L');//end of line

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,10,'',0,1);//end of line

$pdf->Cell(9 ,5,'',0,0);
$pdf->Cell(110 ,5,'......................................................................................',0,0);
$pdf->Cell(70 ,5,'........................................................',0,1);

$pdf->Cell(39 ,5,'',0,0);
$pdf->Cell(80 ,5,'Üzemvezető',0,0);
$pdf->Cell(20 ,5,'',0,0);
$pdf->Cell(60 ,5,'IT Főmérnök',0,1);

$pdf->Output();

?>
