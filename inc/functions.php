<?php
function getEntryShort($tag){
  include("connection.php");

  $sql = 'SELECT DISTINCT entries.title, entries.date, entries.id FROM entries
    JOIN entries_tags ON entries.id = entries_tags.entry_id
    JOIN tags ON entries_tags.tag_id = tags.tag_id';

  $where = '';
  if ($tag != '') { $where = ' WHERE tags.tag_id = ?';}

  $orderby = ' ORDER BY date DESC';

  try{
    $results = $db->prepare($sql . $where . $orderby);
    if($tag != ''){$results->bindValue(1,$tag);}
  } catch (Exception $e){
    echo "Unable to retrieve results.";
    exit;
  }
  $results->execute();

  $entries = $results->fetchAll();
  return $entries;
}

function displayShortEntries($tag = NULL){
  $entryShort = getEntryShort($tag);

  foreach ($entryShort as $key) {
    echo "<article>";
    echo "<h2><a href=\"detail.php?id=" . $key['id'] . "\">" . $key['title'] . "</a></h2>";
    echo "<time datetime=\"" . $key['date'] . "\">" . date('F j, Y',strtotime($key['date'])) . "</time>";
    echo "</article>";
  }

}

function getDetailedEntry($id){
  include("connection.php");

  try{
    $results = $db->prepare("SELECT * FROM entries
      WHERE id = ?");
    $results->bindValue(1,$id,PDO::PARAM_INT);
    $results->execute();
  } catch (Exception $e){
    echo "Unable to retrieve results.";
    exit;
  }

  $entries = $results->fetch(PDO::FETCH_ASSOC);
  return $entries;
}

function getTags($id){
  include("connection.php");

  try {
    $results = $db->prepare("SELECT tags.tag_id,tags.tag FROM tags
      JOIN entries_tags ON entries_tags.tag_id = tags.tag_id
      JOIN entries ON entries.id = entries_tags.entry_id
      WHERE id = ?");
    $results->bindValue(1,$id,PDO::PARAM_INT);
    $results->execute();
  } catch (Exception $e){
    echo "No tags retrieved";
    exit;
  }

  $tags = $results->fetchAll(PDO::FETCH_ASSOC);
  return $tags;
}

function getTagText($tag){
  include("connection.php");

  try {
    $results = $db->prepare("SELECT tag FROM tags
      WHERE tag_id = ?");
    $results->bindValue(1,$tag,PDO::PARAM_INT);
    $results->execute();
  } catch (Exception $e){
    echo "No tags retrieved";
    exit;
  }

  return $results->fetch(PDO::FETCH_ASSOC);
}

function getTagID($tag){
  include("connection.php");

  try {
    $results = $db->prepare("SELECT tag_id FROM tags
      WHERE tag = ?");
    $results->bindValue(1,$tag,PDO::PARAM_STR);
    $results->execute();
  } catch (Exception $e){
    echo "No tags retrieved";
    exit;
  }

  return $results->fetch(PDO::FETCH_ASSOC);
}

function addEntry($title,$date,$time_spent,$learned,$resources){
  include("connection.php");

  $sql = 'INSERT INTO entries (title,date,time_spent,learned,resources) VALUES (?, ?, ?, ?, ?)';

  try{
    $results = $db->prepare($sql);
    $results->bindValue(1,$title,PDO::PARAM_STR);
    $results->bindValue(2,$date,PDO::PARAM_STR);
    $results->bindValue(3,$time_spent,PDO::PARAM_STR);
    $results->bindValue(4,$learned,PDO::PARAM_STR);
    $results->bindValue(5,$resources,PDO::PARAM_STR);
    $results->execute();
  } catch (Exception $e){
    echo "Unable to add entry: " . $e->getMessage();
    exit;
  }

  return $results;
}

function editEntry($title,$date,$time_spent,$learned,$resources,$id){
  include("connection.php");

  $sql = 'UPDATE entries SET title = ?, date = ?, time_spent = ?, learned = ?, resources = ? WHERE id = ?';

  try{
    $results = $db->prepare($sql);
    $results->bindValue(1,$title,PDO::PARAM_STR);
    $results->bindValue(2,$date,PDO::PARAM_STR);
    $results->bindValue(3,$time_spent,PDO::PARAM_STR);
    $results->bindValue(4,$learned,PDO::PARAM_STR);
    $results->bindValue(5,$resources,PDO::PARAM_STR);
    $results->bindValue(6,$id,PDO::PARAM_INT);
    $results->execute();
  } catch (Exception $e){
    echo "Unable to retrieve results: " . $e->getMessage();
    exit;
  }

    return $results;
}

function editTags($tag_list,$id){
  include("connection.php");

  // Make $tag_list into an array
  $tag_list = explode(',',$tag_list);

  // Get list of tags associated with this entry
  $associated_tags = getTagAssociated($id);
  for($i=0;$i<(count($associated_tags));$i++){
    $associated_tags_array[] = $associated_tags[$i]['tag'];
  }

  //Check if entry tags are all associated
  foreach($associated_tags_array AS $key => $i){
    if(!in_array($i,$tag_list)){
      // Tag is associated, but shouldn't be. Remove association with the entry
      $tag_id = getTagID($i);
      //echo "$key is the key to '$i' and should be disassociated with entry $id";
      removeTag($tag_id['tag_id'],$id);
    }
  }

  // Check if entered tags are already associated with the entry
  foreach($tag_list AS $key => $i){
    if(!in_array($i,$associated_tags_array)){
      //echo "$key is the key to '$i' and needs added to the association";
      // Check to see if tag is already created
      checkTag($i);

      // Tag isn't associated with the entry. Associate tag with the entry
      // addTagAssociation($key,$id);
    }
  }
}

function checkTag($i){
  include("connection.php");

  $tag_name = trim($i);
  
  $sql = 'SELECT * FROM tags WHERE tag = ?';
  try{
    $results = $db->prepare($sql);
    $results->bindValue(1,$tag_name,PDO::PARAM_STR);
    $results->execute();
  } catch (Exception $e){
    echo "Unable to retrieve results: " . $e->getMessage();
  }

  if($results->rowcount()>0){
    //No Results. Needs Added
    addTag($tag_name);
  }
  
  return $results[];
}

function addTag($tag_name){
  include("connection.php");

  $sql = 'INSERT INTO tags(tag) VALUES(?)';
  try{
    $results = $db->prepare($sql);
    $results->bindValue(1,$tag_name,PDO::PARAM_STR);
    $results->execute();
  } catch (Exception $e){
    echo "Unable to retrieve results: " . $e->getMessage();
  }

  return $results;
}

function addTagAssociation($tag_id,$entry_id){
  include("connection.php");

  $sql = 'INSERT INTO entries_tags(tag_id,entry_id)
    VALUES(?,?)';
  try{
    $results = $db->prepare($sql);
    $results->bindValue(1,$tag_id,PDO::PARAM_INT);
    $results->bindValue(2,$entry_id,PDO::PARAM_INT);
    $results->execute();
  } catch (Exception $e){
    echo "Unable to insert results: " . $e->getMessage();
  }

  return $results;
}

function removeTag($tag_id,$entry_id){
  include("connection.php");

  $sql = 'DELETE FROM entries_tags
    WHERE tag_id = ? AND entry_id = ?';
  try{
    $results = $db->prepare($sql);
    $results->bindValue(1,$tag_id,PDO::PARAM_INT);
    $results->bindValue(2,$entry_id,PDO::PARAM_INT);
    $results->execute();
  } catch (Exception $e){
    echo "Unable to insert results: " . $e->getMessage();
  }

  return $results;
}

function getTagAssociated($id){
  include("connection.php");
  
  $sql = 'SELECT tag FROM tags 
    JOIN entries_tags ON tags.tag_id = entries_tags.tag_id 
    JOIN entries ON entries_tags.entry_id = entries.id 
    WHERE entries.id = ?';
  try{
    $results = $db->prepare($sql);
    $results->bindValue(1,$id,PDO::PARAM_STR);
    $results->execute();
  } catch (Exception $e){
    echo "Unable to retrieve results: " . $e->getMessage();
  }
  return $results->fetchAll(PDO::FETCH_ASSOC);
}

function deleteEntry($id){
  include("connection.php");

  $sql = 'DELETE FROM entries WHERE id=?';

  try{
    $results = $db->prepare($sql);
    $results->bindValue(1,$id,PDO::PARAM_INT);
    $results->execute();
  } catch (Exception $e){
    echo "Unable to retrieve results: " . $e->getMessage() . "<br />";
    return false;
  }

  if($results->rowCount() >0){
      return true;
  } else{
      return false;
  }
}

?>
