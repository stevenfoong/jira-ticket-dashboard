<?php

require 'config.php';

$url = 'https://'.$domain.'/rest/api/2/search?jql='.urlencode('project = "'.$project.'" AND status != Closed AND status != Resolved AND status != Completed AND reporter != feedback ORDER BY createdDate DESC ');

$curl = curl_init();
curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

$issue = (curl_exec($curl));
$issue_data = json_decode($issue,true);
$records_count = $issue_data["total"];

//echo $records_count;



echo '<table style="width:100%;">';
echo '<tr>
	<th style="width: 10%">工單編號</th>
	<th>狀態</th>
	<th style="width: 50%">工單標題</th>
	<th>報備者</th>
      </tr>';

for ($x = 0; $x < $records_count; $x++) {
        echo '<tr>';
        echo '<th><a href="https://'.$domain.'/browse/'.$issue_data["issues"][$x]["key"].'" target="_blank">'.$issue_data["issues"][$x]["key"] .'</a></th>';
        echo '<th>'.$issue_data["issues"][$x]["fields"]["status"]["name"].'</th>';
        echo '<th>'.$issue_data["issues"][$x]["fields"]["description"].'</th>';
        echo '<th>'.$issue_data["issues"][$x]["fields"]["reporter"]["name"].'</th>';
        echo '</tr>';
}

echo '</table>';



?>
