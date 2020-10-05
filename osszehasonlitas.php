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
                <a href="Anyaglista.php">Villamos anyaglista</a>
                <a href="belsoanyaglista.php">Belső villamos anyaglista</a>
                <a href="projekt_anyaglista.php">Listázott anyagok</a>
                <a style="background-color: #ddd;" href="#">Összehasonlítás</a>
            </nav>

            <form method="post">
             <input type="submit" value="Frissítés" name="frissitesgomb">
            </form>';

            if (isset($_POST['frissitesgomb'])) {
              $stmt = $conn->prepare("DELETE FROM ohalkatresz");
              $successfullyCopied = $stmt->execute();

              if ($successfullyCopied) {
                $stmt2 = $conn->prepare("INSERT INTO ohalkatresz (SELECT * FROM alkatresz)");
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

            $sql="SELECT  'alkatresz' AS `set`, a.*
                  FROM    alkatresz a
                  WHERE   ROW(a.id, a.Megnevezes, a.SAPSzam, a.ME, a.Egysegar) NOT IN
                          (
                          SELECT  Id, Megnevezes, SAPSzam, ME, Egysegar
                          FROM    ohalkatresz
                          )
                  UNION ALL
                  SELECT  'ohalkatresz' AS `set`, o.*
                  FROM    ohalkatresz o
                  WHERE   ROW(o.Id, o.Megnevezes, o.SAPSzam, o.ME, o.Egysegar) NOT IN
                          (
                          SELECT  id, Megnevezes, SAPSzam, ME, Egysegar
                          FROM    alkatresz
                          )";

            $sor=mysqli_query($conn, $sql);

            echo "<table id='AnyaglistaTable'>";
            echo "<tr class='fejlec'>";
            echo "<th>id</th><th>Megnevezés</th><th>SAPSzám</th><th>Mérték egység</th><th>Egységár</th>";
            while ($row=mysqli_fetch_array($sor))
            {
              $sorid=$row['id'];
              echo "<tr>";
              echo "<td>".$row['Id']."</td>";
              echo "<td>".$row['Megnevezes']."</td>";
              echo "<td>".$row['SAPSzam']."</td>";
              echo "<td>".$row['ME']."</td>";
              echo "<td>".$row['Egysegar']."</td>";
              echo "</tr>";
            }
            echo "</table>";
        }
        else {
          echo '<p>You are logged out!</p>';
        }
      ?>
    </main>
  </div>
</div>

<?php
    require "footer.php";
?>
