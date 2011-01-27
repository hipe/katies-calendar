<?php
class CustomAssComment {
  function __construct() {
    $this->tz0 = new DateTimeZone('Etc/GMT+0');
    $this->tz1 = new DateTimeZone('America/New_York');
  }
  function content() {
    return $this->r['content'];
  }
  function person() {
    if (!isset($this->r['person']) || '' == $this->r['person']) {
      return null;
    }
    return $this->r['person'];
  }
  function created_on($fmt) {
    // echo "<PRE>"; var_export(DateTimeZone::listAbbreviations());exit();
    $s = $this->r['created_on'];
    $dt = DateTime::createFromFormat('Y-m-d G:i:s', $s, $this->tz0);
    if (false === $dt) return $s; // arg
    $dt->setTimezone($this->tz1);
    return $dt->format('Y-m-d g:i:sa T');
  }
}
class CommentAss {
  function __construct($connect) {
    $this->p = new PDO($connect);
    $this->p->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->messages = array();
    $this->flyweight = new CustomAssComment();
  }
  function addMessage($msg) {
    $this->messages []= $msg;
    return false;
  }
  function fetchCustomObject() {
    $r = $this->sth->fetch(PDO::FETCH_ASSOC);
    if ($r) {
      $this->flyweight->r = $r;
      $this->r = $r;
      return $this->flyweight;
    } else {
      return null;
    }
  }
  function finish() {
    unset($this->p);
  }
  function hasMessage() {
    return 0 < count($this->messages);
  }
  function message() {
    if (count($this->messages) == 0) return null;
    return join('  ', $this->messages);
  }
  function none() {
    return 0 == $this->r; // hackish
  }
  function processPost() {
    $p = $_POST;
    foreach(array('content', 'person', 'target') as $k) {
      $p[$k] = isset($p[$k]) ? trim($p[$k]) : null;
      if (''==$p[$k]) $p[$k] = null;
    }
    foreach(array('content', 'target') as $k) {
      if (! $p[$k]) return $this->addMessage("$k cannot be blank!");
    }
    $sth = $this->p->prepare('
      insert into comment (person, content, target_string, user_ip)
      values (:ppp, :ccc, :ts, :uip)
    ');
    $v = $sth->execute(array(
      'ppp' => $p['person'],
      'ccc' => $p['content'],
      'ts' => $p['target'],
      'uip' => $_SERVER['REMOTE_ADDR']
    ));
    $this->addMessage("res: ".(var_export($v)));
    return true;
  }
  function query() {
    $this->r = 0;
    $this->sth = $this->p->prepare('
      select * from comment
      where target_string=:target_string
      order by id
    ');
    $this->sth->execute(array('target_string'=>'page-1'));
  }
}

$ca = new CommentAss('sqlite:/var/sites/fapping/dicktarded-'.
'databases/katies-calendar-comments.sqlite3');
?>
<html>
<head>
<style type="text/css">
table.comment {
  margin-top: 20px;
}
table.comment th {
  border: 1.87px solid #333333;
  background-color: #878282;
}
table.comment th div {
  padding: 0 5px 0 5px;
}
table.comment th.top {
  -moz-border-radius-topleft: 8px;
  border-top-left-radius: 8px;
}
table.comment th.bot {
  -moz-border-radius-bottomleft: 8px;
  border-bottom-left-radius: 8px;
}
.haha-wrap {
  position: relative;
}
.haha {
  float: right;
  border-top: 1px solid #999;
  border-left: 1px solid #999;
  background: white;
  position: absolute;
  right: 0;
  bottom: 0;
  width: 61px;
  text-align: right;
  padding-top: 5px;
  border-top-left-radius: 8px;
  -moz-border-radius-topleft: 8px;
}
div.comment {
  background: #999;
  width: 400px;
  padding: 8px 8px 8px 0;
  margin-top: 5px;
  margin-bottom: 5px;
  border-radius: 7px;
  -moz-border-radius: 7px;
}
div.comment .person {
  margin-left: 10px;
  font-weight: bold;
}
div.comment .content {
  margin-left: 10px;
  margin-top: 4px;
  background-color: #ccc;
  border-radius: 6px;
  -moz-border-radius: 6px;
  padding: 6px 6px 4px 6px;
  font-size: 0.87em;
}
</style>
</head>
<body>


<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') { $ca->processPost(); }
?>

<?php if ($ca->hasMessage()): ?>
  <div class='msg'>
    <?php echo htmlentities($ca->message()); ?>
  </div>
<?php endif; ?>


<?php $ca->query(); ?>

<?php while ($r = $ca->fetchCustomObject()): ?>
  <div class="comment">
    <?php if ($r->person()): ?>
      <span class='person'><?php echo htmlentities($r->person()); ?></span>
    <?php else: ?>
      <span class='person anon'>Anonymous</span>
    <?php endif; ?>
    <span class='datetime'><?php echo $r->created_on('%Y-%m-%d %H:%I:%S'); ?>
    </span>
    <div class='content'>
      <?php echo htmlentities($r->content()); ?>
    </div>
  </div>
<?php endwhile; ?>

<?php if ($ca->none()): ?>
<em class='msg em'>No comments yet!</em>
<?php endif; ?>



<form method='post' action='test.php'>
  <input type='hidden' name='target' value='page-1'></input>
  <table class='comment'>
    <tr>
      <th class='top'><div>Name</div></th>
      <td><input type="text" name="person" size="28"></input></td>
    </tr>
    <tr>
      <th class='bot'><div>Comment</div></th>
      <td>
        <div class='haha-wrap'>
          <textarea rows="4" cols="48" name="content"></textarea>
          <div class='haha'>
            <input type='submit' value='Submit'></input>
          </div>
        </div>
      </td>
    </tr>
  </table>
</form>
</body>
</html>
<?php $ca->finish(); ?>