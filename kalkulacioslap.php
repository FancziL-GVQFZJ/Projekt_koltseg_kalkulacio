<?php
  $thisPage='Kalkulacioslap';
  $thisPage1='Kalkulacio';
  require "kalkulacioslapheader.php";
  session_start();
?>

<div id="container">
  <div id="main">
    <main>
      <div>
        <?php
        require 'includes/kapcsolat.inc.php';
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId'])) {

          //Villamos anyaglista táblázat
          echo "<div align= \"center\" id=\"nyomtatas\">";
            echo "<p class='szoveg' style='font-size: large;'><b><u>Villamos anyaglista</u></b></p>";
            echo "<table border=1; style='border-collapse: collapse;' class='table-style'>";
            echo "<tr class='fejlec'>";
            echo "<th></th><th>Megnevezés</th><th>SAPSzám</th><th>Mérték egység</th><th>Egységár</th><th>Darabszám</th><th>Összeg</th>";
            $pid = $_SESSION['projektId'];
            $sor=mysqli_query($conn, "SELECT * FROM sap_anyaglista
                    INNER JOIN pa_kapcsolat
                      ON sap_anyaglista.sap_anyaglista_id = pa_kapcsolat.alkatresz_id
                    INNER JOIN projekt
                      ON pa_kapcsolat.projekt_id = projekt.projekt_id
                      WHERE projekt.projekt_id = $pid
                      ORDER BY sap_anyaglista.sap_anyaglista_id");
            $i=1;
            $anyaglistaar=0;
            while ($row=mysqli_fetch_array($sor))
            {
              $sorid=$row['alkatresz_id'];
              echo "<tr>";
              echo "<td>".$i."</td>";
              echo "<td>".$row['sap_anyaglista_megnevezes']."</td>";
              echo "<td>".$row['sap_anyaglista_id']."</td>";
              echo "<td>".$row['sap_anyaglista_mertekegyseg']."</td>";
              echo "<td>".$row['sap_anyaglista_egysegar']." Ft</td>";
              echo "<td>".$row['pa_dbszam']."</td>";
              $sorar=$row['sap_anyaglista_egysegar']*$row['pa_dbszam'];
              echo "<td>".$sorar." Ft</td>";
              $anyaglistaar=$anyaglistaar+$sorar;
              echo "</tr>";
              $i++;
            }
            echo  "<tr>";
            echo  "<td></td>";
            echo  "<td colspan='5' align='center'>Összesen:</td>";
            echo  "<td align='left'>".$anyaglistaar." Ft</td>";
            echo  "</tr>";
            echo  "</table>";

            //Anyagköltség táblázat
            echo "<p class='szoveg' style='font-size: large;'><b><u>Anyagköltség</u></b></p>";
            echo "<table border=1; style='border-collapse: collapse;' class='table-style'>";
            echo "<tr class='fejlec'>";

            echo "<th>Anyagi megnevezés</th><th>Mértékegység</th><th>Mennyiség</th><th>Egységár</th><th>Összeg</th>";
            $pid = $_SESSION['projektId'];
            $query2="SELECT * FROM anyagkoltseg WHERE projekt_id = '$pid'";
            $sor2=mysqli_query($conn,$query2);

            while ($row=mysqli_fetch_array($sor2))
            {
              echo "<tr>";
              echo "<td>".$row['anyagkoltseg_megnevezes']."</td>";
              echo "<td>".$row["anyagkoltseg_mertekegyseg"]."</td>";
              echo "<td>".$row["anyagkoltseg_mennyiseg"]."</td>";
              echo "<td>".$row["anyagkoltseg_egysegar"]." Ft</td>";
              $sorar=$row["anyagkoltseg_mennyiseg"]*$row["anyagkoltseg_egysegar"];
              echo "<td>".$sorar." Ft</td>";
              $anyagkoltseg=$anyagkoltseg+$sorar;
              echo "</tr>";
            }
            echo  "<tr>";
            echo  "<td>villamos szerelési anyag</td>";
            echo  "<td>db</td>";
            echo  "<td>1</td>";
            echo  "<td align='left'>$anyaglistaar Ft</td>";
            echo  "<td align='left'>$anyaglistaar Ft</td>";
            echo  "</tr>";
            echo  "<tr>";
            echo  "<td colspan='4' align='center'>Összesen:</td>";
            $anyagkoltseg=$anyagkoltseg+$anyaglistaar;
            echo  "<td align='left'>".$anyagkoltseg." Ft</td>";
            echo  "</tr>";
            print "</table>";

            //Munkadíj költség táblázat
            echo "<p class='szoveg' style='font-size: large;'><b><u>Munkadíj költség</u></b></p>";
            echo "<table border=1; style='border-collapse: collapse;' class='table-style'>";
            echo "<tr class='fejlec'>";
            echo "<th>Megnevezés</th><th>Mértékegység</th><th>Mennyiség</th><th>Órabér</th><th>Összeg</th>";
            $query="SELECT * FROM munkadijkoltseg WHERE parent_id IS NULL AND projekt_id = '$pid'";
            $parents=mysqli_query($conn,$query);
            $totalrows = mysqli_num_rows($parents);
            if ($totalrows > 1) {
              $i=1;
            }else {
              $i=0;
            }
            $munkadijkoltseg=0;
            while ($row=mysqli_fetch_array($parents))
            {?>
              <tr>
              <?php
              echo "<td><b>".$row['munkadijkoltseg_megnevezes']."</b></td>";
              echo "<td>".$row["munkadijkoltseg_mertekegyseg"]."</td>";
              echo "<td>".$row["munkadijkoltseg_mennyiseg"]."</td>";
              echo "<td></td><td></td>";
              $sorid=$row['munkadijkoltseg_id'];?>
              </tr>
              <?php
              $arresz = show_children($row['munkadijkoltseg_id'], $i);
              $munkadijkoltseg=$munkadijkoltseg+$arresz;
            }

            //Munkaórák kiírása
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
                echo  "<tr>";
                echo  "<td align='left'>".$dolgozo." munkaidő:</td>";
                echo  "<td>óra</td>";
                echo  "<td align='left'>".$munkaido. "</td>";
                echo  "<td></td><td></td>";
                echo  "</tr>";
              }
            }
            echo  "<tr>";
            echo  "<td colspan='4' align='center'>Összesen:</td>";
            echo  "<td align='left'>".$munkadijkoltseg." Ft</td>";
            echo  "</tr>";
            print "</table>";

            //Egyéb költség táblázat
            echo "<p class='szoveg' style='font-size: large;'><b><u>Egyéb költség</u></b></p>";
            echo "<table border=1; style='border-collapse: collapse;' class='table-style'>";
            echo "<tr class='fejlec'>";
            echo "<th>Megnevezés</th><th>Mértékegység</th><th>Mennyiség</th><th>Órabér</th><th>Összeg</th>";
            $query="SELECT * FROM egyebkoltseg WHERE parent_id IS NULL AND projekt_id = '$pid'";
            $parents=mysqli_query($conn,$query);
            $totalrows = mysqli_num_rows($parents);
            if ($totalrows > 1) {
              $i=1;
            }else {
              $i=0;
            }
            $egyebkoltseg=0;
            while ($row=mysqli_fetch_array($parents))
            {
              echo "<tr>";
              echo "<td><b>".$row['egyebkoltseg_megnevezes']."</b></td>";
              echo "<td>".$row["egyebkoltseg_mertekegyseg"]."</td>";
              echo "<td>".$row["egyebkoltseg_mennyiseg"]."</td>";
              echo "<td></td><td></td>";
              $sorid=$row['egyebkoltseg_id'];
              echo "</tr>";
              $arresz = show_children2($row['egyebkoltseg_id'], $i);
              $egyebkoltseg=$egyebkoltseg+$arresz;
            }
            echo  "<tr>";
            echo  "<td colspan='4' align='center'>Összesen:</td>";
            echo  "<td align='left'>".$egyebkoltseg." Ft</td>";
            echo  "</tr>";

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
            $mindosszesen = $anyagkoltseg + $munkadijkoltseg + $egyebkoltseg;
            echo  "<tr>";
            echo  "<td colspan='4' align='center'>Mindösszesen(1+2+3):</td>";
            echo  "<td align='left'>".$mindosszesen." Ft</td>";
            echo  "</tr>";
            print "</table>";

            //Műszaki tartalom
            $result = mysqli_query($conn,"SELECT * FROM muszakitartalom where projekt_id='$pid'");
            $row = mysqli_fetch_array($result);
            $leiras=$row['muszakitartalom_tartalom'];
            echo "<p class='szoveg' style='font-size: large;'><b><u>Műszaki tartalom</u></b></p>";
            echo '<div style="border:solid 1px #000"; id="mszt";>';
             echo htmlspecialchars_decode($leiras);
            echo "</div>";
         echo "</div>";
        }
        else {
          echo '<p>Jelenleg ki van jelentkezve!</p>';
        }
        ?>
      </div>
    </main>
  </div>
</div>
<?php

//Munkadíj költség táblázat functionja
function show_children($parentID, $i, $depth=1){
  require 'includes/kapcsolat.inc.php';
  global $mernokmido,$muszereszmido;
  $pid = $_SESSION['projektId'];

  $children = mysqli_query($conn,"SELECT * FROM munkadijkoltseg WHERE parent_id=$parentID");

  while ($row = mysqli_fetch_array($children)){
    $sorid=$row['munkadijkoltseg_id'];?>
    <tr>
    <?php if ($row['munkadijkoltseg_mennyiseg']==NULL) {
      echo "<td><b>".str_repeat("&nbsp;", $depth * 5).$row['munkadijkoltseg_megnevezes']."</b></td>";
      echo "<td></td><td></td><td></td><td></td>";
      echo "</tr>";
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
      if ($row2['munkadij_id']==1) {
        $mernokmido=$mernokmido+$row['munkadijkoltseg_mennyiseg'];
      }
      elseif ($row2['munkadij_id']==2){
        $muszereszmido=$muszereszmido+$row['munkadijkoltseg_mennyiseg'];
      }
      echo "<td>".str_repeat("&nbsp;", $depth * 5).$row['munkadijkoltseg_megnevezes']."</td>";
      echo "<td>".$row["munkadijkoltseg_mertekegyseg"]."</td>";
      echo "<td>".$row["munkadijkoltseg_mennyiseg"]."</td>";
      echo "<td>".$row2["projektmunkadij_oraber"]." Ft</td>";
      if ($row['munkadijkoltseg_mennyiseg']!=NULL) {
        $sorar=$row['munkadijkoltseg_mennyiseg']*$row2['projektmunkadij_oraber'];
        echo "<td>".$sorar." Ft</td>";
        $osszegar=$osszegar+$sorar;
      }
      else {
        echo "<td></td>";
      }
      echo "</tr>";
    }
  }
  if ($i == 1) {
    echo  "<tr>";
    echo  "<td></td>";
    echo  "<td colspan='3' align='right'>Összegzett ár:</td>";
    echo  "<td align='left'>".$osszegar." Ft</td>";
    echo  "</tr>";
  }
  return $osszegar;
}

//Egyéb költség táblázat functionja
function show_children2($parentID, $i, $depth=1){
  require 'includes/kapcsolat.inc.php';
  $pid = $_SESSION['projektId'];
  $children = mysqli_query($conn,"SELECT * FROM egyebkoltseg WHERE parent_id=$parentID");

  while ($row = mysqli_fetch_array($children)){
    $sorid=$row['egyebkoltseg_id'];?>
    <tr>
    <?php if ($row['egyebkoltseg_mennyiseg']==NULL) {
      echo "<td><b>".str_repeat("&nbsp;", $depth * 5).$row['egyebkoltseg_megnevezes']."</b></td>";
      echo "<td></td><td></td><td></td><td></td>";
      echo "</tr>";


      $arresz = show_children2($row['egyebkoltseg_id'], $depth+1);
      $osszegar=$osszegar+$arresz;
    }
    else {
      $munkadij = mysqli_query($conn,"SELECT * FROM projektmunkadij
                    INNER JOIN egyebkoltseg
                    ON projektmunkadij.munkadij_id = egyebkoltseg.munkadij_id
                    WHERE egyebkoltseg.egyebkoltseg_id ='$sorid' AND projektmunkadij.projekt_id='$pid'");
      $row2=mysqli_fetch_array($munkadij);
      echo "<td>".str_repeat("&nbsp;", $depth * 5).$row['egyebkoltseg_megnevezes']."</td>";
      echo "<td>".$row["egyebkoltseg_mertekegyseg"]."</td>";
      echo "<td>".$row["egyebkoltseg_mennyiseg"]."</td>";
      echo "<td>".$row2["projektmunkadij_oraber"]." Ft</td>";
      if ($row['egyebkoltseg_mennyiseg']!=NULL) {
        $sorar=$row['egyebkoltseg_mennyiseg']*$row2['projektmunkadij_oraber'];
        echo "<td>".$sorar." Ft</td>";
        $osszegar=$osszegar+$sorar;
      }
      else {
        echo "<td></td>";
      }
      echo "</tr>";
    }
  }
  echo  "<tr>";
  echo  "<td></td>";
  echo  "<td colspan='3' align='right'>Összegzett ár:</td>";
  echo  "<td align='left'>".$osszegar." Ft</td>";
  echo  "</tr>";
  return $osszegar;
}
?>

<?php
    require "footer.php";
?>
