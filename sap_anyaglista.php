<?php
  $thisPage='Anyaglista';
  require "header.php";
  session_start();
?>
<style><?php include 'css/navbar.css';?></style>
<style><?php include 'css/table.css';?></style>

<div id="container">
  <div id="main">
    <main>
      <?php
        require 'includes/dbh.inc.php';
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId']) && ($jogosultsag == 'iras' || $jogosultsag == 'admin')) {

            echo '<nav class="topnav">
                <a style="background-color: #ddd;" href="#">Villamos anyaglista</a>
                <a href="helyi_anyaglista.php">Belső villamos anyaglista</a>
                <a href="projekt_anyaglista.php">Listázott anyagok</a>
                <a href="osszehasonlitas.php">Összehasonlítás</a>
            </nav>
            <p>A teljes alkatrész lista</p>

            <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Keresés..">';

            $sql="SELECT * FROM sap_anyaglista";
            $sor=mysqli_query($conn, $sql);

            echo "<table id='AnyaglistaTable'>";
            echo "<tr class='fejlec'>";
            echo "<th>id</th><th>Megnevezés</th><th>SAPSzám</th><th>Mérték egység</th><th>Egységár</th><th></th>";
            while ($row=mysqli_fetch_array($sor))
            {
              $sorid=$row['sap_anyaglista_id'];
              echo "<tr>";
              echo "<td>".$row['sap_anyaglista_id']."</td>";
              echo "<td>".$row['sap_anyaglista_megnevezes']."</td>";
              echo "<td>".$row['sap_anyaglista_sapszam']."</td>";
              echo "<td>".$row['sap_anyaglista_mertekegyseg']."</td>";
              echo "<td>".$row['sap_anyaglista_egysegar']."</td>";?>
              <td id='add'><span class='addtocart' data-id='<?= $sorid; ?>'>Kiválasztás</span></td>
              <?php echo "</tr>";
            }
            echo "</table>";
        }
        else {
          echo '<p>Jelenleg ki van jelentkezve!</p>';
        }
      ?>
    </main>
  </div>
</div>

<?php
    require "footer.php";
?>
