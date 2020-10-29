<?php
  $thisPage='Kezdooldal';
  require "header.php";
  session_start();
?>
<style><?php include 'css/navbar.css';?></style>
<style><?php include 'css/table.css';?></style>

<div id="container">
  <div id="main">
    <main>
      <?php
        if (isset($_SESSION['userId'])) {
          require 'includes/dbh.inc.php';
          $fid = $_SESSION['userId'];

          echo '<nav class="topnav">
            <ul>
              <li><a style="background-color: #ddd;" href="#">Projektek</a></li>
              <li><a href="jogosultsagok.php">Jogosultságok</a></li>
              <li><a href="munkadijak.php">Munkadíjak</a></li>
              <li><a href="naplo.php">Naplo</a></li>
            </ul>
          </nav>';

          echo "<div class='kezdolap'>";
            echo "<div class='lap'>";
              echo '<p>Projektjeid:</p>';

              $sql=mysqli_query($conn,"SELECT * FROM projekt
                   INNER JOIN pf_kapcsolat
                     ON projekt.projekt_id = pf_kapcsolat.projekt_id
                   INNER JOIN felhasznalo
                     ON pf_kapcsolat.felhasznalo_id = felhasznalo.felhasznalo_id
                     WHERE felhasznalo.felhasznalo_id = $fid");

              echo "<table id='AnyaglistaTable'>";
              echo "<th>Id</th><th>Nev</th>";
              echo "<tr>";
              while ($row=mysqli_fetch_array($sql))
              {
                $pid=$row['projekt_id'];
                $pnev=$row['projekt_nev'];
                echo "<tr>";
                echo "<td>".$row['projekt_id']."</td>";
                echo "<td>".$row['projekt_nev']."</td>"; ?>
                <td id='add'><span class='startprojekt' data-id='<?= $pid; ?>'>Indítás</span></td>
                <td id='del'><span class='deleteprojekt' data-id='<?= $pid; ?>'>Törlés</span></td> <?php
                echo "</tr>";
              }
              echo "</table>"; ?>
              <form name="form1" action="includes/newprojekt.inc.php" method="post">
              <input type="hidden" name="projektnev" value="">
              <button type="submit" name="newprojekt" onclick="projektNevMegadas()">Új projekt létrehozása</button>
              </form> <?php
            echo "</div>";

            echo "<div class='lap'>";
              echo '<p>Veled megosztott projektek:</p>';

              $sql=mysqli_query($conn,"SELECT * FROM projekt
                        INNER JOIN jogosultsag
                          ON projekt.projekt_id = jogosultsag.projekt_id
                          WHERE jogosultsag.felhasznalo_id = '$fid'");


              echo "<table id='AnyaglistaTable'>";
              echo "<th>Tulajdonos</th><th>Jogosultságom</th><th>Projektnév</th>";
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

                echo "<tr>";
                echo "<td>".$row1['felhasznalo_nev']."</td>";
                echo "<td>".$jogosultsag."</td>";
                echo "<td>".$row['projekt_nev']."</td>"; ?>
                <td id='add'><span class='startprojekt' data-id='<?= $pid; ?>'>Indítás</span></td> <?php
                echo "</tr>";
              }
              echo "</table>";
            echo "</div>";
          echo "</div>";
        }
        else {
          echo '<p>Jelenleg ki van jelentkezve!</p>';
        }
      ?>
    </main>
  </div>
</div>

<script type="text/javascript">
function projektNevMegadas(){
  var name = prompt("Add meg a nevet:");
    if ((name != null) && (name != ""))
    {
        document.form1.projektnev.value = name;
    }
}
</script>

<?php
    require "footer.php";
?>
