<?php

$username = '';
$password = '';


if ($_GET['event'] == "comment_update") {
	$from_who = $_GET['user'];
        $issue = file_get_contents('php://input');
        $issue_data = json_decode($issue,true);
	$issue_id = $issue_data["issue"]["key"];
	$issue_summary = $issue_data["issue"]["fields"]["summary"];
	$assignee_id = $issue_data["issue"]["fields"]["assignee"]["displayName"];
	$reporter_id = $issue_data["issue"]["fields"]["reporter"]["displayName"];
	$organization_name = $issue_data["issue"]["fields"]["customfield_11502"]["0"]["name"];
	$user_id = $issue_data["comment"]["author"]["displayName"];
	$comment = $issue_data["comment"]["body"];
        //$url = ''.$issue_id;

        //$curl = curl_init();
        //curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
        //curl_setopt($curl, CURLOPT_URL, $url);
        //curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        //$issue = (curl_exec($curl));
        //$issue_data = json_decode($issue,true);
        //$reporter_key = $issue_data["fields"]["reporter"]["key"];
        //$reporter_displayName = $issue_data["fields"]["reporter"]["displayName"];

        $fp = fopen('data.txt', 'a');//opens file in append mode
//fwrite($fp, $data);
	//fwrite($fp, $issue."\n" );
	fwrite($fp, "Table to Update: ".$from_who."\n");
	fwrite($fp, "Issue Id: ".$issue_id."\n");
	fwrite($fp, "Ticket Title: ".$issue_summary."\n");
	fwrite($fp, "Assigned To: ".$assignee_id."\n");
	fwrite($fp, "Report By: ".$reporter_id."\n");
	fwrite($fp, "From Organization: ".$organization_name."\n");
	fwrite($fp, "Update By: ".$user_id."\n");
	fwrite($fp, "Comment : ".$comment."\n");
        //fwrite($fp, $issue_id." ".$assignee_id." ".$reporter_id." ".$user_id." ".$comment."\n");
        fwrite($fp, "\n");
        fclose($fp);

//Write to DB

	class MyDB extends SQLite3
	{
    		function __construct()
    		{
        		$this->open('comment.db');
    		}
	}

	$db = new MyDB();
	//$db_statement = "INSERT INTO COMMENT VALUES('".$issue_id."','".$reporter_id."','".$assignee_id."','".$organization_name."','".$user_id."','".$comment"','".$from_who."')";
	
	$db->exec("INSERT INTO COMMENT
		(TICKET_ID, TICKET_TITLE, REPORTER, ASSIGNEE, ORGANIZARION, UPDATE_BY, COMMENT, UPDATE_FROM)
		VALUES ('$issue_id','$issue_summary','$reporter_id','$assignee_id','$organization_name','$user_id','$comment','$from_who')");

	$db->close();

}






?>
