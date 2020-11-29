<?php
function printanyagkoltseg(){
  require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
  global $pdf;
  $pid = $_SESSION['projektId'];
  $cellaszelesseg=array(84,30,20,20,35);
  $cellamagassag=5;

  //üres sor
  //$pdf->Cell(59 ,5,'',0,1);//end of line

  $pdf->Cell(0 ,5,'1.Anyagköltség:',0,1,'L');

  $pdf->SetFont('Arial','',10);
  $rows=("SELECT * FROM anyagkoltseg WHERE projekt_id = '$pid'");

  // táblázat fejléce
  $pdf->Cell($cellaszelesseg[0],$cellamagassag,'Anyagi megnevezés',1,0,'C');
  $pdf->Cell($cellaszelesseg[1],$cellamagassag,'ME',1,0,'C');
  $pdf->Cell($cellaszelesseg[2],$cellamagassag,'Mennyiség',1,0,'C');
  $pdf->Cell($cellaszelesseg[3],$cellamagassag,'Egységár',1,0,'C');
  $pdf->Cell($cellaszelesseg[4],$cellamagassag,'Összeg',1,1,'C');

  //táblázatban szereplő adatok
  $fill=false;
  foreach ($conn->query($rows) as $row){
    $sorar=$row['anyagkoltseg_mennyiseg']*$row['anyagkoltseg_egysegar'];
    $pdf->Cell($cellaszelesseg[0],$cellamagassag,$row['anyagkoltseg_megnevezes'],1,0,'L',$fill);
    $pdf->Cell($cellaszelesseg[1],$cellamagassag,$row['anyagkoltseg_mertekegyseg'],1,0,'C',$fill);
    $pdf->Cell($cellaszelesseg[2],$cellamagassag,$row['anyagkoltseg_mennyiseg'],1,0,'C',$fill);
    $pdf->Cell($cellaszelesseg[3],$cellamagassag,$row['anyagkoltseg_egysegar']. 'Ft',1,0,'C',$fill);
    $pdf->Cell($cellaszelesseg[4],$cellamagassag,$sorar.' Ft',1,1,'C',$fill);
    $teljesar=$teljesar+$sorar;
  }

  $sor=mysqli_query($conn, "SELECT * FROM sap_anyaglista
          INNER JOIN pa_kapcsolat
            ON sap_anyaglista.sap_anyaglista_id = pa_kapcsolat.alkatresz_id
          INNER JOIN projekt
            ON pa_kapcsolat.projekt_id = projekt.projekt_id
            WHERE projekt.projekt_id = $pid
            ORDER BY sap_anyaglista.sap_anyaglista_id");
  while ($row=mysqli_fetch_array($sor))
  {
    $sorar=$row['sap_anyaglista_egysegar']*$row['pa_dbszam'];
    $anyaglistaar=$anyaglistaar+$sorar;
  }

  $pdf->Cell($cellaszelesseg[0],$cellamagassag,'villamos szerelési anyag',1,0,'L',$fill);
  $pdf->Cell($cellaszelesseg[1],$cellamagassag,'db',1,0,'C',$fill);
  $pdf->Cell($cellaszelesseg[2],$cellamagassag,'1',1,0,'C',$fill);
  $pdf->Cell($cellaszelesseg[3],$cellamagassag,$anyaglistaar.' Ft',1,0,'C',$fill);
  $pdf->Cell($cellaszelesseg[4],$cellamagassag,$anyaglistaar.' Ft',1,1,'C',$fill);

  $teljesar=$teljesar+$anyaglistaar;
  $pdf->Cell(154,$cellamagassag,'Összesen:',1,0,'C',$fill);
  $pdf->Cell(35,$cellamagassag,$teljesar.' Ft',1,1,'C',$fill);
}
?>
