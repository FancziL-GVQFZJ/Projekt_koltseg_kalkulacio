<?php
//call the FPDF library
require ('../fpdf182/fpdf.php');
require ('../fpdf182/writehtml.php');
//require ('../fpdf182/html_table.php');
//require ('../fpdf182/TextNormalizerFPDF.php');

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
require 'dbh.inc.php';

$pid = $_SESSION['projektId'];

//create pdf object
$pdf = new PDF('P', 'mm', 'A4');
//add new page
$pdf->AddPage();

//set font to arial, bold, 14pt
$pdf->SetFont('Arial','B',14);

//Cell(width , height , text , border , end line , [align] )
//$pdf->Cell(70 ,5,'',0,0);
$pdf->Cell(0,5,'KALKULÁCIÓS ADATLAP' ,0,1,'C');
//$pdf->Cell(89 ,5,'',0,1);//end of line

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,10,'',0,1);//end of line

//set font to arial, regular, 12pt
$pdf->SetFont('Arial','',12);

$pdf->Cell(0 ,5,'Tárgy:',0,1,'L');//end of line

$pdf->Cell(0 ,5,'Nyersvas keverő átépítés műszerszerelési és folyír. Munkái ',0,1,'C');//end of line

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,10,'',0,1);//end of line

$pdf->Cell(0 ,5,'Vállalkozó: IT Ig. Műszerszerelő és Mérlegkarbantartó üzem:',0,1,'L');//end of line

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,5,'',0,1);//end of line

$pdf->Cell(0 ,5,'4.Műszaki tartalom:',0,1,'L');
//$pdf->Cell(59 ,5,'',0,1);//end of line

$pdf->SetFont('Arial','',10);

$result = mysqli_query($conn,"SELECT * FROM muszakitartalom where projekt_id='$pid'");
$row = mysqli_fetch_array($result);
$str=$row['tartalom'];


$pdf->WriteHTML( $str);


//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,10,'',0,1);//end of line

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
