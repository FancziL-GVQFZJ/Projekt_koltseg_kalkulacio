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
            <li><a <?php echo ($thisPage1 == 'SAP') ? ' class="selected"' : ''; ?> href="sap_anyaglista.php">SAP alkatrész</a></li>
            <li><a <?php echo ($thisPage1 == 'Helyi') ? ' class="selected"' : ''; ?> href="helyi_anyaglista.php">Helyi alkatrész</a></li>
            <li><a <?php echo ($thisPage1 == 'Projekt') ? ' class="selected"' : ''; ?> href="projekt_anyaglista.php">Listázott anyagok</a></li>
            <li><a <?php echo ($thisPage1 == 'Osszehas') ? ' class="selected"' : ''; ?> href="osszehasonlitas.php">Összehasonlítás</a></li>
          </ul>
        <?php } ?>
      </div>
    </nav>
  </header>
