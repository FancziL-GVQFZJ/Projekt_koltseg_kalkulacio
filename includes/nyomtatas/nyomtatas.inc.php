<?php
require '../kapcsolat.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/fpdf182/writehtml.php';
include 'anyaglistanyomtatas.inc.php';
include 'anyagkoltsegnyomtatas.inc.php';
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

  // nyomtatványok fejléce
  function Header()
  {
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30,10,'KALKULÁCIÓS ADATLAP',0,0,'C');
    // Line break
    $this->Ln(20);
  }

  // nyomtatványol alja
  function Footer()
  {
      $this->SetY(-20);

      $this->Cell(9 ,5,'',0,0);
      $this->Cell(110 ,5,'......................................................................................',0,0);
      $this->Cell(70 ,5,'........................................................',0,1);

      $this->Cell(39 ,5,'',0,0);
      $this->Cell(80 ,5,'Üzemvezető',0,0);
      $this->Cell(20 ,5,'',0,0);
      $this->Cell(60 ,5,'IT Főmérnök',0,1);

      // Arial italic 8
      //$this->SetFont('Arial','I',8);
      // Page number
      //$this->Cell(0,10,'oldal '.$this->PageNo().'/{nb}',0,0,'C');
  }
}

session_start();
$cellaszelesseg=array(84,30,20,20,35);
$cellamagassag=5;
$mernokmido=0;
$muszereszmido=0;

$focim = $_SESSION['projektNeve'];

//új objektum létrehozása
$pdf = new PDF('P', 'mm', 'A4');
//új oldal hozzáadása
$pdf->AddPage();

//betőméret, stílus arial, regular, 12pt
$pdf->SetFont('Arial','',10);

$pdf->Cell(0 ,5,'Tárgy:',0,1,'L');

$pdf->SetFont('Arial','',12);

$pdf->Cell(0 ,5,$focim,0,1,'C');

$pdf->SetFont('Arial','',10);

$pdf->Cell(189 ,5,'',0,1);

$pdf->Cell(0 ,5,'Vállalkozó: IT Ig. Műszerszerelő és Mérlegkarbantartó üzem:',0,1,'L');//end of line

$pdf->SetFont('Arial','',10);

//a nyomtatási lapon bepipáltakat teszi a nyomtatványra

if (isset($_POST['Anyaglista'])) {
  printanyaglista();
  $pdf->Cell(189 ,5,'',0,1);
}

if (isset($_POST['Anyagkoltseg'])) {
  printanyagkoltseg();
  $pdf->Cell(189 ,5,'',0,1);
}

if (isset($_POST['Munkadíj'])) {
  printmunkadijkoltseg();
  $pdf->Cell(189 ,5,'',0,1);
}

if (isset($_POST['Egyéb'])) {
  printegyebkoltseg();
  $pdf->Cell(189 ,5,'',0,1);
}
$pdf->SetFont('Arial','',10);

if (isset($_POST['Műszaki'])) {
  printmuszakitartalom();
}
$pdf->Cell(189 ,5,'',0,1);

$ma = date("Y.m.d");
$pdf->Cell(0 ,5,$ma,0,1,'L');

//a lap tetejétől ekkor távolságban kezdődik a footer
$pdf->SetY(260);

$pdf->Output();
?>
