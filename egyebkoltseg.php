<?php
  $thisPage='Kalkulacioslap';
  $thisPage1='Egyeb';
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
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId']) && ($jogosultsag == 'iras' || $jogosultsag == 'admin')) {

          $pid = $_SESSION['projektId'];
          $csoport = mysqli_query($conn,"SELECT * FROM egyebkoltseg
                        WHERE projekt_id ='$pid' AND egyebkoltseg_mennyiseg IS NULL");

          echo '<div class="felvetel">
            <div class="felvetelin">
              <p><u>Új adat felvétele:</u></p>
              <form action="includes/databaseinsert/addtoegyebkoltseg.inc.php" method="post">
                Megnevezés: <input type="text" name="name" id="megnevezesid">
                <br>

                Csoport: <select name="csoport" id="csoportidek" disabled>';
                echo "<option  value='0' selected>nincs</option>";
                  while ($row4 = $csoport->fetch_assoc()){ ?>
                    <option value="<?=$row4['egyebkoltseg_id'] ?> " > <?=$row4['egyebkoltseg_megnevezes'] ?></option>  <?php
                  }
                echo '</select><br>';
                $query = mysqli_query($conn,"SELECT * FROM egyebkoltseg
                              WHERE projekt_id ='$pid'");
                $munkadijkezdes = mysqli_num_rows($query);
                if ($munkadijkezdes > 1) {
                  echo "Cím: <input type='checkbox' name='cim' value='pipa' id='checkboxid' checked='ckecked' disabled>";
                }else {
                  echo "Cím: <input 'type='checkbox' name='cim' value='pipa' id='checkboxid' checked='ckecked' disabled>";
                }
                echo "<br>";
                echo "<input class='button' type='submit' value='Az adat felvétele' id='felvetelid' disabled></submit>
              </form>
            </div>
          </div>";
          ?>

          <div align= "center" id="nyomtatas">
            <table class='table-style' id='Egyebkoltsegkalkulacio'>
            <tr class='fejlec'>
            <th>Id</th><th>Munkavégző</th><th>Megnevezés</th><th>Mértékegység</th><th>Mennyiség</th><th>Órabér</th><th>Ár:</th>

            <?php
            $pid = $_SESSION['projektId'];
            $query="SELECT * FROM egyebkoltseg WHERE parent_id IS NULL AND projekt_id = '$pid'";
            $parents=mysqli_query($conn,$query);
            $totalrows = mysqli_num_rows($parents);
            if ($totalrows > 1) {
              $i=1;
            }else {
              $i=0;
            }

            while ($row=mysqli_fetch_array($parents))
            {?>

              <tr id="<?php echo $row['egyebkoltseg_id']; ?>">
              <?php echo "<td>".$row['egyebkoltseg_id']."</td>";
              echo  "<td></td>";
              echo "<td><b>".$row['egyebkoltseg_megnevezes']."</b></td>";
              echo "<td>".$row["egyebkoltseg_mertekegyseg"]."</td>";
              echo "<td>".$row["egyebkoltseg_mennyiseg"]."</td>";
              echo "<td></td><td></td>";
              $sorid=$row['egyebkoltseg_id'];?>
              <td id='del'><span class='deleteek' data-id='<?= $sorid; ?>'>Törlés</span></td>
              </tr>
              <?php
              $arresz = show_children($row['egyebkoltseg_id'], $i);
              $teljesar=$teljesar+$arresz;
            }
            echo  "<tr>";
            echo  "<td></td>";
            echo  "<td colspan='5' align='right'>Teljes ár:</td>";
            echo  "<td align='left'>".$teljesar." Ft</td>";
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
  $children = mysqli_query($conn,"SELECT * FROM egyebkoltseg WHERE parent_id=$parentID");

  while ($row = mysqli_fetch_array($children)){
    $sorid=$row['egyebkoltseg_id'];?>
    <tr id="<?php echo $row['egyebkoltseg_id']; ?>">
    <?php if ($row['egyebkoltseg_mennyiseg']==NULL) {
      echo "<td>".$row['egyebkoltseg_id']."</td>";
      echo "<td><b>".str_repeat("&nbsp;", $depth * 5).$row['egyebkoltseg_megnevezes']."</b></td>";
      echo "<td></td><td></td><td></td><td></td>";?>
      <td id='del'><span class='deleteek' data-id='<?= $sorid; ?>'>Törlés</span></td>
      <?php echo "</tr>";
      $totalrows = mysqli_num_rows($children);
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

      echo "<td>".$row['egyebkoltseg_id']."</td>";
      echo "<td id='mv'><select name='munkavegzo' id='munkavegzo'>";
      $mf = mysqli_query($conn, "SELECT * FROM projektmunkadij WHERE projekt_id = '$pid'");
      while ($row5 = $mf->fetch_assoc()){ ?>
        <option value="<?=$row5['munkadij_id'] ?> " <?=$row5['munkadij_id'] ==
        $row['munkadij_id'] ? ' selected="selected"' : '';?>> <?=$row5['projektmunkadij_munkafajta'] ?></option>
        <?php
      }
      echo "</select></td>";
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
      }?>
      <td id='del'><span class='deleteek' data-id='<?= $sorid; ?>'>Törlés</span></td>
      <?php echo "</tr>";
    }
  }
  if ($i == 1) {
    echo  "<tr>";
    echo  "<td></td>";
    echo  "<td colspan='5' align='right'>Összegzett ár:</td>";
    echo  "<td align='left'>".$osszegar." Ft</td>";
    echo  "</tr>";
  }
  return $osszegar;
}
?>

<script type="text/javascript" src="/Projekt_koltseg_kalkulacio/js/jquery.tabledit.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#Egyebkoltsegkalkulacio').Tabledit({
      deleteButton: false,
      editButton: false,
      columns: {
      identifier: [0, 'Id'],
      editable: [[2, 'Megnevezes'],[3, 'ME'],[4, 'Mennyiseg']]
    },
    hideIdentifier: true,
    url: 'includes/tableedit/eklive_edit.inc.php',
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
      url: "includes/savemvek.inc.php",
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
         document.getElementById("csoportidek").disabled = false;
         document.getElementById("felvetelid").disabled = false;
       }
       else {
         document.getElementById("csoportidek").disabled = true;
         document.getElementById("checkboxid").disabled = true;
         document.getElementById("felvetelid").disabled = true;
       }
    });
</script>

<script type="text/javascript">
$(document).ready(function(){
  $('#csoportidek').change(function(){
   var value = $('#csoportidek').find(":selected").val();

   $.ajax({
     url: 'includes/datainsertek.inc.php',
     type: 'POST',
     data: { id:value },
     success: function(response){

       if(response == 1){
           document.getElementById("felvetelid").disabled = false;
           document.getElementById("checkboxid").disabled = true;
           $('#checkboxid').each(function() {
            this.checked = false;
          });
       }else if(response == 2){
         document.getElementById("felvetelid").disabled = false;
         document.getElementById("checkboxid").disabled = true;
         $('#checkboxid').each(function() {
          this.checked = true;
        });
       }else if(response == 0){
         document.getElementById("felvetelid").disabled = false;
         document.getElementById("checkboxid").disabled = false;
       }else {
         alert("Próbálja meg újra");
       }
     }
   });
 });
});
</script>

<script type="text/javascript">
$("form").submit(function() {
  $("#checkboxid").removeAttr("disabled");
});
</script>

<?php
  require "footer.php";
?>
