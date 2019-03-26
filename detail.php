<?php
include 'inc/header.php';
include 'inc/functions.php';

$id = trim(filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT));
$entry = getDetailedEntry($id);
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
                    </article>
                </div>
            </div>
            <div class="edit">
                <p><a href="edit.php?id=<?php echo $id ?>">Edit Entry</a></p>
            </div>
        </section>
<?php include 'inc/footer.php'; ?>
