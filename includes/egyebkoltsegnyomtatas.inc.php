<?php

function printegyebkoltseg(){
  require 'dbh.inc.php';
  global $pdf;
  $pid = $_SESSION['projektId'];
  $cellaszelesseg=array(84,30,20,20,35);
  $cellamagassag=5;

  $pdf->Cell(0 ,5,'3.Egyéb költség:',0,1,'L');
  //$pdf->Cell(59 ,5,'',0,1);//end of line

  $pdf->SetFont('Arial','',10);
  $rows=("SELECT * FROM egyebkoltseg WHERE parent_id IS NULL AND project_id = '$pid'");
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
    $arresz = show_children2($row['Id']);
    $teljesar=$teljesar+$arresz;

  }


  $pdf->Cell(154,$cellamagassag,'Összesen:',1,0,'R',$fill);
  $pdf->Cell(35,$cellamagassag,$teljesar.' Ft',1,1,'C',$fill);
}



function show_children2($parentID, $depth=1){
  require 'dbh.inc.php';
  global $pdf;
  global $cellaszelesseg;
  global $cellamagassag;
  $cellaszelesseg=array(84,30,20,20,35);
  $cellamagassag=5;
  //  require ('../fpdf182/fpdf.php');
  $children = mysqli_query($conn,"SELECT * FROM egyebkoltseg WHERE parent_id=$parentID");

  while ($row = mysqli_fetch_array($children)){
    $sorid=$row['Id'];

    if ($row['Mennyiseg']==NULL) {
      $pdf->Cell(189,$cellamagassag,str_repeat(" ", $depth * 5).$row['Megnevezes'],1,1,'L',$fill);

      $arresz = show_children($row['Id'], $depth+1);
      $osszegar=$osszegar+$arresz;
    }
    else {
      $munkadij = mysqli_query($conn,"SELECT Oraber FROM munkadij
                    INNER JOIN egyebkoltseg
                    ON munkadij.Id = egyebkoltseg.munkadij_id
                    WHERE egyebkoltseg.Id ='$sorid'");
      $row2=mysqli_fetch_array($munkadij);
      $pdf->Cell($cellaszelesseg[0],$cellamagassag,str_repeat(" ", $depth * 5).$row['Megnevezes'],1,0,'L',$fill);
      $pdf->Cell($cellaszelesseg[1],$cellamagassag,$row['ME'],1,0,'C',$fill);
      $pdf->Cell($cellaszelesseg[2],$cellamagassag,$row['Mennyiseg'],1,0,'C',$fill);
      $pdf->Cell($cellaszelesseg[3],$cellamagassag,$row2['Oraber'],1,0,'C',$fill);
      if ($row['Mennyiseg']!=NULL) {
        $sorar=$row['Mennyiseg']*$row2['Oraber'];
        $pdf->Cell($cellaszelesseg[4],$cellamagassag,$sorar.' Ft',1,1,'C',$fill);
        $osszegar=$osszegar+$sorar;
      }
      else {
        $pdf->Cell($cellaszelesseg[4],$cellamagassag,'',1,1,'C',$fill);
      }
    }
    }
    $pdf->Cell(154,$cellamagassag,'Összegzett ár:',1,0,'R',$fill);
    $pdf->Cell(35,$cellamagassag,$osszegar. 'Ft',1,1,'C',$fill);

    return $osszegar;
}
?>
