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
                <a href="sap_anyaglista.php">Villamos anyaglista</a>
                <a href="helyi_anyaglista.php">Belső villamos anyaglista</a>
                <a href="projekt_anyaglista.php">Listázott anyagok</a>
                <a style="background-color: #ddd;" href="#">Összehasonlítás</a>
            </nav>

            <form method="post">
             <input type="submit" value="Frissítés" name="frissitesgomb">
            </form>';

            if (isset($_POST['frissitesgomb'])) {
              $stmt = $conn->prepare("DELETE FROM sap_osszehasonlitas");
              $successfullyCopied = $stmt->execute();

              if ($successfullyCopied) {
                $stmt2 = $conn->prepare("INSERT INTO sap_osszehasonlitas (SELECT * FROM sap_anyaglista)");
                $successfullyCopied2 = $stmt2->execute();

                if ($successfullyCopied2) {
                  echo '<script type="text/javascript"> alert("Sikeres frissítés");</script>';
                }
                else {
                  echo '<script type="text/javascript"> alert("Sikertelen frissítés");</script>';
                }
              }
              else {
                echo '<script type="text/javascript"> alert("Sikertelen frissítés");</script>';
              }
            }

            $sql1="SELECT  'sap_anyaglista' AS `set`, a.*
                  FROM    sap_anyaglista a
                  WHERE   ROW(a.sap_anyaglista_id, a.sap_anyaglista_megnevezes, a.sap_anyaglista_sapszam,
                          a.sap_anyaglista_mertekegyseg, a.sap_anyaglista_egysegar) NOT IN
                          (
                          SELECT  sap_osszehasonlitas_id, sap_osszehasonlitas_megnevezes, sap_osszehasonlitas_sapszam,
                          sap_osszehasonlitas_mertekegyseg, sap_osszehasonlitas_egysegar
                          FROM    sap_osszehasonlitas
                        )";
                  //UNION ALL
            $sql2="SELECT  'sap_osszehasonlitas' AS `set`, o.*
                  FROM    sap_osszehasonlitas o
                  WHERE   ROW(o.sap_osszehasonlitas_id, o.sap_osszehasonlitas_megnevezes, o.sap_osszehasonlitas_sapszam,
                          o.sap_osszehasonlitas_mertekegyseg, o.sap_osszehasonlitas_egysegar) NOT IN
                          (
                          SELECT  sap_anyaglista_id, sap_anyaglista_megnevezes, sap_anyaglista_sapszam,
                          sap_anyaglista_mertekegyseg, sap_anyaglista_egysegar
                          FROM    sap_anyaglista
                          )";

            $sor1=mysqli_query($conn, $sql1);
            $sor2=mysqli_query($conn, $sql2);
            echo "Sap anyaglistában van";
            echo "<table id='AnyaglistaTable'>";
            echo "<tr class='fejlec'>";
            echo "<th>id</th><th>Megnevezés</th><th>SAPSzám</th><th>Mérték egység</th><th>Egységár</th>";
            while ($row=mysqli_fetch_array($sor1))
            {
              $sorid=$row['sap_anyaglista_id'];
              echo "<tr>";
              echo "<td>".$row['sap_anyaglista_id']."</td>";
              echo "<td>".$row['sap_anyaglista_megnevezes']."</td>";
              echo "<td>".$row['sap_anyaglista_sapszam']."</td>";
              echo "<td>".$row['sap_anyaglista_mertekegyseg']."</td>";
              echo "<td>".$row['sap_anyaglista_egysegar']."</td>";
              echo "</tr>";
            }
            echo "</table>";

            echo "Sap anyaglistában volt";
            echo "<table id='AnyaglistaTable'>";
            echo "<tr class='fejlec'>";
            echo "<th>id</th><th>Megnevezés</th><th>SAPSzám</th><th>Mérték egység</th><th>Egységár</th>";
            while ($row=mysqli_fetch_array($sor2))
            {
              $sorid=$row['sap_osszehasonlitas_id'];
              echo "<tr>";
              echo "<td>".$row['sap_osszehasonlitas_id']."</td>";
              echo "<td>".$row['sap_osszehasonlitas_megnevezes']."</td>";
              echo "<td>".$row['sap_osszehasonlitas_sapszam']."</td>";
              echo "<td>".$row['sap_osszehasonlitas_mertekegyseg']."</td>";
              echo "<td>".$row['sap_osszehasonlitas_egysegar']."</td>";
              echo "</tr>";
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
