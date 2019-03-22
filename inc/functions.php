<?php
function getEntryShort(){
  include("connection.php");

  try{
    $results = $db->query("SELECT title, date FROM entries");
  } catch (Exception $e){
    echo "Unable to retrieve results.";
    exit;
  }

  $entries = $results->fetchAll();
  return $entries;
}

function getDetailedEntry(){
  include("connection.php");

  try{
    $results = $db->query("SELECT * FROM entries");
  } catch (Exception $e){
    echo "Unable to retrieve results.";
    exit;
  }

  $entries = $results->fetchAll();
  return $entries
}

function addEntry($title,$date,$time_spent,$learned,$resources){
  include("connection.php");

  try{
    $results = $db->query("
      INSERT INTO entries
      VALUES (NULL,$title,$date,$time_spent,$learned,$resources)
    ");
  } catch (Exception $e){
    echo "Unable to retrieve results.";
    exit;
  }
}

function editEntry($title,$date,$time_spent,$learned,$resources,$id){
  include("connection.php");

  try{
    $results = $db->query("
      UPDATE entries
      SET title = $title, date = $date, time_spent = $time_spent, learned = $learned, resources = $resources
      WHERE id = $id
    ");
  } catch (Exception $e){
    echo "Unable to retrieve results.";
    exit;
  }
}

function deleteEntry($id){
  include("connection.php");

  try{
    $results = $db->query("DELETE FROM entries WHERE id=$id");
  } catch (Exception $e){
    echo "Unable to retrieve results.";
    exit;
  }
}

?>
