<?php
require dirname(__FILE__).'/secret/comment-ass/all.php';

class MyCommentAss extends CommentAss {
  function render(){
    $r = $this->_render;
    return $r();
  }
}
$ca = new MyCommentAss('sqlite:/var/sites/fapping/dicktarded-'.
  'databases/katies-calendar-comments.sqlite3');

if ($_SERVER['REQUEST_METHOD'] == 'POST') { $ca->processPost(); }

$ca->_render = function() use ($ca) {
?>

<div class='comment-header'>
  <h2>Comments</h2>
</div>

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
      <span class='datetime'>
        <?php echo $r->created_on('%Y-%m-%d %H:%I:%S'); ?>
      </span>
      <div class='content'>
        <?php echo htmlentities($r->content()); ?>
      </div>
    </div>
  <?php endwhile; ?>

  <?php if ($ca->none()): ?>
  <em class='msg em'>No comments yet!</em>
  <?php endif; ?>

  <form method='post' action='<?php
    echo basename($_SERVER['REQUEST_URI']);
  ?>'>
    <input type='hidden' name='target' value='<?php
      echo $ca->targetString()
    ?>'></input>
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

  <?php $ca->finish(); ?>
<?php
};

return $ca;
?>