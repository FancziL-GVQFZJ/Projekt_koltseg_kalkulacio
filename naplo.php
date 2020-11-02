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
      require 'includes/kapcsolat.inc.php';
        if (isset($_SESSION['userId'])) {
          echo '<nav class="topnav">
            <ul>
              <li><a href="index.php">Projektek</a></li>
              <li><a href="jogosultsagok.php">Jogosultságok</a></li>
              <li><a href="munkadijak.php">Munkadíjak</a></li>
              <li><a style="background-color: #ddd;" href="#">Naplo</a></li>
            </ul>
          </nav>';

          echo "<input type='date' name='dateFrom' value='date('Y-m-d')'/>";

          echo '<input type="text" id="myInput" onkeyup="myFunction1()" placeholder="Keresés..">';

          $sql="SELECT * FROM naplo ORDER BY naplo_datum desc ";

          $sor=mysqli_query($conn, $sql);

          echo "<table id='AnyaglistaTable'>";
          echo "<tr class='fejlec'>";
          echo "<th>Dátum</th><th>Adat</th>";
          while ($row=mysqli_fetch_array($sor))
          {
            $sorid=$row['id'];
            echo "<tr>";
            echo "<td>".$row['naplo_datum']."</td>";
            echo "<td>".$row['naplo_esemeny']."</td>";
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
