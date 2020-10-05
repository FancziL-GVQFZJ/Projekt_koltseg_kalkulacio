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
              <li><a href="munkadijkalkulacio.php">Munkadíj költség</a></li>
              <li><a style="background-color: #ddd;" href="#">Egyéb költség</a></li>
              <li><a href="muszakitartalom.php">Műszaki tartalom</a></li>
              <li><a href="kalkulacioslap.php">Kalkulációs Lap</a></li>
              <li><a href="nyomtatasilap.php">Nyomtatási Lap</a></li>
            </ul>
          </nav>

          <p>Új adat felvétele</p>
          <form action='includes/addtoegyebkoltseg.inc.php' method='post'>
          Megnevezés: <input type='text' name='name'>
          Szülő: <input type='text' name='szulo'>
          Cím: <input type='checkbox' name='cim' value='value1'>
          <input type='submit'>
          </form>

          <div align= "center" id="nyomtatas">
            <table id='Egyebkoltsegkalkulacio'>
            <tr class='fejlec'>
            <th>Id</th><th>Munkavégző</th><th>Megnevezés</th><th>Mértékegység</th><th>Mennyiség</th><th>Órabér</th><th>Ár:</th>

            <?php
            $pid = $_SESSION['projektId'];
            $query="SELECT * FROM egyebkoltseg WHERE parent_id IS NULL AND project_id = '$pid'";
            $parents=mysqli_query($conn,$query);

            while ($row=mysqli_fetch_array($parents))
            {?>

              <tr id="<?php echo $row['Id']; ?>">
              <?php echo "<td>".$row['Id']."</td>";
              echo  "<td></td>";
              echo "<td><b>".$row['Megnevezes']."</b></td>";
              echo "<td>".$row["ME"]."</td>";
              echo "<td>".$row["Mennyiseg"]."</td>";
              echo "<td></td><td></td>";
              $sorid=$row['Id'];?>
              <td id='del'><span class='deleteek' data-id='<?= $sorid; ?>'>Törlés</span></td>
              </tr>
              <?php
              $arresz = show_children($row['Id']);
              $teljesar=$teljesar+$arresz;
            }
            echo  "<tr>";
            echo  "<td></td>";
            echo  "<td colspan='5' align='right'>Teljes ár:</td>";
            echo  "<td align='left'>$teljesar</td>";
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

function show_children($parentID, $depth=1){
  require 'includes/dbh.inc.php';
  $children = mysqli_query($conn,"SELECT * FROM egyebkoltseg WHERE parent_id=$parentID");
  /*$munkadij = mysqli_query($conn,"SELECT Oraber FROM munkadij
                INNER JOIN egyebkoltseg
                ON munkadij.MunkaFajta = egyebkoltseg.Megnevezes
                WHERE egyebkoltseg.Id ='$parentID'");
  $row2=mysqli_fetch_array($munkadij);*/

  while ($row = mysqli_fetch_array($children)){
    $sorid=$row['Id'];?>
    <tr id="<?php echo $row['Id']; ?>">
    <?php if ($row['Mennyiseg']==NULL) {
      echo "<td>".$row['Id']."</td>";
      echo "<td><b>".str_repeat("&nbsp;", $depth * 5).$row['Megnevezes']."</b></td>";
      echo "<td></td><td></td><td></td><td></td>";?>
      <td id='del'><span class='deleteek' data-id='<?= $sorid; ?>'>Törlés</span></td>
      <?php echo "</tr>";
      $arresz = show_children($row['Id'], $depth+1);
      $szintar=$szintar+$arresz;
      $osszegkiiras = 1;
    }
    else {
      $munkadij = mysqli_query($conn,"SELECT Oraber FROM munkadij
                    INNER JOIN egyebkoltseg
                    ON munkadij.Id = egyebkoltseg.munkadij_id
                    WHERE egyebkoltseg.Id ='$sorid'");
      $row2=mysqli_fetch_array($munkadij);
      echo "<td>".$row['Id']."</td>";
      echo "<td id='mv'><select name='munkavegzo' id='munkavegzo'>";
      $mf = mysqli_query($conn, "SELECT * FROM munkadij");

      while ($row5 = $mf->fetch_assoc()){ ?>
        <option value="<?=$row5['Id'] ?> " <?=$row5['Id'] == $row['munkadij_id'] ? ' selected="selected"' : '';?>> <?=$row5['MunkaFajta'] ?></option>
        <?php
      }
      echo "</select></td>";
      echo "<td>".str_repeat("&nbsp;", $depth * 5).$row['Megnevezes']."</td>";
      echo "<td>".$row["ME"]."</td>";
      echo "<td>".$row["Mennyiseg"]."</td>";
      echo "<td>".$row2["Oraber"]."</td>";
      if ($row['Mennyiseg']!=NULL) {
        $sorar=$row['Mennyiseg']*$row2['Oraber'];
        echo "<td>".$sorar."</td>";
        $szintar=$szintar+$sorar;
      }
      else {
        echo "<td></td>";
      }?>
      <td id='del'><span class='deleteek' data-id='<?= $sorid; ?>'>Törlés</span></td>
      <?php echo "</tr>";
      $osszegkiiras = 0;
    }
  }
  if ($osszegkiiras == 0) {
    echo  "<tr>";
    echo  "<td></td>";
    echo  "<td colspan='5' align='right'>Összegzett ár:</td>";
    echo  "<td align='left'>$szintar</td>";
    echo  "</tr>";
  }
  return $szintar;
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
    url: 'includes/eklive_edit.inc.php',
    onAlways: function() {location.reload()}
  });
  //Location.reload();
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
          setTimeout(function()
              {
                  location.reload();
              }, 0001);
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
