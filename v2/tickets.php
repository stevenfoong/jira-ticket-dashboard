<?php

  session_start();

//echo $_GET['queue'];

	$username = 'steven.foong';
        $password = 'Orion888!';

	$organizations = array ("信用风险部" , "加盟部" , "迪士尼市场部" , "迪士尼财务部");

switch ($_GET['queue']) {

	case "fengxian28":
		$organization = $organizations[0];
		break;

	case "jiameng38":
		$organization = $organizations[1];
		break;

	case "dimarketing68";
		$organization = $organizations[2];
		break;

	case "dicaiwu889";
		$organization = $organizations[3];
		break;

}

?>



<?php

date_default_timezone_set("Asia/Singapore");
echo "<br>更新 : ".date("Y-m-d")." " .date("h:i:sa") ."<br>";


if  (isset($_GET['queue']) && $_GET['queue'] == 'noc1368' ) {

	foreach($organizations as $item) {
		echo "<br>";
    		echo $item;
    		$url = 'https://jira.sg.orion.co.com/rest/api/2/search?jql='.urlencode('Organizations = "'.$item.'" AND status != Closed AND status != Resolved AND status != Completed AND reporter != feedback ORDER BY createdDate DESC ');

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

	echo '

<style>
.statusblock {
  border-radius: 25px;
  padding: 3px;
  color: black;
  font-size: 9px;
} 
#进行中 {
  background: yellow;
}
#Open {
  background: #F08080;
}
#Canceled {
  background: #98FB98;
}
#等待用户反馈 {
  background: #98FB98;
}
#Escalated {
  background: #F08080;
}
#等待技术人员处理 {
  background: #F08080;
}
</style>
	';

	echo '<table style="width:100%" class="table-bordered datatable>';
	echo '<thead><tr role="row">
		<th style="width: 150px;">工單編號</th>
		<th style="width: 80px;">狀態</th>
		<th>工單標題</th>
		<th style="width: 80px;">報備者</th>
	      </tr></thead>';
for ($x = 0; $x < $records_count; $x++) {
        echo '<tr>';
        echo '<th><a href="https://jira.sg.orion.co.com/browse/'.$issue_data["issues"][$x]["key"].'" target="_blank">'.$issue_data["issues"][$x]["key"] .'</a></th>';
        echo '<th><div class="statusblock" id="'.$issue_data["issues"][$x]["fields"]["status"]["name"].'">'.$issue_data["issues"][$x]["fields"]["status"]["name"].'</div></th>';
        //echo '<th>'.substr($issue_data["issues"][$x]["fields"]["description"], 0 , 30).'...</th>';
        echo '<th>'.$issue_data["issues"][$x]["fields"]["description"].'</th>';
        echo '<th>'.$issue_data["issues"][$x]["fields"]["reporter"]["name"].'</th>';
        echo '</tr>';
}

	echo '</table>';

	}

} elseif (isset($_GET['queue'])) {

	echo '<br>'.$organization.'<br><br>';

	$url = 'https://jira.sg.orion.co.com/rest/api/2/search?jql='.urlencode('Organizations = "'.$organization.'" AND status != Closed AND status != Resolved AND status != Completed AND reporter != feedback ORDER BY createdDate DESC ');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        $issue = (curl_exec($curl));
        $issue_data = json_decode($issue,true);
//$reporter_key = $issue_data["fields"]["reporter"]["key"];
//$reporter_displayName = $issue_data["fields"]["reporter"]["displayName"];
$records_count = $issue_data["total"];

	echo '<table style="width:100%">';
        echo '<tr>
                <th style="width: 10%">工單編號</th>
                <th>狀態</th>
                <th style="width: 50%">工單標題</th>
                <th>報備者</th>
              </tr>';

for ($x = 0; $x < $records_count; $x++) {
        echo '<tr>';
        echo '<th><a href="https://jira.sg.orion.co.com/browse/'.$issue_data["issues"][$x]["key"].'" target="_blank">'.$issue_data["issues"][$x]["key"] .'</a></th>';
        echo '<th>'.$issue_data["issues"][$x]["fields"]["status"]["name"].'</th>';
        echo '<th>'.$issue_data["issues"][$x]["fields"]["description"].'</th>';
        echo '<th>'.$issue_data["issues"][$x]["fields"]["reporter"]["name"].'</th>';
        echo '</tr>';
}

	echo '</table>';

}


?>



