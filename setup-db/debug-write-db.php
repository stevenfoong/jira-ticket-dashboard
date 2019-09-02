<?php

//This file use to debug the DB accessibility. Make sure the entry able to write into the DB.

require_once('db.php');

echo "Opening DB.<br><br>";

$db = new MyDB();

$db->exec("INSERT INTO COMMENT
	(TICKET_ID, TICKET_TITLE, REPORTER, ASSIGNEE, ORGANIZARION, UPDATE_BY, COMMENT, UPDATE_FROM)
        VALUES ('ticket_id','ticket_title','reporter','assignee','organization','update_by','comment','from_who')");

echo "Record inserted.<br><br>";

$db->close();

?>
