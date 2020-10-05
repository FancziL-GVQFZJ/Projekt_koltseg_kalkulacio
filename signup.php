<?php
    require "header.php";
?>

    <main>
      <h1>Signup</h1>
      <?php
        if (isset($_GET['error'])) {
          if ($_GET["error"] == "emptyfields") {
            echo '<p>Fill in all the fields!</p>';
          }
          elseif ($_GET["error"] == "invalidmailuid") {
            echo '<p>Invalid username and e-mail!</p>';
          }
          elseif ($_GET["error"] == "invalidmail") {
            echo '<p>Invalid e-mail!</p>';
          }
          elseif ($_GET["error"] == "passwordcheck") {
            echo '<p>Your passwords do not match!</p>';
          }
          elseif ($_GET["error"] == "usertaken") {
            echo '<p>Username is alredy taken!</p>';
          }
        }
        elseif ($_GET["signup"] == "success") {
          echo '<p>Sugnup succesful!</p>';
        }
      ?>
      <form action="includes/signup.inc.php" method="post">
        <input type="text" name="uid" placeholder="Username">
        <input type="text" name="mail" placeholder="E-mail">
        <input type="password" name="pwd" placeholder="Password">
        <input type="password" name="pwd-repeat" placeholder="Repeat password">
        <button type="submit" name="signup-submit">Signup</button>
      </form>
    </main>

<?php
    require "footer.php";
?>
