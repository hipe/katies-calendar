<?php $c = require('comment-init.php');
  $c->setTargetString('page-1');
  $c->setFormAction('/katies-calendar/index.php');
?>
<html>
<head>
  <title>AHA - katie's calendar</title>
  <link type="text/css" rel="stylesheet" href="styles/base.css" />

<style type="text/css">
#in-wrap {
  width: 460px;
}
</style>

</head>
<body>
  <div id='out-wrap'>
    <div id='in-wrap'>
      <div class='cap'>
        <h2>Katie's Calendar</h2>
      </div>
      <a href='img/full/katie-calendar-01.jpg' class='clicky'>
      <img src="img/katie-calendar-01.jpg" alt="katie's calendar"></img>
      </a>
      <div class='cap'>Squares are removable and velcro'd to wall -- drag and droppable.</div>
      <div clear='clear'></div>
      <div class='nav'>
        <div class='rt'>
          <a class='nav' href='page-2.php'>next &gt;</a>
        </div>
      </div>
      <?php $c->render(); ?>
      <div class='cap bottom'>&nbsp;</div>
    </div>
  </div>
</body>
</html>
