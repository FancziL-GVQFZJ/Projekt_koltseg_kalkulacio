<?php
  $thisPage1='Projektek';
  $thisPage='Kezdooldal';
  require "kezdolapheader.php";
  session_start();
?>

<style><?php include 'css/table.css';?></style>

<?php if (isset($_SESSION['userId'])) { ?>
<div id="container">
  <div id="main">
    <main>
      <?php
          require 'includes/kapcsolat.inc.php';
          echo "<div class='kezdolap'>";
            echo "<div class='lap'>";
              ?>
              <br>
              <form name="form1" action="includes/databaseinsert/newprojekt.inc.php" method="post">
                <input type="hidden" name="projektnev" value="">
                <button class="button" type="submit" name="newprojekt" onclick="projektNevMegadas()">Új projekt létrehozása</button>
              </form>
              <br>
              <?php
              echo '<p class="szoveg" >Projektjeid:</p>';

              $fid = $_SESSION['userId'];
              $sql=mysqli_query($conn,"SELECT * FROM projekt
                           INNER JOIN pf_kapcsolat
                             ON projekt.projekt_id = pf_kapcsolat.projekt_id
                           INNER JOIN felhasznalo
                             ON pf_kapcsolat.felhasznalo_id = felhasznalo.felhasznalo_id
                             WHERE felhasznalo.felhasznalo_id = '$fid'");

              echo "<table class='table-style' id='ProjektTable'>";
              echo "<th></th><th></th><th>Projektnév</th>";
              echo "<tr>";
              $jpid=$_SESSION['projektId'];
              $i=1;
              while ($row=mysqli_fetch_array($sql))
              {
                $pid=$row['projekt_id'];
                $pnev=$row['projekt_nev']; ?>
                <tr id="<?php echo $row['projekt_id']; ?>">
                <?php
                echo "<td>".$row['projekt_id']."</td>";
                echo "<td>".$i."</td>";
                echo "<td>".$row['projekt_nev']."</td>";
                if ($pid != $jpid) {
                  ?><td id='add'><span class='startprojekt' data-id='<?= $pid; ?>'>Kiválasztás</span></td>
                  <td id='del'><span class='deleteprojekt' data-id='<?= $pid; ?>'>Törlés</span></td> <?php
                }
                else { ?>
                  <td id='stop'><span class='stopprojekt' data-id='<?= $pid; ?>'>Befejezés</span></td>
                <?php }
                $i++;
                echo "</tr>";
              }
              echo "</table>";
            echo "</div>";

            echo "<div class='lap'>";
              echo "<br><br><br>";
              echo '<p class="szoveg" >Megosztott projektek:</p>';

              $sql=mysqli_query($conn,"SELECT * FROM projekt
                        INNER JOIN jogosultsag
                          ON projekt.projekt_id = jogosultsag.projekt_id
                          WHERE jogosultsag.felhasznalo_id = '$fid'");


              echo "<table class='table-style'>";
              echo "<th>Tulaj</th><th>Jog</th><th>Projektnév</th>";
              echo "<tr>";
              while ($row=mysqli_fetch_array($sql))
              {
                $pid=$row['projekt_id'];

                if ($row['jogosultsag_iras'] == 1 ) {
                  $jogosultsag = "iras";
                }
                elseif ($row['jogosultsag_iras'] == 0 && $row['jogosultsag_olvasas'] == 1) {
                  $jogosultsag = "olvasas";
                }

                $sql1=mysqli_query($conn,"SELECT * FROM felhasznalo
                        INNER JOIN pf_kapcsolat
                          ON felhasznalo.felhasznalo_id = pf_kapcsolat.felhasznalo_id
                        INNER JOIN projekt
                          ON pf_kapcsolat.projekt_id = projekt.projekt_id
                            WHERE projekt.projekt_id = '$pid'");
                $row1=mysqli_fetch_array($sql1);

                $pid = $row['projekt_id'];

                echo "<tr>";
                echo "<td>".$row1['felhasznalo_nev']."</td>";
                echo "<td>".$jogosultsag."</td>";
                echo "<td>".$row['projekt_nev']."</td>";
                if ($jpid!=$pid) {
                  ?><td id='add'><span class='startprojekt' data-id='<?= $pid; ?>'>Kiválasztás</span></td>
                  <td id='del'><span class='removeprojekt' data-id='<?= $pid; ?>'>Törlés</span></td> <?php
                }
                else { ?>
                  <td id='stop'><span class='stopprojekt' data-id='<?= $pid; ?>'>Befejezés</span></td>
                <?php }
                echo "</tr>";
              }
              echo "</table>";
            echo "</div>";
          echo "</div>";
      ?>
    </main>
  </div>
</div>
<script type="text/javascript" src="/Projekt_koltseg_kalkulacio/js/jquery.tabledit.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('#ProjektTable').Tabledit({
    deleteButton: false,
    editButton: false,
    columns: {
    identifier: [0, 'projekt_id'],
    editable: [[2, 'projekt_nev']]
  },
  hideIdentifier: true,
  url: 'includes/tableedit/pnlive_edit.inc.php',
  onAlways: function() {location.reload()}
});
});
</script>
<?php }
else {
  //echo '<p>Jelenleg ki van jelentkezve!</p>';
} ?>

<script type="text/javascript">
function projektNevMegadas(){
  var name = prompt("Add meg a nevet:");
    if ((name != null) && (name != " "))
    {
        document.form1.projektnev.value = name;
    }
}
</script>



<?php
    require "footer.php";
?>
