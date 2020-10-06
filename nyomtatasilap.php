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
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId']) && ($jogosultsag == 'iras' || $jogosultsag == 'admin')) {

          $pid = $_SESSION['projektId'];
          $result = mysqli_query($conn,"SELECT * FROM muszakitartalom where projekt_id='$pid'");
          $row = mysqli_fetch_array($result);
          $leiras=$row['tartalom'];?>

          <nav class="topnav">
            <ul>
              <li><a href="munkadijkalkulacio.php">Munkadíj költség</a></li>
              <li><a href="egyebkoltseg.php">Egyéb költség</a></li>
              <li><a href="muszakitartalom.php">Műszaki tartalom</a></li>
              <li><a href="kalkulacioslap.php">Kalkulációs Lap</a></li>
              <li><a style="background-color: #ddd;" href="#">Nyomtatási Lap</a></li>
            </ul>
          </nav>

          <!-- <a href="#" class="btn btn-warning" id="print" onclick="printContent('nyomtatas')">Nyomtatás PDF-ben</a> -->

          <?php

          echo "<p>Nyomtatási adatok</p>
          <form action='includes/nyomtatas.inc.php' method='post'><br>
          Tárgy: <input type='text' name='name'><br>
          <input type='checkbox' name='cim' value='value1'>Anyaglista<br>
          <input type='checkbox' name='cim' value='value1'>Munkadíj költség<br>
          <input type='checkbox' name='cim' value='value1'>Egyéb költség<br>
          <input type='checkbox' name='cim' value='value1'>Műszaki tartalom<br>
          <input type='submit' value='Nyomtatás'>
          </form>";



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
    require "footer.php";
?>
