<?php
  session_start();

  if(!isset($_SESSION['user_id']) or empty($_SESSION['user_id'])){
    $_SESSION['user_id'] = uniqid();
  }

  if (isset($_GET['queue']) && !empty($_GET['queue'])) {
    $_SESSION['queue'] = $_GET['queue'];
  }

  $organizations = array ("org1" , "org2" , "org3" , "org4");

  switch ($_GET['queue']) {

        case "org1":
                $_SESSION['organization'] = $organizations[0];
                break;

        case "org2":
                $_SESSION['organization'] = $organizations[1];
                break;

        case "org3";
                $_SESSION['organization'] = $organizations[2];
                break;

        case "org4";
                $_SESSION['organization'] = $organizations[3];
                break;

	case "noc";
		$_SESSION['organization'] = "noc";
		break;

  }

?>
<html>
<head>
   <meta charset="UTF-8">
   <title>Ticketing dashboard</title>
   <style>
     table, th, td {
     border: 1px solid black;
     border-collapse: collapse;
   }
</style>

</head>
<body>
<?php

if (isset($_GET['debug']) && ($_GET['debug'] == "1")) {
    $_SESSION['debug'] = "1";
    echo "<p>";
    echo "Session Status: ".session_status()."<br>";
    echo "Session ID: ".$_SESSION['user_id']."<br>";
    echo "Queue: ".$_SESSION['queue']."<br>";
    echo "</p>";
} else {

    $_SESSION['debug'] = "0";

}

?>

  <audio id="audio" src="beep-07.wav" autoplay="false" ></audio>
  <button>Stop the notification</button>
  <div id="TicketTable">
  <table style="width:100%">
  </table>
  </div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>

  $(document).ready(function(){
    var refreshUsers = setInterval(function() {
      $("#TicketTable").load("tickets.php?queue=<?php echo $_SESSION['queue'] ?>");
    }, 10000); // 5000 ms = 5 sec
  });




  var button = document.querySelector('button');
  var evtSource = new EventSource('ticketstream.php');
  console.log(evtSource.withCredentials);
  console.log(evtSource.readyState);
  console.log(evtSource.url);
  var eventList = document.querySelector('ul');
  evtSource.onopen = function() {
    console.log("Connection to server opened.");
  };
  evtSource.onmessage = function(event) {
    //var newElement = document.createElement("li");
    //newElement.textContent = "message: " + e.data;
    //eventList.appendChild(newElement);
    var jdata = JSON.parse(event.data);
    console.log(jdata);
    console.log(jdata['event']);
    console.log(jdata['message']);
  
    if (!Notification) {
      alert('Desktop notifications not available in your browser. Try Chromium.');
      return;
    }

    if (jdata['event'] == "update") {
      if (Notification.permission !== "granted")
        Notification.requestPermission();
      else {
        var notification = new Notification(jdata['ticket_id']+ " - "+jdata['ticket_title'], {
      //icon: 'http://cdn.sstatic.net/stackexchange/img/logos/so/so-icon.png',
        icon: 'Ticket.png',
        body: " 嗨！工单已经被更新。 \n"+ jdata['update_by']+" 写道：\n" + jdata['comment'],
        });
        audio.play();
        notification.onclick = function (event) {
	  event.preventDefault();
          window.open("https://jira.sg.orion.co.com/browse/"+jdata['ticket_id'],'_blank');
	  window.focus();
        };
      }
    } else if (jdata['event'] == "TicketCreate") {
	  if (Notification.permission !== "granted")
        Notification.requestPermission();
      else {
        var notification = new Notification("新工单 : "+jdata['ticket_id']+" : "+jdata['ticket_title'], {
      //icon: 'http://cdn.sstatic.net/stackexchange/img/logos/so/so-icon.png',
        icon: 'NewTicket.png',
        body: jdata['update_by']+" 创建新工单。",
        });
        audio.play();
        notification.onclick = function (event) {
          event.preventDefault();
          window.open("https://jira.sg.orion.co.com/browse/"+jdata['ticket_id'],'_blank');
          window.focus();
        };
      }
    }
  };

  evtSource.onerror = function() {
    console.log("EventSource failed.");
  };
  button.onclick = function() {
    console.log('Connection closed');
    evtSource.close();
  };
</script>
</body>
</html>
