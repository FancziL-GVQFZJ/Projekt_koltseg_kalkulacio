<?php
  session_start();
  if (isset($_SESSION['userId'])) {?>
    <div id="footer">
    <footer>
      <a href="https://stackoverflow.com/">Stackowerflow</a> <p>Példa</p>
    </footer>
  </div>
    <?php } ?>
  </body>
</div>
</html>
