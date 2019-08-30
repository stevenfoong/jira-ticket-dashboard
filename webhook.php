<?php

$entityBody = file_get_contents('php://input');
$ticket = json_decode($entityBody,true);

//$from_who = $_GET['user'];
$from_who = $ticket["comment"]["author"]["displayName"];
$ticket_id = $ticket["issue"]["id"];
$ticket_key = $ticket["issue"]["key"];
$ticket_summary = $ticket["issue"]["fields"]["summary"];
$assignee_id = $ticket["issue"]["fields"]["assignee"]["displayName"];
$reporter_id = $ticket["issue"]["fields"]["reporter"]["displayName"];
$organization_name = $ticket["issue"]["fields"]["customfield_11502"]["0"]["name"];
$user_id = $ticket["comment"]["author"]["displayName"];
$comment = $ticket["comment"]["body"];

// Debug Usage

$handle = fopen('comment.txt','a');
fwrite($handle,"***".PHP_EOL);
fwrite($handle,print_r($ticket,true).PHP_EOL);
fwrite($handle,"***".PHP_EOL);
fwrite($handle,"Ticket ID : ".$ticket['issue']['id'].PHP_EOL);
fwrite($handle,"***".PHP_EOL);
fwrite($handle,"***".PHP_EOL);
fwrite($handle,"Comment : ".$ticket['comment']['body'].PHP_EOL);
fwrite($handle,"***".PHP_EOL);
fclose($handle);


//Write to DB

class MyDB extends SQLite3 {
  function __construct() {
    $this->open('comment.db');
  }
}

  $db = new MyDB();
	//$db_statement = "INSERT INTO COMMENT VALUES('".$issue_id."','".$reporter_id."','".$assignee_id."','".$organization_name."','".$user_id."','".$comment"','".$from_who."')";
	
  $db->exec("INSERT INTO COMMENT
	(TICKET_ID, TICKET_TITLE, REPORTER, ASSIGNEE, ORGANIZARION, UPDATE_BY, COMMENT, UPDATE_FROM)
	VALUES ('$ticket_id','$ticket_summary','$reporter_id','$assignee_id','$organization_name','$user_id','$comment','$from_who')");

  $db->close();

?>
