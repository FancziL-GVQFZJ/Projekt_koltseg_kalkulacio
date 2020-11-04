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
            <li><a <?php echo ($thisPage1 == 'Projektek') ? ' class="selected"' : ''; ?> href="index.php">Projektek</a></li>
            <li><a <?php echo ($thisPage1 == 'Jogosultsagok') ? ' class="selected"' : ''; ?> href="jogosultsagok.php">Jogosultságok</a></li>
            <li><a <?php echo ($thisPage1 == 'Munkadijak') ? ' class="selected"' : ''; ?> href="munkadijak.php">Munkadíjak</a></li>
            <li><a <?php echo ($thisPage1 == 'Naplo') ? ' class="selected"' : ''; ?> href="naplo.php">Napló</a></li>
          </ul>
        <?php } ?>
      </div>
    </nav>
  </header>
