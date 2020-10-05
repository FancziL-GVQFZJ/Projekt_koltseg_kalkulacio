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

$width_cell=array(84,30,20,20,35);

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

$pdf->Cell(0 ,5,'3.Egyéb költség:',0,1,'L');
//$pdf->Cell(59 ,5,'',0,1);//end of line

$pdf->SetFont('Arial','',10);
$rows=("SELECT * FROM egyebkoltseg WHERE parent_id IS NULL AND project_id = '$pid'");
//$pdf->AddPage();


// Header starts ///
//Second header column//

$pdf->Cell($width_cell[0],10,'Megnevezés',1,0,'C');
//Fourth header column//
$pdf->Cell($width_cell[1],10,'ME',1,0,'C');
//Third header column//
$pdf->Cell($width_cell[2],10,'Mennyiség',1,0,'C');
$pdf->Cell($width_cell[3],10,'Órabér',1,0,'C');
$pdf->Cell($width_cell[4],10,'Összeg',1,1,'C');

$fill=false;
foreach ($conn->query($rows) as $row){
  $pdf->Cell($width_cell[0],10,$row['Megnevezes'],1,0,'L',$fill);
  $pdf->Cell($width_cell[1],10,$row['ME'],1,0,'L',$fill);
  $pdf->Cell($width_cell[2],10,$row['Mennyiseg'],1,0,'C',$fill);
  $pdf->Cell($width_cell[3],10,'',1,0,'C',$fill);
  $pdf->Cell($width_cell[4],10,'',1,1,'C',$fill);
  $arresz = show_children($row['Id']);
  $teljesar=$teljesar+$arresz;

}


$pdf->Cell(154,10,'Összesen:',1,0,'R',$fill);
$pdf->Cell(35,10,$teljesar.' Ft',1,1,'C',$fill);


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




function show_children($parentID, $depth=1){
   require 'dbh.inc.php';
   global $pdf;
   global $width_cell;
//  require ('../fpdf182/fpdf.php');
  $children = mysqli_query($conn,"SELECT * FROM egyebkoltseg WHERE parent_id=$parentID");

  while ($row = mysqli_fetch_array($children)){
    $sorid=$row['Id'];

    if ($row['Mennyiseg']==NULL) {
      $pdf->Cell(189,10,str_repeat("&nbsp;", $depth * 5).$row['Megnevezes'],1,1,'L',$fill);

      $arresz = show_children($row['Id'], $depth+1);
      $osszegar=$osszegar+$arresz;
    }
    else {
      $munkadij = mysqli_query($conn,"SELECT Oraber FROM munkadij
                    INNER JOIN egyebkoltseg
                    ON munkadij.Id = egyebkoltseg.munkadij_id
                    WHERE egyebkoltseg.Id ='$sorid'");
      $row2=mysqli_fetch_array($munkadij);
      $pdf->Cell($width_cell[0],10,str_repeat(" ", $depth * 5).$row['Megnevezes'],1,0,'L',$fill);
      $pdf->Cell($width_cell[1],10,$row['ME'],1,0,'C',$fill);
      $pdf->Cell($width_cell[2],10,$row['Mennyiseg'],1,0,'C',$fill);
      $pdf->Cell($width_cell[3],10,$row2['Oraber'],1,0,'C',$fill);
      if ($row['Mennyiseg']!=NULL) {
        $sorar=$row['Mennyiseg']*$row2['Oraber'];
        $pdf->Cell($width_cell[4],10,$sorar.' Ft',1,1,'C',$fill);
        $osszegar=$osszegar+$sorar;
      }
      else {
        $pdf->Cell($width_cell[4],10,'',1,1,'C',$fill);
      }
    }
    }
    $pdf->Cell(154,10,'Összegzett ár:',1,0,'R',$fill);
    $pdf->Cell(35,10,$osszegar. 'Ft',1,1,'C',$fill);

    return $osszegar;
}
?>
