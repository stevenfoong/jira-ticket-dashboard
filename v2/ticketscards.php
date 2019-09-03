<?php

  session_start();
  require 'config.php';
  require 'functions.php';

?>

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
  #Escalate {
    background: #F08080;
  }
  #等待技术人员处理 {
    background: #F08080;
  }
</style>

<?php

  $username = 'steven.foong';
  $password = 'Orion888!';

  //$username = $_SESSION['username'];
  //$password = encrypt_decrypt('decrypt', $_SESSION['password']);

  //switch ($_SESSION['organization']) {

  //}

  $queue = array ("信用风险部" , "加盟部" , "迪士尼市场部" , "迪士尼财务部");

  date_default_timezone_set("Asia/Singapore");

  foreach($queue as $item) {

    $url = 'https://'.$domain_name.'/rest/api/2/search?jql='.urlencode('Organizations = "'.$item.'" AND status != Closed AND status != Resolved AND status != Completed AND reporter != feedback ORDER BY createdDate DESC ');
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
      <div class="row">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">';
    echo $item;
    echo '</h6>';
    echo '更新 : '.date("Y-m-d").' '.date("h:i:sa");
    echo '
        </div>
          <div class="card-body">
            <table style="width:100%" class="table-bordered datatable>
	    <thead><tr role="row">
	    <th style="width: 150px;">工單編號</th>
            <th style="width: 90px;">狀態</th>
            <th>工單標題</th>
            <th style="width: 80px;">報備者</th>
            </tr></thead>
    ';
    for ($x = 0; $x < $records_count; $x++) {
        echo '<tr>';
        echo '<th><a href="https://'.$domain_name.'/browse/'.$issue_data["issues"][$x]["key"].'" target="_blank">'.$issue_data["issues"][$x]["key"] .'</a></th>';
        echo '<th><div class="statusblock" id="'.$issue_data["issues"][$x]["fields"]["status"]["name"].'">'.$issue_data["issues"][$x]["fields"]["status"]["name"].'</div></th>';
        //echo '<th>'.substr($issue_data["issues"][$x]["fields"]["description"], 0 , 30).'...</th>';
        echo '<th>'.$issue_data["issues"][$x]["fields"]["description"].'</th>';
        echo '<th>'.$issue_data["issues"][$x]["fields"]["reporter"]["name"].'</th>';
        echo '</tr>';
    }
    echo '
	    </table>
         </div>
      </div>
      </div>
    ';


  }


?>

