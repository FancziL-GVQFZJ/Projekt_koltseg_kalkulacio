<?php
    $thisPage='Kalkulacioslap';
    require "header.php";
    session_start();
?>
<style><?php include 'css/navbar.css';?></style>
<style><?php include 'css/table.css';?></style>

<div id="container">
  <div id="main">
    <main>
      <div>
        <?php
        require 'includes/dbh.inc.php';
        //require 'includes/nyomtatas.inc.php';
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId']) && ($jogosultsag == 'iras' || $jogosultsag == 'admin')) { ?>
          <nav class="topnav">
            <ul>
              <li><a href="munkadijak.php">Munkadíjak</a></li>
              <li><a style="background-color: #ddd;" href="#">Munkadíj költség</a></li>
              <li><a href="egyebkoltseg.php">Egyéb költség</a></li>
              <li><a href="muszakitartalom.php">Műszaki tartalom</a></li>
              <li><a href="kalkulacioslap.php">Kalkulációs Lap</a></li>
              <li><a href="nyomtatasilap.php">Nyomtatási Lap</a></li>
            </ul>
          </nav>

          <?php
          // echo "<select id='mv' name='munkavegzo' id='munkavegzo'>";
          // $pid = $_SESSION['projektId'];
          // $csoport = mysqli_query($conn,"SELECT * FROM munkafajta
          //               WHERE project_id ='$pid' AND Mennyiseg IS NULL");
          // echo "<option value='0' selected></option>";
          // while ($row4 = $csoport->fetch_assoc()){ ?>
          <!--    <option value="<?=$row4['Id'] ?> " > <?=$row4['Megnevezes'] ?></option> -->
             <?php
          // }
          // echo "</select>";
          //Csoport: <input type='text' name='csoport'>

          $pid = $_SESSION['projektId'];
          $csoport = mysqli_query($conn,"SELECT * FROM munkafajta
                        WHERE project_id ='$pid' AND Mennyiseg IS NULL");

          echo "<p>Új adat felvétele</p>
          <form action='includes/addtomunkafajta.inc.php' method='post'>
            Megnevezés: <input type='text' name='name'>

            Csoport: <select name='csoport' id='csoport'>";
            echo "<option value='0' selected>nincs</option>";
            while ($row4 = $csoport->fetch_assoc()){ ?>
              <option value="<?=$row4['Id'] ?> " > <?=$row4['Megnevezes'] ?></option>
              <?php
            }
            echo "</select>

            Cím: <input type='checkbox' name='cim' value='pipa'>
            <input type='submit'>
          </form>";

          $mernokmido=0;
          $muszereszmido=0;

          echo "<div align= \"center\" id=\"nyomtatas\">";
            echo "<table id='Munkadijkalkulacio'>";
            echo "<tr class='fejlec'>";
            echo "<th>Id</th><th>Munkavégző</th><th>Megnevezés</th><th>Mértékegység</th><th>Mennyiség</th><th>Egységár</th><th>Ár:</th>";

            $query="SELECT * FROM munkafajta WHERE parent_id IS NULL AND project_id = '$pid'";
            $parents=mysqli_query($conn,$query);
            $totalrows = mysqli_num_rows($parents);
            if ($totalrows > 1) {
              $i=1;
            }else {
              $i=0;
            }

            while ($row=mysqli_fetch_array($parents))
            { ?>
              <tr id="<?php echo $row['Id']; ?>">
              <?php
              $sorid=$row['Id'];
              echo "<td>".$row['Id']."</td>";
              echo "<td></td>";
              echo "<td><b>".$row['Megnevezes']."</b></td>";
              echo "<td>".$row["ME"]."</td>";
              echo "<td>".$row["Mennyiseg"]."</td>";
              echo "<td></td><td></td>"; ?>
              <td id='del'><span class='deletemd' data-id='<?= $sorid; ?>'>Törlés</span></td>
              <?php echo "</tr>";
              $arresz = show_children($row['Id'], $i);
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
          echo '<p>You are logged out!</p>';
        }
        ?>
      </div>
    </main>
  </div>
</div>


<?php
function show_children($parentID, $i, $depth=1){
  require 'includes/dbh.inc.php';

  global $mernokmido,$muszereszmido;

  $children = mysqli_query($conn,"SELECT * FROM munkafajta WHERE parent_id=$parentID");
  /*$munkadij = mysqli_query($conn,"SELECT Oraber FROM munkadij
                INNER JOIN munkafajta
                ON munkadij.MunkaFajta = munkafajta.Megnevezes
                WHERE munkafajta.Id ='$parentID'");
  $row2=mysqli_fetch_array($munkadij);*/
  $totalrows = mysqli_num_rows($children);
  if ($totalrows > 1) {
    $i=1;
  }else {
    $i=0;
  }

  while ($row = mysqli_fetch_array($children)){
    $sorid=$row['Id'];?>
    <tr id="<?php echo $row['Id']; ?>">
    <?php if ($row['Mennyiseg']==NULL) {
      echo "<td>".$row['Id']."</td>";
      echo "<td></td><td><b>".$row['Megnevezes']."</b></td>";
      //.str_repeat("&nbsp;", $depth * 5).
      echo "<td></td><td></td><td></td><td></td>";?>
      <td id='del'><span class='deletemd' data-id='<?= $sorid; ?>'>Törlés</span></td>
      <?php echo "</tr>";
      $arresz = show_children($row['Id'], $i, $depth+1);
      $osszegar=$osszegar+$arresz;
      $osszegkiiras = 1;
    }
    else {
      $munkadij = mysqli_query($conn,"SELECT * FROM munkadij
                    INNER JOIN munkafajta
                    ON munkadij.Id = munkafajta.munkadij_id
                    WHERE munkafajta.Id ='$sorid'");
      $row2=mysqli_fetch_array($munkadij);

      echo "<td>".$row['Id']."</td>";
      echo "<td id='mv'><select name='munkavegzo' id='munkavegzo'>";
      $mf = mysqli_query($conn, "SELECT * FROM munkadij");
      while ($row5 = $mf->fetch_assoc()){ ?>
        <option value="<?=$row5['Id'] ?> " <?=$row5['Id'] == $row['munkadij_id'] ? ' selected="selected"' : '';?>> <?=$row5['MunkaFajta'] ?></option>
        <?php

       }
      echo "</select></td>";
      if ($row2['MunkaFajta']=='Mérnök') {
        $mernokmido=$mernokmido+$row['Mennyiseg'];
      }
      elseif ($row2['MunkaFajta']=='Műszerész'){
        $muszereszmido=$muszereszmido+$row['Mennyiseg'];
      }
      echo "<td>".$row['Megnevezes']."</td>";
      //.str_repeat("&nbsp;", $depth * 5).
      echo "<td>".$row["ME"]."</td>";
      echo "<td>".$row["Mennyiseg"]."</td>";
      echo "<td>".$row2["Oraber"]." Ft</td>";
      if ($row['Mennyiseg']!=NULL) {
        $sorar=$row['Mennyiseg']*$row2['Oraber'];
        echo "<td>".$sorar." Ft</td>";
        $osszegar=$osszegar+$sorar;
      }
      else {
        echo "<td></td>";
      }?>
      <td id='del'><span class='deletemd' data-id='<?= $sorid; ?>'>Törlés</span></td>
      <?php echo "</tr>";
      $osszegkiiras = 0;
    }
  }
  if ($osszegkiiras == 0 && $i == 1) {
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
    url: 'includes/mdlive_edit.inc.php',
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

<?php
    require "footer.php";
?>
