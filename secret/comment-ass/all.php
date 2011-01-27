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
  function formAction() {
    if (isset($this->formAction)) return $this->formAction;
    return $_SERVER['REQUEST_URI'];
  }
  function setFormAction($s) {
    $this->formAction = $s;
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
    return true;
  }
  function query() {
    $this->r = 0;
    $this->sth = $this->p->prepare('
      select * from comment
      where target_string=:target_string
      order by id
    ');
    $this->sth->execute(array('target_string'=>$this->targetString));
  }
  function setTargetString($x) {
    $this->targetString = $x;
  }
  function targetString() {
    return $this->targetString;
  }
}