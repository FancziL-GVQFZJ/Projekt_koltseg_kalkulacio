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
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId'])) {

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

          <p>Nyomtatási adatok</p>
          <form action='includes/nyomtatas.inc.php' method='post'><br>
          Tárgy: <input type='text' name='name'><br>
          <input type='checkbox' name='Anyaglista' value='1'>Anyaglista<br>
          <input type='checkbox' name='Munkadíj' value='2'>Munkadíj költség<br>
          <input type='checkbox' name='Egyéb' value='3'>Egyéb költség<br>
          <input type='checkbox' name='Műszaki' value='4'>Műszaki tartalom<br>
          <input type='submit' value='Nyomtatás'  onclick="$('form').attr('target', '_blank');">
          </form>

          <?php

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
