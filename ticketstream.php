<?php

session_start();

header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");
header("Connection: keep-alive");
header("X-Accel-Buffering: no");

date_default_timezone_set("Asia/Singapore");
$timestamp = date("F d, Y h:i:s A");

class MyDB extends SQLite3 {
  function __construct() {
        $this->open('comment.db');
  }
}

$user_session_id = $_SESSION['user_id'];

$data = array (
        'event'=>'ping',
        'message'=>$timestamp,
);
$str = json_encode($data);
echo "data: {$str}\n\n";

while (1) {

  $db = new MyDB();

  $last_sent = $db->querySingle("SELECT ROW_ID_SENT FROM CONTROL WHERE SESSION_ID = '$user_session_id'");

  $last_msg = $db->querySingle("SELECT count(*) FROM COMMENT");


  if ($_SESSION['debug'] == "1") {
     echo "event: debug \n";
     echo "data: Session ID: ".$user_session_id."\n";
     echo "data: Last Sent Row: ".$last_sent."\n";
     echo "data: Last Msg ID: ".$last_msg."\n\n";
  }


  if (empty($last_sent)) {

    $last_sent = $last_msg;
    $db->exec("REPLACE INTO CONTROL (ROW_ID_SENT, LAST_SENT, SESSION_ID) VALUES ('$last_sent', '$timestamp', '$user_session_id')");

  }

if ($last_msg <> $last_sent) {
  
  $timestamp = date("F d, Y h:i:s A");

  while ($last_sent < $last_msg) {
    $last_sent++;
    $msg = $db->querySingle("SELECT rowid,* FROM COMMENT WHERE rowid = '$last_sent'",true);

    $data = array (
	'event'=>$msg['ORGANIZARION'],
        'message'=>$timestamp,
        'ticket_id'=>$msg['TICKET_ID'],
	'ticket_title'=>$msg['TICKET_TITLE'],
	'comment'=>$msg['COMMENT'],
	'update_by'=>$msg['UPDATE_BY'],
	'org'=>$msg['ORGANIZARION'],
	'ses_org'=>$_SESSION['organization'],
    );
   
   $str = json_encode($data);
   echo "data: {$str}\n\n";
   
    $db->exec("REPLACE INTO CONTROL (ROW_ID_SENT, LAST_SENT, SESSION_ID) VALUES ('$msg[rowid]', '$timestamp', '$user_session_id')");
   
  }

} else {
  $timestamp = date("F d, Y h:i:s A");
  $data = array (
        'event'=>'ping',
        'message'=>$timestamp,
  );
  $str = json_encode($data);
  echo "data: {$str}\n\n";
 
}

  $db->close();

  ob_flush();
  flush();
  sleep(10);
}

?>
