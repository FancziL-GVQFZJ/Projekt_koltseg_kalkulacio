<?php
  session_start();
  if (isset($_SESSION['userId'])) {?>
    <div id="footer">
    <footer>
      <div class="lap">
        <a href="http://dunaferr.hu/">ISD Dunaferr</a>
      </div>
    </footer>
  </div>
    <?php } ?>
  </body>
</div>
</html>
