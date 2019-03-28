<?php
ini_set('display_errors','on');
include 'inc/header.php';
include 'inc/functions.php';

$id = trim(filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT));
$entry = getDetailedEntry($id);
$tags = getTags($id);

if(isset($_POST['delete'])){
  if(deleteEntry(filter_input(INPUT_POST,'delete',FILTER_SANITIZE_NUMBER_INT))){
    header('location:index.php?msg=Entry+Deleted');
    exit;
  } else{
    echo 'false';
    header('location:details.php?id=' . $id . '?msg=Unable+to+Delete+Entry');
    exit;
  }
}

if(isset($_GET['msg'])){
  $error_message = trim(filter_input(INPUT_GET,'msg', FILTER_SANITIZE_STRING));
}

if (isset($error_message)) {
    echo "<p class='message'>$error_message</p>";
}
?>
        <section>
            <div class="container">
                <div class="entry-list single">
                    <article>
                        <h1><?php echo htmlspecialchars_decode($entry['title']); ?></h1>
                        <time datetime="<?php echo $entry['date']; ?>">
                          <?php echo date('F j, Y', strtotime($entry['date'])); ?>
                        </time>
                        <div class="entry">
                            <h3>Time Spent: </h3>
                            <p><?php echo htmlspecialchars_decode($entry['time_spent']); ?></p>
                        </div>
                        <div class="entry">
                            <h3>What I Learned:</h3>
                            <p><?php echo htmlspecialchars_decode($entry['learned']); ?></p>
                        </div>
                        <div class="entry">
                            <h3>Resources to Remember:</h3>
                            <p><?php echo htmlspecialchars_decode($entry['resources']); ?></p>
                        </div>
                        <div class="entry">
                            <h3>Tags:</h3>
                            <ul>
                                <?php if(!empty($tags)){
                                    foreach($tags as $tag){
                                        echo '<li><a href="index.php?tag=' . $tag['tag_id'] . "\">";
                                        echo $tag['tag'];
                                        echo '</a></li>';
                                    }
                                } ?>
                            </ul>
                        </div>
                    </article>
                </div>
            </div>
            <div class="edit">
              <p><a href="edit.php?id=<?php echo $id ?>">Edit Entry</a></p>
              <form method='post' action='detail.php?id=<?php echo $id ?>' onsubmit="return confirm('Are you sure you want to delete this project?');">
                  <input type='hidden' value='<?php echo $id; ?>' name='delete' />
                  <input type='submit' class='button--delete' value='Delete Entry' />
              </form>
          </div>
        </section>
<?php include 'inc/footer.php'; ?>
