<?php
include 'inc/header.php';
include "inc/functions.php";

if(isset($_GET['msg'])){
  $error_message = trim(filter_input(INPUT_GET,'msg', FILTER_SANITIZE_STRING));
}

if (isset($error_message)) {
    echo "<p class='message'>$error_message</p>";
}
?>

<section>
    <div class="container">
        <div class="entry-list">
          <?php displayShortEntries(); ?>
        </div>
    </div>
</section>

<?php include 'inc/footer.php'; ?>
