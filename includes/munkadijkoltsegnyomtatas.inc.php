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
  $rows=("SELECT * FROM munkadijkoltseg WHERE parent_id IS NULL AND projekt_id = '$pid'");
  $query=mysqli_query($conn,$rows);
  $totalrows = mysqli_num_rows($query);
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
    $pdf->Cell($cellaszelesseg[0],$cellamagassag,$row['munkadijkoltseg_megnevezes'],1,0,'L',$fill);
    $pdf->Cell($cellaszelesseg[1],$cellamagassag,$row['munkadijkoltseg_mertekegyseg'],1,0,'L',$fill);
    $pdf->Cell($cellaszelesseg[2],$cellamagassag,$row['munkadijkoltseg_mennyiseg'],1,0,'C',$fill);
    $pdf->Cell($cellaszelesseg[3],$cellamagassag,'',1,0,'C',$fill);
    $pdf->Cell($cellaszelesseg[4],$cellamagassag,'',1,1,'C',$fill);
    $arresz = show_children($row['munkadiijkoltseg_id'],$i);
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
  $children = mysqli_query($conn,"SELECT * FROM munkadijkoltseg WHERE parent_id=$parentID");

  while ($row = mysqli_fetch_array($children)){
    $sorid=$row['munkadiijkoltseg_id'];

    if ($row['munkadijkoltseg_mennyiseg']==NULL) {
      $pdf->Cell(189,$cellamagassag,str_repeat(" ", $depth * 5).$row['munkadijkoltseg_megnevezes'],1,1,'L',$fill);
      $totalrows = mysqli_num_rows($children);
      if ($totalrows > 1) {
        $i=1;
      }else {
        $i=0;
      }
      $arresz = show_children($row['munkadiijkoltseg_id'], $i, $depth+1);
      $osszegar=$osszegar+$arresz;
    }
    else {
      $munkadij = mysqli_query($conn,"SELECT * FROM projektmunkadij
                    INNER JOIN munkadijkoltseg
                    ON projektmunkadij.munkadij_id = munkadijkoltseg.munkadij_id
                    WHERE munkadijkoltseg.munkadiijkoltseg_id ='$sorid' AND projektmunkadij.projekt_id='$pid'");
      $row2=mysqli_fetch_array($munkadij);
      $pdf->Cell($cellaszelesseg[0],$cellamagassag,str_repeat(" ", $depth * 5).$row['munkadijkoltseg_megnevezes'],1,0,'L',$fill);
      $pdf->Cell($cellaszelesseg[1],$cellamagassag,$row['munkadijkoltseg_mertekegyseg'],1,0,'C',$fill);
      $pdf->Cell($cellaszelesseg[2],$cellamagassag,$row['munkadijkoltseg_mennyiseg'],1,0,'C',$fill);
      $pdf->Cell($cellaszelesseg[3],$cellamagassag,$row2['pm_Oraber'],1,0,'C',$fill);
      if ($row['munkadijkoltseg_mennyiseg']!=NULL) {
        $sorar=$row['munkadijkoltseg_mennyiseg']*$row2['pm_Oraber'];
        $pdf->Cell($cellaszelesseg[4],$cellamagassag,$sorar.' Ft',1,1,'C',$fill);
        $osszegar=$osszegar+$sorar;
      }
      else {
        $pdf->Cell($cellaszelesseg[4],$cellamagassag,'',1,1,'C',$fill);
      }
      if ($row2['Munkadij_id']==1) {
        $mernokmido=$mernokmido+$row['munkadijkoltseg_mennyiseg'];
      }
      elseif ($row2['Munkadij_id']==2){
        $muszereszmido=$muszereszmido+$row['munkadijkoltseg_mennyiseg'];
      }
    }
    }
    if ($i == 1) {
      $pdf->Cell(154,$cellamagassag,'Összegzett ár:',1,0,'R',$fill);
      $pdf->Cell(35,$cellamagassag,$osszegar. 'Ft',1,1,'C',$fill);
    }
    return $osszegar;
}
?>
