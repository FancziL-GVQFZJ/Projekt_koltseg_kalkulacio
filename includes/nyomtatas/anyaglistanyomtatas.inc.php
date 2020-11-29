<?php
function printanyaglista(){
  require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
  global $pdf;
  $pid = $_SESSION['projektId'];
  $cellaszelesseg=array(5,89,25,10,20,15,25);
  $cellamagassag=5;

  //üres sor
  //$pdf->Cell(59 ,5,'',0,1);//end of line

  $pdf->Cell(0 ,5,'Anyaglista:',0,1,'L');

  $pdf->SetFont('Arial','',10);
  $rows=("SELECT * FROM sap_anyaglista
          INNER JOIN pa_kapcsolat
            ON sap_anyaglista.sap_anyaglista_id = pa_kapcsolat.alkatresz_id
          INNER JOIN projekt
            ON pa_kapcsolat.projekt_id = projekt.projekt_id
            WHERE projekt.projekt_id = $pid
            ORDER BY sap_anyaglista.sap_anyaglista_id");


  // táblázat fejléce
  $pdf->Cell($cellaszelesseg[0],$cellamagassag,'',1,0,'C');
  $pdf->Cell($cellaszelesseg[1],$cellamagassag,'Megnevezés',1,0,'C');
  $pdf->Cell($cellaszelesseg[2],$cellamagassag,'SAP szám',1,0,'C');
  $pdf->Cell($cellaszelesseg[3],$cellamagassag,'ME',1,0,'C');
  $pdf->Cell($cellaszelesseg[4],$cellamagassag,'Egységár',1,0,'C');
  $pdf->Cell($cellaszelesseg[5],$cellamagassag,'DB szám',1,0,'C');
  $pdf->Cell($cellaszelesseg[6],$cellamagassag,'Ár összesen',1,1,'C');

  $fill=false;

  //táblázatban szereplő adatok
  $i=0;
  foreach ($conn->query($rows) as $row) {
  $i++;
  $pdf->Cell($cellaszelesseg[0],$cellamagassag,$i,1,0,'C',$fill);
  $pdf->Cell($cellaszelesseg[1],$cellamagassag,$row['sap_anyaglista_megnevezes'],1,0,'L',$fill);
  $pdf->Cell($cellaszelesseg[2],$cellamagassag,$row['sap_anyaglista_id'],1,0,'C',$fill);
  $pdf->Cell($cellaszelesseg[3],$cellamagassag,$row['sap_anyaglista_mertekegyseg'],1,0,'C',$fill);
  $pdf->Cell($cellaszelesseg[4],$cellamagassag,$row['sap_anyaglista_egysegar'].' Ft',1,0,'C',$fill);
  $pdf->Cell($cellaszelesseg[5],$cellamagassag,$row['pa_dbszam'],1,0,'C',$fill);
  $sorar=$row['sap_anyaglista_egysegar']*$row['pa_dbszam'];
  $pdf->Cell($cellaszelesseg[6],$cellamagassag,$sorar.' Ft',1,1,'C',$fill);
  $osszegar=$osszegar+$sorar;
  }

  $pdf->Cell(164,$cellamagassag,'Összesen',1,0,'C',$fill);
  $pdf->Cell(25,$cellamagassag,$osszegar.' Ft',1,1,'C',$fill);
}

?>
