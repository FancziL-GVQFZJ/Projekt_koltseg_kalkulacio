<?php
function printmunkadijkoltseg(){
  require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
  global $pdf;
  $pid = $_SESSION['projektId'];
  $cellaszelesseg=array(84,30,20,20,35);
  $cellamagassag=5;

  //üres sor
  //$pdf->Cell(59 ,5,'',0,1);//end of line

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
  // táblázat fejléce
  $pdf->Cell($cellaszelesseg[0],$cellamagassag,'Megnevezés',1,0,'C');
  $pdf->Cell($cellaszelesseg[1],$cellamagassag,'ME',1,0,'C');
  $pdf->Cell($cellaszelesseg[2],$cellamagassag,'Mennyiség',1,0,'C');
  $pdf->Cell($cellaszelesseg[3],$cellamagassag,'Órabér',1,0,'C');
  $pdf->Cell($cellaszelesseg[4],$cellamagassag,'Összeg',1,1,'C');

  //táblázatban szereplő adatok
  $fill=false;
  foreach ($conn->query($rows) as $row){
    $pdf->Cell($cellaszelesseg[0],$cellamagassag,$row['munkadijkoltseg_megnevezes'],1,0,'L',$fill);
    $pdf->Cell($cellaszelesseg[1],$cellamagassag,$row['munkadijkoltseg_mertekegyseg'],1,0,'L',$fill);
    $pdf->Cell($cellaszelesseg[2],$cellamagassag,$row['munkadijkoltseg_mennyiseg'],1,0,'C',$fill);
    $pdf->Cell($cellaszelesseg[3],$cellamagassag,'',1,0,'C',$fill);
    $pdf->Cell($cellaszelesseg[4],$cellamagassag,'',1,1,'C',$fill);
    $arresz = show_children($row['munkadijkoltseg_id'],$i);
    $teljesar=$teljesar+$arresz;

  }
  $pdf->Cell(189 ,5,'',1,1);

  //munkaidők kiírása
  $munkas = mysqli_query($conn,"SELECT * FROM projektmunkadij
                WHERE projekt_id = '$pid'");
  while ($row=mysqli_fetch_array($munkas))
  {
    $dolgozo=$row['projektmunkadij_munkafajta'];
    $dolgozoid=$row['munkadij_id'];
    $munkaido=0;
    $munkadij = mysqli_query($conn,"SELECT * FROM munkadijkoltseg
                  INNER JOIN projektmunkadij
                  ON munkadijkoltseg.munkadij_id = projektmunkadij.munkadij_id
                  WHERE munkadijkoltseg.projekt_id='$pid'
                  AND projektmunkadij.projekt_id='$pid'
                  AND munkadijkoltseg.munkadij_id='$dolgozoid'
                  AND munkadijkoltseg.parent_id IS NOT NULL");
    while ($row1=mysqli_fetch_array($munkadij))
    {
      $munkaido=$munkaido+$row1['munkadijkoltseg_mennyiseg'];
    }
    if ($munkaido > 0) {
      $pdf->Cell($cellaszelesseg[0],$cellamagassag,$dolgozo.' munkaidő',1,0,'L');
      $pdf->Cell($cellaszelesseg[1],$cellamagassag,'óra',1,0,'C');
      $pdf->Cell($cellaszelesseg[2],$cellamagassag,$munkaido,1,0,'C');
      $pdf->Cell($cellaszelesseg[3],$cellamagassag,'',1,0,'C');
      $pdf->Cell($cellaszelesseg[4],$cellamagassag,'',1,1,'C');
    }
  }

  $pdf->Cell(154,$cellamagassag,'Összesen:',1,0,'C',$fill);
  $pdf->Cell(35,$cellamagassag,$teljesar.' Ft',1,1,'C',$fill);
}

// functionnal a munkafajták kiírása
function show_children($parentID, $i, $depth=1){
  require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
  global $pdf;
  global $cellaszelesseg;
  global $cellamagassag;
  $pid = $_SESSION['projektId'];
  $children = mysqli_query($conn,"SELECT * FROM munkadijkoltseg WHERE parent_id=$parentID");

  while ($row = mysqli_fetch_array($children)){
    $sorid=$row['munkadijkoltseg_id'];

    if ($row['munkadijkoltseg_mennyiseg']==NULL) {
      $pdf->Cell(189,$cellamagassag,str_repeat(" ", $depth * 5).$row['munkadijkoltseg_megnevezes'],1,1,'L',$fill);
      $totalrows = mysqli_num_rows($children);
      if ($totalrows > 1) {
        $i=1;
      }else {
        $i=0;
      }
      $arresz = show_children($row['munkadijkoltseg_id'], $i, $depth+1);
      $osszegar=$osszegar+$arresz;
    }
    else {
      $munkadij = mysqli_query($conn,"SELECT * FROM projektmunkadij
                    INNER JOIN munkadijkoltseg
                    ON projektmunkadij.munkadij_id = munkadijkoltseg.munkadij_id
                    WHERE munkadijkoltseg.munkadijkoltseg_id ='$sorid' AND projektmunkadij.projekt_id='$pid'");
      $row2=mysqli_fetch_array($munkadij);
      $pdf->Cell($cellaszelesseg[0],$cellamagassag,str_repeat(" ", $depth * 5).$row['munkadijkoltseg_megnevezes'],1,0,'L',$fill);
      $pdf->Cell($cellaszelesseg[1],$cellamagassag,$row['munkadijkoltseg_mertekegyseg'],1,0,'C',$fill);
      $pdf->Cell($cellaszelesseg[2],$cellamagassag,$row['munkadijkoltseg_mennyiseg'],1,0,'C',$fill);
      $pdf->Cell($cellaszelesseg[3],$cellamagassag,$row2['projektmunkadij_oraber'].' Ft',1,0,'C',$fill);
      if ($row['munkadijkoltseg_mennyiseg']!=NULL) {
        $sorar=$row['munkadijkoltseg_mennyiseg']*$row2['projektmunkadij_oraber'];
        $pdf->Cell($cellaszelesseg[4],$cellamagassag,$sorar.' Ft',1,1,'C',$fill);
        $osszegar=$osszegar+$sorar;
      }
      else {
        $pdf->Cell($cellaszelesseg[4],$cellamagassag,'',1,1,'C',$fill);
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
