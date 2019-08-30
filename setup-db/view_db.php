<?php

require_once('db.php');

$db = new MyDB();
$result = $db->query('SELECT rowid, * FROM COMMENT');
//var_dump($result->fetchArray());

echo "From COMMENT:<br><br>";

while ($row = $result->fetchArray()) {
    var_dump($row);
    echo '<br><br>';
}

$result2 = $db->query('SELECT rowid, * FROM CONTROL');

echo "From CONTROL:<br><br>";

while ($row2 = $result2->fetchArray()) {
    var_dump($row2);
    echo '<br><br>';
}

$db->close();

?>
