<?php
  session_start();
  if (isset($_SESSION['userId'])) {?>
    <div id="footer">
    <footer>
      <div class="footer">
        <div class="lap">
          <a href="http://dunaferr.hu/" target="_blank">ISD Dunaferr Zrt. weboldal</a>
        </div>
        <div class="lap">
          <a href="http://dunaferr.hu/" target="_blank">ISD Dunaferr Zrt. email</a>
        </div>
      </div>
    </footer>
  </div>
    <?php } ?>
  </body>
</div>
</html>
