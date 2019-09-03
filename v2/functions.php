<?php

//include 'config.php';

function encrypt_decrypt($action, $string) {
    $output = false;
    //$encrypt_method = "AES-256-CBC";
    //$encrypt_method = "aes-256-cfb";
    $encrypt_method = $GLOBALS["cipher"];
    //$secret_key = 'This is my secret key';
    $secret_key = "e8AvWgHVZyBgs7399jyNbYszcpuj5nAh";
    //$secret_iv = 'This is my secret iv';
    $secret_iv = $_SESSION['iv'];
    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

function find_jira_role() {
    $url = 'https://'.$GLOBALS["domain_name"].'/rest/api/2/mypermissions';
    $result = jira_get($url);
    $result_data = json_decode($result,true);

    if ($result_data["permissions"]["SERVICEDESK_AGENT"]["havePermission"]) {
      $_SESSION['agent'] = true;
    } else {
      $_SESSION['agent'] = false;
    }

$_SESSION['function_run'] = "yesy";
$_SESSION['function_result'] = $result;

}


function jira_get($link) {

  $ch = curl_init($link);
  curl_setopt($ch, CURLOPT_HTTPGET, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_VERBOSE, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, 1);
  curl_setopt($ch, CURLOPT_USERPWD, $_SESSION['username'].":".encrypt_decrypt('decrypt', $_SESSION['password']));
  if ($_SESSION['debug']) {
    $verbose = fopen('error-curl.txt', 'w+');
    curl_setopt($ch, CURLOPT_STDERR, $verbose);
  }

  $result = curl_exec($ch);
  //echo curl_error($ch);
  //echo $jsonDataEncoded;
  //var_dump ($result);
  //$result_data = json_decode($result,true);
  curl_close($ch);
  //fclose($verbose);
  return $result;

}

function jira_put($link) {

  return $result;

}

?>
