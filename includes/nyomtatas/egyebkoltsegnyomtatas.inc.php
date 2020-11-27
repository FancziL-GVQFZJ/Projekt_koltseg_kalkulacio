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
  $parents=mysqli_query($conn,$rows);
  $totalrows = mysqli_num_rows($parents);
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
    $pdf->Cell($cellaszelesseg[0],$cellamagassag,$row['egyebkoltseg_megnevezes'],1,0,'L',$fill);
    $pdf->Cell($cellaszelesseg[1],$cellamagassag,$row['egyebkoltseg_mertekegyseg'],1,0,'L',$fill);
    $pdf->Cell($cellaszelesseg[2],$cellamagassag,$row['egyebkoltseg_mennyiseg'],1,0,'C',$fill);
    $pdf->Cell($cellaszelesseg[3],$cellamagassag,'',1,0,'C',$fill);
    $pdf->Cell($cellaszelesseg[4],$cellamagassag,'',1,1,'C',$fill);
    $arresz = show_children2($row['egyebkoltseg_id'], $i);
    $egyebkoltseg=$egyebkoltseg+$arresz;
  }
  $pdf->Cell(154,$cellamagassag,'Összesen:',1,0,'C',$fill);
  $pdf->Cell(35,$cellamagassag,$egyebkoltseg.' Ft',1,1,'C',$fill);

  $anyaglistaar=0;
  $pid = $_SESSION['projektId'];
  $sor=mysqli_query($conn, "SELECT * FROM sap_anyaglista
        INNER JOIN pa_kapcsolat
          ON sap_anyaglista.sap_anyaglista_id = pa_kapcsolat.alkatresz_id
        INNER JOIN projekt
          ON pa_kapcsolat.projekt_id = projekt.projekt_id
          WHERE projekt.projekt_id = '$pid'
          ORDER BY sap_anyaglista.sap_anyaglista_id");
  while ($row=mysqli_fetch_array($sor))
  {
    $sorar=$row['sap_anyaglista_egysegar']*$row['pa_dbszam'];
    $anyaglistaar=$anyaglistaar+$sorar;
  }

  $anyagkoltseg=0;
  $query2="SELECT * FROM anyagkoltseg WHERE projekt_id = '$pid'";
  $sor2=mysqli_query($conn,$query2);

  while ($row=mysqli_fetch_array($sor2))
  {
    $sorar=$row['anyagkoltseg_egysegar']*$row['anyagkoltseg_mennyiseg'];
    $anyagkoltseg=$anyagkoltseg+$sorar;
  }

  $munkadijkoltseg=0;
  $munkadij = mysqli_query($conn,"SELECT * FROM munkadijkoltseg
                INNER JOIN projektmunkadij
                ON munkadijkoltseg.munkadij_id = projektmunkadij.munkadij_id
                WHERE munkadijkoltseg.projekt_id='$pid'
                AND projektmunkadij.projekt_id='$pid'
                AND munkadijkoltseg.parent_id IS NOT NULL");
  while ($row1=mysqli_fetch_array($munkadij))
  {
    $sorar1=$row1['munkadijkoltseg_mennyiseg']*$row1['projektmunkadij_oraber'];
    $munkadijkoltseg=$munkadijkoltseg+$sorar1;
  }
  $mindosszesen = $anyaglistaar + $anyagkoltseg + $munkadijkoltseg + $egyebkoltseg;
  $pdf->Cell(154,$cellamagassag,'Mindösszesen:(1+2+3)',1,0,'C',$fill);
  $pdf->Cell(35,$cellamagassag,$mindosszesen.' Ft',1,1,'C',$fill);
}

function show_children2($parentID, $i, $depth=1){
  require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
  $pid = $_SESSION['projektId'];
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
      $parents=mysqli_query($conn,$rows);
      $totalrows = mysqli_num_rows($parents);
      if ($totalrows > 1) {
        $i=1;
      }else {
        $i=0;
      }
      $arresz = show_children($row['egyebkoltseg_id'], $i, $depth+1);
      $osszegar=$osszegar+$arresz;
    }
    else {
      $munkadij = mysqli_query($conn,"SELECT * FROM projektmunkadij
                    INNER JOIN egyebkoltseg
                    ON projektmunkadij.munkadij_id = egyebkoltseg.munkadij_id
                    WHERE egyebkoltseg.egyebkoltseg_id ='$sorid' AND projektmunkadij.projekt_id='$pid'");
      $row2=mysqli_fetch_array($munkadij);
      $pdf->Cell($cellaszelesseg[0],$cellamagassag,str_repeat(" ", $depth * 5).$row['egyebkoltseg_megnevezes'],1,0,'L',$fill);
      $pdf->Cell($cellaszelesseg[1],$cellamagassag,$row['egyebkoltseg_mertekegyseg'],1,0,'C',$fill);
      $pdf->Cell($cellaszelesseg[2],$cellamagassag,$row['egyebkoltseg_mennyiseg'],1,0,'C',$fill);
      $pdf->Cell($cellaszelesseg[3],$cellamagassag,$row2['projektmunkadij_oraber'].' Ft',1,0,'C',$fill);
      if ($row['egyebkoltseg_mennyiseg']!=NULL) {
        $sorar=$row['egyebkoltseg_mennyiseg']*$row2['projektmunkadij_oraber'];
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
