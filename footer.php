<?php
session_start();
if (isset($_SESSION['userId'])) {?>
  <footer id="footer">
    <a href="https://stackoverflow.com/">Stackowerflow</a> <p>Példa</p>
  </footer>
  <?php } ?>
</body>
</html>
