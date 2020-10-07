<?php
//require 'dbh.inc.php';
//require ('../fpdf182/fpdf.php');

//A4 width : 219mm
//default margin : 10mm each side
//writable horizontal : 219-(10*2)=189mm

// class PDF extends FPDF
// {
//   function Cell( $w, $h = 0, $t = '', $b = 0, $l = 0, $a = '', $f = false, $y = '' ) {
//
//     parent::Cell( $w, $h, iconv( 'UTF-8', 'ISO-8859-2', $t ), $b, $l, $a, $f, $y );
//   }
// }
//
// session_start();
//
// $focim = 'Nyersvas keverő átépítés műszerszerelési és folyír. Munkái ';
//
// //create pdf object
// $pdf = new PDF('P', 'mm', 'A4');
// //add new page
// $pdf->AddPage();
//
// //set font to arial, bold, 14pt
// $pdf->SetFont('Arial','B',14);
//
// //Cell(width , height , text , border , end line , [align] )
// //$pdf->Cell(70 ,5,'',0,0);
// $pdf->Cell(0,5,'KALKULÁCIÓS ADATLAP',0,1,'C');
// //$pdf->Cell(89 ,5,'',0,1);//end of line
//
// //make a dummy empty cell as a vertical spacer
// $pdf->Cell(189 ,10,'',0,1);//end of line
//
// //set font to arial, regular, 12pt
// $pdf->SetFont('Arial','',12);
//
// $pdf->Cell(0 ,5,'Tárgy:',0,1,'L');//end of line
//
// $pdf->Cell(0 ,5,$focim,0,1,'C');//end of line
//
// //make a dummy empty cell as a vertical spacer
// $pdf->Cell(189 ,5,'',0,1);//end of line
//
// $pdf->Cell(0 ,5,'Vállalkozó: IT Ig. Műszerszerelő és Mérlegkarbantartó üzem:',0,1,'L');//end of line
//
// //make a dummy empty cell as a vertical spacer
// $pdf->Cell(189 ,5,'',0,1);//end of line
//
// printmunkadijkoltseg();
//
// //make a dummy empty cell as a vertical spacer
// $pdf->Cell(189 ,5,'',0,1);//end of line
//
// $ma = date("Y.m.d");
// $pdf->Cell(0 ,5,$ma,0,1,'L');//end of line
//
// //make a dummy empty cell as a vertical spacer
// $pdf->Cell(189 ,10,'',0,1);//end of line
//
// $pdf->Cell(9 ,5,'',0,0);
// $pdf->Cell(110 ,5,'......................................................................................',0,0);
// $pdf->Cell(70 ,5,'........................................................',0,1);
//
// $pdf->Cell(39 ,5,'',0,0);
// $pdf->Cell(80 ,5,'Üzemvezető',0,0);
// $pdf->Cell(20 ,5,'',0,0);
// $pdf->Cell(60 ,5,'IT Főmérnök',0,1);
//
// $pdf->Output();

function printmunkadijkoltseg(){
  require 'dbh.inc.php';
  global $pdf;
  $pid = $_SESSION['projektId'];
  $cellaszelesseg=array(84,30,20,20,35);
  $cellamagassag=5;

  $pdf->Cell(0 ,5,'2.Munkadíj költség:',0,1,'L');

  $pdf->SetFont('Arial','',10);
  $rows=("SELECT * FROM munkafajta WHERE parent_id IS NULL AND project_id = '$pid'");

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
    $arresz = show_children($row['Id']);
    $teljesar=$teljesar+$arresz;

  }

  $pdf->Cell(154,$cellamagassag,'Összesen:',1,0,'R',$fill);
  $pdf->Cell(35,$cellamagassag,$teljesar.' Ft',1,1,'C',$fill);
}

function show_children($parentID, $depth=1){
  require 'dbh.inc.php';
  global $pdf;
  global $cellaszelesseg;
  global $cellamagassag;
  $cellaszelesseg=array(84,30,20,20,35);
  $cellamagassag=5;
  $children = mysqli_query($conn,"SELECT * FROM munkafajta WHERE parent_id=$parentID");

  while ($row = mysqli_fetch_array($children)){
    $sorid=$row['Id'];

    if ($row['Mennyiseg']==NULL) {
      $pdf->Cell(189,$cellamagassag,str_repeat("&nbsp;", $depth * 5).$row['Megnevezes'],1,1,'L',$fill);

      $arresz = show_children($row['Id'], $depth+1);
      $osszegar=$osszegar+$arresz;
    }
    else {
      $munkadij = mysqli_query($conn,"SELECT * FROM munkadij
                    INNER JOIN munkafajta
                    ON munkadij.Id = munkafajta.munkadij_id
                    WHERE munkafajta.Id ='$sorid'");
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
