<?php
//require 'dbh.inc.php';
//require ('../fpdf182/fpdf.php');

//A4 width : 219mm
//default margin : 10mm each side
//writable horizontal : 219-(10*2)=189mm

// class PDF extends FPDF
// {
//   function Cell( $w, $h = 0, $t = '', $b = 0, $l = 0, $a = '', $f = false, $y = '' ) {
//
//     parent::Cell( $w, $h, iconv( 'UTF-8', 'ISO-8859-2', $t ), $b, $l, $a, $f, $y );
//   }
// }

//session_start();


// $focim = 'Nyersvas keverő átépítés műszerszerelési és folyír. Munkái ';
//
// //create pdf object
// $pdf = new PDF('P', 'mm', 'A4');
// //add new page
// $pdf->AddPage();
//
// //set font to arial, bold, 14pt
// $pdf->SetFont('Arial','B',14);
//
// //Cell(width , height , text , border , end line , [align] )
// $pdf->Cell(0,5,'KALKULÁCIÓS ADATLAP',0,1,'C');
//
// //make a dummy empty cell as a vertical spacer
// $pdf->Cell(189 ,10,'',0,1);//end of line
//
// //set font to arial, regular, 12pt
// $pdf->SetFont('Arial','',12);
//
// $pdf->Cell(0 ,5,'Tárgy:',0,1,'L');//end of line
//
// $pdf->Cell(0 ,5,$focim,0,1,'C');//end of line
//
// //make a dummy empty cell as a vertical spacer
// $pdf->Cell(189 ,10,'',0,1);//end of line
//
// $pdf->Cell(0 ,5,'Vállalkozó: IT Ig. Műszerszerelő és Mérlegkarbantartó üzem:',0,1,'L');//end of line
//
// //make a dummy empty cell as a vertical spacer
// $pdf->Cell(189 ,5,'',0,1);//end of line
//
// printanyaglista();
//
// //make a dummy empty cell as a vertical spacer
// $pdf->Cell(189 ,10,'',0,1);//end of line
//
// $ma = date("Y.m.d");
// $pdf->Cell(0 ,5,$ma,0,1,'L');//end of line
//
// //make a dummy empty cell as a vertical spacer
// $pdf->Cell(189 ,10,'',0,1);//end of line
//
// $pdf->Cell(9 ,5,'',0,0);
// $pdf->Cell(110 ,5,'......................................................................................',0,0);
// $pdf->Cell(70 ,5,'........................................................',0,1);
//
// $pdf->Cell(39 ,5,'',0,0);
// $pdf->Cell(80 ,5,'Üzemvezető',0,0);
// $pdf->Cell(20 ,5,'',0,0);
// $pdf->Cell(60 ,5,'IT Főmérnök',0,1);
//
// $pdf->Output();


function printanyaglista(){
  require 'dbh.inc.php';
  global $pdf;
  $pid = $_SESSION['projektId'];
  $cellaszelesseg=array(5,89,25,10,20,15,25);

  $cellamagassag=5;

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
  // Header starts ///
  $pdf->Cell($cellaszelesseg[0],$cellamagassag,'',1,0,'C');
  $pdf->Cell($cellaszelesseg[1],$cellamagassag,'Megnevezés',1,0,'C');
  $pdf->Cell($cellaszelesseg[2],$cellamagassag,'SAPSzam',1,0,'C');
  $pdf->Cell($cellaszelesseg[3],$cellamagassag,'ME',1,0,'C');
  $pdf->Cell($cellaszelesseg[4],$cellamagassag,'Egységár',1,0,'C');
  $pdf->Cell($cellaszelesseg[5],$cellamagassag,'DBszam',1,0,'C');
  $pdf->Cell($cellaszelesseg[6],$cellamagassag,'Ár összesen',1,1,'C');

  $fill=false;

  /// each record is one row  ///
  $i=0;
  foreach ($conn->query($rows) as $row) {
  $i++;
  $pdf->Cell($cellaszelesseg[0],$cellamagassag,$i,1,0,'C',$fill);
  $pdf->Cell($cellaszelesseg[1],$cellamagassag,$row['Megnevezes'],1,0,'L',$fill);
  $pdf->Cell($cellaszelesseg[2],$cellamagassag,$row['SAPSzam'],1,0,'C',$fill);
  $pdf->Cell($cellaszelesseg[3],$cellamagassag,$row['ME'],1,0,'C',$fill);
  $pdf->Cell($cellaszelesseg[4],$cellamagassag,$row['Egysegar'].' Ft',1,0,'C',$fill);
  $pdf->Cell($cellaszelesseg[5],$cellamagassag,$row['DBszam'],1,0,'C',$fill);
  $sorar=$row['Egysegar']*$row['DBszam'];
  $pdf->Cell($cellaszelesseg[6],$cellamagassag,$sorar.' Ft',1,1,'C',$fill);
  $osszegar=$osszegar+$sorar;
  }
  /// end of records ///
  $pdf->Cell(164,$cellamagassag,'Összesen',1,0,'C',$fill);
  $pdf->Cell(25,$cellamagassag,$osszegar.' Ft',1,1,'C',$fill);
}

?>
