<?php
  session_start();
 ?>
<!DOCTYPE html>
<html>
    <head>
        <link href="css/navbar.css" rel="stylesheet" type="text/css">
        <meta charset="utf-8">
        <meta name="description" content="This is an example for meta description. This will often show up in the search results.">
        <meta name=viewport content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="/Projekt_koltseg_kalkulacio/js/delfromcart.js"></script>
        <script src="/Projekt_koltseg_kalkulacio/js/delfrommd.js"></script>
        <script src="/Projekt_koltseg_kalkulacio/js/delfrommdij.js"></script>
        <script src="/Projekt_koltseg_kalkulacio/js/delfromek.js"></script>
        <script src="/Projekt_koltseg_kalkulacio/js/addtocart.js"></script>
        <script src="/Projekt_koltseg_kalkulacio/js/startprojekt.js"></script>
        <script src="/Projekt_koltseg_kalkulacio/js/deleteprojekt.js"></script>
        <script src="/Projekt_koltseg_kalkulacio/js/filter_table.js"></script>
        <script src="/Projekt_koltseg_kalkulacio/js/jquery.tabledit.js"></script>
        <script src="/Projekt_koltseg_kalkulacio/js/jquery-3.5.1.js"></script>
        <script src="/Projekt_koltseg_kalkulacio/js/addmunka.js"></script>
        <script src="/Projekt_koltseg_kalkulacio/ckeditor/ckeditor.js"></script>
        <script src="/Projekt_koltseg_kalkulacio/ckeditor/ckeditor.js"></script>
        <title>Title</title>
        <meta charset="UTF-8">
    </head>
<body>

  <header>
    <nav class="topnav">
      <a href="#">
        <img src="img/vmlogo.png" alt="logo" class="logo">
      </a>

      <div>
        <?php
        if (isset($_SESSION['userId'])) {
          require 'includes/dbh.inc.php';
          $fid = $_SESSION['userId'];
          $fnev = $_SESSION['userUid'];
          $pid = $_SESSION['projektId'];
          $jogosultsag = $_SESSION['jogosultsag']; ?>

          <ul>
          <li><a <?php echo ($thisPage == 'Kezdooldal') ? ' class="selected"' : ''; ?> href="index.php">Kezdőlap</a></li>
          <?php if (isset($_SESSION['projektId']) && ($jogosultsag == 'iras' || $jogosultsag == 'admin')) { ?>
            <li><a <?php echo ($thisPage == 'Anyaglista') ? ' class="selected"' : ''; ?> href="anyaglista.php">Villamos anyaglista</a></li>
            <li><a <?php echo ($thisPage == 'Kalkulacioslap') ? ' class="selected"' : ''; ?> href="kalkulacioslap.php">Kalkulációs lap</a></li>
          <?php } elseif (isset($_SESSION['projektId']) && $jogosultsag == 'olvasas') { ?>
            <li><a <?php echo ($thisPage == 'Kalkulacioslap') ? ' class="selected"' : ''; ?> href="kalkulacioslap.php">Kalkulációs lap</a></li>
          <?php } ?>
          </ul>

          <?php echo '<div class="logout-container">
            <form action="includes/logout.inc.php" method="post">';
              if (isset($_SESSION['projektId'])) {
              $projektneve = $_SESSION['projektNeve'];
              echo '<p>Jelenlegi projekt: '.$projektneve.'('.$jogosultsag.')</p>';
              }
            echo '<p>Jelenlegi felhasználó: '.$fnev.'</p>
                    <button type="submit" name="logout-submit">Kijelentkezés</button>
                    </form>
          </div>';

        }else {
          echo '<div class="login-container">
                    <form action="includes/login.inc.php" method="post">
                    <input type="text" name="mailuid" placeholder="Felhasználónév...">
                    <input type="password" name="pwd" placeholder="Jelszó...">
                    <button type="submit" name="login-submit">Bejelentkezés</button>
                    </form>
                </div>';
        }
        ?>
      </div>
    </nav>
  </header>
