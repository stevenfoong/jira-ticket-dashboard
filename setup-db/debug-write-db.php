<?php

require_once('db.php');

echo "Opening DB.<br><br>";

$db = new MyDB();

$db->exec("INSERT INTO COMMENT
	(TICKET_ID, TICKET_TITLE, REPORTER, ASSIGNEE, ORGANIZARION, UPDATE_BY, COMMENT, UPDATE_FROM)
        VALUES ('ticket_id','ticket_title','reporter','assignee','organization','update_by','comment','from_who')");

echo "Record inserted.<br><br>";

$db->close();

?>
