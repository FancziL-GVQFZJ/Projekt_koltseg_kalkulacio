<?php

function printanyaglista(){
  require 'dbh.inc.php';
  global $pdf;
  $pid = $_SESSION['projektId'];
  $cellaszelesseg=array(5,89,25,10,20,15,25);
  $cellamagassag=5;

  $pdf->Cell(0 ,5,'1.Anyagköltség:',0,1,'L');

  $pdf->SetFont('Arial','',10);
  $rows=("SELECT * FROM helyi_anyaglista
        INNER JOIN pa_kapcsolat
          ON helyi_anyaglista.helyi_anyaglista_id = pa_kapcsolat.alkatresz_id
        INNER JOIN projekt
          ON pa_kapcsolat.projekt_id = projekt.projekt_id
          WHERE projekt.projekt_id = '$pid'
          ORDER BY helyi_anyaglista.helyi_anyaglista_id");
  // Header starts ///
  $pdf->Cell($cellaszelesseg[0],$cellamagassag,'',1,0,'C');
  $pdf->Cell($cellaszelesseg[1],$cellamagassag,'Megnevezés',1,0,'C');
  $pdf->Cell($cellaszelesseg[2],$cellamagassag,'SAPSzam',1,0,'C');
  $pdf->Cell($cellaszelesseg[3],$cellamagassag,'ME',1,0,'C');
  $pdf->Cell($cellaszelesseg[4],$cellamagassag,'Egységár',1,0,'C');
  $pdf->Cell($cellaszelesseg[5],$cellamagassag,'pa_dbszam',1,0,'C');
  $pdf->Cell($cellaszelesseg[6],$cellamagassag,'Ár összesen',1,1,'C');

  $fill=false;

  /// each record is one row  ///
  $i=0;
  foreach ($conn->query($rows) as $row) {
  $i++;
  $pdf->Cell($cellaszelesseg[0],$cellamagassag,$i,1,0,'C',$fill);
  $pdf->Cell($cellaszelesseg[1],$cellamagassag,$row['helyi_anyaglista_megnevezes'],1,0,'L',$fill);
  $pdf->Cell($cellaszelesseg[2],$cellamagassag,$row['helyi_anyaglista_sapszam'],1,0,'C',$fill);
  $pdf->Cell($cellaszelesseg[3],$cellamagassag,$row['helyi_anyaglista_mertekegyseg'],1,0,'C',$fill);
  $pdf->Cell($cellaszelesseg[4],$cellamagassag,$row['helyi_anyaglista_egysegar'].' Ft',1,0,'C',$fill);
  $pdf->Cell($cellaszelesseg[5],$cellamagassag,$row['pa_dbszam'],1,0,'C',$fill);
  $sorar=$row['helyi_anyaglista_egysegar']*$row['pa_dbszam'];
  $pdf->Cell($cellaszelesseg[6],$cellamagassag,$sorar.' Ft',1,1,'C',$fill);
  $osszegar=$osszegar+$sorar;
  }
  /// end of records ///
  $pdf->Cell(164,$cellamagassag,'Összesen',1,0,'C',$fill);
  $pdf->Cell(25,$cellamagassag,$osszegar.' Ft',1,1,'C',$fill);
}

?>
