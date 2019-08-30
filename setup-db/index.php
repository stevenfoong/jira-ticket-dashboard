<?php
   require_once('db.php');
   
   $db = new MyDB();
   if(!$db) {
      echo $db->lastErrorMsg();
   } else {
      echo "- Opened database successfully<br>";
   }

   // Create COMMENT Table

   $sql =<<<EOF
      CREATE TABLE COMMENT
      (TICKET_ID        TEXT    NOT NULL,
      TICKET_TITLE      TEXT    NOT NULL,
      REPORTER          TEXT    NOT NULL,
      ASSIGNEE          TEXT    NOT NULL,
      ORGANIZARION      TEXT    NOT NULL,
      UPDATE_BY         TEXT    NOT NULL,
      COMMENT           CHAR(50),
      UPDATE_FROM       TEXT    NOT NULL);
EOF;

   $ret = $db->exec($sql);
   if(!$ret){
      echo $db->lastErrorMsg();
   } else {
      echo "- COMMENT Table created successfully<br>";
   }

   // Create CONTROL table

   $sql =<<<EOF
      CREATE TABLE CONTROL
      (ROW_ID_SENT  	TEXT 	NOT NULL,
      SESSION_ID        TEXT    NOT NULL,
      LAST_SENT		TEXT	NOT NULL);
EOF;

   $ret = $db->exec($sql);
   if(!$ret){
      echo $db->lastErrorMsg();
   } else {
      echo "- CONTROL Table created successfully<br>";
   }

   // Create index of SESSION_ID column

   $sql =<<<EOF
	   CREATE UNIQUE INDEX idx_control_sessionid ON control (SESSION_ID);
EOF;

   $ret = $db->exec($sql);
   if(!$ret){
      echo $db->lastErrorMsg();
   } else {
      echo "- Index of SESSION_ID column created successfully<br>";
   }

   $db->close();

   echo "- DB creation complete<br>";

?>
