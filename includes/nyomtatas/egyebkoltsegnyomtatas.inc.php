<?php
function printegyebkoltseg(){
  require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
  global $pdf;
  $pid = $_SESSION['projektId'];
  $cellaszelesseg=array(84,30,20,20,35);
  $cellamagassag=5;

  $pdf->Cell(0 ,5,'3.Egyéb költség:',0,1,'L');
  //$pdf->Cell(59 ,5,'',0,1);//end of line

  $pdf->SetFont('Arial','',10);
  $rows=("SELECT * FROM egyebkoltseg WHERE parent_id IS NULL AND projekt_id = '$pid'");
  // Header starts ///

  $pdf->Cell($cellaszelesseg[0],$cellamagassag,'Megnevezés',1,0,'C');
  $pdf->Cell($cellaszelesseg[1],$cellamagassag,'ME',1,0,'C');
  $pdf->Cell($cellaszelesseg[2],$cellamagassag,'Mennyiség',1,0,'C');
  $pdf->Cell($cellaszelesseg[3],$cellamagassag,'Órabér',1,0,'C');
  $pdf->Cell($cellaszelesseg[4],$cellamagassag,'Összeg',1,1,'C');

  $fill=false;
  foreach ($conn->query($rows) as $row){
    $pdf->Cell($cellaszelesseg[0],$cellamagassag,$row['egyebkoltseg_megnevezes'],1,0,'L',$fill);
    $pdf->Cell($cellaszelesseg[1],$cellamagassag,$row['egyebkoltseg_mertekegyseg'],1,0,'L',$fill);
    $pdf->Cell($cellaszelesseg[2],$cellamagassag,$row['egyebkoltseg_mennyiseg'],1,0,'C',$fill);
    $pdf->Cell($cellaszelesseg[3],$cellamagassag,'',1,0,'C',$fill);
    $pdf->Cell($cellaszelesseg[4],$cellamagassag,'',1,1,'C',$fill);
    $arresz = show_children2($row['egyebkoltseg_id']);
    $teljesar=$teljesar+$arresz;

  }


  $pdf->Cell(154,$cellamagassag,'Összesen:',1,0,'C',$fill);
  $pdf->Cell(35,$cellamagassag,$teljesar.' Ft',1,1,'C',$fill);
}



function show_children2($parentID, $depth=1){
  require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
  global $pdf;
  global $cellaszelesseg;
  global $cellamagassag;
  $cellaszelesseg=array(84,30,20,20,35);
  $cellamagassag=5;

  $children = mysqli_query($conn,"SELECT * FROM egyebkoltseg WHERE parent_id=$parentID");

  while ($row = mysqli_fetch_array($children)){
    $sorid=$row['egyebkoltseg_id'];

    if ($row['egyebkoltseg_mennyiseg']==NULL) {
      $pdf->Cell(189,$cellamagassag,str_repeat(" ", $depth * 5).$row['egyebkoltseg_megnevezes'],1,1,'L',$fill);

      $arresz = show_children($row['egyebkoltseg_id'], $depth+1);
      $osszegar=$osszegar+$arresz;
    }
    else {
      $munkadij = mysqli_query($conn,"SELECT * FROM munkadij
                    INNER JOIN egyebkoltseg
                    ON munkadij.munkadij_id = egyebkoltseg.munkadij_id
                    WHERE egyebkoltseg.egyebkoltseg_id ='$sorid'");
      $row2=mysqli_fetch_array($munkadij);
      $pdf->Cell($cellaszelesseg[0],$cellamagassag,str_repeat(" ", $depth * 5).$row['egyebkoltseg_megnevezes'],1,0,'L',$fill);
      $pdf->Cell($cellaszelesseg[1],$cellamagassag,$row['egyebkoltseg_mertekegyseg'],1,0,'C',$fill);
      $pdf->Cell($cellaszelesseg[2],$cellamagassag,$row['egyebkoltseg_mennyiseg'],1,0,'C',$fill);
      $pdf->Cell($cellaszelesseg[3],$cellamagassag,$row2['munkadij_oraber'],1,0,'C',$fill);
      if ($row['egyebkoltseg_mennyiseg']!=NULL) {
        $sorar=$row['egyebkoltseg_mennyiseg']*$row2['munkadij_oraber'];
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