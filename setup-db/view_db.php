<?php

require_once('db.php');

$db = new MyDB();
$result = $db->query('SELECT rowid, * FROM COMMENT');
//var_dump($result->fetchArray());

echo "COMMENT table:<br><br>";
echo '<table style="width:100%; border: 1px solid black" >';

while ($row = $result->fetchArray()) {
    //var_dump($row);
    //echo '<br><br>';
    echo '<tr style="border: 1px solid black">';
    foreach($row as $value){
      echo '<th style="border: 1px solid black">'.$value.'</th>';
    }
    echo "</tr>";
}

echo "</table>";

$result2 = $db->query('SELECT rowid, * FROM CONTROL');

echo "<br><br>CONTROL table:<br><br>";
echo '<table style="width:100%; border: 1px solid black" >';

while ($row2 = $result2->fetchArray()) {
    //var_dump($row2);
    //echo '<br><br>';
    echo '<tr style="border: 1px solid black">';
    foreach($row2 as $value){
      echo '<th style="border: 1px solid black">'.$value.'</th>';
    }
    echo "</tr>";
}

echo "</table>";

$db->close();

?>
