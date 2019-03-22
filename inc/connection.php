<?php
// connect to the database
try{
    $db = new PDO("sqlite:".__DIR__."/journal.db");
} catch (Exception $e){
  echo "Unable to connect: ";
  echo $e->getMessage();
  exit;
}

?>
