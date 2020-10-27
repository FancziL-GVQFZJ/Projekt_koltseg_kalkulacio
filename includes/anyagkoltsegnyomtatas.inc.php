<?php

function printanyagkoltseg(){
  require 'dbh.inc.php';
  global $pdf;
  $pid = $_SESSION['projektId'];
  $cellaszelesseg=array(84,30,20,20,35);
  $cellamagassag=5;

  $pdf->Cell(0 ,5,'1.Anyagköltség:',0,1,'L');
  //$pdf->Cell(59 ,5,'',0,1);//end of line

  $pdf->SetFont('Arial','',10);
  $rows=("SELECT * FROM anyagkoltseg WHERE projekt_id = '$pid'");
  // Header starts ///

  $pdf->Cell($cellaszelesseg[0],$cellamagassag,'Anyagi megnevezés',1,0,'C');
  $pdf->Cell($cellaszelesseg[1],$cellamagassag,'ME',1,0,'C');
  $pdf->Cell($cellaszelesseg[2],$cellamagassag,'Mennyiség',1,0,'C');
  $pdf->Cell($cellaszelesseg[3],$cellamagassag,'Egységár',1,0,'C');
  $pdf->Cell($cellaszelesseg[4],$cellamagassag,'Összeg',1,1,'C');

  $fill=false;
  foreach ($conn->query($rows) as $row){
    $sorar=$row['Mennyiseg']*$row['Egysegar'];
    $pdf->Cell($cellaszelesseg[0],$cellamagassag,$row['Megnevezes'],1,0,'L',$fill);
    $pdf->Cell($cellaszelesseg[1],$cellamagassag,$row['ME'],1,0,'L',$fill);
    $pdf->Cell($cellaszelesseg[2],$cellamagassag,$row['Mennyiseg'],1,0,'C',$fill);
    $pdf->Cell($cellaszelesseg[3],$cellamagassag,$row['Egysegar'],1,0,'C',$fill);
    $pdf->Cell($cellaszelesseg[4],$cellamagassag,$sorar,1,1,'C',$fill);
    $teljesar=$teljesar+$sorar;
  }

  $query = "SELECT * FROM alkatresz
        INNER JOIN pa_kapcsolat
          ON alkatresz.id = pa_kapcsolat.alkatresz_id
        INNER JOIN projekt
          ON pa_kapcsolat.projekt_id = projekt.idProjekt
          WHERE projekt.idProjekt = '$pid'
          ORDER BY alkatresz.id";

  $sor=mysqli_query($conn, $query);
  while ($row=mysqli_fetch_array($sor))
  {
    $sorar=$row['Egysegar']*$row['DBszam'];
    $anyaglistaar=$anyaglistaar+$sorar;
  }

  $pdf->Cell($cellaszelesseg[0],$cellamagassag,'villamos szerelési anyag',1,0,'L',$fill);
  $pdf->Cell($cellaszelesseg[1],$cellamagassag,'db',1,0,'L',$fill);
  $pdf->Cell($cellaszelesseg[2],$cellamagassag,'1',1,0,'C',$fill);
  $pdf->Cell($cellaszelesseg[3],$cellamagassag,$anyaglistaar,1,0,'C',$fill);
  $pdf->Cell($cellaszelesseg[4],$cellamagassag,$anyaglistaar,1,1,'C',$fill);

  $teljesar=$teljesar+$anyaglistaar;
  $pdf->Cell(154,$cellamagassag,'Összesen:',1,0,'C',$fill);
  $pdf->Cell(35,$cellamagassag,$teljesar.' Ft',1,1,'C',$fill);
}
?>
