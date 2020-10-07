<?php

function printanyaglista(){
  require 'dbh.inc.php';
  global $pdf;
  $pid = $_SESSION['projektId'];
  $cellaszelesseg=array(5,89,25,10,20,15,25);
  $cellamagassag=5;

  $pdf->Cell(0 ,5,'1.Anyagköltség:',0,1,'L');

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
