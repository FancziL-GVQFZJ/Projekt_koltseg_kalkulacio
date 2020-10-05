<?php
//call the FPDF library
require ('../fpdf182/fpdf.php');
//require ('../fpdf182/html_table.php');
//require ('../fpdf182/TextNormalizerFPDF.php');

//A4 width : 219mm
//default margin : 10mm each side
//writable horizontal : 219-(10*2)=189mm


//require('../fpdf182/htmlparser.inc.php');

class PDF extends FPDF
{
  function Cell( $w, $h = 0, $t = '', $b = 0, $l = 0, $a = '', $f = false, $y = '' ) {

    parent::Cell( $w, $h, iconv( 'UTF-8', 'ISO-8859-2', $t ), $b, $l, $a, $f, $y );
  }
}

session_start();
require 'dbh.inc.php';

$width_cell=array(5,89,25,10,20,15,25);

$pid = $_SESSION['projektId'];

//create pdf object
$pdf = new PDF('P', 'mm', 'A4');
//add new page
$pdf->AddPage();

//set font to arial, bold, 14pt
$pdf->SetFont('Arial','B',14);

//Cell(width , height , text , border , end line , [align] )
//$pdf->Cell(70 ,5,'',0,0);
$pdf->Cell(0,5,'KALKULÁCIÓS ADATLAP',0,1,'C');
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

$pdf->Cell(0 ,5,'1.Anyagköltség:',0,1,'L');
//$pdf->Cell(59 ,5,'',0,1);//end of line

$pdf->SetFont('Arial','',10);
$rows=("SELECT Megnevezes, SAPSzam, ME, Egysegar, DBszam FROM alkatresz
      INNER JOIN pa_kapcsolat
        ON alkatresz.id = pa_kapcsolat.alkatresz_id
      INNER JOIN projekt
        ON pa_kapcsolat.projekt_id = projekt.idProjekt
        WHERE projekt.idProjekt = $pid
        ORDER BY alkatresz.id");
//$pdf->AddPage();



// Header starts ///
//First header column //
$pdf->Cell($width_cell[0],10,'',1,0,'C');
//Second header column//
$pdf->Cell($width_cell[1],10,'Megnevezés',1,0,'C');
//Third header column//
$pdf->Cell($width_cell[2],10,'SAPSzam',1,0,'C');
//Fourth header column//
$pdf->Cell($width_cell[3],10,'ME',1,0,'C');
//Third header column//
$pdf->Cell($width_cell[4],10,'Egységár',1,0,'C');
$pdf->Cell($width_cell[5],10,'DBszam',1,0,'C');
$pdf->Cell($width_cell[6],10,'Ár összesen',1,1,'C');
//// header ends ///////
$fill=false;

/// each record is one row  ///
$i=0;
foreach ($conn->query($rows) as $row) {
$i++;
$pdf->Cell($width_cell[0],10,$i,1,0,'C',$fill);
$pdf->Cell($width_cell[1],10,$row['Megnevezes'],1,0,'L',$fill);
$pdf->Cell($width_cell[2],10,$row['SAPSzam'],1,0,'C',$fill);
$pdf->Cell($width_cell[3],10,$row['ME'],1,0,'C',$fill);
$pdf->Cell($width_cell[4],10,$row['Egysegar'].' Ft',1,0,'C',$fill);
$pdf->Cell($width_cell[5],10,$row['DBszam'],1,0,'C',$fill);
$sorar=$row['Egysegar']*$row['DBszam'];
$pdf->Cell($width_cell[6],10,$sorar.' Ft',1,1,'C',$fill);
$osszegar=$osszegar+$sorar;
//$fill = !$fill;
}
/// end of records ///
$pdf->Cell(164,10,'Összesen',1,0,'C',$fill);
$pdf->Cell(25,10,$osszegar.' Ft',1,1,'C',$fill);


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

<!-- <script type="text/javascript">
  function printContent(el) {
  var restorepage = document.body.innerHTML;
  var printcontent = document.getElementById(el).innerHTML;
  document.body.innerHTML = printcontent;
  window.print();
  document.body.innerHTML = restorepage;
  }
</script> -->
