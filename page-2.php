<?php $c = require('comment-init.php');
  $c->setTargetString('page-2');
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
      <a href='img/full/2011-01-27_02.38.42.jpg' class='clicky'>
      <img src="img/2011-01-27_02.38.42.jpg" alt="katie's calendar"></img>
      </a>
      <div class='cap'>There isn't one space for every day, just a square for every event, so calender is smaller, faster, more efficient.</div>
      <div clear='clear'></div>
      <div class='nav'>
        <div class='lf'>
          <a class='nav' href='index.php'>&lt; prev - establishing shot</a>
        </div>
        <div class='rt'>
          <a class='nav' href='page-3.php'>even closer! - next &gt;</a>
        </div>
      </div>
      <?php $c->render(); ?>
      <div class='cap bottom'>&nbsp;</div>
    </div>
  </div>
</body>
</html>
