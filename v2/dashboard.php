<?php 
  session_start();

  require 'config.php';
  require 'functions.php';

  date_default_timezone_set("Asia/Singapore");

  if (isset($_POST["action"]) && $_POST["action"]=="login"){

    //echo $_POST["action"];
    //echo $_POST["exampleInputEmail"];
    //echo $_POST["exampleInputPassword"];

    $ch = curl_init('https://'.$domain_name.'/rest/auth/1/session');
    $jsonData = array( 'username' => $_POST['exampleInputEmail'], 'password' => $_POST['exampleInputPassword'] );
    $jsonDataEncoded = json_encode($jsonData);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
 
    $result = curl_exec($ch);
    curl_close($ch);
    unset($ch);

    $sess_arr = json_decode($result, true);

    //echo '<pre>';
    //var_dump($ch);
    //var_dump($sess_arr);
    //echo'</pre>';

    if(isset($sess_arr['errorMessages'][0])) { 
      // Log In Failed
      $_SESSION['login_failed'] = $sess_arr['errorMessages'][0];
      //echo $sess_arr['errorMessages'][0];
      include ("login.php");
    } else {
      // Log In Success

      //setcookie($sess_arr['session']['name'], $sess_arr['session']['value'], time() + (86400 * 30), "/");
      //echo "Login Success!<br>";
      unset($_SESSION['login_failed']);
      $_SESSION['user_id'] = uniqid();
      $ivlen = openssl_cipher_iv_length($cipher);
      $_SESSION['iv'] = openssl_random_pseudo_bytes($ivlen);
      $_SESSION['username'] = $_POST["exampleInputEmail"];
      $_SESSION['password'] = encrypt_decrypt('encrypt', $_POST["exampleInputPassword"]);

      find_jira_role();

      //echo $_POST["exampleInputPassword"]."<br>";
      //echo $_SESSION['password']."<br>";
      //echo encrypt_decrypt('decrypt', $_SESSION['password'])."<br>";
      include ("index.html");
    }
  } elseif (!isset($_SESSION['user_id']) or empty($_SESSION['user_id'])){

    include ("login.php");

  } else {

  //echo "test 2";
  //include ("index.html");
    include ("top.html");
    include ("sidebar.html");
    include ("content.html");
    include ("bottom.html");

  }
?>
