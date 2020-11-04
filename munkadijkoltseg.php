<?php
    $thisPage='Kalkulacioslap';
    $thisPage1='Munkadij';
    require "kalkulacioslapheader.php";
    session_start();
?>
<style><?php include 'css/navbar.css';?></style>
<style><?php include 'css/table.css';?></style>

<div id="container">
  <div id="main">
    <main>
      <div>
        <?php
        require 'includes/kapcsolat.inc.php';
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId']) && ($jogosultsag == 'iras' || $jogosultsag == 'admin')) { ?>
          
          <?php
          $pid = $_SESSION['projektId'];
          $csoport = mysqli_query($conn,"SELECT * FROM munkadijkoltseg
                        WHERE projekt_id ='$pid' AND munkadijkoltseg_mennyiseg IS NULL");

          echo '<p>Új adat felvétele</p>
          <form action="includes/databaseinsert/addtomunkafajta.inc.php" method="post">
            Megnevezés: <input type="text" name="name" id="megnevezesid">

            Csoport: <select style="display: none;" name="csoport" id="csoportid" onchange="OnSelectionChange(this.value)">';
            echo "<option  value='0' selected>nincs</option>";
              while ($row4 = $csoport->fetch_assoc()){ ?>
                <option value="<?=$row4['munkadiijkoltseg_id'] ?> " > <?=$row4['munkadijkoltseg_megnevezes'] ?></option>  <?php
              }
            echo '</select>';
            echo "Cím: <input type='checkbox' name='cim' value='pipa' id='checkboxid'>";
            echo "<input type='submit' style='display: none;' value='Felvétel' id='felvetelid'></submit>
          </form>";

          // $query1 = mysqli_query($conn,"SELECT * FROM munkadijkoltseg
          //               WHERE parent_id ='$id' AND Mennyiseg IS NOT NULL");
          // $resurt1 = mysqli_num_rows($query1);
          //
          // $query2 = mysqli_query($conn,"SELECT * FROM munkadijkoltseg
          //               WHERE Id = '$id' AND parent_id IS NOT NULL AND Mennyiseg IS NULL");
          // $resurt2 = mysqli_num_rows($query2);


          $mernokmido=0;
          $muszereszmido=0;

          echo "<div align= \"center\" id=\"nyomtatas\">";
            echo "<table id='Munkadijkalkulacio'>";
            echo "<tr class='fejlec'>";
            echo "<th>Id</th><th>Munkavégző</th><th>Megnevezés</th><th>Mértékegység</th><th>Mennyiség</th><th>Egységár</th><th>Ár:</th>";

            $query="SELECT * FROM munkadijkoltseg WHERE parent_id IS NULL AND projekt_id = '$pid'";
            $parents=mysqli_query($conn,$query);
            $totalrows = mysqli_num_rows($parents);
            if ($totalrows > 1) {
              $i=1;
            }else {
              $i=0;
            }

            while ($row=mysqli_fetch_array($parents))
            { ?>
              <tr id="<?php echo $row['munkadiijkoltseg_id']; ?>">
              <?php
              $sorid=$row['munkadiijkoltseg_id'];
              echo "<td>".$row['munkadiijkoltseg_id']."</td>";
              echo "<td></td>";
              echo "<td><b>".$row['munkadijkoltseg_megnevezes']."</b></td>";
              echo "<td>".$row["munkadijkoltseg_mertekegyseg"]."</td>";
              echo "<td>".$row["munkadijkoltseg_mennyiseg"]."</td>";
              echo "<td></td><td></td>"; ?>
              <td id='del'><span class='deletemd' data-id='<?= $sorid; ?>'>Törlés</span></td>
              <?php echo "</tr>";
              $arresz = show_children($row['munkadiijkoltseg_id'], $i);
              $teljesar=$teljesar+$arresz;
            }
            echo  "<tr>";
            echo  "<td></td><td></td>";
            echo  "<td align='left'>Mérnöki munkaidő:</td>";
            echo  "<td>óra</td>";
            echo  "<td align='left'>$mernokmido</td>";
            echo  "<td></td><td></td>";
            echo  "</tr>";
            echo  "<tr>";
            echo  "<td></td><td></td>";
            echo  "<td align='left'>Szerelői minkaidő:</td>";
            echo  "<td>óra</td>";
            echo  "<td align='left'>$muszereszmido</td>";
            echo  "<td></td><td></td>";
            echo  "</tr>";
            echo  "<tr>";
            echo  "<td></td>";
            echo  "<td colspan='5' align='center'>Összesen:</td>";
            echo  "<td align='left'>$teljesar Ft</td>";
            echo  "</tr>";
            print "</table>";
          echo  "</div>";
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
function show_children($parentID, $i, $depth=1){
  require 'includes/kapcsolat.inc.php';
  $pid = $_SESSION['projektId'];
  global $mernokmido,$muszereszmido;

  $children = mysqli_query($conn,"SELECT * FROM munkadijkoltseg WHERE parent_id='$parentID'");

  while ($row = mysqli_fetch_array($children)){
    $sorid=$row['munkadiijkoltseg_id'];?>
    <tr id="<?php echo $row['munkadiijkoltseg_id']; ?>">
    <?php
    if ($row['munkadijkoltseg_mennyiseg']==NULL) {
      echo "<td>".$row['munkadiijkoltseg_id']."</td>";
      echo "<td></td><td><b>".$row['munkadijkoltseg_megnevezes']."</b></td>";
      echo "<td></td><td></td><td></td><td></td>";?>
      <td id='del'><span class='deletemd' data-id='<?= $sorid; ?>'>Törlés</span></td> <?php
      echo "</tr>";
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

      echo "<td>".$row['munkadiijkoltseg_id']."</td>";
      echo "<td id='mv'><select name='munkavegzo' id='munkavegzo'>";
      $mf = mysqli_query($conn, "SELECT * FROM projektmunkadij WHERE projekt_id = '$pid'");
      while ($row5 = $mf->fetch_assoc()){ ?>
        <option value="<?=$row5['munkadij_id'] ?> " <?=$row5['munkadij_id'] ==
        $row['munkadij_id'] ? ' selected="selected"' : '';?>> <?=$row5['projektmunkadij_munkafajta'] ?></option>
        <?php

       }
      echo "</select></td>";
      if ($row2['munkadij_id']==1) {
        $mernokmido=$mernokmido+$row['munkadijkoltseg_mennyiseg'];
      }
      elseif ($row2['munkadij_id']==2){
        $muszereszmido=$muszereszmido+$row['munkadijkoltseg_mennyiseg'];
      }
      echo "<td>".$row['munkadijkoltseg_megnevezes']."</td>";
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
      }?>
      <td id='del'><span class='deletemd' data-id='<?= $sorid; ?>'>Törlés</span></td>
      <?php echo "</tr>";
    }
  }
  if ($i == 1) {
    echo  "<tr>";
    echo  "<td></td>";
    echo  "<td colspan='5' align='right'>Összegzett ár:</td>";
    echo  "<td align='left'>$osszegar Ft</td>";
    echo  "</tr>";
  }
  return $osszegar;
}
?>

<script type="text/javascript" src="/Projekt_koltseg_kalkulacio/js/jquery.tabledit.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#Munkadijkalkulacio').Tabledit({
      deleteButton: false,
      editButton: false,
      columns: {
      identifier: [0, 'Id'],
      editable: [[2, 'Megnevezes'],[3, 'ME'],[4, 'Mennyiseg']]
    },
    hideIdentifier: true,
    url: 'includes/tableedit/mdlive_edit.inc.php',
    onAlways: function() {location.reload()}
  });
});
</script>

<script type="text/javascript">
$('td#mv').on('change', function() {
  var sorid = $(this).parent().find('td:first-child').text();
  var munka = $(this).parent().find('#munkavegzo option:selected').val();
  $.ajax({
      type: "POST",
      url: "includes/savemv.inc.php",
      data : {id:sorid, mid:munka},
      success: function(response)
      {
        if(response == 1){
          alert('Sikeres változtatás.');
          window.location.reload();
        }else if(response == 0){
            alert('Nem megfelelő id.');
        }else{
            alert('Sikertelen változtatás.');
        }
      }
  });
  console.log(jogosultsag);
});
</script>

<script type="text/javascript">
$("#megnevezesid").keyup(function () {
       if ($(this).val()) {
          $("#csoportid").show();
        //  $("#checkboxid").show();
          $("#felvetelid").show();
       }
       else {
          $("#csoportid").hide();
        //  $("#checkboxid").hide();
          $("#felvetelid").hide();
       }
    });
</script>

<?php
    require "footer.php";
?>
