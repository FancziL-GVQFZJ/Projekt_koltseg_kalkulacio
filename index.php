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
          echo '<nav class="topnav">
              <a style="background-color: #ddd;" href="#">Projektek</a>
              <a href="jogosultsagok.php">Jogosultságok</a>
              <a href="naplo.php">Naplo</a>
          </nav>';

          echo "<div class='kezdolap'>";
            echo "<div class='lap'>";
              echo '<p>Projektjeid:</p>';

              $fid = $_SESSION['userId'];
              $sql = "SELECT idProjekt, projektNev FROM projekt
                    INNER JOIN pf_kapcsolat
                      ON projekt.idProjekt = pf_kapcsolat.projektId
                    INNER JOIN users
                      ON pf_kapcsolat.userId = users.idUsers
                      WHERE users.idUsers = $fid";

              $sor=mysqli_query($conn, $sql);

              echo "<table id='AnyaglistaTable'>";
              echo "<th>Id</th><th>Nev</th>";
              echo "<tr>";
              while ($row=mysqli_fetch_array($sor))
              {
                $pid=$row['idProjekt'];
                $pnev=$row['projektNev'];
                echo "<tr>";
                echo "<td>".$row['idProjekt']."</td>";
                echo "<td>".$row['projektNev']."</td>";
                ?>
                <!-- <form action="includes/projektstart.inc.php" method="post">
                <input type="hidden" name="projektid" value="<?php //echo htmlentities($pid); ?>">
                <input type="hidden" name="projektnev" value="<?php //echo htmlentities($pnev); ?>">
                <button type="submit" name="projektstart">Indítás</button>
                </form> -->
                <td id='add'><span class='startprojekt' data-id='<?= $pid; ?>'>Indítás</span></td>


                <!-- <form action="includes/projektdelete.inc.php" method="post">
                <input type="hidden" name="projektid" value="<?php //echo htmlentities($pid); ?>">
                <button type="submit" name="projektdel">Törlés</button>
                </form> -->
                <td id='del'><span class='deleteprojekt' data-id='<?= $pid; ?>'>Törlés</span></td>

                <?php
                echo "</tr>";
              }
              echo "</table>";
              ?>
              <form name="form1" action="includes/newprojekt.inc.php" method="post">
              <input type="hidden" name="projektnev" value="">
              <button type="submit" name="newprojekt" onclick="projektNevMegadas()">Új projekt létrehozása</button>
              </form>
              <?php
            echo "</div>";

            echo "<div class='lap'>";
              echo '<p>Veled megosztott projektek:</p>';

              $fid = $_SESSION['userId'];
              $sql = "SELECT idProjekt, projektNev FROM projekt
                    INNER JOIN jogosultsag
                      ON projekt.idProjekt = jogosultsag.projekt_id
                      WHERE jogosultsag.user_id = $fid";

              $sor=mysqli_query($conn, $sql);

              echo "<table id='AnyaglistaTable'>";
              echo "<th>Id</th><th>Nev</th>";
              echo "<tr>";
              while ($row=mysqli_fetch_array($sor))
              {
                $pid=$row['idProjekt'];
                $pnev=$row['projektNev'];
                echo "<tr>";
                echo "<td>".$row['idProjekt']."</td>";
                echo "<td>".$row['projektNev']."</td>";
                ?>
                <td id='add'><span class='startprojekt' data-id='<?= $pid; ?>'>Indítás</span></td>
                <!-- <form action="includes/projektstart.inc.php" method="post">
                <input type="hidden" name="projektid" value="<?php //echo htmlentities($pid); ?>">
                <input type="hidden" name="projektnev" value="<?php //echo htmlentities($pnev); ?>">
                <button type="submit" name="projektstart">Indítás</button>
                </form> -->
                <?php
                echo "</tr>";
              }
              echo "</table>";
            echo "</div>";
          echo "</div>";
        }
        else {
          echo '<p>You are logged out!</p>';
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
