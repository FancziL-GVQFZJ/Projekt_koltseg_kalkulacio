<?php
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <link href="css/navbar.css" rel="stylesheet" type="text/css">
    <meta charset="utf-8">
    <meta name="description" content="Projekt költség kalkulációs rendszer.">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/delfromcart.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/delfrommd.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/delfrommdij.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/delfrompmdij.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/delfromek.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/delfromak.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/delfromha.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/addtocart.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/addtohelyi.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/startprojekt.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/stopprojekt.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/deleteprojekt.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/removeprojekt.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/filter_table.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/filter_table1.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/jquery.tabledit.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/jquery-3.5.1.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/ckeditor/ckeditor.js"></script>
    <title>Projekt költség kalkulációs rendszer</title>
    <meta charset="UTF-8">
  </head>

<div class="border">
  <body>
    <?php
    if (isset($_SESSION['userId'])) {
      require 'includes/kapcsolat.inc.php';
      $fid = $_SESSION['userId'];
      $fnev = $_SESSION['userName'];
      $pid = $_SESSION['projektId'];
      $jogosultsag = $_SESSION['jogosultsag'];
      $thisPage == 'Kezdooldal';?>
      <header>
        <div class="projektnev-container">
          <a href="#">
            <img src="img/vmlogo.png" alt="logo" class="logo">
          </a>
          <?php
          if (isset($_SESSION['projektId'])) {
            $projektneve = $_SESSION['projektNeve'];
            echo '<p>Jelenlegi projekt: <b style="font-size: 22px;">'.$projektneve.'</b>('.$jogosultsag.')</p>';
          }else {
            echo '<p><b>Projekt költség kalkulációs rendszer</b></p>';
          }
          ?>
        </div>
        <nav class="topnav">
          <ul>
            <li><a <?php echo ($thisPage == 'Kezdooldal') ? ' class="selected"' : ''; ?> href="index.php">Kezdőlap</a></li>
            <li><a <?php echo ($thisPage == 'Anyaglista') ? ' class="selected"' : ''; ?> href="sap_anyaglista.php">Villamos anyaglista</a></li>
          <?php if (isset($_SESSION['projektId'])) { ?>
            <li><a <?php echo ($thisPage == 'Kalkulacioslap') ? ' class="selected"' : ''; ?> href="kalkulacioslap.php">Kalkulációs lap</a></li>
          <?php } ?>
          </ul>  <?php

          echo '<div class="logout-container">
            <form action="includes/logout.inc.php" method="post">';
              echo '<p>Jelenlegi felhasználó: <b>'.$fnev.'</b></p>
              <button type="submit" name="logout-submit">Kijelentkezés</button>
            </form>
          </div>';?>
        </nav>
      </header>
      <?php } else { ?>
        <div class="bejelentkezofelulet">
          <div class="cim-container">
            <p>Pojekt költség kalkulációs rendszer</p>
          </div>

          <div class="login-container">
            <form class="login-form" action="includes/login.inc.php" method="post">
              <label>Felhasználónév:</label><br>
              <input type="text" name="mailuid" placeholder="A felhasználóneved..."><br>
              <label>Jelszó:</label><br>
              <input type="password" name="pwd" placeholder="A jelszavad..."><br>
              <button style="" type="submit" name="login-submit">Bejelentkezés</button>
            </form>
          </div>
        </div>
      <?php } ?>
  </body>
</html>
