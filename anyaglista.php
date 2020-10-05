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
                <a href="belsoanyaglista.php">Belső villamos anyaglista</a>
                <a href="projekt_anyaglista.php">Listázott anyagok</a>
                <a href="osszehasonlitas.php">Összehasonlítás</a>
            </nav>
            
            <p>A teljes alkatrész lista</p>

            <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Keresés..">';

            $sql="SELECT * FROM alkatresz";
            $sor=mysqli_query($conn, $sql);

            echo "<table id='AnyaglistaTable'>";
            echo "<tr class='fejlec'>";
            for($i=0;$i<5;$i++)
            {
              echo "<td>".mysqli_fetch_field_direct($sor, $i)->name."</td>";
            }
            while ($row=mysqli_fetch_array($sor))
            {
              $sorid=$row['id'];
              echo "<tr>";
              echo "<td>".$row['id']."</td>";
              echo "<td>".$row['Megnevezes']."</td>";
              echo "<td>".$row['SAPSzam']."</td>";
              echo "<td>".$row['ME']."</td>";
              echo "<td>".$row['Egysegar']."</td>";?>
              <td id='add'><span class='addtocart' data-id='<?= $sorid; ?>'>Kiválasztás</span></td>
              <?php echo "</tr>";
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
