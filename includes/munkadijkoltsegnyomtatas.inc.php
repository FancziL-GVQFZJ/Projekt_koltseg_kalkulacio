<?php

function printmunkadijkoltseg(){
  require 'dbh.inc.php';
  global $pdf;
  $pid = $_SESSION['projektId'];
  // $cellaszelesseg=array(84,30,20,20,35);
  // $cellamagassag=5;
  // $mernokmido=0;
  // $muszereszmido=0;
  global $cellaszelesseg;
  global $cellamagassag;
  global $mernokmido,$muszereszmido;

  $pdf->Cell(0 ,5,'2.Munkadíj költség:',0,1,'L');

  $pdf->SetFont('Arial','',10);
  $rows=("SELECT * FROM munkafajta WHERE parent_id IS NULL AND project_id = '$pid'");
  $totalrows = mysqli_num_rows($rows);
  if ($totalrows > 1) {
    $i=1;
  }else {
    $i=0;
  }
  // Header starts ///

  $pdf->Cell($cellaszelesseg[0],$cellamagassag,'Megnevezés',1,0,'C');
  $pdf->Cell($cellaszelesseg[1],$cellamagassag,'ME',1,0,'C');
  $pdf->Cell($cellaszelesseg[2],$cellamagassag,'Mennyiség',1,0,'C');
  $pdf->Cell($cellaszelesseg[3],$cellamagassag,'Órabér',1,0,'C');
  $pdf->Cell($cellaszelesseg[4],$cellamagassag,'Összeg',1,1,'C');

  $fill=false;
  foreach ($conn->query($rows) as $row){
    $pdf->Cell($cellaszelesseg[0],$cellamagassag,$row['Megnevezes'],1,0,'L',$fill);
    $pdf->Cell($cellaszelesseg[1],$cellamagassag,$row['ME'],1,0,'L',$fill);
    $pdf->Cell($cellaszelesseg[2],$cellamagassag,$row['Mennyiseg'],1,0,'C',$fill);
    $pdf->Cell($cellaszelesseg[3],$cellamagassag,'',1,0,'C',$fill);
    $pdf->Cell($cellaszelesseg[4],$cellamagassag,'',1,1,'C',$fill);
    $arresz = show_children($row['Id'],$i);
    $teljesar=$teljesar+$arresz;

  }
  $pdf->Cell(189 ,5,'',1,1);
  $pdf->Cell($cellaszelesseg[0],$cellamagassag,'Mérnöki munkaidő',1,0,'L');
  $pdf->Cell($cellaszelesseg[1],$cellamagassag,'óra',1,0,'C');
  $pdf->Cell($cellaszelesseg[2],$cellamagassag,$mernokmido,1,0,'C');
  $pdf->Cell($cellaszelesseg[3],$cellamagassag,'',1,0,'C');
  $pdf->Cell($cellaszelesseg[4],$cellamagassag,'',1,1,'C');

  $pdf->Cell($cellaszelesseg[0],$cellamagassag,'Szerelői munkaidő',1,0,'L');
  $pdf->Cell($cellaszelesseg[1],$cellamagassag,'óra',1,0,'C');
  $pdf->Cell($cellaszelesseg[2],$cellamagassag,$muszereszmido,1,0,'C');
  $pdf->Cell($cellaszelesseg[3],$cellamagassag,'',1,0,'C');
  $pdf->Cell($cellaszelesseg[4],$cellamagassag,'',1,1,'C');

  $pdf->Cell(154,$cellamagassag,'Összesen:',1,0,'R',$fill);
  $pdf->Cell(35,$cellamagassag,$teljesar.' Ft',1,1,'C',$fill);
}

function show_children($parentID, $i, $depth=1){
  require 'dbh.inc.php';
  global $pdf;
  global $cellaszelesseg;
  global $cellamagassag;
  global $mernokmido,$muszereszmido;
  $pid = $_SESSION['projektId'];
  $children = mysqli_query($conn,"SELECT * FROM munkafajta WHERE parent_id=$parentID");

  while ($row = mysqli_fetch_array($children)){
    $sorid=$row['Id'];

    if ($row['Mennyiseg']==NULL) {
      $pdf->Cell(189,$cellamagassag,str_repeat(" ", $depth * 5).$row['Megnevezes'],1,1,'L',$fill);
      $totalrows = mysqli_num_rows($children);
      if ($totalrows > 1) {
        $i=1;
      }else {
        $i=0;
      }
      $arresz = show_children($row['Id'], $i, $depth+1);
      $osszegar=$osszegar+$arresz;
      $osszegkiiras = 1;
    }
    else {
      $munkadij = mysqli_query($conn,"SELECT * FROM projektmunkadij
                    INNER JOIN munkafajta
                    ON projektmunkadij.Munkadij_id = munkafajta.munkadij_id
                    WHERE munkafajta.Id ='$sorid' AND projektmunkadij.Projekt_id='$pid'");
      $row2=mysqli_fetch_array($munkadij);
      $pdf->Cell($cellaszelesseg[0],$cellamagassag,str_repeat(" ", $depth * 5).$row['Megnevezes'],1,0,'L',$fill);
      $pdf->Cell($cellaszelesseg[1],$cellamagassag,$row['ME'],1,0,'C',$fill);
      $pdf->Cell($cellaszelesseg[2],$cellamagassag,$row['Mennyiseg'],1,0,'C',$fill);
      $pdf->Cell($cellaszelesseg[3],$cellamagassag,$row2['pm_Oraber'],1,0,'C',$fill);
      if ($row['Mennyiseg']!=NULL) {
        $sorar=$row['Mennyiseg']*$row2['pm_Oraber'];
        $pdf->Cell($cellaszelesseg[4],$cellamagassag,$sorar.' Ft',1,1,'C',$fill);
        $osszegar=$osszegar+$sorar;
      }
      else {
        $pdf->Cell($cellaszelesseg[4],$cellamagassag,'',1,1,'C',$fill);
        if ($row2['Munkadij_id']==1) {
          $mernokmido=$mernokmido+$row['Mennyiseg'];
        }
        elseif ($row2['Munkadij_id']==2){
          $muszereszmido=$muszereszmido+$row['Mennyiseg'];
        }
      }
      $osszegkiiras = 0;
    }
    }
    if ($osszegkiiras == 0 && $i == 1) {
      $pdf->Cell(154,$cellamagassag,'Összegzett ár:',1,0,'R',$fill);
      $pdf->Cell(35,$cellamagassag,$osszegar. 'Ft',1,1,'C',$fill);
    }


    return $osszegar;
}
?>
