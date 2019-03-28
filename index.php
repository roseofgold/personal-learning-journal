<?php
include 'inc/header.php';
include "inc/functions.php";

if(isset($_GET['msg'])){
  $error_message = trim(filter_input(INPUT_GET,'msg', FILTER_SANITIZE_STRING));
}

if(isset($_GET['tag'])){
  $tag = trim(filter_input(INPUT_GET,'tag',FILTER_SANITIZE_STRING));
} else {
  $tag = '';
}

if (isset($error_message)) {
    echo "<p class='message'>$error_message</p>";
}
?>

<section>
    <div class="container">
        <div class="entry-list">
          <?php displayShortEntries($tag); ?>
        </div>
    </div>
</section>

<?php include 'inc/footer.php'; ?>
