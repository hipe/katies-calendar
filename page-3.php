<?php $c = require('comment-init.php');
  $c->setTargetString('page-3');
?>
<html>
<head>
  <title>AHA - katie's calendar</title>
  <link type="text/css" rel="stylesheet" href="styles/base.css" />
</head>
<style type="text/css">
#in-wrap {
  width: 800px;
}
</style>
<body>
  <div id='out-wrap'>
    <div id='in-wrap'>
      <div class='cap'>
        <h2>Katie's Calendar</h2>
      </div>
      <a href='img/full/2011-01-27_02.39.19.jpg' class='clicky'>
      <img src="img/2011-01-27_02.39.19.jpg" alt="katie's calendar"></img>
      </a>
      <div class='cap'>If you look closely you can read it.</div>
      <div clear='clear'></div>
      <div class='nav'>
        <div class='lf'>
          <a class='nav' href='page-2.php'>&lt; prev - medium shot</a>
        </div>
      </div>
      <?php $c->render(); ?>
      <div class='cap bottom'>&nbsp;</div>
    </div>
  </div>
</body>
</html>
