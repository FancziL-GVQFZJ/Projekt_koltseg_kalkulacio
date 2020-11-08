<?php
  session_start();
  require "header.php";
 ?>
 <style><?php include 'css/navbar.css';?></style>
  <header>
    <nav class="topnav">
      <div>
        <?php if (isset($_SESSION['userId'])) { ?>
          <ul>
            <?php if ($jogosultsag == 'iras' || $jogosultsag == 'admin'){ ?>
              <li><a <?php echo ($thisPage1 == 'Munka') ? ' class="selected"' : ''; ?> href="projektmunkadij.php">Projekt munkadíj</a></li>
              <li><a <?php echo ($thisPage1 == 'Anyag') ? ' class="selected"' : ''; ?> href="anyagkoltseg.php">Anyagköltség</a></li>
              <li><a <?php echo ($thisPage1 == 'Munkadij') ? ' class="selected"' : ''; ?> href="munkadijkoltseg.php">Munkadíj költség</a></li>
              <li><a <?php echo ($thisPage1 == 'Egyeb') ? ' class="selected"' : ''; ?> href="egyebkoltseg.php">Egyéb költség</a></li>
              <li><a <?php echo ($thisPage1 == 'Muszaki') ? ' class="selected"' : ''; ?> href="muszakitartalom.php">Műszaki tartalom</a></li>
            <?php } ?>
            <li><a <?php echo ($thisPage1 == 'Kalkulacio') ? ' class="selected"' : ''; ?> href="kalkulacioslap.php">Kalkulációs lap</a></li>
            <li><a <?php echo ($thisPage1 == 'Nyomtatas') ? ' class="selected"' : ''; ?> href="nyomtatasilap.php">Nyomtatási lap</a></li>
          </ul>
        <?php } ?>
      </div>
    </nav>
  </header>
