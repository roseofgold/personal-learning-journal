<?php
function getEntryShort(){
  include("connection.php");

  try{
    $results = $db->prepare("SELECT title, date, id FROM entries ORDER BY date DESC");
  } catch (Exception $e){
    echo "Unable to retrieve results.";
    exit;
  }
  $results->execute();

  $entries = $results->fetchAll();
  return $entries;
}

function displayShortEntries(){
  $entryShort = getEntryShort();

  foreach ($entryShort as $key) {
    echo "<article>";
    echo "<h2><a href=\"detail.php?pg=" . $key['id'] . "\">" . $key['title'] . "</a></h2>";
    echo "<time datetime=\"" . $key['date'] . "\">" . date('F j, Y',strtotime($key['date'])) . "</time>";
    echo "</article>";
  }

}

function getDetailedEntry($id){
  include("connection.php");

  try{
    $results = $db->prepare("SELECT * FROM entries WHERE id = $id");
  } catch (Exception $e){
    echo "Unable to retrieve results.";
    exit;
  }
  $results->execute();

  $entries = $results->fetchAll();
  return $entries;
}

function addEntry($title,$date,$time_spent,$learned,$resources){
  include("connection.php");

  try{
    $results = $db->query("
      INSERT INTO entries
      VALUES (NULL,$title,$date,$time_spent,$learned,$resources)
    ");
  } catch (Exception $e){
    echo "Unable to add entry.";
    exit;
  }
}

function editEntry($title,$date,$time_spent,$learned,$resources,$id){
  include("connection.php");

  try{
    $results = $db->prepare("
      UPDATE entries
      SET title = $title, date = $date, time_spent = $time_spent, learned = $learned, resources = $resources
      WHERE id = $id
    ");
  } catch (Exception $e){
    echo "Unable to retrieve results.";
    exit;
  }

  $results->execute();

}

function deleteEntry($id){
  include("connection.php");

  try{
    $results = $db->query("DELETE FROM entries WHERE id=$id");
  } catch (Exception $e){
    echo "Unable to retrieve results.";
    exit;
  }

  $results->execute();

}

?>
